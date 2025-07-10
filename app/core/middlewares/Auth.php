<?php

namespace App\core\middlewares;

class Auth
{
    public function __invoke()
    {
        if (!isset($_SESSION['client_id'])) {
            header('Location: /login');
            exit();
        }
    }
}