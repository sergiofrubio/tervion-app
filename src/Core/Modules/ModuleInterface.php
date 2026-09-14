<?php

declare(strict_types=1);

namespace App\Core\Modules;

use App\Router\Router;

/**
 * Contrato base para módulos de Tervion ERP.
 */
interface ModuleInterface
{
    /**
     * Inicializa el módulo, sus hooks y suscripciones de eventos.
     */
    public function boot(): void;

    /**
     * Registra rutas personalizadas del módulo en el router de la aplicación.
     */
    public function registerRoutes(Router $router): void;
}
