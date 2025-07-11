<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Config;
use App\Core\Database;

try {
    // Tester le chargement de la config
    Config::load();
    
    echo "✅ Configuration chargée avec succès\n";
    echo "DB_HOST: " . Config::get('DB_HOST') . "\n";
    echo "DB_NAME: " . Config::get('DB_NAME') . "\n";
    echo "DB_USER: " . Config::get('DB_USER') . "\n";
    echo "APP_NAME: " . Config::get('APP_NAME') . "\n";
    
    // Tester la connexion à la base de données
    $database = Database::getInstance();
    
    if ($database->testConnection()) {
        echo "✅ Connexion à la base de données réussie\n";
    } else {
        echo "❌ Échec de la connexion à la base de données\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}