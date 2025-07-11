<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use App\Core\Database;

class DashboardController
{
    private TransactionRepository $transactionRepo;

    public function __construct()
    {
        $database = Database::getInstance();
        $pdo = $database->getConnection();
        $this->transactionRepo = new TransactionRepository($pdo);
    }

    public function index(): void
    {
        // Démarrer la session si pas déjà fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $compteId = $_SESSION['compte_id'];
        $page = (int) ($_GET['page'] ?? 1);
        $limit = 5; // 5 transactions par page
        $offset = ($page - 1) * $limit;

        // Récupérer le solde
        $solde = $this->transactionRepo->getSoldeByCompte($compteId);

        // Récupérer les transactions
        $transactions = $this->transactionRepo->getTransactionsByCompte($compteId, $limit, $offset);

        // Compter le total des transactions pour la pagination
        $totalTransactions = $this->transactionRepo->countTransactionsByCompte($compteId);
        $totalPages = ceil($totalTransactions / $limit);

        // Formater les transactions pour l'affichage
        $transactionsFormatted = $this->formatTransactions($transactions, $compteId);

        $this->render('dashboard/index', [
            'title' => 'Dashboard - Max It SA',
            'user_name' => $_SESSION['user_name'],
            'user_phone' => $_SESSION['user_phone'],
            'user_email' => $_SESSION['user_email'],
            'solde' => $solde,
            'transactions' => $transactionsFormatted,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_transactions' => $totalTransactions
        ]);
    }

    private function formatTransactions(array $transactions, int $compteId): array
    {
        $formatted = [];

        foreach ($transactions as $transaction) {
            $isExpeditor = $transaction['expediteur_id'] == $compteId;
            
            if ($isExpeditor) {
                // Transaction sortante (retrait/paiement)
                if ($transaction['type_transaction'] === 'paiement') {
                    $type = 'paiement';
                    $color = 'orange';
                    $icon = 'credit-card';
                    $description = $transaction['description'] ?: 'Paiement';
                } else {
                    $type = 'retrait';
                    $color = 'red';
                    $icon = 'arrow-up';
                    $description = 'Envoi vers ' . ($transaction['destinataire_nom'] ?: $transaction['destinataire_tel']);
                }
                $montant = '-' . number_format($transaction['montant'], 0, ',', ' ');
            } else {
                // Transaction entrante (dépôt)
                $type = 'depot';
                $color = 'green';
                $icon = 'arrow-down';
                $description = $transaction['description'] ?: ('Reçu de ' . ($transaction['expediteur_nom'] ?: $transaction['expediteur_tel']));
                $montant = '+' . number_format($transaction['montant'], 0, ',', ' ');
            }

            $formatted[] = [
                'id' => $transaction['id'],
                'type' => $type,
                'color' => $color,
                'icon' => $icon,
                'description' => $description,
                'montant' => $montant,
                'date' => date('d/m/Y H:i', strtotime($transaction['created_at'])),
                'statut' => $transaction['statut'] ?? 'reussi'
            ];
        }

        return $formatted;
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function render(string $template, array $data = []): void
    {
        extract($data);
        
        $templatePath = __DIR__ . "/../../templates/$template.php";
        
        if (!file_exists($templatePath)) {
            throw new \Exception("Template $template not found");
        }
        
        include $templatePath;
    }
}