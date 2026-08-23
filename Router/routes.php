<?php

use App\Router\Router;

$router = new Router();

// Rutas Públicas (Sin autenticación)
$router->add('GET', '/', 'LandingController@index', false);
$router->add('GET', '/privacidad', 'LandingController@privacidad', false);
$router->add('GET', '/terminos', 'LandingController@terminos', false);
$router->add('GET', '/cookies', 'LandingController@cookies', false);
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

// Rutas de Administración SaaS (SuperAdmin)
$router->add('GET', '/superadmin/clientes', 'SaasAdminController@tenants', true, ['SuperAdmin']);
$router->add('GET', '/superadmin/clientes/crear', 'SaasAdminController@createTenant', true, ['SuperAdmin']);
$router->add('POST', '/superadmin/clientes/crear', 'SaasAdminController@createTenant', true, ['SuperAdmin']);
$router->add('POST', '/superadmin/clientes/estado', 'SaasAdminController@updateStatus', true, ['SuperAdmin']);
$router->add('POST', '/superadmin/clientes/plan', 'SaasAdminController@updatePlan', true, ['SuperAdmin']);
$router->add('GET', '/superadmin/facturas', 'SaasAdminController@invoices', true, ['SuperAdmin']);
$router->add('GET', '/superadmin/facturas/crear', 'SaasAdminController@createInvoice', true, ['SuperAdmin']);
$router->add('POST', '/superadmin/facturas/crear', 'SaasAdminController@createInvoice', true, ['SuperAdmin']);
$router->add('POST', '/superadmin/facturas/estado', 'SaasAdminController@updateInvoiceStatus', true, ['SuperAdmin']);
$router->add('GET', '/superadmin/facturas/pdf', 'SaasAdminController@invoicePdf', true, ['SuperAdmin']);

// Rutas Generales (Requieren autenticación, accesibles por todos los roles)
$allRoles = ['SuperAdmin', 'Administrador', 'Fisioterapeuta', 'Secretario', 'Paciente'];
$router->add('GET', '/inicio', 'HomeController@index', true, $allRoles);
$router->add('GET', '/perfil', 'ProfileController@index', true, $allRoles);
$router->add('GET', '/perfil/editar', 'ProfileController@edit', true, $allRoles);
$router->add('POST', '/perfil/editar', 'ProfileController@edit', true, $allRoles);

// Rutas para Administradores y Fisioterapeutas/Secretarios
$staffRoles = ['Administrador', 'Fisioterapeuta', 'Secretario'];

// Rutas para Pacientes
$router->add('GET', '/pacientes', 'PatientController@list', true, $staffRoles);
$router->add('GET', '/pacientes/buscar', 'PatientController@search', true, $staffRoles);
$router->add('GET', '/pacientes/crear', 'PatientController@create', true, ['Administrador', 'Secretario']);
$router->add('POST', '/pacientes/crear', 'PatientController@create', true, ['Administrador', 'Secretario']);
$router->add('POST', '/pacientes/eliminar', 'PatientController@delete', true, ['Administrador']);
$router->add('GET', '/pacientes/editar', 'PatientController@edit', true, ['Administrador', 'Secretario']);
$router->add('POST', '/pacientes/editar', 'PatientController@edit', true, ['Administrador', 'Secretario']);
$router->add('GET', '/pacientes/detalle', 'PatientController@detail', true, $staffRoles);
$router->add('GET', '/pacientes/pdf', 'PatientController@createPDF', true, $staffRoles);
$router->add('POST', '/pacientes/pdf', 'PatientController@createPDF', true, $staffRoles);
$router->add('GET', '/pacientes/consentimiento-pdf', 'PatientController@downloadConsent', true, array_merge($staffRoles, ['Paciente']));

// Ruta de búsqueda de trabajadores (usado para asignar citas)
$router->add('GET', '/trabajadores/buscar', 'PatientController@searchWorkers', true, $staffRoles);


$router->add('GET', '/citas', 'AppointmentController@list', true, $staffRoles);
$router->add('GET', '/citas/crear', 'AppointmentController@create', true, $staffRoles);
$router->add('POST', '/citas/crear', 'AppointmentController@create', true, $staffRoles);
$router->add('POST', '/citas/eliminar', 'AppointmentController@delete', true, $staffRoles);
$router->add('POST', '/citas/editar', 'AppointmentController@edit', true, $staffRoles);
$router->add('GET', '/citas/editar', 'AppointmentController@edit', true, $staffRoles);
$router->add('GET', '/citas/slots', 'AppointmentController@getSlots', true, ['Administrador', 'Fisioterapeuta', 'Secretario', 'Paciente']);
$router->add('GET', '/citas/dias-disponibles', 'AppointmentController@getAvailableDays', true, ['Administrador', 'Fisioterapeuta', 'Secretario', 'Paciente']);

$router->add('GET', '/configuracion', 'SettingController@index', true, ['Administrador']);
$router->add('GET', '/configuracion/horarios/crear', 'SettingController@createHorario', true, ['Administrador']);
$router->add('POST', '/configuracion/horarios/crear', 'SettingController@createHorario', true, ['Administrador']);
$router->add('GET', '/configuracion/horarios/editar', 'SettingController@editHorario', true, ['Administrador']);
$router->add('POST', '/configuracion/horarios/editar', 'SettingController@editHorario', true, ['Administrador']);

$router->add('GET', '/configuracion/ausencias/crear', 'SettingController@createAusencia', true, ['Administrador']);
$router->add('POST', '/configuracion/ausencias/crear', 'SettingController@createAusencia', true, ['Administrador']);
$router->add('GET', '/configuracion/ausencias/editar', 'SettingController@editAusencia', true, ['Administrador']);
$router->add('POST', '/configuracion/ausencias/editar', 'SettingController@editAusencia', true, ['Administrador']);

