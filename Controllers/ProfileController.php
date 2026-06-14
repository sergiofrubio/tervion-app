<?php
namespace App\Controllers;
use App\Core\Controller;

class ProfileController extends Controller
{
    /**
     * Muestra el perfil del paciente con sus datos personales y métodos de pago guardados.
     *
     * @return void
     */
    public function index()
    {
        $userModel = $this->model('User');
        
        $usuario = $userModel->getByusuario_id($_SESSION['usuario_id']);
        
        if (!$usuario) {
            header('Location: ' . PROJECT_ROOT . '/logout');
            $this->exitApp();
        }

        $data = [
            'usuario' => $usuario,
            'pageTitle' => 'Mi Perfil - Velion'
        ];

        $this->view('patient-view/profile/index', $data);
    }

    /**
     * Modifica/actualiza los datos del perfil del paciente.
     *
     * Si es POST, procesa los datos del formulario, los valida y los actualiza en la base de datos.
     * Si falla o es GET, redirige a la vista del perfil con los errores oportunos.
     *
     * @return void
     */
    public function edit()
    {
        $userModel = $this->model('User');
        $usuario_id = $_SESSION['usuario_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8'),
                'apellidos' => htmlspecialchars($_POST['apellidos'] ?? '', ENT_QUOTES, 'UTF-8'),
                'telefono' => htmlspecialchars($_POST['telefono'] ?? '', ENT_QUOTES, 'UTF-8'),
                'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? '',
                'direccion' => htmlspecialchars($_POST['direccion'] ?? '', ENT_QUOTES, 'UTF-8'),
                'provincia' => htmlspecialchars($_POST['provincia'] ?? '', ENT_QUOTES, 'UTF-8'),
                'municipio' => htmlspecialchars($_POST['municipio'] ?? '', ENT_QUOTES, 'UTF-8'),
                'cp' => htmlspecialchars($_POST['cp'] ?? '', ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'),
                'rol' => 'Paciente', // Keep the same role
                'genero' => $_POST['genero'] ?? 'Otro'
            ];

            if (!empty($_POST['pass'])) {
                $data['pass'] = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            }

            if ($userModel->update($usuario_id, $data)) {
                // Update session info if needed
                $_SESSION['nombre'] = $data['nombre'];
                header('Location: ' . PROJECT_ROOT . '/paciente/perfil?success=1');
                $this->exitApp();
            } else {
                $data['error'] = "Error al actualizar el perfil.";
                $data['usuario'] = $userModel->getByusuario_id($usuario_id);
                $this->view('patient-view/profile/index', $data);
            }
        } else {
            header('Location: ' . PROJECT_ROOT . '/paciente/perfil');
        }
    }
}
