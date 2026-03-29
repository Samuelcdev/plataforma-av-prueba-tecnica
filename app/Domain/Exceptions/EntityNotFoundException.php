<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

final class EntityNotFoundException extends DomainException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message = 'Entity was not found.',
        array $context = [],
        ?string $errorCode = 'ENTITY_NOT_FOUND',
    ) {
        parent::__construct($message, $context, $errorCode);
    }

    public static function for(string $entity, string|int $id): self
    {
        return new self(
            sprintf('%s with id [%s] was not found.', $entity, (string) $id),
            [
                'entity' => $entity,
                'id' => $id,
            ],
        );
    }
}
