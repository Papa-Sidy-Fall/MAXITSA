<?php

namespace App\Repository;

use App\Entity\Compte;
use PDO;

class CompteRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Compte $compte): ?int
    {
        $sql = "INSERT INTO compte (numtel, photocnirecto, photocniverso, client_id, typedecompte, solde, status, created_at, updated_at) 
                VALUES (:numtel, :photocnirecto, :photocniverso, :client_id, :typedecompte, :solde, :status, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'numtel' => $compte->getNumTel(),
            'photocnirecto' => $compte->getPhotoCniRecto(),
            'photocniverso' => $compte->getPhotoCniVerso(),
            'client_id' => $compte->getClientId(),
            'typedecompte' => $compte->getTypeDeCompte(),
            'solde' => $compte->getSolde(),
            'status' => $compte->getStatus()
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    public function findByPhone(string $phone): ?Compte
    {
        $sql = "SELECT * FROM compte WHERE numtel = :phone";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['phone' => $phone]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        
        return $this->hydrate(new Compte(), $data);
    }
    
    public function phoneExists(string $telephone): bool
    {
        $sql = "SELECT COUNT(*) FROM compte WHERE telephone = :telephone";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['telephone' => $telephone]);
        return $stmt->fetchColumn() > 0;
    }
}