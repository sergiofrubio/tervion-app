<?php
use App\Routes\Router;

$router = new Router();

// Rutas Públicas (Sin autenticación)
$router->add('GET', '/landing', 'LandingController@index', false);
$router->add('GET', '/', 'LoginController@index', false);
$router->add('GET', '/login', 'LoginController@index', false);
$router->add('POST', '/login', 'LoginController@iniciarSesion', false);
$router->add('POST', '/login/reset-password', 'LoginController@generatePasswordResetToken', false);
$router->add('GET', '/logout', 'LoginController@finishSesion', false);

// Rutas Generales (Requieren autenticación, accesibles por todos los roles)
$router->add('GET', '/inicio', 'HomeController@index', true, ['Administrador', 'Fisioterapeuta', 'Paciente']);

// Rutas para Administradores y Fisioterapeutas
$staffRoles = ['Administrador', 'Fisioterapeuta'];

$router->add('GET', '/usuarios', 'UserController@list', true, $staffRoles);
$router->add('GET', '/usuarios/search', 'UserController@search', true, $staffRoles);
$router->add('GET', '/usuarios/create', 'UserController@create', true, ['Administrador']); // Solo admin crea usuarios
$router->add('POST', '/usuarios/create', 'UserController@create', true, ['Administrador']);
$router->add('POST', '/usuarios/delete', 'UserController@delete', true, ['Administrador']);
$router->add('GET', '/usuarios/edit', 'UserController@edit', true, ['Administrador']);
$router->add('POST', '/usuarios/edit', 'UserController@edit', true, ['Administrador']);
$router->add('GET', '/usuarios/detail', 'UserController@detail', true, $staffRoles);
$router->add('GET', '/usuarios/pdf', 'UserController@createPDF', true, $staffRoles);
$router->add('POST', '/usuarios/pdf', 'UserController@createPDF', true, $staffRoles);


$router->add('GET', '/citas', 'AppointmentController@list', true, $staffRoles);
$router->add('GET', '/citas/create', 'AppointmentController@create', true, $staffRoles);
$router->add('POST', '/citas/create', 'AppointmentController@create', true, $staffRoles);
$router->add('POST', '/citas/delete', 'AppointmentController@delete', true, $staffRoles);
$router->add('POST', '/citas/edit', 'AppointmentController@edit', true, $staffRoles);
$router->add('GET', '/citas/edit', 'AppointmentController@edit', true, $staffRoles);
$router->add('GET', '/citas/slots', 'AppointmentController@getSlots', true, $staffRoles);

$router->add('GET', '/configuracion', 'ConfiguracionController@index', true, ['Administrador']);
$router->add('GET', '/configuracion/horarios/create', 'ConfiguracionController@createHorario', true, ['Administrador']);
$router->add('POST', '/configuracion/horarios/create', 'ConfiguracionController@createHorario', true, ['Administrador']);
$router->add('GET', '/configuracion/horarios/edit', 'ConfiguracionController@editHorario', true, ['Administrador']);
$router->add('POST', '/configuracion/horarios/edit', 'ConfiguracionController@editHorario', true, ['Administrador']);

$router->add('GET', '/configuracion/ausencias/create', 'ConfiguracionController@createAusencia', true, ['Administrador']);
$router->add('POST', '/configuracion/ausencias/create', 'ConfiguracionController@createAusencia', true, ['Administrador']);
$router->add('GET', '/configuracion/ausencias/edit', 'ConfiguracionController@editAusencia', true, ['Administrador']);
$router->add('POST', '/configuracion/ausencias/edit', 'ConfiguracionController@editAusencia', true, ['Administrador']);

$router->add('GET', '/configuracion/especialidades/create', 'ConfiguracionController@createEspecialidad', true, ['Administrador']);
$router->add('POST', '/configuracion/especialidades/create', 'ConfiguracionController@createEspecialidad', true, ['Administrador']);
$router->add('GET', '/configuracion/especialidades/edit', 'ConfiguracionController@editEspecialidad', true, ['Administrador']);
$router->add('POST', '/configuracion/especialidades/edit', 'ConfiguracionController@editEspecialidad', true, ['Administrador']);

$router->add('GET', '/configuracion/bonos/create', 'ConfiguracionController@createBono', true, ['Administrador']);
$router->add('POST', '/configuracion/bonos/create', 'ConfiguracionController@createBono', true, ['Administrador']);
$router->add('GET', '/configuracion/bonos/edit', 'ConfiguracionController@editBono', true, ['Administrador']);
$router->add('POST', '/configuracion/bonos/edit', 'ConfiguracionController@editBono', true, ['Administrador']);

