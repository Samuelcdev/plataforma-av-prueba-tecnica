<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\OperativeEntity;
use App\Domain\Repositories\OperativeRepositoryInterface;
use App\Infrastructure\Mapper\OperativeMapper;
use App\Models\Operative;

final class EloquentOperativeRepository implements OperativeRepositoryInterface
{
    public function __construct(private OperativeMapper $mapper)
    {
    }

    public function all(): array
    {
        return $this->mapper->toCollectionEntity(
            Operative::query()->orderBy('name')->get()
        );
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
}
