<?php

use App\Core\Router;
use App\Controller\AuthController;
use App\Controller\DashboardController;
use App\Controller\CompteController;
use App\Controller\CommercialController;

$router = new Router();

// Routes publiques
$router->get('/', [AuthController::class, 'showLogin']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);

// Routes protégées
$router->get('/dashboard', [DashboardController::class, 'index'], ['auth']);
$router->get('/logout', [AuthController::class, 'logout'], ['auth']);

// Routes des comptes (US2, US5, US7)
$router->get('/comptes', [CompteController::class, 'index'], ['auth']);
$router->get('/comptes/create', [CompteController::class, 'create'], ['auth']);
$router->post('/comptes/store', [CompteController::class, 'store'], ['auth']);
$router->post('/comptes/make-principal', [CompteController::class, 'makePrincipal'], ['auth']);
$router->get('/comptes/{id}', [CompteController::class, 'show'], ['auth']);
$router->get('/comptes/{id}/transactions', [CompteController::class, 'transactions'], ['auth']);

// Routes Service Commercial (US6)
$router->get('/commercial', [CommercialController::class, 'index'], ['auth']);
$router->post('/commercial/search', [CommercialController::class, 'search'], ['auth']);
$router->get('/commercial/compte/{id}/transactions', [CommercialController::class, 'viewTransactions'], ['auth']);

return $router;