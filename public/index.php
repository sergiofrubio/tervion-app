<?php
require_once '../vendor/autoload.php';
require_once '../src/Core/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $mensajeActivo = 'Versión Beta activa. Esta herramienta está en desarrollo. Si ves un fallo, avísanos.';
$mensajeActivo = '';
$GLOBALS['systemAlertMessage'] = trim((string)$mensajeActivo);

require_once '../src/Router/routes.php';
