<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\OperativeEntity;
use App\Domain\Repositories\OperativeRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\OperativeMapper;
use App\Infrastructure\Persistence\Models\Operative;

final class EloquentOperativeRepository implements OperativeRepositoryInterface
{
    public function __construct(private OperativeMapper $mapper)
    {
    }

    public function all(array $filters = []): array
    {
        $query = Operative::query();
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
        $query = Operative::query();
        $this->applyFilters($query, $filters);

        return (int) $query->count();
    }

    public function findById(string $id): ?OperativeEntity
    {
        $operative = Operative::query()->find($id);

        return $operative ? $this->mapper->toEntity($operative) : null;
    }

    public function save(OperativeEntity $operativeEntity): OperativeEntity
    {
        $operative = Operative::query()->find($operativeEntity->getId()) ?? new Operative();

        $operative->id = $operativeEntity->getId();
        $operative->document_type = $operativeEntity->getDocumentType();
        $operative->document = $operativeEntity->getDocument();
        $operative->name = $operativeEntity->getName();
        $operative->is_active = $operativeEntity->getIsActive();

        $operative->save();

        return $this->mapper->toEntity($operative->fresh() ?? $operative);
    }

    public function deleteById(string $id): bool
    {
        $operative = Operative::query()->find($id);

        return $operative ? (bool) $operative->delete() : false;
    }

    private function applyFilters(\Illuminate\Database\Eloquent\Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('document', 'like', '%' . $search . '%');
            });
        }

        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== null) {
            $query->where('is_active', (bool) $filters['is_active']);
        }
    }
}
