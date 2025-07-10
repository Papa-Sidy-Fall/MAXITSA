<?php

namespace App\Core;

use PDO;
use PDOException;

class App
{
    private static $instance = null;
    private $dependencies = [];

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->initializeDependencies();
    }

    private function initializeDependencies()
    {
        $this->dependencies = [
            'core' => [
                'router' => new Router(),
                'database' => Database::getInstance(),
            ],
            'services' => [
                // Services seront ajoutés ici
            ],
            'repositories' => [
                // Repositories seront ajoutés ici
            ],
        ];
    }

    public static function getDependency(string $key)
    {
        $instance = self::getInstance();
        $keys = explode('.', $key);
        $value = $instance->dependencies;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public function run()
    {
        try {
            // Démarrer la session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $router = self::getDependency('core.router');
            $router->dispatch();
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            error_log('Database error: ' . $e->getMessage());
            header('HTTP/1.1 500 Internal Server Error');
            echo 'An error occurred';
        } catch (\Exception $e) {
            // Gestion des autres erreurs
            error_log('Application error: ' . $e->getMessage());
            header('HTTP/1.1 500 Internal Server Error');
            echo 'An error occurred: ' . $e->getMessage(); // Pour le debug
        }
    }
    
    private function handleError(string $message): void
    {
        http_response_code(500);
        echo "<h1>Erreur</h1>";
        echo "<p>{$message}</p>";
        echo "<a href='/'>Retour à l'accueil</a>";
    }
}