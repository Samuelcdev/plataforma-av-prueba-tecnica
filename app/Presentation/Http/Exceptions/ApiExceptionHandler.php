<?php

declare(strict_types=1);

namespace App\Presentation\Http\Exceptions;

use App\Domain\Exceptions\DomainException;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\ConflictException;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Domain\Exceptions\ValidationException as DomainValidationException;
use App\Presentation\Http\Responses\ApiResponse;
use Illuminate\Foundation\Configuration\Exceptions;
use Throwable;

final class ApiExceptionHandler
{
    public static function register(Exceptions $exceptions): void
    {
        $exceptions->render(function (InvalidCredentialsException $e) {
            return ApiResponse::error($e->getMessage(), 401);
        });

        $exceptions->render(function (UnauthorizedDomainException $e) {
            return ApiResponse::error($e->getMessage(), 401);
        });

        $exceptions->render(function (EntityNotFoundException $e) {
            return ApiResponse::error($e->getMessage(), 404);
        });

        $exceptions->render(function (ConflictException $e) {
            return ApiResponse::error($e->getMessage(), 409);
        });

        $exceptions->render(function (DomainValidationException $e) {
            return ApiResponse::error(
                message: $e->getMessage(),
                status: 422,
            );
        });

        $exceptions->render(function (DomainException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        });

        $exceptions->render(function (Throwable $e) {
            if (app()->environment('local')) {
                \Illuminate\Support\Facades\Log::error($e->getMessage());
            }

            return ApiResponse::error(
                message: 'Hubo un error en el servidor. Contacta con soporte.',
                status: 500,
            );
        });
    }
}
