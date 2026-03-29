<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

final class InvalidCredentialsException extends DomainException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message = 'Invalid credentials.',
        array $context = [],
        ?string $errorCode = 'INVALID_CREDENTIALS',
    ) {
        parent::__construct($message, $context, $errorCode);
    }

    public static function default(): self
    {
        return new self();
    }
}
