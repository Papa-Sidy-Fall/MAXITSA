<?php

namespace App\Service;

use App\Core\Validator;
use App\Repository\CompteRepository;
use App\Repository\TransactionRepository;

class CompteService
{
    private CompteRepository $compteRepo;
    private TransactionRepository $transactionRepo;
    private Validator $validator;

    public function __construct(
        CompteRepository $compteRepo,
        TransactionRepository $transactionRepo,
        Validator $validator
    ) {
        $this->compteRepo = $compteRepo;
        $this->transactionRepo = $transactionRepo;
        $this->validator = $validator;
    }

    /**
     * Récupérer tous les comptes d'un client
     */
    public function getComptesByClient(int $clientId): array
    {
        return $this->compteRepo->findByClient($clientId);
    }

    /**
     * Créer un compte secondaire (US2)
     */
    public function createCompteSecondaire(int $clientId, array $data, array $files): array
    {
        // Validation
        $rules = [
            'numtel' => 'required|phone',
        ];

        $messages = [
            'numtel.required' => 'Le numéro de téléphone est requis',
            'numtel.phone' => 'Format de numéro de téléphone invalide',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // Vérifier que le numéro n'existe pas déjà
        if ($this->compteRepo->findByNumtel($data['numtel'])) {
            return [
                'success' => false,
                'errors' => ['numtel' => 'Ce numéro est déjà utilisé']
            ];
        }

        try {
            $this->compteRepo->beginTransaction();

            // Préparer les données du compte
            $compteData = [
                'numtel' => $data['numtel'],
                'client_id' => $clientId,
                'typedecompte' => 'secondaire',
                'solde' => 0.00,
                'status' => 'actif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Traitement des fichiers CNI si fournis
            if (!empty($files['photocnirecto']['name'])) {
                $compteData['photocnirecto'] = $this->handleFileUpload($files['photocnirecto'], 'cni_recto');
            }

            if (!empty($files['photocniverso']['name'])) {
                $compteData['photocniverso'] = $this->handleFileUpload($files['photocniverso'], 'cni_verso');
            }

            $compteId = $this->compteRepo->create($compteData);

            if (!$compteId) {
                throw new \Exception('Erreur lors de la création du compte');
            }

            $this->compteRepo->commit();

            return [
                'success' => true,
                'compte_id' => $compteId,
                'message' => 'Compte secondaire créé avec succès'
            ];

        } catch (\Exception $e) {
            $this->compteRepo->rollback();
            return [
                'success' => false,
                'errors' => ['general' => 'Erreur système: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Changer un compte secondaire en compte principal (US5)
     */
    public function changeComptePrincipal(int $clientId, ?int $compteId): array
    {
        if (!$compteId) {
            return ['success' => false, 'message' => 'ID de compte manquant'];
        }

        // Vérifier que le compte appartient au client
        $compte = $this->compteRepo->findByIdAndClient($compteId, $clientId);
        if (!$compte) {
            return ['success' => false, 'message' => 'Compte non trouvé'];
        }

        if ($compte['typedecompte'] === 'principal') {
            return ['success' => false, 'message' => 'Ce compte est déjà principal'];
        }

        try {
            $this->compteRepo->beginTransaction();

            // Changer l'ancien compte principal en secondaire
            $this->compteRepo->updateTypeCompte($clientId, 'principal', 'secondaire');

            // Changer le compte sélectionné en principal
            $this->compteRepo->updateCompteType($compteId, 'principal');

            $this->compteRepo->commit();

            return ['success' => true, 'message' => 'Compte principal modifié avec succès'];

        } catch (\Exception $e) {
            $this->compteRepo->rollback();
            return ['success' => false, 'message' => 'Erreur système: ' . $e->getMessage()];
        }
    }

    /**
     * Récupérer les détails d'un compte
     */
    public function getCompteDetails(int $compteId, int $clientId): ?array
    {
        return $this->compteRepo->findByIdAndClient($compteId, $clientId);
    }

    /**
     * Récupérer les transactions récentes d'un compte
     */
    public function getRecentTransactions(int $compteId, int $limit = 10): array
    {
        return $this->transactionRepo->findRecentByCompte($compteId, $limit);
    }

    /**
     * Récupérer les transactions avec filtres (US7)
     */
    public function getTransactionsWithFilters(int $compteId, array $filters): array
    {
        $perPage = 20;
        $page = $filters['page'] ?? 1;
        $offset = ($page - 1) * $perPage;

        // Construire les conditions de filtre
        $conditions = [];
        $params = ['compte_id' => $compteId];

        if (!empty($filters['date_debut'])) {
            $conditions[] = "created_at >= :date_debut";
            $params['date_debut'] = $filters['date_debut'] . ' 00:00:00';
        }

        if (!empty($filters['date_fin'])) {
            $conditions[] = "created_at <= :date_fin";
            $params['date_fin'] = $filters['date_fin'] . ' 23:59:59';
        }

        if (!empty($filters['type'])) {
            $conditions[] = "type_transaction = :type";
            $params['type'] = $filters['type'];
        }

        $transactions = $this->transactionRepo->findWithFilters($compteId, $conditions, $params, $perPage, $offset);
        $total = $this->transactionRepo->countWithFilters($compteId, $conditions, $params);

        return [
            'transactions' => $transactions,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'has_next' => $page < ceil($total / $perPage),
                'has_prev' => $page > 1
            ]
        ];
    }

    /**
     * Rechercher un compte par numéro (US6 - Service Commercial)
     */
    public function searchCompteByNumber(string $numtel): ?array
    {
        $compte = $this->compteRepo->findByNumtel($numtel);
        
        if (!$compte) {
            return null;
        }

        // Ajouter les informations du client
        $compte['transactions_recentes'] = $this->getRecentTransactions($compte['id'], 10);
        
        return $compte;
    }

    /**
     * Gérer l'upload d'un fichier
     */
    private function handleFileUpload(array $file, string $prefix): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new \Exception('Type de fichier non autorisé');
        }

        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($file['size'] > $maxSize) {
            throw new \Exception('Fichier trop volumineux');
        }

        $uploadDir = 'public/uploads/cni/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }

        throw new \Exception('Erreur lors de l\'upload du fichier');
    }
}
