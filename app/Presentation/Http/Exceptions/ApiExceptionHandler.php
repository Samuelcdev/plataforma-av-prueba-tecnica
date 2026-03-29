<?php

declare(strict_types=1);

namespace App\Presentation\Http\Exceptions;

use App\Domain\Exceptions\DomainException;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\ConflictException;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Domain\Exceptions\ValidationException as DomainValidationException;
use App\Infrastructure\Logging\LoggerInterface;
use App\Presentation\Http\Responses\ApiResponse;
use Illuminate\Foundation\Configuration\Exceptions;
use Throwable;

final class ApiExceptionHandler
{
    public static function register(Exceptions $exceptions): void
    {
        $exceptions->render(function (InvalidCredentialsException $e) {
            app(LoggerInterface::class)->warning('Invalid credentials attempt');
            return ApiResponse::error($e->getMessage(), 401);
        });

        $exceptions->render(function (UnauthorizedDomainException $e) {
            app(LoggerInterface::class)->warning('Unauthorized access attempt');
            return ApiResponse::error($e->getMessage(), 401);
        });

        $exceptions->render(function (EntityNotFoundException $e) {
            app(LoggerInterface::class)->info('Entity not found');
            return ApiResponse::error($e->getMessage(), 404);
        });

        $exceptions->render(function (ConflictException $e) {
            app(LoggerInterface::class)->warning('Conflict detected');
            return ApiResponse::error($e->getMessage(), 409);
        });

        $exceptions->render(function (DomainValidationException $e) {
            app(LoggerInterface::class)->warning('Validation error');
            return ApiResponse::error(
                message: $e->getMessage(),
                status: 422,
            );
        });

        $exceptions->render(function (DomainException $e) {
            app(LoggerInterface::class)->error('Domain exception');
            return ApiResponse::error($e->getMessage(), 400);
        });

        $exceptions->render(function (Throwable $e) {
            app(LoggerInterface::class)->critical('Unhandled exception', [
                'exception' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return ApiResponse::error(
                message: 'Hubo un error en el servidor. Contacta con soporte.',
                status: 500,
            );
        });
    }
}
