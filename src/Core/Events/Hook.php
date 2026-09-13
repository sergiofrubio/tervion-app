<?php

declare(strict_types=1);

namespace App\Core\Events;

/**
 * Tervion ERP Hook & Event System
 *
 * Permite a los módulos de terceros y extensiones propietarias
 * suscribirse a acciones (Actions) y transformar datos (Filters).
 */
class Hook
{
    /**
     * @var array<string, array<int, array{callback: callable, priority: int}>>
     */
    protected static array $actions = [];

    /**
     * @var array<string, array<int, array{callback: callable, priority: int}>>
     */
    protected static array $filters = [];

    /**
     * Registra un callback para una acción determinada.
     */
    public static function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        self::$actions[$hook][] = [
            'callback' => $callback,
            'priority' => $priority,
        ];
    }

    /**
     * Ejecuta todos los callbacks registrados para una acción.
     */
    public static function doAction(string $hook, mixed ...$args): void
    {
        if (!isset(self::$actions[$hook])) {
            return;
        }

        $callbacks = self::$actions[$hook];
        usort($callbacks, fn($a, $b) => $a['priority'] <=> $b['priority']);

        foreach ($callbacks as $item) {
            call_user_func_array($item['callback'], $args);
        }
    }

    /**
     * Registra un filtro para transformar un valor.
     */
    public static function addFilter(string $hook, callable $callback, int $priority = 10): void
    {
        self::$filters[$hook][] = [
            'callback' => $callback,
            'priority' => $priority,
        ];
    }

    /**
     * Aplica filtros sucesivos a un valor inicial.
     */
    public static function applyFilters(string $hook, mixed $value, mixed ...$args): mixed
    {
        if (!isset(self::$filters[$hook])) {
            return $value;
        }

        $callbacks = self::$filters[$hook];
        usort($callbacks, fn($a, $b) => $a['priority'] <=> $b['priority']);

        foreach ($callbacks as $item) {
            $value = call_user_func_array($item['callback'], array_merge([$value], $args));
        }

        return $value;
    }

    /**
     * Limpia todos los hooks (útil para pruebas unitarias).
     */
    public static function reset(): void
    {
        self::$actions = [];
        self::$filters = [];
    }
}
