<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTO\Order\AssignOrderOperativesDto;
use App\Application\DTO\Order\CancelOrderDto;
use App\Application\DTO\Order\CreateOrderDto;
use App\Application\DTO\Order\GetOrdersDto;
use App\Application\DTO\Order\OrderItemInputDto;
use App\Application\DTO\Order\OrderPayloadDto;
use App\Application\DTO\Order\UpdateOrderDto;
use App\Domain\Entities\OrderAssignmentEntity;
use App\Domain\Entities\OrderEntity;
use App\Domain\Entities\OrderItemEntity;
use App\Domain\Entities\UserEntity;
use App\Domain\Exceptions\ConflictException;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\Repositories\HotelRepositoryInterface;
use App\Domain\Repositories\ItemRepositoryInterface;
use App\Domain\Repositories\OperativeRepositoryInterface;
use App\Domain\Repositories\OrderAssignmentRepositoryInterface;
use App\Domain\Repositories\OrderItemRepositoryInterface;
use App\Domain\Repositories\OrderRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\SanctumAuthProviderInterface;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class OrderService
{
    private const ADMIN_ROLE_ID = 1;
    private const HOTEL_ROLE_ID = 2;
    private const STATUS_ACTIVE = 'active';
    private const STATUS_PENDING = 'pending';
    private const STATUS_CANCELLED = 'cancelled';

    public function __construct(
        private OrderRepositoryInterface $orders,
        private OrderItemRepositoryInterface $orderItems,
        private OrderAssignmentRepositoryInterface $orderAssignments,
        private HotelRepositoryInterface $hotels,
        private AdminRepositoryInterface $admins,
        private OperativeRepositoryInterface $operatives,
        private ItemRepositoryInterface $items,
        private UserRepositoryInterface $users,
        private SanctumAuthProviderInterface $sanctumAuth,
    ) {
    }

    /**
     * @return array{orders: list<OrderPayloadDto>, total: int}
     */
    public function index(GetOrdersDto $dto): array
    {
        $user = $this->getAuthenticatedUser();
        $filters = [
            'search' => $dto->getSearch(),
            'page' => $dto->getPage(),
            'total' => $dto->getTotal(),
            'sort' => $dto->getSort(),
            'order' => $dto->getOrder(),
            'start_from' => $dto->getStartFrom(),
        ];

        if ($user->getRoleId() === self::ADMIN_ROLE_ID) {
            $orders = $this->orders->all($filters);
            $total = $this->orders->count($filters);

            return [
                'orders' => array_map(
                    fn (OrderEntity $order): OrderPayloadDto => $this->toPayload($order),
                    $orders,
                ),
                'total' => $total,
            ];
        }

        if ($user->getRoleId() === self::HOTEL_ROLE_ID) {
            $hotel = $this->getHotelByUserOrFail($user->getId());
            $orders = $this->orders->findByHotelId($hotel->getId(), $filters);
            $total = $this->orders->countByHotelId($hotel->getId(), $filters);

            return [
                'orders' => array_map(
                    fn (OrderEntity $order): OrderPayloadDto => $this->toPayload($order),
                    $orders,
                ),
                'total' => $total,
            ];
        }

        throw UnauthorizedDomainException::accessDenied('User role is not allowed for this action.');
    }

    public function show(string $orderId): OrderPayloadDto
    {
        $user = $this->getAuthenticatedUser();
        $order = $this->getOrderOrFail($orderId);

        if ($user->getRoleId() === self::ADMIN_ROLE_ID) {
            return $this->toPayload($order);
        }

        if ($user->getRoleId() === self::HOTEL_ROLE_ID) {
            $hotel = $this->getHotelByUserOrFail($user->getId());
            $this->assertHotelOwnsOrder($hotel->getId(), $order);

            return $this->toPayload($order);
        }

        throw UnauthorizedDomainException::accessDenied('User role is not allowed for this action.');
    }

    public function store(CreateOrderDto $dto): OrderPayloadDto
    {
        $user = $this->assertRole(self::HOTEL_ROLE_ID);
        $hotel = $this->getHotelByUserOrFail($user->getId());

        $this->assertValidDateRange($dto->getStartDate(), $dto->getEndDate());
        $this->assertValidItems($dto->getItems());

        return DB::transaction(function () use ($dto, $hotel): OrderPayloadDto {
            $order = new OrderEntity(
                id: (string) Str::uuid(),
                hotelId: $hotel->getId(),
                name: $dto->getName(),
                serviceType: $dto->getServiceType(),
                startDate: $dto->getStartDate(),
                endDate: $dto->getEndDate(),
                status: self::STATUS_ACTIVE,
            );

            $savedOrder = $this->orders->save($order);
            $this->replaceOrderItems($savedOrder->getId(), $dto->getItems());

            return $this->toPayload($savedOrder);
        });
    }

    public function update(UpdateOrderDto $dto): OrderPayloadDto
    {
        $user = $this->assertRole(self::HOTEL_ROLE_ID);
        $hotel = $this->getHotelByUserOrFail($user->getId());
        $order = $this->getOrderOrFail($dto->getId());

        $this->assertHotelOwnsOrder($hotel->getId(), $order);
        $this->assertOrderIsActive($order);
        $this->assertValidDateRange($dto->getStartDate(), $dto->getEndDate());
        $this->assertValidItems($dto->getItems());

        return DB::transaction(function () use ($dto, $order): OrderPayloadDto {
            $order->setName($dto->getName())
                ->setServiceType($dto->getServiceType())
                ->setStartDate($dto->getStartDate())
                ->setEndDate($dto->getEndDate())
                ->setUpdatedAt(new DateTimeImmutable());

            $savedOrder = $this->orders->save($order);
            $this->replaceOrderItems($savedOrder->getId(), $dto->getItems());

            return $this->toPayload($savedOrder);
        });
    }

    public function assignOperatives(AssignOrderOperativesDto $dto): OrderPayloadDto
    {
        $user = $this->assertRole(self::ADMIN_ROLE_ID);
        $admin = $this->getAdminByUserOrFail($user->getId());
        $order = $this->getOrderOrFail($dto->getOrderId());

        $this->assertOrderIsActive($order);

        DB::transaction(function () use ($dto, $admin, $order): void {
            $operativeIds = array_values(array_unique($dto->getOperativeIds()));

            foreach ($operativeIds as $operativeId) {
                $operative = $this->operatives->findById($operativeId);

                if ($operative === null) {
                    throw EntityNotFoundException::for('Operative', $operativeId);
                }

                if (! $operative->getIsActive()) {
                    throw ConflictException::because(sprintf('Operative [%s] is inactive.', $operativeId));
                }
            }

            $existingAssignments = $this->orderAssignments->findByOrderId($order->getId());
            $existingByOperativeId = [];
            foreach ($existingAssignments as $assignment) {
                $existingByOperativeId[$assignment->getOperativeId()] = $assignment;
            }

            foreach ($existingAssignments as $assignment) {
                if (! in_array($assignment->getOperativeId(), $operativeIds, true)) {
                    $this->orderAssignments->deleteById($assignment->getId());
                }
            }

            foreach ($operativeIds as $operativeId) {
                if (isset($existingByOperativeId[$operativeId])) {
                    continue;
                }

                $assignment = new OrderAssignmentEntity(
                    id: (string) Str::uuid(),
                    orderId: $order->getId(),
                    operativeId: $operativeId,
                    adminId: $admin->getId(),
                );

                $this->orderAssignments->save($assignment);
            }
        });

        $updatedOrder = $this->getOrderOrFail($order->getId());

        return $this->toPayload($updatedOrder);
    }

    public function cancel(CancelOrderDto $dto): OrderPayloadDto
    {
        $user = $this->assertRole(self::HOTEL_ROLE_ID);
        $hotel = $this->getHotelByUserOrFail($user->getId());
        $order = $this->getOrderOrFail($dto->getOrderId());

        $this->assertHotelOwnsOrder($hotel->getId(), $order);
        $this->assertOrderIsActive($order);

        $order->setStatus(self::STATUS_CANCELLED)
            ->setUpdatedAt(new DateTimeImmutable());

        $savedOrder = $this->orders->save($order);

        return $this->toPayload($savedOrder);
    }

    /**
     * @param list<OrderItemInputDto> $items
     */
    private function replaceOrderItems(string $orderId, array $items): void
    {
        $this->orderItems->deleteByOrderId($orderId);

        foreach ($items as $itemInput) {
            $orderItem = new OrderItemEntity(
                id: (string) Str::uuid(),
                orderId: $orderId,
                itemId: $itemInput->getItemId(),
                quantity: $itemInput->getQuantity(),
            );

            $this->orderItems->save($orderItem);
        }
    }

    /**
     * @param list<OrderItemInputDto> $items
     */
    private function assertValidItems(array $items): void
    {
        if ($items === []) {
            throw ValidationException::withErrors([
                'items' => ['At least one item is required.'],
            ]);
        }

        foreach ($items as $itemInput) {
            if ($itemInput->getQuantity() < 1) {
                throw ValidationException::withErrors([
                    'items' => ['Item quantity must be greater than or equal to 1.'],
                ]);
            }

            if ($this->items->findById($itemInput->getItemId()) === null) {
                throw EntityNotFoundException::for('Item', $itemInput->getItemId());
            }
        }
    }

    private function assertValidDateRange(DateTimeImmutable $startDate, DateTimeImmutable $endDate): void
    {
        if ($startDate >= $endDate) {
            throw ValidationException::withErrors([
                'end_date' => ['End date must be greater than start date.'],
            ]);
        }
    }

    private function getAuthenticatedUser(): UserEntity
    {
        $currentUserId = $this->sanctumAuth->currentUserId();

        if ($currentUserId === null) {
            throw UnauthorizedDomainException::accessDenied();
        }

        $user = $this->users->findById($currentUserId);

        if ($user === null) {
            throw EntityNotFoundException::for('User', $currentUserId);
        }

        if (! $user->getIsActive()) {
            throw UnauthorizedDomainException::accessDenied('User account is inactive.');
        }

        return $user;
    }

    private function assertRole(int $roleId): UserEntity
    {
        $user = $this->getAuthenticatedUser();

        if ($user->getRoleId() !== $roleId) {
            throw UnauthorizedDomainException::accessDenied('User role is not allowed for this action.');
        }

        return $user;
    }

    private function getHotelByUserOrFail(string $userId): \App\Domain\Entities\HotelEntity
    {
        $hotel = $this->hotels->findByUserId($userId);

        if ($hotel === null) {
            throw EntityNotFoundException::for('Hotel', $userId);
        }

        return $hotel;
    }

    private function getAdminByUserOrFail(string $userId): \App\Domain\Entities\AdminEntity
    {
        $admin = $this->admins->findByUserId($userId);

        if ($admin === null) {
            throw EntityNotFoundException::for('Admin', $userId);
        }

        return $admin;
    }

    private function getOrderOrFail(string $orderId): OrderEntity
    {
        $order = $this->orders->findById($orderId);

        if ($order === null) {
            throw EntityNotFoundException::for('Order', $orderId);
        }

        return $order;
    }

    private function assertHotelOwnsOrder(string $hotelId, OrderEntity $order): void
    {
        if ($order->getHotelId() !== $hotelId) {
            throw UnauthorizedDomainException::accessDenied('You can only manage orders from your hotel.');
        }
    }

    private function assertOrderIsActive(OrderEntity $order): void
    {
        $allowedStatuses = [self::STATUS_ACTIVE, self::STATUS_PENDING];
        if (! in_array($order->getStatus(), $allowedStatuses, true)) {
            throw ConflictException::because(sprintf('Order [%s] is not in a manageable state.', $order->getId()));
        }
    }

    private function toPayload(OrderEntity $order): OrderPayloadDto
    {
        $items = array_map(function (OrderItemEntity $orderItem): OrderItemEntity {
            $item = $this->items->findById($orderItem->getItemId());
            if ($item !== null) {
                $orderItem->setItemName($item->getName());
            }

            return $orderItem;
        }, $this->orderItems->findByOrderId($order->getId()));
        $assignments = $this->orderAssignments->findByOrderId($order->getId());

        return new OrderPayloadDto($order, $items, $assignments);
    }
}
