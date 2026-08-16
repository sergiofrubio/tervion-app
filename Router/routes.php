<?php
use App\Router\Router;

$router = new Router();

// Rutas Públicas (Sin autenticación)
$router->add('GET', '/', 'LandingController@index', false);
$router->add('GET', '/login', 'LoginController@index', false);
$router->add('POST', '/login', 'LoginController@iniciarSesion', false);
$router->add('POST', '/login/reset-password', 'LoginController@generatePasswordResetToken', false);
$router->add('GET', '/login/reset-password', 'LoginController@showResetForm', false);
$router->add('POST', '/login/update-password', 'LoginController@updatePassword', false);
$router->add('GET', '/registro', 'RegisterController@index', false);
$router->add('POST', '/registro', 'RegisterController@register', false);
$router->add('GET', '/logout', 'LoginController@finishSesion', false);

// Rutas de Citas Públicas (Confirmación y Cron)
$router->add('GET', '/citas/confirmar', 'AppointmentController@confirmarAsistencia', false);
$router->add('GET', '/citas/cron/recordatorios', 'AppointmentController@enviarRecordatorios', false);

// Rutas Generales (Requieren autenticación, accesibles por todos los roles)
$router->add('GET', '/inicio', 'HomeController@index', true, ['Administrador', 'Fisioterapeuta', 'Paciente']);

// Rutas para Administradores y Fisioterapeutas/Secretarios (Staff)
$staffRoles = ['Administrador', 'Fisioterapeuta', 'Secretario'];

// Rutas para Pacientes
$router->add('GET', '/pacientes', 'PatientController@list', true, $staffRoles);
$router->add('GET', '/pacientes/search', 'PatientController@search', true, $staffRoles);
$router->add('GET', '/pacientes/create', 'PatientController@create', true, ['Administrador', 'Secretario']);
$router->add('POST', '/pacientes/create', 'PatientController@create', true, ['Administrador', 'Secretario']);
$router->add('POST', '/pacientes/delete', 'PatientController@delete', true, ['Administrador']);
$router->add('GET', '/pacientes/edit', 'PatientController@edit', true, ['Administrador', 'Secretario']);
$router->add('POST', '/pacientes/edit', 'PatientController@edit', true, ['Administrador', 'Secretario']);
$router->add('GET', '/pacientes/detail', 'PatientController@detail', true, $staffRoles);
$router->add('GET', '/pacientes/pdf', 'PatientController@createPDF', true, $staffRoles);
$router->add('POST', '/pacientes/pdf', 'PatientController@createPDF', true, $staffRoles);

// Ruta de búsqueda de trabajadores (usado para asignar citas)
$router->add('GET', '/trabajadores/search', 'PatientController@searchWorkers', true, $staffRoles);


$router->add('GET', '/citas', 'AppointmentController@list', true, $staffRoles);
$router->add('GET', '/citas/create', 'AppointmentController@create', true, $staffRoles);
$router->add('POST', '/citas/create', 'AppointmentController@create', true, $staffRoles);
$router->add('POST', '/citas/delete', 'AppointmentController@delete', true, $staffRoles);
$router->add('POST', '/citas/edit', 'AppointmentController@edit', true, $staffRoles);
$router->add('GET', '/citas/edit', 'AppointmentController@edit', true, $staffRoles);
$router->add('GET', '/citas/slots', 'AppointmentController@getSlots', true, ['Administrador', 'Fisioterapeuta', 'Secretario', 'Paciente']);
$router->add('GET', '/citas/dias-disponibles', 'AppointmentController@getAvailableDays', true, ['Administrador', 'Fisioterapeuta', 'Secretario', 'Paciente']);

