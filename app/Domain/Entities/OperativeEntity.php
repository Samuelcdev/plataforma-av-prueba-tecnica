<?php

namespace App\Domain\Entities;

final class OperativeEntity
{
    private string $id;

    private string $documentType;

    private string $document;

    private string $name;

    private bool $isActive;

    public function __construct(
        string $id,
        string $documentType,
        string $document,
        string $name,
        bool $isActive = true,
    ) {
        $this->id = $id;
        $this->documentType = $documentType;
        $this->document = $document;
        $this->name = $name;
        $this->isActive = $isActive;
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

    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    public function setDocumentType(string $documentType): self
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function setDocument(string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
