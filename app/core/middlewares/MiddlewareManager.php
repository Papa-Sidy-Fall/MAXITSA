<?php

namespace App\Core\Middlewares;

class MiddlewareManager
{
    private static array $middlewares = [
        'auth' => [Auth::class, 'handle'],
        'guest' => [Auth::class, 'guest'],
        'admin' => [Auth::class, 'admin'],
        'profile.complete' => [Auth::class, 'profileComplete'],
        'account.active' => [Auth::class, 'accountActive'],
        'rate.limited' => [Auth::class, 'rateLimited'],
        'reauth' => [Auth::class, 'requireReauth'],
        'business.hours' => [Auth::class, 'businessHours'],
        'maintenance' => [Auth::class, 'notInMaintenance']
    ];

    /**
     * Enregistre un nouveau middleware
     */
    public static function register(string $name, callable $handler): void
    {
        self::$middlewares[$name] = $handler;
    }

    /**
     * Exécute un middleware
     */
    public static function run(string $name, ...$params): bool
    {
        if (!isset(self::$middlewares[$name])) {
            throw new \Exception("Middleware '$name' non trouvé");
        }

        $handler = self::$middlewares[$name];
        return call_user_func_array($handler, $params);
    }

    /**
     * Exécute plusieurs middlewares
     */
    public static function runMultiple(array $middlewares, ...$params): bool
    {
        foreach ($middlewares as $middleware) {
            if (!self::run($middleware, ...$params)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Récupère tous les middlewares enregistrés
     */
    public static function getAll(): array
    {
        return self::$middlewares;
    }
}