$router->add('GET', '/configuracion', 'SettingController@index', true, ['Administrador']);
$router->add('GET', '/configuracion/horarios/create', 'SettingController@createHorario', true, ['Administrador']);
$router->add('POST', '/configuracion/horarios/create', 'SettingController@createHorario', true, ['Administrador']);
$router->add('GET', '/configuracion/horarios/edit', 'SettingController@editHorario', true, ['Administrador']);
$router->add('POST', '/configuracion/horarios/edit', 'SettingController@editHorario', true, ['Administrador']);

$router->add('GET', '/configuracion/ausencias/create', 'SettingController@createAusencia', true, ['Administrador']);
$router->add('POST', '/configuracion/ausencias/create', 'SettingController@createAusencia', true, ['Administrador']);
$router->add('GET', '/configuracion/ausencias/edit', 'SettingController@editAusencia', true, ['Administrador']);
$router->add('POST', '/configuracion/ausencias/edit', 'SettingController@editAusencia', true, ['Administrador']);




$router->add('GET', '/configuracion/bonos/create', 'SettingController@createBono', true, ['Administrador']);
$router->add('POST', '/configuracion/bonos/create', 'SettingController@createBono', true, ['Administrador']);
$router->add('GET', '/configuracion/bonos/edit', 'SettingController@editBono', true, ['Administrador']);
$router->add('POST', '/configuracion/bonos/edit', 'SettingController@editBono', true, ['Administrador']);

$router->add('POST', '/configuracion/clinica/update', 'SettingController@saveClinica', true, ['Administrador']);
$router->add('POST', '/configuracion/clinica/save', 'SettingController@saveClinica', true, ['Administrador']);
$router->add('POST', '/configuracion/tarjeta/update', 'SettingController@updateTarjeta', true, ['Administrador']);
$router->add('POST', '/configuracion/suscripcion/update-plan', 'SettingController@updatePlan', true, ['Administrador']);
$router->add('POST', '/configuracion/suscripcion/cancel-downgrade', 'SettingController@cancelPlanDowngrade', true, ['Administrador']);

$router->add('GET', '/historial/create', 'MedicalReportController@create', true, $staffRoles);
$router->add('POST', '/historial/create', 'MedicalReportController@create', true, $staffRoles);
$router->add('GET', '/historial/detail', 'MedicalReportController@detail', true, $staffRoles);
$router->add('GET', '/historial/pdf', 'MedicalReportController@pdf', true, $staffRoles);

$router->add('GET', '/facturas', 'InvoiceController@list', true, $staffRoles);
$router->add('GET', '/facturas/create', 'InvoiceController@create', true, $staffRoles);
$router->add('POST', '/facturas/create', 'InvoiceController@create', true, $staffRoles);
$router->add('GET', '/facturas/edit', 'InvoiceController@edit', true, $staffRoles);
$router->add('POST', '/facturas/edit', 'InvoiceController@edit', true, $staffRoles);
$router->add('POST', '/facturas/delete', 'InvoiceController@delete', true, $staffRoles);
$router->add('GET', '/facturas/pdf', 'InvoiceController@pdf', true, $staffRoles);
$router->add('GET', '/facturas/reenviar', 'InvoiceController@reenviarVerifactu', true, $staffRoles);
$router->add('POST', '/facturas/reenviar', 'InvoiceController@reenviarVerifactu', true, $staffRoles);

$router->add('GET', '/nominas', 'PayrollController@list', true, ['Administrador']);
$router->add('GET', '/nominas/contratos', 'ContractController@list', true, ['Administrador']);
$router->add('GET', '/nominas/contratos/create', 'ContractController@create', true, ['Administrador']);
$router->add('POST', '/nominas/contratos/create', 'ContractController@create', true, ['Administrador']);
$router->add('GET', '/nominas/contratos/edit', 'ContractController@edit', true, ['Administrador']);
$router->add('POST', '/nominas/contratos/edit', 'ContractController@edit', true, ['Administrador']);
$router->add('GET', '/nominas/generate', 'PayrollController@generate', true, ['Administrador']);
$router->add('POST', '/nominas/generate', 'PayrollController@generate', true, ['Administrador']);
$router->add('GET', '/nominas/detail', 'PayrollController@detail', true, ['Administrador']);
$router->add('GET', '/nominas/pdf', 'PayrollController@pdf', true, ['Administrador']);

