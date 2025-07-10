<?php

// Afficher toutes les erreurs pour le debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

// Démarrer la session
session_start();

use App\Core\App;

try {
    $app = App::getInstance();
    $app->run();
} catch (Exception $e) {
    echo "<h1>Erreur détaillée :</h1>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Fichier:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Ligne:</strong> " . $e->getLine() . "</p>";
    echo "<h2>Stack trace:</h2>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
