<?php
namespace App\Controllers;
use App\Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LoginController extends Controller {

    private $loginModel;

    /**
     * Constructor de LoginController.
     *
     * Inicializa el modelo de inicio de sesión.
     */
    public function __construct() {
        $this->loginModel = $this->model('Login');
    }

    /**
     * Muestra la vista de inicio de sesión si no hay una sesión activa.
     *
     * Redirige al inicio si el usuario ya ha iniciado sesión.
     *
     * @return void
     */
    public function index(){
        //Si borras este if, cuando haces localhost/login después de haberse loggeado te redirige a la vista
        // de login, en lugar de a inicio.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['email'])) {
            header('Location: ' . PROJECT_ROOT . '/inicio');
            exit();
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
    public function iniciarSesion() {
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
    private function startSession($usuario) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array_merge($_SESSION, $usuario);
        
        header('Location: ' . PROJECT_ROOT . '/inicio');
        exit();
    }

    /**
     * Finaliza la sesión actual del usuario, destruye las cookies de sesión y redirige al login.
     *
     * @return void
     */
    public function finishSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
        exit();
    }

    /**
     * Redirige al inicio de sesión con un mensaje y tipo de alerta específicos.
     *
     * @param string $message Mensaje a mostrar.
     * @param string $alertType Tipo de alerta (por ejemplo, 'danger', 'warning', 'success').
     * @return void
     */
    private function redirectWithMessage($message, $alertType) {
        header("Location: " . PROJECT_ROOT . "/login?alert=$alertType&message=$message");
        exit();
    }
}

?>
