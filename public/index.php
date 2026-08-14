<?php
require_once '../vendor/autoload.php';
require_once '../Core/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensajeActivo = '';
$GLOBALS['systemAlertMessage'] = trim((string)$mensajeActivo);

require_once '../Router/routes.php';