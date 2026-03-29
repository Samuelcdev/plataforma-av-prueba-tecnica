<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Application\DTO\Hotel\HotelPayloadDto;

final class HotelResource
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(HotelPayloadDto $payload): array
    {
        $hotel = $payload->getHotel();

        return [
            'id' => $hotel->getId(),
            'user_id' => $hotel->getUserId(),
            'nit' => $hotel->getNit(),
            'document_type' => $hotel->getDocumentType(),
            'name' => $hotel->getName(),
            'phone' => $hotel->getPhone(),
            'address' => $hotel->getAddress(),
            'created_at' => $hotel->getCreatedAt()?->format(DATE_ATOM),
            'updated_at' => $hotel->getUpdatedAt()?->format(DATE_ATOM),
            'deleted_at' => $hotel->getDeletedAt()?->format(DATE_ATOM),
            'user' => UserResource::toArray($payload->getUser()),
        ];
    }
}
