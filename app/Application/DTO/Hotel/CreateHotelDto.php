<?php

declare(strict_types=1);

namespace App\Application\DTO\Hotel;

final class CreateHotelDto
{
    public function __construct(
        private string $username,
        private string $nit,
        private string $documentType,
        private string $name,
        private ?string $phone = null,
        private ?string $address = null,
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getNit(): string
    {
        return $this->nit;
    }

    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
}
