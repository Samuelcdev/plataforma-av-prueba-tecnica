<?php

namespace App\Domain\Entities;

final class AdminEntity
{
    private string $id;
    private string $userId;
    private string $documentType;
    private string $document;
    private string $name;
    private ?string $email;
    private ?string $phone;

    public function __construct(
        string $id,
        string $userId,
        string $documentType,
        string $document,
        string $name,
        ?string $email = null,
        ?string $phone = null,
    ) {
        $this->setId($id);
        $this->setUserId($userId);
        $this->setDocumentType($documentType);
        $this->setDocument($document);
        $this->setName($name);
        $this->setEmail($email);
        $this->setPhone($phone);
    }

    // Getters and Setters
    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
