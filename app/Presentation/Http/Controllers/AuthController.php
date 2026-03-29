<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Application\DTO\Auth\CurrentUserDto;
use App\Application\DTO\Auth\LoginDto;
use App\Application\Services\AuthService;
use App\Presentation\Http\Requests\LoginRequest;
use App\Presentation\Http\Resources\AuthResource;
use App\Presentation\Http\Resources\UserResource;
use App\Presentation\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

final class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $dto = new LoginDto(
            username: (string) $payload['username'],
            password: (string) $payload['password'],
            deviceName: (string) ($payload['device_name'] ?? 'api-token'),
        );

        $result = $this->authService->login($dto);

        return ApiResponse::success(
            data: AuthResource::toArray($result),
            message: 'Login successful',
            status: 200,
        );
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout(new CurrentUserDto(request()->bearerToken()));

        return ApiResponse::success(
            data: null,
            message: 'Logout successful',
            status: 200,
        );
    }

    public function me(): JsonResponse
    {
        $user = $this->authService->me(new CurrentUserDto(request()->bearerToken()));

        return ApiResponse::success(
            data: UserResource::toArray($user),
            message: 'Authenticated user retrieved',
            status: 200,
        );
    }
}
