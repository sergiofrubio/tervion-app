<?php

namespace App\Controllers;

use App\Core\Controller;

class ProfileController extends Controller
{
    /**
     * Muestra el perfil del usuario loggeado con sus datos personales.
     *
     * @return void
     */
    public function index()
    {
        $userModel = $this->model('User');
        $usuario_id = $_SESSION['usuario_id'] ?? null;

        if (!$usuario_id) {
            header('Location: ' . PROJECT_ROOT . '/login');
            $this->exitApp();
        }

        $usuario = $userModel->getByusuario_id($usuario_id);

        if (!$usuario) {
            header('Location: ' . PROJECT_ROOT . '/logout');
            $this->exitApp();
        }

        $data = [
            'usuario' => $usuario,
            'pageTitle' => 'Mi Perfil'
        ];

        $this->view('profile/index', $data);
    }

    /**
     * Modifica/actualiza los datos del perfil del usuario loggeado.
     *
     * @return void
     */
    public function edit()
    {
        $userModel = $this->model('User');
        $usuario_id = $_SESSION['usuario_id'] ?? null;

        if (!$usuario_id) {
            header('Location: ' . PROJECT_ROOT . '/login');
            $this->exitApp();
        }

        $usuarioActual = $userModel->getByusuario_id($usuario_id);

        if (!$usuarioActual) {
            header('Location: ' . PROJECT_ROOT . '/logout');
            $this->exitApp();
        }

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
                'rol' => $usuarioActual['rol'] ?? ($_SESSION['rol'] ?? 'Paciente'),
                'genero' => $_POST['genero'] ?? 'Otro',
                'nss' => htmlspecialchars($_POST['nss'] ?? ($usuarioActual['nss'] ?? ''), ENT_QUOTES, 'UTF-8'),
                'iban' => htmlspecialchars($_POST['iban'] ?? ($usuarioActual['iban'] ?? ''), ENT_QUOTES, 'UTF-8'),
                'grupo_cotizacion' => $usuarioActual['grupo_cotizacion'] ?? 1
            ];

            if (!empty($_POST['pass'])) {
                $data['pass'] = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            }

            if ($userModel->update($usuario_id, $data)) {
                $_SESSION['nombre'] = $data['nombre'];
                header('Location: ' . PROJECT_ROOT . '/perfil?success=1');
                $this->exitApp();
            } else {
                $data['error'] = "Error al actualizar el perfil.";
                $data['usuario'] = $userModel->getByusuario_id($usuario_id);
                $data['pageTitle'] = 'Mi Perfil';
                $this->view('profile/index', $data);
            }
        } else {
            header('Location: ' . PROJECT_ROOT . '/perfil');
            $this->exitApp();
        }
    }
}
