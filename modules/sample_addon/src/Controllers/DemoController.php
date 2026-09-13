<?php

declare(strict_types=1);

namespace Modules\SampleAddon\Controllers;

use App\Core\Controller;

class DemoController extends Controller
{
    public function index(): void
    {
        $data = [
            'pageTitle' => 'Módulo de Extensión Demo',
            'appName' => 'Tervion Modular Add-on'
        ];

        // Renderizar vista ubicada dentro de modules/sample_addon/Views/
        $this->view('@modules/sample_addon/Views/demo', $data);
    }
}
