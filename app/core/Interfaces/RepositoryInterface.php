<?php

namespace App\Core\Interfaces;

interface RepositoryInterface
{
    /**
     * Créer une nouvelle entité
     */
    public function create(array $data): ?int;

    /**
     * Trouver une entité par ID
     */
    public function findById(int $id): ?array;

    /**
     * Trouver toutes les entités
     */
    public function findAll(): array;

    /**
     * Mettre à jour une entité
     */
    public function update(int $id, array $data): bool;

    /**
     * Supprimer une entité
     */
    public function delete(int $id): bool;

    /**
     * Compter le nombre d'entités
     */
    public function count(): int;

    /**
     * Vérifier si une entité existe
     */
    public function exists(int $id): bool;
}
