<?php

namespace App\Controller;

use App\Core\Database;
use App\Service\AuthService;
use App\Repository\ClientRepository;
use App\Repository\CompteRepository;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $database = Database::getInstance();
        $pdo = $database->getConnection();
        
        $clientRepo = new ClientRepository($pdo);
        $compteRepo = new CompteRepository($pdo);
        
        $this->authService = new AuthService($clientRepo, $compteRepo);
    }

    public function showLogin(): void
    {
        $this->render('auth/login');
    }

    public function showRegister(): void
    {
        $this->render('auth/register');
    }

    public function register(): void
    {
        $result = $this->authService->register($_POST, $_FILES);
        
        if ($result['success']) {
            $this->redirect('/login?success=1');
        } else {
            $this->render('auth/register', [
                'errors' => $result['errors'],
                'old' => $_POST
            ]);
        }
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->authService->login($login, $password);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['user_name'] = $result['user']['prenom'] . ' ' . $result['user']['nom'];
            $_SESSION['user_phone'] = $result['user']['phone'];
            $_SESSION['user_email'] = $result['user']['email'];
            $_SESSION['compte_id'] = $result['user']['compte_id'];
            
            $this->redirect('/dashboard');
        } else {
            $this->render('auth/login', [
                'error' => $result['error'],
                'old_login' => $login
            ]);
        }
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_destroy();
        $this->redirect('/login');
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