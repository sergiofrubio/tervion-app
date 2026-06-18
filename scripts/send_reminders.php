<?php
// Bootstrap the application for CLI execution
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Core/config.php';

use App\Controllers\AppointmentController;

$controller = new AppointmentController();
$controller->enviarRecordatorios();
