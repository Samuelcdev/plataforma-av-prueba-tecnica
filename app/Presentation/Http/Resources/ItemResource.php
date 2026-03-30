<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Domain\Entities\ItemEntity;

final class ItemResource
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(ItemEntity $item): array
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'description' => $item->getDescription(),
            'price' => $item->getPrice(),
            'is_active' => $item->getIsActive(),
            'created_at' => $item->getCreatedAt()?->format(DATE_ATOM),
        ];
    }
}
