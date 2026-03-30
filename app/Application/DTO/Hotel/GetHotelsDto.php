<?php

declare(strict_types=1);

namespace App\Application\DTO\Hotel;

final class GetHotelsDto
{
    public function __construct(
        private ?string $search = null,
    ) {
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }
}
