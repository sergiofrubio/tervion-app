<?php
// Directorios base del sistema de archivos
define('ROOT_PATH', dirname(__DIR__, 2)); // Raíz del proyecto (/var/www/html)

define('PUBLIC_PATH', ROOT_PATH . '/public');      // Carpeta public
define('SRC_PATH', ROOT_PATH . '/src');            // Carpeta src
define('TEMPLATE_DIR', SRC_PATH . '/Templates/');
define('VIEWS_DIR', SRC_PATH . '/Views/');

// define('TEMPLATE_DIR', __DIR__ . '/../Templates/');
define('PROJECT_ROOT', '');

function public_path(string $path = ''): string
{
    return ROOT_PATH . '/public' . ($path ? '/' . ltrim($path, '/') : '');
}

function view_path(string $path = ''): string
{
    return ROOT_PATH . '/src/Views' . ($path ? '/' . ltrim($path, '/') : '');
}
