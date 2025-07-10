<?php

namespace App\Entity;

class Compte
{
    private ?int $id = null;
    private string $numTel;
    private ?string $photoCniRecto = null;
    private ?string $photoCniVerso = null;
    private int $clientId;
    private string $typeDeCompte = 'principal';
    private float $solde = 0.00;
    private string $status = 'actif';
    private ?\DateTime $createdAt = null;
    private ?\DateTime $updatedAt = null;

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNumTel(): string { return $this->numTel; }
    public function getPhotoCniRecto(): ?string { return $this->photoCniRecto; }
    public function getPhotoCniVerso(): ?string { return $this->photoCniVerso; }
    public function getClientId(): int { return $this->clientId; }
    public function getTypeDeCompte(): string { return $this->typeDeCompte; }
    public function getSolde(): float { return $this->solde; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }

    // Setters
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setNumTel(string $numTel): self { $this->numTel = $numTel; return $this; }
    public function setPhotoCniRecto(?string $photoCniRecto): self { $this->photoCniRecto = $photoCniRecto; return $this; }
    public function setPhotoCniVerso(?string $photoCniVerso): self { $this->photoCniVerso = $photoCniVerso; return $this; }
    public function setClientId(int $clientId): self { $this->clientId = $clientId; return $this; }
    public function setTypeDeCompte(string $typeDeCompte): self { $this->typeDeCompte = $typeDeCompte; return $this; }
    public function setSolde(float $solde): self { $this->solde = $solde; return $this; }
    public function setStatus(string $status): self { $this->status = $status; return $this; }
    public function setCreatedAt(?\DateTime $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function setUpdatedAt(?\DateTime $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
}