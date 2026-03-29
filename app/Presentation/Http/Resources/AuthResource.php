<?php

declare(strict_types=1);

namespace App\Presentation\Http\Resources;

use App\Application\DTO\Auth\AuthPayloadDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property AuthPayloadDto $resource
 */
final class AuthResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payload = $this->resource;

        return [
            'token' => $payload->getToken(),
            'token_type' => $payload->getTokenType(),
            'user' => UserResource::make($payload->getUser()),
        ];
    }
}
