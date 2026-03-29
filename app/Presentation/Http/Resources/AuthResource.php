<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Application\DTO\Auth\AuthPayloadDto;

final class AuthResource
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(AuthPayloadDto $payload): array
    {
        return [
            'token' => $payload->getToken(),
            'token_type' => $payload->getTokenType(),
            'user' => UserResource::toArray($payload->getUser()),
        ];
    }
}
