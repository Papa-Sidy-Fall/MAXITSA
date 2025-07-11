<?php

require_once 'vendor/autoload.php';

use App\Core\Router;
use App\src\controller\AuthController;

echo "=== Test des Routes ===\n";

$router = new Router();

try {
    // Test d'ajout de route
    $router->get('/test', [AuthController::class, 'login'], []);
    echo "✅ Route ajoutée avec succès\n";
    
    // Afficher les routes
    $routes = $router->getRoutes();
    echo "Nombre de routes: " . count($routes) . "\n";
    
    foreach ($routes as $route) {
        echo "- {$route['method']} {$route['path']} -> {$route['handler'][0]}::{$route['handler'][1]}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Test du fichier de routes ===\n";

try {
    $routesFile = __DIR__ . '/routes/route.web.php';
    if (file_exists($routesFile)) {
        $loadedRouter = require $routesFile;
        if ($loadedRouter instanceof Router) {
            echo "✅ Fichier de routes chargé avec succès\n";
            $routes = $loadedRouter->getRoutes();
            echo "Nombre de routes chargées: " . count($routes) . "\n";
        } else {
            echo "❌ Le fichier de routes ne retourne pas un Router\n";
        }
    } else {
        echo "❌ Fichier de routes non trouvé: $routesFile\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lors du chargement des routes: " . $e->getMessage() . "\n";
}