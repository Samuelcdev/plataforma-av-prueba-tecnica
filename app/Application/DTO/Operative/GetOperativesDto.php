<?php

declare(strict_types=1);

namespace App\Application\DTO\Operative;

final class GetOperativesDto
{
    public function __construct(
        private ?string $search = null,
        private int $page = 1,
        private int $total = 10,
        private ?bool $isActive = true,
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }
}
