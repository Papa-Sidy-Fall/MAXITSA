<?php

namespace App\Controller;

class HomeController
{
    public function index(): void
    {
        header("Location: /login");
        exit;
    }
}