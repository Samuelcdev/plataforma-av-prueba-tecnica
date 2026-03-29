<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\ItemEntity;
use App\Domain\Repositories\ItemRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\ItemMapper;
use App\Infrastructure\Persistence\Models\Item;
use DateTimeImmutable;

final class EloquentItemRepository implements ItemRepositoryInterface
{
    public function __construct(private ItemMapper $mapper)
    {
    }

    public function all(): array
    {
        return $this->mapper->toCollectionEntity(
            Item::query()->orderBy('name')->get()
        );
    }

    public function findById(string $id): ?ItemEntity
    {
        $item = Item::query()->find($id);

        return $item ? $this->mapper->toEntity($item) : null;
    }

    public function save(ItemEntity $itemEntity): ItemEntity
    {
        $item = Item::query()->find($itemEntity->getId()) ?? new Item();

        $item->id = $itemEntity->getId();
        $item->name = $itemEntity->getName();
        $item->description = $itemEntity->getDescription();
        $item->price = $itemEntity->getPrice();
        $item->is_active = $itemEntity->getIsActive();

        if ($itemEntity->getCreatedAt() !== null) {
            $item->created_at = $this->toDatabaseDateTime($itemEntity->getCreatedAt());
        }

        $item->save();

        return $this->mapper->toEntity($item->fresh() ?? $item);
    }

    public function deleteById(string $id): bool
    {
        $item = Item::query()->find($id);

        return $item ? (bool) $item->delete() : false;
    }
    private function toDatabaseDateTime(?DateTimeImmutable $value): ?string
    {
        return $value?->format('Y-m-d H:i:s');
    }
}
