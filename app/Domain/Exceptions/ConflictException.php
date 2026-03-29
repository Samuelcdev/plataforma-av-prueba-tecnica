<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

final class ConflictException extends DomainException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message = 'A domain conflict occurred.',
        array $context = [],
        ?string $errorCode = 'DOMAIN_CONFLICT',
    ) {
        parent::__construct($message, $context, $errorCode);
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function because(string $reason, array $context = []): self
    {
        return new self($reason, $context);
    }
}
