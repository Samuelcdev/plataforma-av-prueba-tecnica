<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

final class UnauthorizedDomainException extends DomainException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message = 'Action is not authorized.',
        array $context = [],
        ?string $errorCode = 'UNAUTHORIZED_ACTION',
    ) {
        parent::__construct($message, $context, $errorCode);
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function accessDenied(string $reason = 'Access denied.', array $context = []): self
    {
        return new self($reason, $context);
    }
}
