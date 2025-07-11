<?php

namespace App\Core\Helpers;

use App\Core\Session;

class FlashHelper
{
    /**
     * Affiche tous les messages flash
     */
    public static function display(): string
    {
        $messages = Session::getFlashMessages();
        $html = '';

        foreach ($messages as $message) {
            $type = $message['type'];
            $text = htmlspecialchars($message['message']);
            
            $alertClass = self::getAlertClass($type);
            $icon = self::getIcon($type);
            
            $html .= "<div class=\"alert $alertClass alert-dismissible fade show\" role=\"alert\">";
            $html .= "<i class=\"$icon me-2\"></i>$text";
            $html .= "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\"></button>";
            $html .= "</div>";
        }

        return $html;
    }

    /**
     * Affiche les messages flash avec Tailwind CSS
     */
    public static function displayTailwind(): string
    {
        $messages = Session::getFlashMessages();
        $html = '';

        foreach ($messages as $message) {
            $type = $message['type'];
            $text = htmlspecialchars($message['message']);
            
            $classes = self::getTailwindClasses($type);
            $icon = self::getTailwindIcon($type);
            
            $html .= "<div class=\"$classes rounded-lg p-4 mb-4\">";
            $html .= "<div class=\"flex items-center\">";
            $html .= "<span class=\"mr-2\">$icon</span>";
            $html .= "<span>$text</span>";
            $html .= "</div>";
            $html .= "</div>";
        }

        return $html;
    }

    private static function getAlertClass(string $type): string
    {
        return match($type) {
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-info',
            default => 'alert-info'
        };
    }

    private static function getIcon(string $type): string
    {
        return match($type) {
            'success' => 'fas fa-check-circle',
            'error' => 'fas fa-exclamation-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'info' => 'fas fa-info-circle',
            default => 'fas fa-info-circle'
        };
    }

    private static function getTailwindClasses(string $type): string
    {
        return match($type) {
            'success' => 'bg-green-100 border border-green-400 text-green-700',
            'error' => 'bg-red-100 border border-red-400 text-red-700',
            'warning' => 'bg-yellow-100 border border-yellow-400 text-yellow-700',
            'info' => 'bg-blue-100 border border-blue-400 text-blue-700',
            default => 'bg-blue-100 border border-blue-400 text-blue-700'
        };
    }

    private static function getTailwindIcon(string $type): string
    {
        return match($type) {
            'success' => '✅',
            'error' => '❌',
            'warning' => '⚠️',
            'info' => 'ℹ️',
            default => 'ℹ️'
        };
    }
}