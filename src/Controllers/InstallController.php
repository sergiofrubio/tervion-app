<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\InstallerService;

class InstallController extends Controller
{
    /**
     * Muestra la pantalla del asistente de configuración inicial.
     */
    public function index()
    {
        if (InstallerService::isInstalled()) {
            header('Location: ' . PROJECT_ROOT . '/login');
            $this->exitApp();
        }

        $requirements = InstallerService::checkRequirements();
        $this->view('install/index', [
            'requirements' => $requirements,
            'data' => $_SESSION['install_form_data'] ?? [],
            'error' => $_SESSION['install_error'] ?? null
        ]);

        unset($_SESSION['install_error'], $_SESSION['install_form_data']);
    }

    /**
     * Procesa los datos enviados desde el asistente de instalación.
     */
    public function process()
    {
        if (InstallerService::isInstalled()) {
            header('Location: ' . PROJECT_ROOT . '/login');
            $this->exitApp();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . PROJECT_ROOT . '/install');
            $this->exitApp();
        }

        // Datos de la Clínica
        $nombreClinica = trim($_POST['nombre_comercial'] ?? '');
        $razonSocial = trim($_POST['razon_social'] ?? '');
        $nifCif = trim($_POST['nif_cif'] ?? '');
        $telefonoContacto = trim($_POST['telefono_contacto'] ?? '');
        $emailContacto = trim($_POST['email_contacto'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $ciudad = trim($_POST['ciudad'] ?? '');
        $provincia = trim($_POST['provincia_estado'] ?? '');
        $cp = trim($_POST['codigo_postal'] ?? '');
        $pais = trim($_POST['pais'] ?? 'España');
        $sitioWeb = trim($_POST['sitio_web'] ?? '');

        // Datos del Administrador
        $adminNombre = trim($_POST['admin_nombre'] ?? '');
        $adminApellidos = trim($_POST['admin_apellidos'] ?? '');
        $adminDni = trim($_POST['admin_dni'] ?? '');
        $adminEmail = trim($_POST['admin_email'] ?? '');
        $adminPass = $_POST['admin_pass'] ?? '';
        $adminConfirmPass = $_POST['admin_confirm_pass'] ?? '';

        $_SESSION['install_form_data'] = $_POST;

        // Validaciones
        if (empty($nombreClinica)) {
            $_SESSION['install_error'] = 'El nombre comercial de la clínica es obligatorio.';
            header('Location: ' . PROJECT_ROOT . '/install');
            $this->exitApp();
        }

        if (empty($adminNombre) || empty($adminEmail)) {
            $_SESSION['install_error'] = 'El nombre y el correo electrónico del administrador son obligatorios.';
            header('Location: ' . PROJECT_ROOT . '/install');
            $this->exitApp();
        }

        if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['install_error'] = 'El formato del correo electrónico del administrador no es válido.';
            header('Location: ' . PROJECT_ROOT . '/install');
            $this->exitApp();
        }

        if (strlen($adminPass) < 8) {
            $_SESSION['install_error'] = 'La contraseña del administrador debe contener al menos 8 caracteres.';
            header('Location: ' . PROJECT_ROOT . '/install');
            $this->exitApp();
        }

        if ($adminPass !== $adminConfirmPass) {
            $_SESSION['install_error'] = 'Las contraseñas no coinciden.';
            header('Location: ' . PROJECT_ROOT . '/install');
            $this->exitApp();
        }

        try {
            $clinicaData = [
                'nombre_comercial' => $nombreClinica,
                'razon_social' => !empty($razonSocial) ? $razonSocial : $nombreClinica,
                'nif_cif' => !empty($nifCif) ? $nifCif : 'B00000000',
                'telefono_contacto' => $telefonoContacto,
                'email_contacto' => $emailContacto,
                'direccion' => $direccion,
                'ciudad' => $ciudad,
                'provincia_estado' => $provincia,
                'codigo_postal' => $cp,
                'pais' => $pais,
                'sitio_web' => $sitioWeb
            ];

            $adminData = [
                'nombre' => $adminNombre,
                'apellidos' => $adminApellidos,
                'dni' => $adminDni,
                'email' => $adminEmail,
                'pass' => $adminPass,
                'telefono' => $telefonoContacto
            ];

            InstallerService::install($clinicaData, $adminData);
            unset($_SESSION['install_form_data']);

            header('Location: ' . PROJECT_ROOT . '/login?alert=success&message=' . urlencode('¡Configuración inicial completada con éxito! Ya puedes iniciar sesión con tu cuenta de administrador.'));
            $this->exitApp();
        } catch (\Throwable $e) {
            $_SESSION['install_error'] = 'Error en la instalación: ' . $e->getMessage();
            header('Location: ' . PROJECT_ROOT . '/install');
            $this->exitApp();
        }
    }
}