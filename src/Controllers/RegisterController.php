<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\DataBase;
use PDO;

class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro multi-paso.
     *
     * @return void
     */
    public function index()
    {
        if (isset($_SESSION['email'])) {
            header('Location: ' . PROJECT_ROOT . '/inicio');
            $this->exitApp();
        }
        $this->view('landing/registro', ['data' => [], 'error' => null]);
    }

    public $db = null;

    /**
     * Procesa la solicitud de registro de cliente y autónomo.
     *
     * @return void
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . PROJECT_ROOT . '/registro');
            $this->exitApp();
        }

        $db = $this->db ?: (new DataBase())->connect();

        // Datos del Administrador
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass = $_POST['pass'] ?? '';
        $confirm_pass = $_POST['confirm_pass'] ?? '';

        // Validaciones básicas
        if (empty($nombre)) {
            $this->view('landing/registro', ['error' => 'Por favor, introduce tu nombre.', 'data' => $_POST]);
            return;
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('landing/registro', ['error' => 'El formato del correo electrónico no es válido.', 'data' => $_POST]);
            return;
        }

        if (strlen($pass) < 8) {
            $this->view('landing/registro', ['error' => 'La contraseña debe tener al menos 8 caracteres.', 'data' => $_POST]);
            return;
        }

        if ($pass !== $confirm_pass) {
            $this->view('landing/registro', ['error' => 'Las contraseñas no coinciden.', 'data' => $_POST]);
            return;
        }

        try {
            // Verificar si el correo ya está registrado
            $stmt = $db->prepare("SELECT usuario_id FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $this->view('landing/registro', ['error' => 'El correo electrónico ya se encuentra registrado.', 'data' => $_POST]);
                return;
            }

            // Generar un identificador de 9 caracteres único para el usuario
            do {
                $usuario_id = 'A' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
                $stmtCheck = $db->prepare("SELECT usuario_id FROM usuarios WHERE usuario_id = :id LIMIT 1");
                $stmtCheck->execute([':id' => $usuario_id]);
            } while ($stmtCheck->fetch());

            // Iniciar Transacción
            $db->beginTransaction();

            // 1. Insertar el usuario con rol Administrador
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
            $stmtUser = $db->prepare("INSERT INTO usuarios (dni, nombre, apellidos, telefono, fecha_nacimiento, direccion, provincia, municipio, cp, email, pass, genero, rol) 
                                      VALUES (:dni, :nombre, :apellidos, NULL, '1990-01-01', NULL, NULL, NULL, NULL, :email, :pass, 'Otro', 'Administrador')");
            $stmtUser->execute([
                ':dni' => $usuario_id,
                ':nombre' => $nombre,
                ':apellidos' => '',
                ':email' => $email,
                ':pass' => $hashedPass
            ]);
            $newAdminId = (int)$db->lastInsertId();

            // 2. Registrar cuenta de cliente inicial vinculada al administrador
            $nombreEmpresa = 'Clínica de ' . $nombre;
            $slugBase = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nombreEmpresa));
            $slug = trim($slugBase, '-') . '-' . substr($usuario_id, 1, 4);

            $stmtCuenta = $db->prepare("INSERT INTO cuentas_clientes (nombre_empresa, nif_cif, slug, plan_suscripcion, email_admin, estado_cuenta) 
                                        VALUES (:nombre_empresa, :nif_cif, :slug, 'Community', :email_admin, 'Activo')");
            $stmtCuenta->execute([
                ':nombre_empresa' => $nombreEmpresa,
                ':nif_cif' => $usuario_id,
                ':slug' => $slug,
                ':email_admin' => $email
            ]);

            $db->commit();

            // Redirigir al login con mensaje de éxito
            header('Location: ' . PROJECT_ROOT . '/login?alert=success&message=' . urlencode('¡Registro completado con éxito! Ya puedes iniciar sesión con tu cuenta de administrador.'));
            $this->exitApp();
        } catch (\Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $this->view('landing/registro', ['error' => 'Ocurrió un error inesperado al procesar el registro: ' . $e->getMessage(), 'data' => $_POST]);
        }
    }
}