$router->add('GET', '/configuracion/bonos/crear', 'SettingController@createBono', true, ['Administrador']);
$router->add('POST', '/configuracion/bonos/crear', 'SettingController@createBono', true, ['Administrador']);
$router->add('GET', '/configuracion/bonos/editar', 'SettingController@editBono', true, ['Administrador']);
$router->add('POST', '/configuracion/bonos/editar', 'SettingController@editBono', true, ['Administrador']);

$router->add('POST', '/configuracion/clinica/actualizar', 'SettingController@saveClinica', true, ['Administrador']);
$router->add('POST', '/configuracion/clinica/guardar', 'SettingController@saveClinica', true, ['Administrador']);
$router->add('POST', '/configuracion/tarjeta/actualizar', 'SettingController@updateTarjeta', true, ['Administrador']);
$router->add('POST', '/configuracion/suscripcion/actualizar', 'SettingController@updatePlan', true, ['Administrador']);
$router->add('POST', '/configuracion/suscripcion/cancel-downgrade', 'SettingController@cancelPlanDowngrade', true, ['Administrador']);

$router->add('GET', '/historial/crear', 'MedicalReportController@create', true, $staffRoles);
$router->add('POST', '/historial/crear', 'MedicalReportController@create', true, $staffRoles);
$router->add('GET', '/historial/detalle', 'MedicalReportController@detail', true, $staffRoles);
$router->add('GET', '/historial/pdf', 'MedicalReportController@pdf', true, $staffRoles);

$router->add('GET', '/facturas', 'InvoiceController@list', true, $staffRoles);
$router->add('GET', '/facturas/crear', 'InvoiceController@create', true, $staffRoles);
$router->add('POST', '/facturas/crear', 'InvoiceController@create', true, $staffRoles);
$router->add('GET', '/facturas/editar', 'InvoiceController@edit', true, $staffRoles);
$router->add('POST', '/facturas/editar', 'InvoiceController@edit', true, $staffRoles);
$router->add('POST', '/facturas/eliminar', 'InvoiceController@delete', true, $staffRoles);
$router->add('GET', '/facturas/pdf', 'InvoiceController@pdf', true, array_merge($staffRoles, ['SuperAdmin']));
$router->add('GET', '/facturas/reenviar', 'InvoiceController@reenviarVerifactu', true, $staffRoles);
$router->add('POST', '/facturas/reenviar', 'InvoiceController@reenviarVerifactu', true, $staffRoles);

$router->add('GET', '/nominas', 'PayrollController@list', true, ['Administrador']);
$router->add('GET', '/nominas/contratos', 'ContractController@list', true, ['Administrador']);
$router->add('GET', '/nominas/contratos/crear', 'ContractController@create', true, ['Administrador']);
$router->add('POST', '/nominas/contratos/crear', 'ContractController@create', true, ['Administrador']);
$router->add('GET', '/nominas/contratos/editar', 'ContractController@edit', true, ['Administrador']);
$router->add('POST', '/nominas/contratos/editar', 'ContractController@edit', true, ['Administrador']);
$router->add('GET', '/nominas/generar', 'PayrollController@generate', true, ['Administrador']);
$router->add('POST', '/nominas/generar', 'PayrollController@generate', true, ['Administrador']);
$router->add('GET', '/nominas/detalle', 'PayrollController@detail', true, ['Administrador']);
$router->add('GET', '/nominas/pdf', 'PayrollController@pdf', true, ['Administrador']);

// Rutas de Control Horario
$router->add('GET', '/fichajes', 'TimeRecordController@index', true, $staffRoles);
$router->add('POST', '/fichajes/fichar', 'TimeRecordController@fichar', true, $staffRoles);
$router->add('GET', '/fichajes/admin', 'TimeRecordController@adminIndex', true, ['Administrador']);
$router->add('POST', '/fichajes/admin/guardar', 'TimeRecordController@guardar', true, ['Administrador']);

// Rutas de Contabilidad y Obligaciones Fiscales
$router->add('GET', '/contabilidad', 'AccountingController@dashboard', true, ['Administrador']);
$router->add('GET', '/contabilidad/gastos', 'AccountingController@expenses', true, ['Administrador']);
$router->add('POST', '/contabilidad/gastos', 'AccountingController@expenses', true, ['Administrador']);
$router->add('POST', '/contabilidad/gastos/eliminar', 'AccountingController@deleteExpense', true, ['Administrador']);
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
$router->add('GET', '/paciente/citas/editar', 'AppointmentController@edit', true, ['Paciente']);
$router->add('POST', '/paciente/citas/editar', 'AppointmentController@edit', true, ['Paciente']);
$router->add('POST', '/paciente/citas/eliminar', 'AppointmentController@delete', true, ['Paciente']);

$router->add('GET', '/paciente/tienda', 'ShopController@list', true, ['Paciente']);
$router->add('GET', '/paciente/tienda/pago', 'ShopController@pago', true, ['Paciente']);
$router->add('POST', '/paciente/tienda/procesar-pago', 'ShopController@procesarPago', true, ['Paciente']);
$router->add('POST', '/paciente/tienda/notificacion', 'ShopController@notificacion', false);
$router->add('GET', '/paciente/tienda/confirmacion', 'ShopController@confirmacion', true, ['Paciente']);
$router->add('GET', '/paciente/tienda/error', 'ShopController@errorPago', true, ['Paciente']);

$router->add('GET', '/paciente/facturas', 'InvoiceController@list', true, ['Paciente']);
$router->add('GET', '/paciente/facturas/pdf', 'InvoiceController@pdf', true, ['Paciente']);

$router->handleRequest();
