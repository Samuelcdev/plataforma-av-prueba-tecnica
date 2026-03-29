<?php

declare(strict_types=1);

namespace App\Application\DTO\Order;

final class OrderItemInputDto
{
    public function __construct(
        private string $itemId,
        private int $quantity,
    ) {
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
