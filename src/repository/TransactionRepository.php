<?php

namespace App\Repository;

use PDO;

class TransactionRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

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
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':compte_id', $compteId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function countTransactionsByCompte(int $compteId): int
    {
        $sql = "SELECT COUNT(*) FROM transaction 
                WHERE expediteur_id = :compte_id OR destinataire_id = :compte_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['compte_id' => $compteId]);
        
        return $stmt->fetchColumn();
    }

    public function getSoldeByCompte(int $compteId): float
    {
        $sql = "SELECT solde FROM compte WHERE id = :compte_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['compte_id' => $compteId]);
        
        return (float) $stmt->fetchColumn();
    }
}