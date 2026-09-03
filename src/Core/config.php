<?php
// Directorios base del sistema de archivos
define('ROOT_PATH', dirname(__DIR__, 2)); // Raíz del proyecto (/var/www/html)

define('PUBLIC_PATH', ROOT_PATH . '/public');      // Carpeta public
define('SRC_PATH', ROOT_PATH . '/src');            // Carpeta src
define('TEMPLATE_DIR', SRC_PATH . '/Templates/');
define('VIEWS_DIR', SRC_PATH . '/Views/');

define('PROJECT_ROOT', '');

// Detección del entorno de la aplicación (production, local, testing, dev)
$appEnv = getenv('APP_ENV') ?: ($_ENV['APP_ENV'] ?? 'production');
define('APP_ENV', strtolower(trim($appEnv)));

// Configuración de visualización y registro de errores
if (APP_ENV === 'production') {
    // En producción: ocultar errores y excepciones a los usuarios
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
} else {
    // En entornos locales o de desarrollo: mostrar errores para depuración
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// Siempre activar el logging de errores en el servidor
ini_set('log_errors', '1');

// Manejador global de excepciones no capturadas
set_exception_handler(function (\Throwable $exception) {
    // Registrar el error detallado con stack trace en los logs del servidor (Apache/PHP)
    error_log(sprintf(
        "[TERVION EXCEPTION] %s: %s in %s:%d\nStack trace:\n%s",
        get_class($exception),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    ));

    if (defined('TESTING') && TESTING) {
        throw $exception;
    }

    // En producción o si display_errors está apagado, mostrar vista amigable 500
    if (APP_ENV === 'production' || !ini_get('display_errors')) {
        if (!headers_sent()) {
            http_response_code(500);
        }
        $errorView = defined('VIEWS_DIR') ? VIEWS_DIR . '500.php' : __DIR__ . '/../Views/500.php';
        if (file_exists($errorView)) {
            require $errorView;
        } else {
            echo "<h1>Error 500</h1><p>Ha ocurrido un error interno en el servidor.</p>";
        }
        exit();
    }

    // En entorno de desarrollo (local/dev), mostrar la excepción para facilitar el trabajo
    if (!headers_sent()) {
        http_response_code(500);
    }
    echo "<h1>Error en la aplicación (Modo Debug)</h1>";
    echo "<p><strong>Tipo:</strong> " . htmlspecialchars(get_class($exception)) . "</p>";
    echo "<p><strong>Mensaje:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
    echo "<p><strong>Archivo:</strong> " . htmlspecialchars($exception->getFile()) . " en la línea " . $exception->getLine() . "</p>";
    echo "<pre style='background:#f4f4f4;padding:15px;border:1px solid #ddd;border-radius:8px;'>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
    exit();
});

// Manejador de errores fatales de PHP
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        error_log(sprintf(
            "[TERVION FATAL] %s in %s:%d",
            $error['message'],
            $error['file'],
            $error['line']
        ));

        if (defined('TESTING') && TESTING) {
            return;
        }

        if (APP_ENV === 'production' || !ini_get('display_errors')) {
            if (!headers_sent()) {
                http_response_code(500);
            }
            $errorView = defined('VIEWS_DIR') ? VIEWS_DIR . '500.php' : __DIR__ . '/../Views/500.php';
            if (file_exists($errorView)) {
                require $errorView;
            } else {
                echo "<h1>Error 500</h1><p>Ha ocurrido un error interno en el servidor.</p>";
            }
        }
    }
});

function public_path(string $path = ''): string
{
    return ROOT_PATH . '/public' . ($path ? '/' . ltrim($path, '/') : '');
}

function view_path(string $path = ''): string
{
    return ROOT_PATH . '/src/Views' . ($path ? '/' . ltrim($path, '/') : '');
}
