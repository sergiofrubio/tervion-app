# Guía de Desarrollo de Módulos y Extensiones para Tervion ERP

Tervion ERP cuenta con una arquitectura modular desacoplada inspirada en los modelos open-core (como Odoo y WordPress). Esta arquitectura permite a desarrolladores de la comunidad, partners y a nuestro propio equipo construir extensiones que:

1. **No tocan el código del núcleo (src/).**
2. **Pueden tener su propia licencia** (abierta, comercial o propietaria bajo repositorio privado, gracias a LGPLv3).
3. **Se cargan de forma Plug & Play** simplemente depositando la carpeta en el directorio modules/.

---

## 📁 Anatomía de un Módulo

Todos los módulos deben ubicarse en una subcarpeta dentro del directorio modules/ en la raíz del proyecto:

`	ext
modules/
└── mi_modulo/
    ├── module.json         # Manifiesto obligatorio con metadatos del módulo
    ├── Module.php          # Clase de arranque (implementa ModuleInterface)
    ├── src/                # Código fuente del módulo (PSR-4 autoloading)
    │   ├── Controllers/
    │   ├── Models/
    │   └── Services/
    ├── Views/              # Vistas HTML / plantillas PHP del módulo
    └── database/           # (Opcional) Migraciones SQL para tablas propias
`

---

## ⚙️ Paso a Paso para Crear un Módulo

### 1. El Manifiesto (module.json)

Crea modules/mi_modulo/module.json:

`json
{
   id: mi_modulo,
  name: Mi Módulo de Prueba,
  version: 1.0.0,
  author: Tu Nombre o Empresa,
  description: Breve descripción de la funcionalidad añadida.,
  namespace: Modules\\MiModulo\\,
  menu_items: [
    {
      title: Mi Módulo,
      route: /mi-modulo,
      icon: puzzle,
      roles: [Administrador]
    }
  ]
}
`

* **id**: Identificador único en formato snake_case.
* **
amespace**: Namespace PSR-4 que mapeará automáticamente a la carpeta src/ del módulo.
* **menu_items**: Elementos de navegación que se insertarán automáticamente en la barra lateral del ERP cuando el usuario posea uno de los 
oles especificados. El icono corresponde a [Bootstrap Icons](https://icons.getbootstrap.com/) (sin el prefijo i-).

---

### 2. Clase de Arranque (Module.php)

Crea modules/mi_modulo/Module.php:

`php
<?php

declare(strict_types=1);

namespace Modules\MiModulo;

use App\Core\Modules\ModuleInterface;
use App\Core\Events\Hook;
use App\Router\Router;

class Module implements ModuleInterface
{
    /**
     * Inicializa hooks, filtros y suscripción de eventos.
     */
    public function boot(): void
    {
        // Escuchar eventos globales del ERP
        Hook::addAction('app.init', function () {
            // Código a ejecutar al inicializarse la aplicación
        });
    }

    /**
     * Registra rutas en el Router del sistema.
     */
    public function registerRoutes(Router ): void
    {
        ->add(
            'GET',
            '/mi-modulo',
            'Modules\MiModulo\Controllers\MiModuloController@index',
            true,
            ['Administrador']
        );
    }
}
`

---

### 3. Crear el Controlador (src/Controllers/...)

Crea modules/mi_modulo/src/Controllers/MiModuloController.php:

`php
<?php

declare(strict_types=1);

namespace Modules\MiModulo\Controllers;

use App\Core\Controller;

class MiModuloController extends Controller
{
    public function index(): void
    {
         = [
            'pageTitle' => 'Panel de Mi Módulo'
        ];

        // Se usa @modules/ para resolver vistas de la carpeta modules
        ->view('@modules/mi_modulo/Views/index', );
    }
}
`

---

### 4. Crear la Vista (Views/...)

Crea modules/mi_modulo/Views/index.php:

`php
<?php require_once dirname(__DIR__, 3) . '/src/Templates/header.php'; ?>

<main class=p-6 max-w-7xl mx-auto>
    <div class=bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8>
        <h1 class=text-2xl font-bold text-slate-900><?= htmlspecialchars() ?></h1>
        <p class=text-sm text-slate-500 mt-2>¡Hola desde mi módulo independiente!</p>
    </div>
</main>

<?php require_once dirname(__DIR__, 3) . '/src/Templates/footer.php'; ?>
`

---

## 🪝 Sistema de Hooks y Eventos

Tervion expone la clase App\Core\Events\Hook con soporte para:

### Actions (Acciones)
Permite ejecutar código cuando un suceso ocurre en el ERP:
`php
use App\Core\Events\Hook;

// Suscribirse:
Hook::addAction('invoice.created', function (int ) {
    // Sincronizar con API externa
}, 10);

// Ejecutar (en el Core):
Hook::doAction('invoice.created', );
`

### Filters (Filtros)
Permite interceptar y mutar un valor antes de que sea procesado:
`php
use App\Core\Events\Hook;

// Registrar filtro:
Hook::addFilter('email.subject', function (string ) {
    return '[URGENTE] ' . ;
});

// Aplicar filtro (en el Core):
 = Hook::applyFilters('email.subject', );
`

---

## 🛡️ Base de Datos y Buenas Prácticas

1. **Prefijo en tablas propias:** Si tu módulo requiere tablas en MySQL/MariaDB, nómbralas obligatoriamente con el prefijo mod_[id_modulo]_ (ejemplo: mod_whatsapp_logs).
2. **Repositorios Privados / Código Propietario:** Puedes versionar tu módulo como un submódulo de Git o clonarlo dentro de modules/ desde tu repositorio privado. El .gitignore del proyecto Tervion ignora por defecto el contenido de modules/, garantizando que tu código comercial nunca se suba al repositorio público.
