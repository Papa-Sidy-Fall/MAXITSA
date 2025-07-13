<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;
use App\Service\CompteService;
use App\Repository\CompteRepository;
use App\Repository\TransactionRepository;

class CommercialController extends AbstractController
{
    private CompteService $compteService;

    public function __construct()
    {
        parent::__construct();
        
        $database = Database::getInstance();
        $compteRepo = new CompteRepository($database);
        $transactionRepo = new TransactionRepository($database);
        $validator = new Validator();
        
        $this->compteService = new CompteService($compteRepo, $transactionRepo, $validator);
    }

    /**
     * Interface de recherche pour le Service Commercial (US6)
     */
    public function index(): void
    {
        $this->render('commercial/search', [
            'title' => 'Interface Service Commercial'
        ]);
    }

    /**
     * Rechercher un compte par numéro (US6)
     */
    public function search(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/commercial');
            return;
        }

        $numtel = $_POST['numtel'] ?? '';
        $compte = null;
        $error = null;

        if (empty($numtel)) {
            $error = 'Veuillez saisir un numéro de téléphone';
        } else {
            $compte = $this->compteService->searchCompteByNumber($numtel);
            if (!$compte) {
                $error = 'Aucun compte trouvé pour ce numéro';
            }
        }

        $this->render('commercial/search', [
            'title' => 'Interface Service Commercial',
            'compte' => $compte,
            'error' => $error,
            'search_numtel' => $numtel
        ]);
    }

    /**
     * Voir l'historique complet d'un compte (US6)
     */
    public function viewTransactions(): void
    {
        $compteId = $this->getRouteParam('id');
        $numtel = $_GET['numtel'] ?? '';

        // Vérifier que le compte existe
        $compte = $this->compteService->searchCompteByNumber($numtel);
        if (!$compte || $compte['id'] != $compteId) {
            Session::flash('error', 'Compte non trouvé');
            $this->redirect('/commercial');
            return;
        }

        // Récupérer les filtres de recherche
        $filters = [
            'date_debut' => $_GET['date_debut'] ?? null,
            'date_fin' => $_GET['date_fin'] ?? null,
            'type' => $_GET['type'] ?? null,
            'page' => (int)($_GET['page'] ?? 1)
        ];

        $result = $this->compteService->getTransactionsWithFilters($compteId, $filters);

        $this->render('commercial/transactions', [
            'compte' => $compte,
            'transactions' => $result['transactions'],
            'pagination' => $result['pagination'],
            'filters' => $filters,
            'title' => 'Historique des transactions - Service Commercial'
        ]);
    }
}
