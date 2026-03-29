<?php

declare(strict_types=1);

namespace App\Infrastructure\Mapper;

use App\Domain\Entities\OperativeEntity;
use App\Models\Operative;
use Illuminate\Support\Collection;

final class OperativeMapper
{
    public function toEntity(Operative $operative): OperativeEntity
    {
        return new OperativeEntity(
            (string) $operative->id,
            (string) $operative->document_type,
            (string) $operative->document,
            (string) $operative->name,
            (bool) $operative->is_active,
        );
    }

    /**
     * @return array<OperativeEntity>
     */
    public function toCollectionEntity(Collection|array $items): array
    {
        $models = $items instanceof Collection ? $items->all() : $items;

        return array_map(
            fn (mixed $item): OperativeEntity => $this->toEntity($item),
            $models
        );
    }
}
