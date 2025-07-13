<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Core\Interfaces\DatabaseConnectionInterface;
use App\Entity\Client;

class ClientRepository extends AbstractRepository
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
        return 'client';
    }

    /**
     * Hydrater une entité Client
     */
    protected function hydrate(object $entity, array $data): object
    {
        if (!$entity instanceof Client) {
            $entity = new Client();
        }

        $entity->setId($data['id'] ?? null);
        $entity->setPrenom($data['prenom'] ?? '');
        $entity->setNom($data['nom'] ?? '');
        $entity->setEmail($data['email'] ?? '');
        $entity->setCni($data['cni'] ?? '');
        $entity->setPassword($data['password'] ?? '');

        return $entity;
    }

    /**
     * Créer un client avec entité
     */
    public function createClient(Client $client): ?int
    {
        $data = [
            'prenom' => $client->getPrenom(),
            'nom' => $client->getNom(),
            'email' => $client->getEmail(),
            'cni' => $client->getCni(),
            'password' => $client->getPassword(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->create($data);
    }
    
    /**
     * Trouver un client par email
     */
    public function findByEmail(string $email): ?Client
    {
        $sql = "SELECT * FROM client WHERE email = :email";
        $data = $this->queryOne($sql, ['email' => $email]);
        
        if (!$data) return null;
        
        return $this->hydrate(new Client(), $data);
    }
    
    /**
     * Trouver un client par numéro de téléphone
     */
    public function findByPhone(string $phone): ?Client
    {
        $sql = "SELECT c.* FROM client c 
                INNER JOIN compte co ON c.id = co.client_id 
                WHERE co.numtel = :phone";
        $data = $this->queryOne($sql, ['phone' => $phone]);
        
        if (!$data) return null;
        
        return $this->hydrate(new Client(), $data);
    }
    
    /**
     * Vérifier si un email existe
     */
    public function emailExists(string $email): bool
    {
        $sql = "SELECT COUNT(*) as total FROM client WHERE email = :email";
        $result = $this->queryOne($sql, ['email' => $email]);
        return $result && (int)$result['total'] > 0;
    }
    
    /**
     * Vérifier si un CNI existe
     */
    public function cniExists(string $cni): bool
    {
        $sql = "SELECT COUNT(*) as total FROM client WHERE cni = :cni";
        $result = $this->queryOne($sql, ['cni' => $cni]);
        return $result && (int)$result['total'] > 0;
    }

    /**
     * Vérifier si un téléphone existe
     */
    public function telephoneExists(string $telephone): bool
    {
        $sql = "SELECT COUNT(*) as total FROM compte WHERE numtel = :telephone";
        $result = $this->queryOne($sql, ['telephone' => $telephone]);
        return $result && (int)$result['total'] > 0;
    }

    /**
     * Trouver un client par email ou téléphone
     */
    public function findByEmailOrPhone(string $login): ?array
    {
        $sql = "SELECT c.*, co.id as compte_id, co.numtel as num_tel 
                FROM client c 
                LEFT JOIN compte co ON c.id = co.client_id 
                WHERE c.email = :login OR co.numtel = :login";
        
        return $this->queryOne($sql, ['login' => $login]);
    }
}