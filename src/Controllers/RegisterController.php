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

        // Datos del Autónomo / Administrador
        $usuario_id = trim($_POST['usuario_id'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
        $direccion = trim($_POST['direccion'] ?? '');
        $provincia = trim($_POST['provincia'] ?? '');
        $municipio = trim($_POST['municipio'] ?? '');
        $cp = trim($_POST['cp'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass = $_POST['pass'] ?? '';
        $confirm_pass = $_POST['confirm_pass'] ?? '';
        $genero = $_POST['genero'] ?? 'Otro';
        $nss = trim($_POST['nss'] ?? '');
        $iban = trim($_POST['iban'] ?? '');

        // Datos de la Clínica
        $nombre_comercial = trim($_POST['nombre_comercial'] ?? '');
        $razon_social = trim($_POST['razon_social'] ?? '');
        $telefono_contacto = trim($_POST['telefono_contacto'] ?? '');
        $email_contacto = trim($_POST['email_contacto'] ?? '');
        $sitio_web = trim($_POST['sitio_web'] ?? '');
        $direccion_calle = trim($_POST['direccion_calle'] ?? '');
        $ciudad = trim($_POST['ciudad'] ?? '');
        $provincia_estado = trim($_POST['provincia_estado'] ?? '');
        $codigo_postal = trim($_POST['codigo_postal'] ?? '');
        $pais = trim($_POST['pais'] ?? 'España');

        // Validaciones básicas
        if (empty($usuario_id) || strlen($usuario_id) !== 9) {
            $this->view('landing/registro', ['error' => 'El NIF/DNI debe tener exactamente 9 caracteres.', 'data' => $_POST]);
            return;
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('landing/registro', ['error' => 'El formato del correo electrónico del administrador no es válido.', 'data' => $_POST]);
            return;
        }

        if (strlen($pass) < 8) {
            $this->view('landing/registro', ['error' => 'La contraseña debe tener al menos 8 caracteres.', 'data' => $_POST]);
            return;
        }

        if ($pass !== $confirm_pass) {
            $this->view('landing/registro', ['error' => 'Las contraseñas del administrador no coinciden.', 'data' => $_POST]);
            return;
        }

        if (empty($nombre) || empty($apellidos) || empty($fecha_nacimiento)) {
            $this->view('landing/registro', ['error' => 'Por favor, rellene todos los campos obligatorios del administrador.', 'data' => $_POST]);
            return;
        }

        if (empty($nombre_comercial) || empty($direccion_calle) || empty($ciudad) || empty($telefono_contacto)) {
            $this->view('landing/registro', ['error' => 'Por favor, rellene todos los campos obligatorios de la clínica.', 'data' => $_POST]);
            return;
        }

        try {
            // Verificar si el correo o NIF/DNI ya están registrados
            $stmt = $db->prepare("SELECT usuario_id FROM usuarios WHERE usuario_id = :id OR email = :email LIMIT 1");
            $stmt->execute([':id' => $usuario_id, ':email' => $email]);
            if ($stmt->fetch()) {
                $this->view('landing/registro', ['error' => 'El NIF/DNI o el correo electrónico del administrador ya se encuentra registrado.', 'data' => $_POST]);
                return;
            }

            // Verificar si el correo de la clínica ya está registrado
            if (!empty($email_contacto)) {
                $stmt = $db->prepare("SELECT id_clinica FROM clinicas WHERE email_contacto = :email LIMIT 1");
                $stmt->execute([':email' => $email_contacto]);
                if ($stmt->fetch()) {
                    $this->view('landing/registro', ['error' => 'El correo electrónico de la clínica ya se encuentra registrado.', 'data' => $_POST]);
                    return;
                }
            }

            // Iniciar Transacción
            $db->beginTransaction();

            // 1. Insertar el usuario Administrador
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
            $stmtUser = $db->prepare("INSERT INTO usuarios (usuario_id, nombre, apellidos, telefono, fecha_nacimiento, direccion, provincia, municipio, cp, email, pass, genero, rol) 
                                      VALUES (:usuario_id, :nombre, :apellidos, :telefono, :fecha_nacimiento, :direccion, :provincia, :municipio, :cp, :email, :pass, :genero, 'Administrador')");
            $stmtUser->execute([
                ':usuario_id' => $usuario_id,
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':telefono' => $telefono ?: null,
                ':fecha_nacimiento' => $fecha_nacimiento,
                ':direccion' => $direccion ?: null,
                ':provincia' => $provincia ?: null,
                ':municipio' => $municipio ?: null,
                ':cp' => $cp ?: null,
                ':email' => $email,
                ':pass' => $hashedPass,
                ':genero' => $genero
            ]);

            // 2. Insertar en empleados para el administrador autónomo
            $stmtEmp = $db->prepare("INSERT INTO empleados (usuario_id, nss, iban, grupo_cotizacion) VALUES (:usuario_id, :nss, :iban, 1)");
            $stmtEmp->execute([
                ':usuario_id' => $usuario_id,
                ':nss' => $nss ?: null,
                ':iban' => $iban ?: null
            ]);

            // 3. Insertar la clínica
            $stmtClinica = $db->prepare("INSERT INTO clinicas (nombre_comercial, razon_social, direccion_calle, ciudad, provincia_estado, codigo_postal, pais, telefono_contacto, email_contacto, sitio_web, activo) 
                                         VALUES (:nombre_comercial, :razon_social, :direccion_calle, :ciudad, :provincia_estado, :codigo_postal, :pais, :telefono_contacto, :email_contacto, :sitio_web, 1)");
            $stmtClinica->execute([
                ':nombre_comercial' => $nombre_comercial,
                ':razon_social' => $razon_social ?: null,
                ':direccion_calle' => $direccion_calle,
                ':ciudad' => $ciudad,
                ':provincia_estado' => $provincia_estado ?: null,
                ':codigo_postal' => $codigo_postal ?: null,
                ':pais' => $pais ?: 'España',
                ':telefono_contacto' => $telefono_contacto,
                ':email_contacto' => $email_contacto ?: null,
                ':sitio_web' => $sitio_web ?: null
            ]);

            // 4. Insertar cuenta de cliente con plan de suscripción
            $slug = strtolower(str_replace(' ', '-', $nombre_comercial));
            $stmtCuenta = $db->prepare("INSERT INTO cuentas_clientes (nombre_empresa, nif_cif, slug, plan_suscripcion, email_admin, estado_cuenta) 
                                        VALUES (:nombre_empresa, :nif_cif, :slug, :plan_suscripcion, :email_admin, 'Activo')");
            $stmtCuenta->execute([
                ':nombre_empresa' => $nombre_comercial,
                ':nif_cif' => $usuario_id,
                ':slug' => $slug,
                ':plan_suscripcion' => $_POST['plan_suscripcion'] ?? 'Premium',
                ':email_admin' => $email
            ]);

            // 5. Guardar la tarjeta en metodos_pago (si se proporcionó)
            $card_holder = trim($_POST['card_holder'] ?? '');
            $card_number = trim($_POST['card_number'] ?? '');
            $card_expiry = trim($_POST['card_expiry'] ?? '');
            $card_cvv = trim($_POST['card_cvv'] ?? '');

            if (!empty($card_number)) {
                $last4 = substr(str_replace(' ', '', $card_number), -4);
                $stmtMP = $db->prepare("INSERT INTO metodos_pago (usuario_id, tipo, proveedor, last4, fecha_expiracion, token_externo, es_predeterminado, nombre_titular, numero_completo, cvv, creado_por) 
                                        VALUES (:usuario_id, 'Tarjeta', 'Visa', :last4, :fecha_expiracion, :token_externo, 1, :nombre_titular, :numero_completo, :cvv, :usuario_id)");
                $stmtMP->execute([
                    ':usuario_id' => $usuario_id,
                    ':last4' => $last4,
                    ':fecha_expiracion' => $card_expiry,
                    ':token_externo' => 'tok_' . bin2hex(random_bytes(8)),
                    ':nombre_titular' => $card_holder,
                    ':numero_completo' => $card_number,
                    ':cvv' => $card_cvv
                ]);
            }

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
