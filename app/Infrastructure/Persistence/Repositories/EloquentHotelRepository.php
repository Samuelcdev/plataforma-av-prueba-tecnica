<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\HotelEntity;
use App\Domain\Repositories\HotelRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\HotelMapper;
use App\Infrastructure\Persistence\Models\Hotel;
use DateTimeImmutable;

final class EloquentHotelRepository implements HotelRepositoryInterface
{
    public function __construct(private HotelMapper $mapper)
    {
    }

    public function all(): array
    {
        return $this->mapper->toCollectionEntity(
            Hotel::query()->orderBy('name')->get()
        );
    }

    public function findById(string $id): ?HotelEntity
    {
        $hotel = Hotel::query()->find($id);

        return $hotel ? $this->mapper->toEntity($hotel) : null;
    }

    public function save(HotelEntity $hotelEntity): HotelEntity
    {
        $hotel = Hotel::query()->find($hotelEntity->getId()) ?? new Hotel();

        $hotel->id = $hotelEntity->getId();
        $hotel->user_id = $hotelEntity->getUserId();
        $hotel->nit = $hotelEntity->getNit();
        $hotel->document_type = $hotelEntity->getDocumentType();
        $hotel->name = $hotelEntity->getName();
        $hotel->phone = $hotelEntity->getPhone();
        $hotel->address = $hotelEntity->getAddress();

        if ($hotelEntity->getCreatedAt() !== null) {
            $hotel->created_at = $this->toDatabaseDateTime($hotelEntity->getCreatedAt());
        }

        if ($hotelEntity->getUpdatedAt() !== null) {
            $hotel->updated_at = $this->toDatabaseDateTime($hotelEntity->getUpdatedAt());
        }

        $hotel->save();

        return $this->mapper->toEntity($hotel->fresh() ?? $hotel);
    }

    public function deleteById(string $id): bool
    {
        $hotel = Hotel::query()->find($id);

        return $hotel ? (bool) $hotel->delete() : false;
    }
    private function toDatabaseDateTime(?DateTimeImmutable $value): ?string
    {
        return $value?->format('Y-m-d H:i:s');
    }
}
