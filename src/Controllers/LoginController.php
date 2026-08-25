<?php

namespace App\Controllers;

use App\Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LoginController extends Controller
{

    private $loginModel;

    /**
     * Constructor de LoginController.
     *
     * Inicializa el modelo de inicio de sesión.
     */
    public function __construct()
    {
        $this->loginModel = $this->model('Login');
    }

    /**
     * Muestra la vista de inicio de sesión si no hay una sesión activa.
     *
     * Redirige al inicio si el usuario ya ha iniciado sesión.
     *
     * @return void
     */
    public function index()
    {
        if (isset($_SESSION['email'])) {
            header('Location: ' . PROJECT_ROOT . '/inicio');
            $this->exitApp();
        }
        $this->view('login/login');
    }

    /**
     * Procesa la solicitud de inicio de sesión.
     *
     * Verifica las credenciales del usuario y redirige al panel de control si son válidas,
     * o muestra un mensaje de advertencia si no lo son.
     *
     * @return void
     */
    public function iniciarSesion()
    {
        $email = $_POST['email'] ?? null;
        $pass = $_POST['pass'] ?? null;

        if ($email && $pass) {
            $usuario = $this->loginModel->getByEmail($email);

            if ($usuario) {
                if (password_verify($pass, $usuario['pass'])) {
                    $this->startSession($usuario);
                } else {
                    $this->redirectWithMessage("Contraseña incorrecta.", 'warning');
                }
            } else {
                $this->redirectWithMessage("Usuario no encontrado.", 'warning');
            }
        } else {
            $this->redirectWithMessage("Faltan credenciales.", 'warning');
        }
    }

    /**
     * Inicia una sesión de PHP y guarda la información del usuario en la sesión.
     *
     * @param array $usuario Datos del usuario a almacenar en la sesión.
     * @return void
     */
    private function startSession($usuario)
    {
        $_SESSION = array_merge($_SESSION, $usuario);

        header('Location: ' . PROJECT_ROOT . '/inicio');
        $this->exitApp();
    }

    /**
     * Finaliza la sesión actual del usuario, destruye las cookies de sesión y redirige al login.
     *
     * @return void
     */
    public function finishSesion()
    {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        header("Location: " . PROJECT_ROOT . "/login?alert=success&message=Sesion finalizada");
        $this->exitApp();
    }

    /**
     * Redirige al inicio de sesión con un mensaje y tipo de alerta específicos.
     *
     * @param string $message Mensaje a mostrar.
     * @param string $alertType Tipo de alerta (por ejemplo, 'danger', 'warning', 'success').
     * @return void
     */
    private function redirectWithMessage($message, $alertType)
    {
        header("Location: " . PROJECT_ROOT . "/login?alert=$alertType&message=" . urlencode($message));
        $this->exitApp();
    }

    /**
     * Genera un token para restablecer la contraseña y envía el correo con el enlace.
     *
     * @return void
     */
    public function generatePasswordResetToken()
    {
        $email = $_POST['resetEmail'] ?? null;

        if ($email) {
            $usuario = $this->loginModel->getByEmail($email);

            if ($usuario) {
                $token = bin2hex(random_bytes(32));
                if ($this->loginModel->saveResetToken($email, $token)) {
                    $notificationController = new NotificationController();

                    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                    $host = $_SERVER['HTTP_HOST'];
                    $resetLink = $scheme . '://' . $host . PROJECT_ROOT . '/login/reset-password?token=' . $token;

                    $subject = 'Restablecer Contraseña - Tervion';
                    $body = "
                        <h2>Hola, {$usuario['nombre']}</h2>
                        <p>Has solicitado restablecer tu contraseña para tu cuenta en Tervion.</p>
                        <p>Haz clic en el siguiente enlace para establecer una nueva contraseña (este enlace expira en 1 hora):</p>
                        <p><a href='{$resetLink}' style='background-color:#0f172a; color:#ffffff; padding:10px 20px; text-decoration:none; border-radius:8px; display:inline-block;'>Restablecer Contraseña</a></p>
                        <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                    ";

                    $notificationController->sendEmail($email, $subject, $body);
                }
            }
            // Por seguridad, mostramos el mismo mensaje siempre
            $this->redirectWithMessage("Si el correo electrónico está registrado, recibirás un enlace para restablecer tu contraseña.", 'success');
        } else {
            $this->redirectWithMessage("Correo electrónico no válido.", 'warning');
        }
    }

    /**
     * Muestra el formulario para restablecer la contraseña si el token es válido.
     *
     * @return void
     */
    public function showResetForm()
    {
        $token = $_GET['token'] ?? null;

        if ($token) {
            $resetRequest = $this->loginModel->getByToken($token);

            if ($resetRequest) {
                $this->view('login/resetPassword', ['token' => $token]);
                return;
            }
        }

        $this->redirectWithMessage("El enlace de restablecimiento de contraseña no es válido o ha expirado.", 'danger');
    }

    /**
     * Procesa el cambio de contraseña.
     *
     * @return void
     */
    public function updatePassword()
    {
        $token = $_POST['token'] ?? null;
        $pass = $_POST['pass'] ?? null;
        $confirmPassword = $_POST['confirmPassword'] ?? null;

        if (!$token || !$pass || !$confirmPassword) {
            $this->redirectWithMessage("Datos incompletos.", 'warning');
        }

        if ($pass !== $confirmPassword) {
            header("Location: " . PROJECT_ROOT . "/login/reset-password?token=" . $token . "&alert=danger&message=" . urlencode("Las contraseñas no coinciden."));
            $this->exitApp();
        }

        if (strlen($pass) < 8) {
            header("Location: " . PROJECT_ROOT . "/login/reset-password?token=" . $token . "&alert=danger&message=" . urlencode("La contraseña debe tener al menos 8 caracteres."));
            $this->exitApp();
        }

        $resetRequest = $this->loginModel->getByToken($token);

        if ($resetRequest) {
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
            if ($this->loginModel->updateUserPassword($resetRequest['email'], $hashedPassword)) {
                $this->loginModel->deleteResetToken($token);
                $this->redirectWithMessage("Contraseña restablecida correctamente. Ya puedes iniciar sesión.", 'success');
            } else {
                header("Location: " . PROJECT_ROOT . "/login/reset-password?token=" . $token . "&alert=danger&message=" . urlencode("Error al actualizar la contraseña."));
                $this->exitApp();
            }
        } else {
            $this->redirectWithMessage("El token no es válido o ha expirado.", 'danger');
        }
    }
}
