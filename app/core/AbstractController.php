<?php

namespace App\Core;

abstract class AbstractController
{
    public function __construct()
    {
        // Initialisation de base pour tous les contrôleurs
    }

    /**
     * Rendre une vue
     */
    protected function render(string $view, array $data = []): void
    {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);
        
        // Construire le chemin vers le template
        $templatePath = __DIR__ . '/../../templates/' . $view . '.php';
        
        if (file_exists($templatePath)) {
            include $templatePath;
        } else {
            throw new \Exception("Template non trouvé: $templatePath");
        }
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Obtenir un paramètre de route
     */
    protected function getRouteParam(string $key): ?string
    {
        // Cette méthode sera implémentée selon le système de routage
        return $_GET[$key] ?? null;
    }

    /**
     * Vérifier que l'utilisateur est connecté
     */
    protected function requireAuth(): void
    {
        if (!Session::isLoggedIn()) {
            Session::flash('error', 'Vous devez être connecté pour accéder à cette page.');
            $this->redirect('/login');
        }
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
