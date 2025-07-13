<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Core\Interfaces\DatabaseConnectionInterface;
use App\Entity\Compte;

class CompteRepository extends AbstractRepository
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
        return 'compte';
    }

    /**
     * Hydrater une entité Compte
     */
    protected function hydrate(object $entity, array $data): object
    {
        if (!$entity instanceof Compte) {
            $entity = new Compte();
        }

        $entity->setId($data['id'] ?? null);
        $entity->setNumTel($data['numtel'] ?? '');
        $entity->setPhotoCniRecto($data['photocnirecto'] ?? null);
        $entity->setPhotoCniVerso($data['photocniverso'] ?? null);
        $entity->setClientId($data['client_id'] ?? 0);
        $entity->setTypeDeCompte($data['typedecompte'] ?? 'principal');
        $entity->setSolde($data['solde'] ?? 0.00);
        $entity->setStatus($data['status'] ?? 'actif');

        return $entity;
    }

    /**
     * Créer un compte
     */
    public function createCompte(Compte $compte): ?int
    {
        $data = [
            'numtel' => $compte->getNumTel(),
            'photocnirecto' => $compte->getPhotoCniRecto(),
            'photocniverso' => $compte->getPhotoCniVerso(),
            'client_id' => $compte->getClientId(),
            'typedecompte' => $compte->getTypeDeCompte(),
            'solde' => $compte->getSolde(),
            'status' => $compte->getStatus(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->create($data);
    }

    /**
     * Trouver tous les comptes d'un client
     */
    public function findByClient(int $clientId): array
    {
        $sql = "SELECT * FROM compte WHERE client_id = :client_id ORDER BY typedecompte DESC, created_at ASC";
        return $this->query($sql, ['client_id' => $clientId]);
    }

    /**
     * Trouver un compte par numéro de téléphone
     */
    public function findByNumtel(string $numtel): ?array
    {
        $sql = "SELECT c.*, cl.prenom, cl.nom, cl.email 
                FROM compte c 
                JOIN client cl ON c.client_id = cl.id 
                WHERE c.numtel = :numtel";
        return $this->queryOne($sql, ['numtel' => $numtel]);
    }

    /**
     * Trouver un compte par téléphone (compatibilité)
     */
    public function findByPhone(string $phone): ?Compte
    {
        $sql = "SELECT * FROM compte WHERE numtel = :phone";
        $data = $this->queryOne($sql, ['phone' => $phone]);
        
        if (!$data) return null;
        
        return $this->hydrate(new Compte(), $data);
    }

    /**
     * Trouver un compte par ID et client
     */
    public function findByIdAndClient(int $compteId, int $clientId): ?array
    {
        $sql = "SELECT * FROM compte WHERE id = :id AND client_id = :client_id";
        return $this->queryOne($sql, ['id' => $compteId, 'client_id' => $clientId]);
    }

    /**
     * Mettre à jour le type de tous les comptes d'un client
     */
    public function updateTypeCompte(int $clientId, string $oldType, string $newType): bool
    {
        $sql = "UPDATE compte SET typedecompte = :new_type, updated_at = CURRENT_TIMESTAMP 
                WHERE client_id = :client_id AND typedecompte = :old_type";
        return $this->db->execute($sql, [
            'client_id' => $clientId,
            'old_type' => $oldType,
            'new_type' => $newType
        ]);
    }

    /**
     * Mettre à jour le type d'un compte spécifique
     */
    public function updateCompteType(int $compteId, string $type): bool
    {
        $sql = "UPDATE compte SET typedecompte = :type, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        return $this->db->execute($sql, ['id' => $compteId, 'type' => $type]);
    }

    /**
     * Vérifier si un numéro de téléphone existe (compatibilité)
     */
    public function phoneExists(string $telephone): bool
    {
        $sql = "SELECT COUNT(*) FROM compte WHERE numtel = :telephone";
        $result = $this->queryOne($sql, ['telephone' => $telephone]);
        return $result && $result['count'] > 0;
    }
}