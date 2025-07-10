<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private string $currentUri;
    private string $currentMethod;
    
    public function __construct()
    {
        $this->currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->currentMethod = $_SERVER['REQUEST_METHOD'];
        $this->initializeRoutes();
    }
    
    private function initializeRoutes(): void
    {
        // Routes GET
        $this->get('/', 'HomeController@index');
        $this->get('/login', 'AuthController@showLogin');
        $this->get('/register', 'AuthController@showRegister');
        $this->get('/dashboard', 'DashboardController@index');
        $this->get('/transfert', 'TransactionController@showTransfert');
        $this->get('/depot', 'TransactionController@showDepot');
        $this->get('/historique', 'TransactionController@showHistorique');
        $this->get('/logout', 'AuthController@logout');
        
        // Routes POST
        $this->post('/login', 'AuthController@login');
        $this->post('/register', 'AuthController@register');
        $this->post('/transfert', 'TransactionController@effectuerTransfert');
        $this->post('/depot', 'TransactionController@effectuerDepot');
    }
    
    public function get(string $path, string $action): void
    {
        $this->addRoute('GET', $path, $action);
    }
    
    public function post(string $path, string $action): void
    {
        $this->addRoute('POST', $path, $action);
    }
    
    private function addRoute(string $method, string $path, string $action): void
    {
        $this->routes[$method][$path] = $action;
    }
    
    public function dispatch(): void
    {
        $route = $this->routes[$this->currentMethod][$this->currentUri] ?? null;
        
        if (!$route) {
            $this->handleNotFound();
            return;
        }
        
        $this->executeRoute($route);
    }
    
    private function executeRoute(string $route): void
    {
        [$controllerName, $methodName] = explode('@', $route);
        
        // CORRECTION: Utilisez le bon namespace selon composer.json
        $controllerClass = "App\\Controller\\{$controllerName}";
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $methodName)) {
            throw new \Exception("Method {$methodName} not found in {$controllerClass}");
        }
        
        $controller->$methodName();
    }
    
    private function handleNotFound(): void
    {
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
        echo "<p>La page demandée n'existe pas.</p>";
        echo "<a href='/'>Retour à l'accueil</a>";
    }
}