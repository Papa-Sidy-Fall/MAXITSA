<?php

namespace App\Controller;

class DashboardController
{
    public function index(): void
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        
        // Pour l'instant, affichage simple
        echo "<h1>Bienvenue " . ($_SESSION['user_name'] ?? 'Utilisateur') . "</h1>";
        echo "<p>Téléphone: " . ($_SESSION['user_phone'] ?? 'N/A') . "</p>";
        echo "<p>Email: " . ($_SESSION['user_email'] ?? 'N/A') . "</p>";
        echo "<a href='/logout'>Se déconnecter</a>";
    }
}