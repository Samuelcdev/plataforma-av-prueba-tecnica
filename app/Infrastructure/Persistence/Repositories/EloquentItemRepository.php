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

    public function all(array $filters = []): array
    {
        $query = Item::query();
        $this->applyFilters($query, $filters);

        $total = (int) ($filters['total'] ?? 10);
        $page = (int) ($filters['page'] ?? 1);
        $total = max(1, min($total, 100));
        $page = max(1, $page);

        return $this->mapper->toCollectionEntity(
            $query
                ->orderBy('name')
                ->offset(($page - 1) * $total)
                ->limit($total)
                ->get()
        );
    }

    public function count(array $filters = []): int
    {
        $query = Item::query();
        $this->applyFilters($query, $filters);

        return (int) $query->count();
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

    private function applyFilters(\Illuminate\Database\Eloquent\Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== null) {
            $query->where('is_active', (bool) $filters['is_active']);
        }
    }

    private function toDatabaseDateTime(?DateTimeImmutable $value): ?string
    {
        return $value?->format('Y-m-d H:i:s');
    }
}
