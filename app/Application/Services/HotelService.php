<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTO\Hotel\CreateHotelDto;
use App\Application\DTO\Hotel\GetHotelsDto;
use App\Application\DTO\Hotel\HotelPayloadDto;
use App\Application\DTO\Hotel\UpdateHotelDto;
use App\Application\Security\PasswordHasherInterface;
use App\Domain\Entities\HotelEntity;
use App\Domain\Entities\UserEntity;
use App\Domain\Exceptions\ConflictException;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Domain\Repositories\HotelRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Auth\SanctumAuthProviderInterface;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class HotelService
{
    private const ADMIN_ROLE_ID = 1;
    private const HOTEL_ROLE_ID = 2;

    public function __construct(
        private HotelRepositoryInterface $hotels,
        private UserRepositoryInterface $users,
        private SanctumAuthProviderInterface $sanctumAuth,
        private PasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @return array{hotels: list<HotelPayloadDto>, total: int}
     */
    public function index(GetHotelsDto $dto): array
    {
        $this->assertAuthenticatedAdmin();

        $filters = [
            'search' => $dto->getSearch(),
            'page' => $dto->getPage(),
            'total' => $dto->getTotal(),
        ];

        return [
            'hotels' => array_map(
                fn (HotelEntity $hotel): HotelPayloadDto => $this->toPayload($hotel),
                $this->hotels->all($filters),
            ),
            'total' => $this->hotels->count($filters),
        ];
    }

    public function show(string $id): HotelPayloadDto
    {
        $this->assertAuthenticatedAdmin();

        return $this->toPayload($this->getHotelOrFail($id));
    }

    public function store(CreateHotelDto $dto): HotelPayloadDto
    {
        $this->assertAuthenticatedAdmin();

        $this->assertUsernameAvailable($dto->getUsername());
        $this->assertNitAvailable($dto->getNit());

        return DB::transaction(function () use ($dto): HotelPayloadDto {
            $password = Str::password(16);
            $user = new UserEntity(
                id: (string) Str::uuid(),
                username: $dto->getUsername(),
                password: $this->passwordHasher->hash($password),
                roleId: self::HOTEL_ROLE_ID,
                isActive: true,
            );

            $savedUser = $this->users->save($user);

            $hotel = new HotelEntity(
                id: (string) Str::uuid(),
                userId: $savedUser->getId(),
                nit: $dto->getNit(),
                documentType: $dto->getDocumentType(),
                name: $dto->getName(),
                phone: $dto->getPhone(),
                address: $dto->getAddress(),
            );

            $savedHotel = $this->hotels->save($hotel);

            return new HotelPayloadDto($savedHotel, $savedUser);
        });
    }

    public function update(UpdateHotelDto $dto): HotelPayloadDto
    {
        $this->assertAuthenticatedAdmin();

        $hotel = $this->getHotelOrFail($dto->getId());

        $this->assertNitAvailable($dto->getNit(), $hotel->getId());

        $hotel->setNit($dto->getNit())
            ->setDocumentType($dto->getDocumentType())
            ->setName($dto->getName())
            ->setPhone($dto->getPhone())
            ->setAddress($dto->getAddress())
            ->setUpdatedAt(new DateTimeImmutable());

        $savedHotel = $this->hotels->save($hotel);

        return $this->toPayload($savedHotel);
    }

    public function destroy(string $id): void
    {
        $this->assertAuthenticatedAdmin();

        $hotel = $this->getHotelOrFail($id);

        DB::transaction(function () use ($hotel): void {
            $user = $this->users->findById($hotel->getUserId());

            if ($user === null) {
                throw EntityNotFoundException::for('User', $hotel->getUserId());
            }

            $user->setIsActive(false)
                ->setUpdatedAt(new DateTimeImmutable());

            $this->users->save($user);
            $this->users->deleteById($user->getId());
            $this->hotels->deleteById($hotel->getId());
        });
    }

    private function assertAuthenticatedAdmin(): UserEntity
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

        if ($user->getRoleId() !== self::ADMIN_ROLE_ID) {
            throw UnauthorizedDomainException::accessDenied('Only admin users can perform this action.');
        }

        return $user;
    }

    private function getHotelOrFail(string $id): HotelEntity
    {
        $hotel = $this->hotels->findById($id);

        if ($hotel === null) {
            throw EntityNotFoundException::for('Hotel', $id);
        }

        return $hotel;
    }

    private function toPayload(HotelEntity $hotel): HotelPayloadDto
    {
        $user = $this->users->findById($hotel->getUserId());

        if ($user === null) {
            throw EntityNotFoundException::for('User', $hotel->getUserId());
        }

        return new HotelPayloadDto($hotel, $user);
    }

    private function assertUsernameAvailable(string $username): void
    {
        $existing = $this->users->findByUsernameIncludingDeleted($username);

        if ($existing !== null) {
            throw ConflictException::because(sprintf('Username [%s] is already in use.', $username));
        }
    }

    private function assertNitAvailable(string $nit, ?string $ignoreHotelId = null): void
    {
        $existing = $this->hotels->findByNit($nit);

        if ($existing !== null && $existing->getId() !== $ignoreHotelId) {
            throw ConflictException::because(sprintf('NIT [%s] is already in use.', $nit));
        }
    }
}
