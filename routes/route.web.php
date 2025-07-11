<?php

use App\Core\Router;
use App\Controller\AuthController;
use App\Controller\DashboardController;

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

return $router;