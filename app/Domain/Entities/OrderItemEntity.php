<?php

namespace App\Domain\Entities;

final class OrderItemEntity
{
    private string $id;

    private string $orderId;

    private string $itemId;

    private int $quantity;

    private ?string $itemName;

    public function __construct(string $id, string $orderId, string $itemId, int $quantity = 1, ?string $itemName = null)
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->itemId = $itemId;
        $this->quantity = $quantity;
        $this->itemName = $itemName;
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

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function setItemId(string $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(?string $itemName): self
    {
        $this->itemName = $itemName;

        return $this;
    }
}
