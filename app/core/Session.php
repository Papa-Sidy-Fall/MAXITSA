<?php

namespace App\Core;

class Session
{
    private static bool $started = false;

    /**
     * Démarrer la session
     */
    public static function start(): void
    {
        if (!self::$started && session_status() === PHP_SESSION_NONE) {
            session_start();
            self::$started = true;
        }
    }

    /**
     * Définir une valeur de session
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Obtenir une valeur de session
     */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Vérifier si une clé existe
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Supprimer une valeur
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Vider toute la session
     */
    public static function clear(): void
    {
        self::start();
        $_SESSION = [];
    }

    /**
     * Détruire la session
     */
    public static function destroy(): void
    {
        self::start();
        session_destroy();
        self::$started = false;
    }

    /**
     * Régénérer l'ID de session
     */
    public static function regenerate(): void
    {
        self::start();
        session_regenerate_id(true);
    }

    /**
     * Messages flash
     */
    public static function flash(string $type, string $message): void
    {
        self::start();
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Obtenir un message flash
     */
    public static function getFlash(string $type): ?string
    {
        self::start();
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    /**
     * Vérifier si un message flash existe
     */
    public static function hasFlash(string $type): bool
    {
        self::start();
        return isset($_SESSION['flash'][$type]);
    }

    /**
     * Connecter un utilisateur
     */
    public static function login(array $userData): void
    {
        self::start();
        $_SESSION['user'] = $userData;
        $_SESSION['user_id'] = $userData['id'] ?? null;
        $_SESSION['compte_id'] = $userData['compte_id'] ?? null;
        self::regenerate();
    }

    /**
     * Déconnecter l'utilisateur
     */
    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        self::destroy();
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    public static function isLoggedIn(): bool
    {
        self::start();
        return isset($_SESSION['user_id']);
    }

    /**
     * Obtenir l'utilisateur connecté
     */
    public static function getUser(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    /**
     * Obtenir l'ID de l'utilisateur connecté
     */
    public static function getUserId(): ?int
    {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }
}
