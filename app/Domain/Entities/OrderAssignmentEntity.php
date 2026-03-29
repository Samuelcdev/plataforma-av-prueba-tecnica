<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

final class OrderAssignmentEntity
{
    private string $id;

    private string $orderId;

    private string $operativeId;

    private string $adminId;

    private ?DateTimeImmutable $assignedAt;

    public function __construct(
        string $id,
        string $orderId,
        string $operativeId,
        string $adminId,
        ?DateTimeImmutable $assignedAt = null,
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->operativeId = $operativeId;
        $this->adminId = $adminId;
        $this->assignedAt = $assignedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getOperativeId(): string
    {
        return $this->operativeId;
    }

    public function setOperativeId(string $operativeId): self
    {
        $this->operativeId = $operativeId;

        return $this;
    }

    public function getAdminId(): string
    {
        return $this->adminId;
    }

    public function setAdminId(string $adminId): self
    {
        $this->adminId = $adminId;

        return $this;
    }

    public function getAssignedAt(): ?DateTimeImmutable
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(?DateTimeImmutable $assignedAt): self
    {
        $this->assignedAt = $assignedAt;

        return $this;
    }
}
