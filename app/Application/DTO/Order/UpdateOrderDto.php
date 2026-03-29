<?php

declare(strict_types=1);

namespace App\Application\DTO\Order;

use DateTimeImmutable;

final class UpdateOrderDto
{
    /**
     * @param list<OrderItemInputDto> $items
     */
    public function __construct(
        private string $id,
        private string $name,
        private string $serviceType,
        private DateTimeImmutable $startDate,
        private DateTimeImmutable $endDate,
        private array $items,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getServiceType(): string
    {
        return $this->serviceType;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @return list<OrderItemInputDto>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
