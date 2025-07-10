<?php
require_once __DIR__.'/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load();

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'maxitsa');
define('DB_USER', $_ENV['DB_USER'] ?? 'postgres');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');