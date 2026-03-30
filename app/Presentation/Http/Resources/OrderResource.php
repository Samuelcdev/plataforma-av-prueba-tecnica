<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Application\DTO\Order\OrderPayloadDto;
use App\Domain\Entities\OrderAssignmentEntity;
use App\Domain\Entities\OrderItemEntity;

final class OrderResource
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(OrderPayloadDto $payload): array
    {
        $order = $payload->getOrder();

        return [
            'id' => $order->getId(),
            'hotel_id' => $order->getHotelId(),
            'name' => $order->getName(),
            'service_type' => $order->getServiceType(),
            'status' => $order->getStatus(),
            'start_date' => $order->getStartDate()->format(DATE_ATOM),
            'end_date' => $order->getEndDate()->format(DATE_ATOM),
            'created_at' => $order->getCreatedAt()?->format(DATE_ATOM),
            'updated_at' => $order->getUpdatedAt()?->format(DATE_ATOM),
            'items' => array_map(
                static fn (OrderItemEntity $item): array => [
                    'id' => $item->getItemId(),
                    'name' => $item->getItemName(),
                    'quantity' => $item->getQuantity(),
                ],
                $payload->getItems(),
            ),
            'assignments' => array_map(
                static fn (OrderAssignmentEntity $assignment): array => [
                    'id' => $assignment->getId(),
                    'operative_id' => $assignment->getOperativeId(),
                    'operative_name' => $assignment->getOperativeName(),
                    'admin_id' => $assignment->getAdminId(),
                    'assigned_at' => $assignment->getAssignedAt()?->format(DATE_ATOM),
                ],
                $payload->getAssignments(),
            ),
        ];
    }
}
