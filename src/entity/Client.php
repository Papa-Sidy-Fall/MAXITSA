<?php

namespace App\Entity;

class Client
{
    private ?int $id = null;
    private string $prenom;
    private string $nom;
    private string $email;
    private string $cni;
    private string $password;
    private ?\DateTime $createdAt = null;
    private ?\DateTime $updatedAt = null;

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getPrenom(): string { return $this->prenom; }
    public function getNom(): string { return $this->nom; }
    public function getEmail(): string { return $this->email; }
    public function getCni(): string { return $this->cni; }
    public function getPassword(): string { return $this->password; }
    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function getUpdatedAt(): ?\DateTime { return $this->updatedAt; }

    // Setters
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function setCni(string $cni): self { $this->cni = $cni; return $this; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    public function setCreatedAt(?\DateTime $createdAt): self { $this->createdAt = $createdAt; return $this; }
    public function setUpdatedAt(?\DateTime $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }
}