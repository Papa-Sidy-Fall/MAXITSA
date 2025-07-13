<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Core\Interfaces\DatabaseConnectionInterface;
use App\Entity\Transaction;

class TransactionRepository extends AbstractRepository
{
    public function __construct(DatabaseConnectionInterface $db)
    {
        parent::__construct($db);
    }

    /**
     * Obtenir le nom de la table
     */
    protected function getTableName(): string
    {
        return 'transaction';
    }

    /**
     * Hydrater une entité Transaction
     */
    protected function hydrate(object $entity, array $data): object
    {
        if (!$entity instanceof Transaction) {
            $entity = new Transaction();
        }

        // Ici on peut hydrater l'entité Transaction si elle existe
        // Pour l'instant on retourne l'entity telle quelle
        return $entity;
    }

    /**
     * Récupérer les transactions d'un compte (méthode existante)
     */
    public function getTransactionsByCompte(int $compteId, int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT t.*, 
                       c_exp.numtel as expediteur_tel,
                       c_dest.numtel as destinataire_tel,
                       cl_exp.prenom || ' ' || cl_exp.nom as expediteur_nom,
                       cl_dest.prenom || ' ' || cl_dest.nom as destinataire_nom
                FROM transaction t
                LEFT JOIN compte c_exp ON t.expediteur_id = c_exp.id
                LEFT JOIN compte c_dest ON t.destinataire_id = c_dest.id
                LEFT JOIN client cl_exp ON c_exp.client_id = cl_exp.id
                LEFT JOIN client cl_dest ON c_dest.client_id = cl_dest.id
                WHERE t.expediteur_id = :compte_id OR t.destinataire_id = :compte_id
                ORDER BY t.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        return $this->query($sql, [
            'compte_id' => $compteId,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * Compter les transactions d'un compte (méthode existante)
     */
    public function countTransactionsByCompte(int $compteId): int
    {
        $sql = "SELECT COUNT(*) as total FROM transaction 
                WHERE expediteur_id = :compte_id OR destinataire_id = :compte_id";
        
        $result = $this->queryOne($sql, ['compte_id' => $compteId]);
        return $result ? (int)$result['total'] : 0;
    }

    /**
     * Récupérer le solde d'un compte (méthode existante)
     */
    public function getSoldeByCompte(int $compteId): float
    {
        $sql = "SELECT solde FROM compte WHERE id = :compte_id";
        $result = $this->queryOne($sql, ['compte_id' => $compteId]);
        return $result ? (float)$result['solde'] : 0.0;
    }

    /**
     * Récupérer les transactions récentes d'un compte
     */
    public function findRecentByCompte(int $compteId, int $limit = 10): array
    {
        $sql = "SELECT t.*, 
                       c1.numtel as expediteur_numtel,
                       c2.numtel as destinataire_numtel
                FROM transaction t
                LEFT JOIN compte c1 ON t.expediteur_id = c1.id
                LEFT JOIN compte c2 ON t.destinataire_id = c2.id
                WHERE t.expediteur_id = :compte_id OR t.destinataire_id = :compte_id
                ORDER BY t.created_at DESC
                LIMIT :limit";
        
        return $this->query($sql, ['compte_id' => $compteId, 'limit' => $limit]);
    }

    /**
     * Récupérer les transactions avec filtres et pagination
     */
    public function findWithFilters(int $compteId, array $conditions, array $params, int $limit, int $offset): array
    {
        $whereClause = "(expediteur_id = :compte_id OR destinataire_id = :compte_id)";
        
        if (!empty($conditions)) {
            $whereClause .= " AND " . implode(" AND ", $conditions);
        }

        $sql = "SELECT t.*, 
                       c1.numtel as expediteur_numtel,
                       c2.numtel as destinataire_numtel
                FROM transaction t
                LEFT JOIN compte c1 ON t.expediteur_id = c1.id
                LEFT JOIN compte c2 ON t.destinataire_id = c2.id
                WHERE $whereClause
                ORDER BY t.created_at DESC
                LIMIT :limit OFFSET :offset";

        $params['limit'] = $limit;
        $params['offset'] = $offset;

        return $this->query($sql, $params);
    }

    /**
     * Compter les transactions avec filtres
     */
    public function countWithFilters(int $compteId, array $conditions, array $params): int
    {
        $whereClause = "(expediteur_id = :compte_id OR destinataire_id = :compte_id)";
        
        if (!empty($conditions)) {
            $whereClause .= " AND " . implode(" AND ", $conditions);
        }

        $sql = "SELECT COUNT(*) as total FROM transaction WHERE $whereClause";

        $result = $this->queryOne($sql, $params);
        return $result ? (int)$result['total'] : 0;
    }
}