<?php

namespace App\Core\Interfaces;

use PDO;

interface DatabaseConnectionInterface
{
    /**
     * Obtenir l'instance de connexion
     */
    public static function getInstance(): self;
    
    /**
     * Obtenir la connexion PDO
     */
    public function getConnection(): PDO;
    
    /**
     * Tester la connexion
     */
    public function testConnection(): bool;
    
    /**
     * Commencer une transaction
     */
    public function beginTransaction(): bool;
    
    /**
     * Valider une transaction
     */
    public function commit(): bool;
    
    /**
     * Annuler une transaction
     */
    public function rollback(): bool;
    
    /**
     * Vérifier si une transaction est active
     */
    public function inTransaction(): bool;
    
    /**
     * Exécuter une requête préparée
     */
    public function execute(string $query, array $params = []): bool;
    
    /**
     * Récupérer des données
     */
    public function fetch(string $query, array $params = []): ?array;
    
    /**
     * Récupérer toutes les données
     */
    public function fetchAll(string $query, array $params = []): array;
    
    /**
     * Obtenir le dernier ID inséré
     */
    public function lastInsertId(): string;
}
