<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Infrastructure\Auth\SanctumAuthProviderInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthMiddleware
{
    public function __construct(private SanctumAuthProviderInterface $sanctumAuth)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->sanctumAuth->currentUserId() === null) {
            throw UnauthorizedDomainException::accessDenied('Unauthenticated.');
        }

        return $next($request);
    }
}
