<?php

declare(strict_types=1);

namespace Modules\SampleAddon;

use App\Core\Modules\ModuleInterface;
use App\Core\Events\Hook;
use App\Router\Router;

class Module implements ModuleInterface
{
    public function boot(): void
    {
        // Suscribirse a una acción del ERP
        Hook::addAction('app.init', function () {
            // Lógica de inicio personalizada
        });

        // Modificar o filtrar datos si fuese necesario
        Hook::addFilter('app.title', function (string $title): string {
            return $title;
        });
    }

    public function registerRoutes(Router $router): void
    {
        $staffRoles = ['Administrador', 'Terapeuta', 'Secretario'];
        $router->add('GET', '/extensiones/demo', 'Modules\SampleAddon\Controllers\DemoController@index', true, $staffRoles);
    }
}
