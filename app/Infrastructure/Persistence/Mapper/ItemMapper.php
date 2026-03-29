<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Entities\ItemEntity;
use App\Infrastructure\Persistence\Models\Item;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;

final class ItemMapper
{
    public function toEntity(Item $item): ItemEntity
    {
        return new ItemEntity(
            (string) $item->id,
            (string) $item->name,
            $item->description !== null ? (string) $item->description : null,
            (string) $item->price,
            (bool) $item->is_active,
            $this->toImmutable($item->created_at),
        );
    }

    /**
     * @return array<ItemEntity>
     */
    public function toCollectionEntity(Collection|array $items): array
    {
        $models = $items instanceof Collection ? $items->all() : $items;

        return array_map(
            fn (mixed $item): ItemEntity => $this->toEntity($item),
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
