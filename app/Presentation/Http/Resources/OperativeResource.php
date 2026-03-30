<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Domain\Entities\OperativeEntity;

final class OperativeResource
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(OperativeEntity $operative): array
    {
        return [
            'id' => $operative->getId(),
            'name' => $operative->getName(),
            'document' => $operative->getDocument(),
            'document_type' => $operative->getDocumentType(),
            'is_active' => $operative->getIsActive(),
        ];
    }
}
