<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            // Charger la configuration depuis .env
            Config::load();
            
            $host = Config::get('DB_HOST', 'localhost');
            $dbname = Config::get('DB_NAME', 'maxitsa');
            $username = Config::get('DB_USER', 'postgres');
            $password = Config::get('DB_PASSWORD', '');
            $port = Config::get('DB_PORT', '5432');
            
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            
            $this->connection = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            
            // Log de connexion réussie en mode debug
            if (Config::get('APP_DEBUG', false)) {
                error_log("Connexion à la base de données réussie : $dsn");
            }
            
        } catch (PDOException $e) {
            error_log("Erreur de connexion à la base de données: " . $e->getMessage());
            throw new \Exception("Erreur de connexion à la base de données. Vérifiez votre configuration.");
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function testConnection(): bool
    {
        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}