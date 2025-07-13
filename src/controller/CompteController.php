<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;
use App\Service\CompteService;
use App\Repository\CompteRepository;
use App\Repository\TransactionRepository;

class CompteController extends AbstractController
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
     * Afficher la liste des comptes du client connecté
     */
    public function index(): void
    {
        $this->requireAuth();
        
        Session::start();
        $clientId = Session::get('user_id'); // Correction: utiliser 'user_id' au lieu de 'user.id'
        
        if (!$clientId) {
            Session::flash('error', 'Session expirée. Veuillez vous reconnecter.');
            $this->redirect('/login');
            return;
        }
        
        $comptes = $this->compteService->getComptesByClient($clientId);
        
        $this->render('compte/index', [
            'comptes' => $comptes,
            'title' => 'Mes Comptes'
        ]);
    }

    /**
     * Afficher le formulaire de création d'un compte secondaire
     */
    public function create(): void
    {
        $this->requireAuth();
        
        $this->render('compte/create', [
            'title' => 'Ajouter un compte secondaire'
        ]);
    }

    /**
     * Traiter la création d'un compte secondaire
     */
    public function store(): void
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/comptes/create');
            return;
        }

        Session::start();
        $clientId = Session::get('user_id'); // Correction: utiliser 'user_id'
        
        if (!$clientId) {
            Session::flash('error', 'Session expirée. Veuillez vous reconnecter.');
            $this->redirect('/login');
            return;
        }
        
        $result = $this->compteService->createCompteSecondaire($clientId, $_POST, $_FILES);
        
        if ($result['success']) {
            Session::flash('success', 'Compte secondaire créé avec succès !');
            $this->redirect('/comptes');
        } else {
            Session::set('old_input', $_POST);
            $this->render('compte/create', [
                'errors' => $result['errors'],
                'old' => $_POST,
                'title' => 'Ajouter un compte secondaire'
            ]);
        }
    }

    /**
     * Changer un compte secondaire en compte principal (US5)
     */
    public function makePrincipal(): void
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/comptes');
            return;
        }

        $compteId = $_POST['compte_id'] ?? null;
        Session::start();
        $clientId = Session::get('user_id');
        
        $result = $this->compteService->changeComptePrincipal($clientId, $compteId);
        
        if ($result['success']) {
            Session::flash('success', 'Compte principal modifié avec succès !');
        } else {
            Session::flash('error', $result['message']);
        }
        
        $this->redirect('/comptes');
    }

    /**
     * Afficher les détails d'un compte
     */
    public function show(): void
    {
        $this->requireAuth();
        
        $compteId = $this->getRouteParam('id');
        Session::start();
        $clientId = Session::get('user_id');
        
        $compte = $this->compteService->getCompteDetails($compteId, $clientId);
        
        if (!$compte) {
            Session::flash('error', 'Compte non trouvé ou accès non autorisé.');
            $this->redirect('/comptes');
            return;
        }
        
        // Récupérer les 10 dernières transactions
        $transactions = $this->compteService->getRecentTransactions($compteId, 10);
        
        $this->render('compte/show', [
            'compte' => $compte,
            'transactions' => $transactions,
            'title' => 'Détails du compte'
        ]);
    }

    /**
     * Historique complet des transactions (US7)
     */
    public function transactions(): void
    {
        $this->requireAuth();
        
        $compteId = $this->getRouteParam('id');
        Session::start();
        $clientId = Session::get('user_id');
        
        // Vérifier que le compte appartient au client
        $compte = $this->compteService->getCompteDetails($compteId, $clientId);
        if (!$compte) {
            Session::flash('error', 'Accès non autorisé.');
            $this->redirect('/comptes');
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
        
        $this->render('compte/transactions', [
            'compte' => $compte,
            'transactions' => $result['transactions'],
            'pagination' => $result['pagination'],
            'filters' => $filters,
            'title' => 'Historique des transactions'
        ]);
    }
}
