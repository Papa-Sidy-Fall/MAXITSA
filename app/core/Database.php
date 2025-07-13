<?php

namespace App\Core;

use App\Core\Interfaces\DatabaseConnectionInterface;
use PDO;
use PDOException;

class Database implements DatabaseConnectionInterface
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

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    }

    public function rollback(): bool
    {
        return $this->connection->rollback();
    }

    public function inTransaction(): bool
    {
        return $this->connection->inTransaction();
    }

    public function execute(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->connection->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return false;
        }
    }

    public function fetch(string $query, array $params = []): ?array
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result === false ? null : $result;
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return null;
        }
    }

    public function fetchAll(string $query, array $params = []): array
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return [];
        }
    }

    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }
}