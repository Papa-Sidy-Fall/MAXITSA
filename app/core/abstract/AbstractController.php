<?php

namespace App\core\abstract;

abstract class AbstractController
{
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
    
    protected function render(string $template, array $data = []): void
    {
        extract($data);
        
        $templatePath = __DIR__ . "/../../templates/$template.php";
        
        if (!file_exists($templatePath)) {
            throw new \Exception("Template $template not found");
        }
        
        include $templatePath;
    }
}