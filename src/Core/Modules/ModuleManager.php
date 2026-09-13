<?php

declare(strict_types=1);

namespace App\Core\Modules;

use App\Router\Router;
use Throwable;

/**
 * Gestor dinámico del ciclo de vida de módulos externos y extensiones de terceros.
 */
class ModuleManager
{
    protected static ?ModuleManager $instance = null;
    protected string $modulesPath;
    
    /**
     * @var array<string, array<string, mixed>>
     */
    protected array $loadedModules = [];

    /**
     * @var array<string, ModuleInterface>
     */
    protected array $moduleInstances = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $registeredMenuItems = [];

    public function __construct(?string $modulesPath = null)
    {
        $this->modulesPath = $modulesPath ?? dirname(__DIR__, 3) . '/modules';
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Escanea el directorio de módulos y carga sus clases y manifiestos.
     */
    public function discoverAndBoot(): void
    {
        if (!is_dir($this->modulesPath)) {
            return;
        }

        $entries = scandir($this->modulesPath);
        if ($entries === false) {
            return;
        }

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $moduleDir = $this->modulesPath . '/' . $entry;
            if (!is_dir($moduleDir)) {
                continue;
            }

            $manifestFile = $moduleDir . '/module.json';
            if (!file_exists($manifestFile)) {
                continue;
            }

            $manifestContent = file_get_contents($manifestFile);
            $manifest = json_decode($manifestContent ?: '{}', true);
            if (!is_array($manifest) || empty($manifest['id'])) {
                continue;
            }

            $moduleId = (string)$manifest['id'];
            $this->loadedModules[$moduleId] = $manifest;
            $this->loadedModules[$moduleId]['_path'] = $moduleDir;

            // Recopilar elementos de menú
            if (!empty($manifest['menu_items']) && is_array($manifest['menu_items'])) {
                foreach ($manifest['menu_items'] as $item) {
                    $this->registeredMenuItems[] = $item;
                }
            }

            // Registrar Autoloader PSR-4 para el namespace del módulo
            $namespace = $manifest['namespace'] ?? ('Modules\\' . $this->studlyCaps($moduleId) . '\\');
            $srcPath = $moduleDir . '/src';

            spl_autoload_register(function (string $class) use ($namespace, $srcPath) {
                if (str_starts_with($class, $namespace)) {
                    $relativeClass = substr($class, strlen($namespace));
                    $file = $srcPath . '/' . str_replace('\\', '/', $relativeClass) . '.php';
                    if (file_exists($file)) {
                        require_once $file;
                    }
                }
            });

            // Instanciar clase Module de entrada si existe
            $entryClass = $moduleDir . '/Module.php';
            if (file_exists($entryClass)) {
                require_once $entryClass;
                $className = $namespace . 'Module';
                if (class_exists($className) && is_subclass_of($className, ModuleInterface::class)) {
                    try {
                        /** @var ModuleInterface $moduleInstance */
                        $moduleInstance = new $className();
                        $moduleInstance->boot();
                        $this->moduleInstances[$moduleId] = $moduleInstance;
                    } catch (Throwable $e) {
                        error_log('[Tervion ModuleManager] Error booting module ' . $moduleId . ': ' . $e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * Registra las rutas de todos los módulos activos en el Router central.
     */
    public function registerRoutes(Router $router): void
    {
        foreach ($this->moduleInstances as $module) {
            $module->registerRoutes($router);
        }

        // Cargar archivo routes.php si el módulo lo define de forma procedural
        foreach ($this->loadedModules as $mod) {
            $routesFile = ($mod['_path'] ?? '') . '/routes.php';
            if (file_exists($routesFile)) {
                require_once $routesFile;
            }
        }
    }

    /**
     * Devuelve la lista de módulos cargados.
     */
    public function getLoadedModules(): array
    {
        return $this->loadedModules;
    }

    /**
     * Devuelve los elementos de menú aportados por los módulos.
     */
    public function getMenuItems(): array
    {
        return $this->registeredMenuItems;
    }

    /**
     * Convierte identificadores snake_case o kebab-case a StudlyCaps.
     */
    protected function studlyCaps(string $str): string
    {
        $clean = str_replace(['-', '_'], ' ', $str);
        return str_replace(' ', '', ucwords($clean));
    }
}