// Rutas de Control Horario
$router->add('GET', '/control-horario', 'TimeRecordController@index', true, $staffRoles);
$router->add('POST', '/control-horario/fichar', 'TimeRecordController@fichar', true, $staffRoles);
$router->add('GET', '/control-horario/admin', 'TimeRecordController@adminIndex', true, ['Administrador']);
$router->add('POST', '/control-horario/admin/guardar', 'TimeRecordController@guardar', true, ['Administrador']);

// Rutas de Contabilidad y Obligaciones Fiscales
$router->add('GET', '/contabilidad', 'AccountingController@dashboard', true, ['Administrador']);
$router->add('GET', '/contabilidad/gastos', 'AccountingController@expenses', true, ['Administrador']);
$router->add('POST', '/contabilidad/gastos', 'AccountingController@expenses', true, ['Administrador']);
$router->add('POST', '/contabilidad/gastos/delete', 'AccountingController@deleteExpense', true, ['Administrador']);
$router->add('GET', '/contabilidad/impuestos', 'AccountingController@taxes', true, ['Administrador']);
$router->add('GET', '/contabilidad/exportar/emitidas', 'AccountingController@exportLibroEmitidas', true, ['Administrador']);
$router->add('GET', '/contabilidad/exportar/recibidas', 'AccountingController@exportLibroRecibidas', true, ['Administrador']);
$router->add('POST', '/contabilidad/gastos/auto-import', 'AccountingController@autoImport', true, ['Administrador']);
$router->add('POST', '/contabilidad/banco/importar', 'AccountingController@importBankFeed', true, ['Administrador']);
$router->add('GET', '/contabilidad/cron/trimestral', 'AccountingController@runQuarterlyCron', true, ['Administrador']);

// Rutas para Pacientes
$router->add('GET', '/paciente/citas', 'AppointmentController@list', true, ['Paciente']);
$router->add('GET', '/paciente/citas/nueva', 'AppointmentController@create', true, ['Paciente']);
$router->add('POST', '/paciente/citas/nueva', 'AppointmentController@create', true, ['Paciente']);
$router->add('GET', '/paciente/citas/edit', 'AppointmentController@edit', true, ['Paciente']);
$router->add('POST', '/paciente/citas/edit', 'AppointmentController@edit', true, ['Paciente']);
$router->add('POST', '/paciente/citas/delete', 'AppointmentController@delete', true, ['Paciente']);

$router->add('GET', '/paciente/perfil', 'ProfileController@index', true, ['Paciente']);
$router->add('GET', '/paciente/perfil/edit', 'ProfileController@edit', true, ['Paciente']);
$router->add('POST', '/paciente/perfil/edit', 'ProfileController@edit', true, ['Paciente']);

$router->add('GET', '/paciente/tienda', 'ShopController@list', true, ['Paciente']);
$router->add('GET', '/paciente/tienda/pago', 'ShopController@pago', true, ['Paciente']);
$router->add('POST', '/paciente/tienda/procesar-pago', 'ShopController@procesarPago', true, ['Paciente']);
$router->add('POST', '/paciente/tienda/notificacion', 'ShopController@notificacion', false);
$router->add('GET', '/paciente/tienda/confirmacion', 'ShopController@confirmacion', true, ['Paciente']);
$router->add('GET', '/paciente/tienda/error', 'ShopController@errorPago', true, ['Paciente']);

$router->add('GET', '/paciente/facturas', 'InvoiceController@list', true, ['Paciente']);
$router->add('GET', '/paciente/facturas/pdf', 'InvoiceController@pdf', true, ['Paciente']);

$router->handleRequest();