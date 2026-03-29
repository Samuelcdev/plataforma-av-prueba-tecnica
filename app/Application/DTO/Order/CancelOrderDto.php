<?php

declare(strict_types=1);

namespace App\Application\DTO\Order;

final class CancelOrderDto
{
    public function __construct(private string $orderId)
    {
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}
