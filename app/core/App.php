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
        // Charger la configuration en premier
        Config::load();
        
        // Configurer l'affichage des erreurs selon l'environnement
        if (Config::get('APP_DEBUG', false)) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            error_reporting(0);
        }
        
        $this->initializeDependencies();
    }

    private function initializeDependencies()
    {
        $this->dependencies = [
            'core' => [
                'router' => new Router(),
                'database' => Database::getInstance(),
                'config' => Config::class,
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
            // Tester la connexion à la base de données
            $database = self::getDependency('core.database');
            if (!$database->testConnection()) {
                throw new \Exception('Impossible de se connecter à la base de données');
            }
            
            $router = self::getDependency('core.router');
            $router->dispatch();
            
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            error_log('Database error: ' . $e->getMessage());
            $this->handleError('Erreur de base de données', 500);
        } catch (\Exception $e) {
            // Gestion des autres erreurs
            error_log('Application error: ' . $e->getMessage());
            $this->handleError('Une erreur est survenue', 500);
        }
    }

    private function handleError(string $message, int $code = 500): void
    {
        http_response_code($code);
        
        if (Config::get('APP_DEBUG', false)) {
            echo "<h1>Erreur $code</h1>";
            echo "<p>$message</p>";
            if (isset($e)) {
                echo "<pre>" . $e->getTraceAsString() . "</pre>";
            }
        } else {
            echo "<h1>Erreur</h1><p>Une erreur est survenue. Veuillez réessayer plus tard.</p>";
        }
    }
}