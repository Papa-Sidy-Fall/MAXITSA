<?php

namespace App\Core;

abstract class AbstractController
{
    protected function render(string $template, array $data = []): void
    {
        // Extraire les variables pour les rendre disponibles dans le template
        extract($data);
        
        // Construire le chemin du template
        $templatePath = __DIR__ . "/../../templates/{$template}.php";
        
        if (!file_exists($templatePath)) {
            throw new \Exception("Template {$template} not found");
        }
        
        // Inclure le template
        include $templatePath;
    }
    
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
    
    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function getPostData(): array
    {
        return $_POST;
    }
    
    protected function getGetData(): array
    {
        return $_GET;
    }
}