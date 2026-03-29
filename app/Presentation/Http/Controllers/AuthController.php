<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Application\DTO\Auth\CurrentUserDto;
use App\Application\DTO\Auth\LoginDto;
use App\Application\Services\AuthService;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Domain\Exceptions\ValidationException as DomainValidationException;
use App\Presentation\Http\Requests\LoginRequest;
use App\Presentation\Http\Resources\AuthResource;
use App\Presentation\Http\Resources\UserResource;
use App\Presentation\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

final class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $payload = $request->validated();

        try {
            $result = $this->authService->login(new LoginDto(
                username: (string) $payload['username'],
                password: (string) $payload['password'],
                deviceName: (string) ($payload['device_name'] ?? 'api-token'),
            ));

            return ApiResponse::success(
                data: AuthResource::make($result),
                message: 'Login successful.',
                status: 200,
                total: 1,
            );
        } catch (InvalidCredentialsException $e) {
            return ApiResponse::error($e->getMessage(), 401);
        } catch (UnauthorizedDomainException $e) {
            return ApiResponse::error($e->getMessage(), 403);
        } catch (DomainValidationException $e) {
            return ApiResponse::error($e->getMessage(), 422, ['errors' => $e->getErrors()]);
        } catch (Throwable $e) {
            return ApiResponse::error('Authentication error.', 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout(new CurrentUserDto(request()->bearerToken()));

            return ApiResponse::success(
                data: null,
                message: 'Logout successful.',
                status: 200,
                total: 0,
            );
        } catch (UnauthorizedDomainException $e) {
            return ApiResponse::error($e->getMessage(), 401);
        } catch (Throwable $e) {
            return ApiResponse::error('Logout error.', 500);
        }
    }

    public function me(): JsonResponse
    {
        try {
            $user = $this->authService->me(new CurrentUserDto(request()->bearerToken()));

            return ApiResponse::success(
                data: UserResource::make($user),
                message: 'Authenticated user retrieved.',
                status: 200,
                total: 1,
            );
        } catch (UnauthorizedDomainException $e) {
            return ApiResponse::error($e->getMessage(), 401);
        } catch (EntityNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        } catch (Throwable $e) {
            return ApiResponse::error('Authentication error.', 500);
        }
    }
}