$router->add('POST', '/configuracion/clinica/update', 'ConfiguracionController@updateClinica', true, ['Administrador']);

$router->add('GET', '/historial/create', 'MedicalHistoryController@create', true, $staffRoles);
$router->add('POST', '/historial/create', 'MedicalHistoryController@create', true, $staffRoles);
$router->add('GET', '/historial/detail', 'MedicalHistoryController@detail', true, $staffRoles);
$router->add('GET', '/historial/pdf', 'MedicalHistoryController@pdf', true, $staffRoles);

$router->add('GET', '/facturas', 'InvoiceController@list', true, $staffRoles);
$router->add('GET', '/facturas/create', 'InvoiceController@create', true, $staffRoles);
$router->add('POST', '/facturas/create', 'InvoiceController@create', true, $staffRoles);
$router->add('GET', '/facturas/edit', 'InvoiceController@edit', true, $staffRoles);
$router->add('POST', '/facturas/edit', 'InvoiceController@edit', true, $staffRoles);
$router->add('POST', '/facturas/delete', 'InvoiceController@delete', true, $staffRoles);
$router->add('GET', '/facturas/pdf', 'InvoiceController@pdf', true, $staffRoles);

$router->add('GET', '/nominas', 'PayrollController@list', true, ['Administrador']);
$router->add('GET', '/nominas/contratos', 'PayrollController@listContracts', true, ['Administrador']);
$router->add('GET', '/nominas/contratos/create', 'PayrollController@createContract', true, ['Administrador']);
$router->add('POST', '/nominas/contratos/create', 'PayrollController@createContract', true, ['Administrador']);
$router->add('GET', '/nominas/contratos/edit', 'PayrollController@editContract', true, ['Administrador']);
$router->add('POST', '/nominas/contratos/edit', 'PayrollController@editContract', true, ['Administrador']);
$router->add('GET', '/nominas/generate', 'PayrollController@generate', true, ['Administrador']);
$router->add('POST', '/nominas/generate', 'PayrollController@generate', true, ['Administrador']);
$router->add('GET', '/nominas/detail', 'PayrollController@detail', true, ['Administrador']);
$router->add('GET', '/nominas/pdf', 'PayrollController@pdf', true, ['Administrador']);

// Rutas para Pacientes
$router->add('GET', '/vista-pacientes/citas', 'AppointmentController@list', true, ['Paciente']);
$router->add('GET', '/vista-pacientes/citas/nueva', 'AppointmentController@create', true, ['Paciente']);
$router->add('POST', '/vista-pacientes/citas/nueva', 'AppointmentController@create', true, ['Paciente']);
$router->add('GET', '/vista-pacientes/citas/edit', 'AppointmentController@edit', true, ['Paciente']);
$router->add('POST', '/vista-pacientes/citas/edit', 'AppointmentController@edit', true, ['Paciente']);
$router->add('POST', '/vista-pacientes/citas/delete', 'AppointmentController@delete', true, ['Paciente']);

$router->add('GET', '/vista-pacientes/perfil', 'ProfileController@index', true, ['Paciente']);
$router->add('GET', '/vista-pacientes/perfil/edit', 'ProfileController@edit', true, ['Paciente']);
$router->add('POST', '/vista-pacientes/perfil/edit', 'ProfileController@edit', true, ['Paciente']);
$router->add('POST', '/vista-pacientes/perfil/add-payment-method', 'ProfileController@addPaymentMethod', true, ['Paciente']);
$router->add('GET', '/vista-pacientes/perfil/delete-payment-method', 'ProfileController@deletePaymentMethod', true, ['Paciente']);
$router->add('GET', '/vista-pacientes/perfil/set-primary-payment', 'ProfileController@setPrimaryPaymentMethod', true, ['Paciente']);

$router->add('GET', '/vista-pacientes/tienda', 'ShopController@list', true, ['Paciente']);
$router->add('GET', '/vista-pacientes/tienda/pago', 'ShopController@pago', true, ['Paciente']);
$router->add('POST', '/vista-pacientes/tienda/procesar-pago', 'ShopController@procesarPago', true, ['Paciente']);

$router->add('GET', '/vista-pacientes/facturas', 'InvoiceController@list', true, ['Paciente']);

$router->handleRequest();