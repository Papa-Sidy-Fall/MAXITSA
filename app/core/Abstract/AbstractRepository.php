<?php

namespace App\Core\Abstract;

use App\Core\Interfaces\DatabaseConnectionInterface;
use App\Core\Interfaces\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    protected DatabaseConnectionInterface $db;

    public function __construct(DatabaseConnectionInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Obtenir le nom de la table (à implémenter dans les classes enfants)
     */
    abstract protected function getTableName(): string;

    /**
     * Hydrater une entité avec les données (à implémenter dans les classes enfants)
     */
    abstract protected function hydrate(object $entity, array $data): object;

    /**
     * Créer une nouvelle entité
     */
    public function create(array $data): ?int
    {
        $fields = array_keys($data);
        $placeholders = array_map(fn($field) => ":$field", $fields);
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->getTableName(),
            implode(', ', $fields),
            implode(', ', $placeholders)
        );
        
        if ($this->db->execute($sql, $data)) {
            return (int)$this->db->lastInsertId();
        }
        
        return null;
    }

    /**
     * Trouver une entité par ID
     */
    public function findById(int $id): ?array
    {
        $sql = sprintf("SELECT * FROM %s WHERE id = :id", $this->getTableName());
        return $this->db->fetch($sql, ['id' => $id]);
    }

    /**
     * Trouver toutes les entités
     */
    public function findAll(): array
    {
        $sql = sprintf("SELECT * FROM %s", $this->getTableName());
        return $this->db->fetchAll($sql);
    }

    /**
     * Mettre à jour une entité
     */
    public function update(int $id, array $data): bool
    {
        $fields = array_keys($data);
        $setClause = implode(', ', array_map(fn($field) => "$field = :$field", $fields));
        
        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = :id",
            $this->getTableName(),
            $setClause
        );
        
        $data['id'] = $id;
        return $this->db->execute($sql, $data);
    }

    /**
     * Supprimer une entité
     */
    public function delete(int $id): bool
    {
        $sql = sprintf("DELETE FROM %s WHERE id = :id", $this->getTableName());
        return $this->db->execute($sql, ['id' => $id]);
    }

    /**
     * Compter le nombre d'entités
     */
    public function count(): int
    {
        $sql = sprintf("SELECT COUNT(*) as total FROM %s", $this->getTableName());
        $result = $this->db->fetch($sql);
        return $result ? (int)$result['total'] : 0;
    }

    /**
     * Vérifier si une entité existe
     */
    public function exists(int $id): bool
    {
        $sql = sprintf("SELECT COUNT(*) as total FROM %s WHERE id = :id", $this->getTableName());
        $result = $this->db->fetch($sql, ['id' => $id]);
        return $result && (int)$result['total'] > 0;
    }

    /**
     * Commencer une transaction
     */
    public function beginTransaction(): bool
    {
        return $this->db->beginTransaction();
    }

    /**
     * Valider une transaction
     */
    public function commit(): bool
    {
        return $this->db->commit();
    }

    /**
     * Annuler une transaction
     */
    public function rollback(): bool
    {
        return $this->db->rollback();
    }

    /**
     * Vérifier si une transaction est active
     */
    public function inTransaction(): bool
    {
        return $this->db->inTransaction();
    }

    /**
     * Exécuter une requête SQL personnalisée
     */
    protected function query(string $sql, array $params = []): array
    {
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Exécuter une requête SQL personnalisée qui retourne une seule ligne
     */
    protected function queryOne(string $sql, array $params = []): ?array
    {
        return $this->db->fetch($sql, $params);
    }
}
