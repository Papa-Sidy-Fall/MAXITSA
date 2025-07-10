<?php

namespace App\Repository;

use App\Entity\Client;
use PDO;

class ClientRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Client $client): ?int
    {
        $sql = "INSERT INTO client (prenom, nom, email, cni, password, created_at, updated_at) 
                VALUES (:prenom, :nom, :email, :cni, :password, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'prenom' => $client->getPrenom(),
            'nom' => $client->getNom(),
            'email' => $client->getEmail(),
            'cni' => $client->getCni(),
            'password' => $client->getPassword()
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    public function findByEmail(string $email): ?Client
    {
        $sql = "SELECT * FROM client WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        
        return $this->hydrate(new Client(), $data);
    }
    
    public function findByPhone(string $phone): ?Client
    {
        $sql = "SELECT c.* FROM client c 
                INNER JOIN compte co ON c.id = co.client_id 
                WHERE co.numtel = :phone";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['phone' => $phone]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;
        
        return $this->hydrate(new Client(), $data);
    }
    
    public function emailExists(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM client WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function cniExists(string $cni): bool
    {
        $sql = "SELECT COUNT(*) FROM client WHERE cni = :cni";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['cni' => $cni]);
        return $stmt->fetchColumn() > 0;
    }

    public function telephoneExists(string $telephone): bool
    {
        $sql = "SELECT COUNT(*) FROM compte WHERE numtel = :telephone";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['telephone' => $telephone]);
        return $stmt->fetchColumn() > 0;
    }

    public function findByEmailOrPhone(string $login): ?array
    {
        $sql = "SELECT c.*, co.id as compte_id, co.numtel as num_tel 
                FROM client c 
                LEFT JOIN compte co ON c.id = co.client_id 
                WHERE c.email = :login OR co.numtel = :login";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['login' => $login]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
}