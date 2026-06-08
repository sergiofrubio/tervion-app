<?php
namespace App\Controllers;
use App\Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LoginController extends Controller {

    private $loginModel;

    public function __construct() {
        $this->loginModel = $this->model('Login');
    }

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

    private function startSession($usuario) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array_merge($_SESSION, $usuario);
        
        header('Location: ' . PROJECT_ROOT . '/inicio');
        exit();
    }

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

    private function redirectWithMessage($message, $alertType) {
        header("Location: " . PROJECT_ROOT . "/login?alert=$alertType&message=$message");
        exit();
    }
}

?>
