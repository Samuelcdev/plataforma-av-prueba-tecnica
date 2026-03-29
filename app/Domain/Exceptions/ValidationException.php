<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use Throwable;

final class ValidationException extends DomainException
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $errors;

    /**
     * @param array<string, array<int, string>|string> $errors
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message = 'Validation failed',
        array $errors = [],
        array $context = [],
        ?string $errorCode = 'VALIDATION_ERROR',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        $this->errors = self::normalizeErrors($errors);

        parent::__construct(
            $message,
            array_merge($context, ['errors' => $this->errors]),
            $errorCode,
            $code,
            $previous,
        );
    }

    /**
     * @param array<string, array<int, string>|string> $errors
     */
    public static function withErrors(array $errors, string $message = 'Validation failed'): self
    {
        return new self($message, $errors);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<string, array<int, string>|string> $errors
     * @return array<string, array<int, string>>
     */
    private static function normalizeErrors(array $errors): array
    {
        $normalized = [];

        foreach ($errors as $field => $messages) {
            if (is_string($messages)) {
                $normalized[$field] = [$messages];
                continue;
            }

            $normalized[$field] = array_values(
                array_map(
                    static fn (mixed $message): string => (string) $message,
                    $messages
                )
            );
        }

        return $normalized;
    }
}
