<?php

use App\core\Router;
use App\src\controller\AuthController;
use App\src\controller\AccountController;

$router = new Router();

// Routes publiques
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);

// Routes protégées
$router->get('/account', [AccountController::class, 'index'], ['auth']);
$router->get('/create-account', [AccountController::class, 'create'], ['auth']);
$router->post('/create-account', [AccountController::class, 'create'], ['auth']);
$router->get('/logout', [AuthController::class, 'logout'], ['auth']);

return $router;