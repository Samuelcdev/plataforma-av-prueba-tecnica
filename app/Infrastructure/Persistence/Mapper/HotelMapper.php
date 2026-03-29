<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use App\Domain\Entities\HotelEntity;
use App\Models\Hotel;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;

final class HotelMapper
{
    public function toEntity(Hotel $hotel): HotelEntity
    {
        return new HotelEntity(
            (string) $hotel->id,
            (string) $hotel->user_id,
            (string) $hotel->nit,
            (string) $hotel->document_type,
            (string) $hotel->name,
            $hotel->phone !== null ? (string) $hotel->phone : null,
            $hotel->address !== null ? (string) $hotel->address : null,
            $this->toImmutable($hotel->created_at),
            $this->toImmutable($hotel->updated_at),
        );
    }

    /**
     * @return array<HotelEntity>
     */
    public function toCollectionEntity(Collection|array $items): array
    {
        $models = $items instanceof Collection ? $items->all() : $items;

        return array_map(
            fn (mixed $item): HotelEntity => $this->toEntity($item),
            $models
        );
    }

    private function toImmutable(mixed $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return new DateTimeImmutable((string) $value);
    }
}
