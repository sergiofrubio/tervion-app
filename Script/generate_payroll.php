<?php
// Bootstrap the application for CLI execution
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Core/config.php';

use App\Controllers\PayrollController;

$controller = new PayrollController();
$controller->processAllMonthlyPayrolls(date('m'), date('Y'));
