<?php

declare(strict_types=1);

namespace App\Application\DTO\Order;

final class AssignOrderOperativesDto
{
    /**
     * @param list<string> $operativeIds
     */
    public function __construct(
        private string $orderId,
        private array $operativeIds,
    ) {
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return list<string>
     */
    public function getOperativeIds(): array
    {
        return $this->operativeIds;
    }
}
