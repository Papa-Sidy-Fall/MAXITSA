<?php

namespace App\Controller;

use App\Service\AuthService;
use App\Repository\ClientRepository;
use App\Repository\CompteRepository;
use App\Core\Database;

class AuthController
{
    private AuthService $authService;
    
    public function __construct()
    {
        // Initialiser les dépendances
        $database = Database::getInstance();
        $pdo = $database->getConnection();
        
        $clientRepo = new ClientRepository($pdo);
        $compteRepo = new CompteRepository($pdo);
        
        $this->authService = new AuthService($clientRepo, $compteRepo);
    }
    
    public function showLogin(): void
    {
        // Si déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
            return;
        }
        
        $this->render('auth/login', [
            'title' => 'Connexion - Max It SA',
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? []
        ]);
        
        // Nettoyer les messages de session
        unset($_SESSION['errors'], $_SESSION['old']);
    }
    
    public function showRegister(): void
    {
        // Si déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
            return;
        }
        
        $this->render('auth/register', [
            'title' => 'Inscription - Max It SA',
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? []
        ]);
        
        // Nettoyer les messages de session
        unset($_SESSION['errors'], $_SESSION['old']);
    }
    
    public function login(): void
    {
        $data = $this->getPostData();
        
        // Validation simple
        if (empty($data['login']) || empty($data['password'])) {
            $_SESSION['errors'] = ['general' => 'Tous les champs sont requis'];
            $_SESSION['old'] = $data;
            $this->redirect('/login');
            return;
        }
        
        // Authentification avec le service
        $result = $this->authService->login($data['login'], $data['password']);
        
        if ($result['success']) {
            // Connexion réussie
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['user_name'] = $result['user']['prenom'] . ' ' . $result['user']['nom'];
            $_SESSION['user_phone'] = $result['user']['phone'];
            $_SESSION['user_email'] = $result['user']['email'];
            $_SESSION['compte_id'] = $result['user']['compte_id'];
            
            $this->redirect('/dashboard');
        } else {
            // Erreur de connexion
            $_SESSION['errors'] = ['general' => $result['error']];
            $_SESSION['old'] = $data;
            $this->redirect('/login');
        }
    }
    
    public function register(): void
    {
        $data = $this->getPostData();
        $files = $_FILES ?? [];
        
        // Inscription avec le service
        $result = $this->authService->register($data, $files);
        
        if ($result['success']) {
            // Inscription réussie
            $_SESSION['success'] = $result['message'];
            $this->redirect('/login');
        } else {
            // Erreurs d'inscription
            $_SESSION['errors'] = $result['errors'];
            $_SESSION['old'] = $data;
            $this->redirect('/register');
        }
    }
    
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/');
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
    
    // Méthode utilitaire pour récupérer les données POST
    private function getPostData(): array
    {
        return [
            'prenom' => $_POST['prenom'] ?? '',
            'nom' => $_POST['nom'] ?? '',
            'cni' => $_POST['cni'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telephone' => $_POST['telephone'] ?? '',
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'login' => $_POST['login'] ?? ''
        ];
    }
}