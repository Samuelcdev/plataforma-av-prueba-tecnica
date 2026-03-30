<?php

declare(strict_types=1);

namespace App\Application\DTO\Order;

final class GetOrdersDto
{
    public function __construct(
        private ?string $search = null,
        private int $page = 1,
        private int $total = 10,
    ) {
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
