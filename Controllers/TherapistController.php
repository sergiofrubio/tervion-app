<?php

namespace App\Controllers;

use App\Core\Controller;

class TherapistController extends Controller
{
    /**
     * Muestra la lista de terapeutas y personal de la clínica.
     */
    public function list()
    {
        $userModel = $this->model('User');
        $workers = $userModel->getWorkers();

        $data = [
            'workers' => $workers
        ];

        $this->view('therapist/list', $data);
    }

    /**
     * Muestra el formulario para crear un nuevo terapeuta o personal.
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');

            $data = [
                'usuario_id' => htmlspecialchars($_POST['usuario_id'] ?? '', ENT_QUOTES, 'UTF-8'),
                'nombre' => htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8'),
                'apellidos' => htmlspecialchars($_POST['apellidos'] ?? '', ENT_QUOTES, 'UTF-8'),
                'telefono' => htmlspecialchars($_POST['telefono'] ?? '', ENT_QUOTES, 'UTF-8'),
                'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
                'direccion' => htmlspecialchars($_POST['direccion'] ?? '', ENT_QUOTES, 'UTF-8'),
                'provincia' => htmlspecialchars($_POST['provincia'] ?? '', ENT_QUOTES, 'UTF-8'),
                'municipio' => htmlspecialchars($_POST['municipio'] ?? '', ENT_QUOTES, 'UTF-8'),
                'cp' => htmlspecialchars($_POST['cp'] ?? '', ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'),
                'pass' => password_hash(!empty($_POST['pass']) ? $_POST['pass'] : $_POST['usuario_id'], PASSWORD_DEFAULT),
                'rol' => $_POST['rol'] ?? 'Fisioterapeuta',
                'genero' => $_POST['genero'] ?? 'Otro',
                'nss' => htmlspecialchars($_POST['nss'] ?? '', ENT_QUOTES, 'UTF-8'),
                'iban' => htmlspecialchars($_POST['iban'] ?? '', ENT_QUOTES, 'UTF-8'),
                'grupo_cotizacion' => (int)($_POST['grupo_cotizacion'] ?? 1),
                'rgpd_aceptado' => 1,
                'fecha_consentimiento' => date('Y-m-d H:i:s')
            ];

            if (!empty($data['usuario_id']) && !empty($data['nombre']) && $userModel->save($data)) {
                header('Location: ' . PROJECT_ROOT . '/terapeutas?alert=success&message=Facultativo registrado correctamente');
                $this->exitApp();
            } else {
                header('Location: ' . PROJECT_ROOT . '/terapeutas/crear?alert=danger&message=Error al guardar el facultativo o DNI ya existente');
                $this->exitApp();
            }
        } else {
            $this->view('therapist/form');
        }
    }

    /**
     * Muestra la vista detallada de un terapeuta (perfil, nóminas, citas, horaios).
     */
    public function detail()
    {
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            header('Location: ' . PROJECT_ROOT . '/terapeutas');
            $this->exitApp();
        }

        $userModel = $this->model('User');
        $therapist = $userModel->getByusuario_id($id);

        if (!$therapist || $therapist['rol'] === 'Paciente') {
            header('Location: ' . PROJECT_ROOT . '/terapeutas?alert=danger&message=Facultativo no encontrado');
            $this->exitApp();
        }

        $payrollModel = $this->model('Payroll');
        $contractModel = $this->model('Contract');
        $appointmentModel = $this->model('Appointment');
        $settingModel = $this->model('Setting');

        $nominas = $payrollModel->getPayrollsByWorker($id);
        $contrato = $contractModel->getContractByWorker($id);
        $citas = $appointmentModel->getAll();
        $horarios = $settingModel->getHorariosByFisio($id);
        $ausencias = $settingModel->getAusenciasByFisio($id);
        
        // Filtrar citas del fisioterapeuta
        $citasFisio = array_filter($citas, function($c) use ($id) {
            return ($c['fisioterapeuta_id'] ?? '') === $id;
        });

        $data = [
            'therapist' => $therapist,
            'nominas' => $nominas,
            'contrato' => $contrato,
            'citas' => array_values($citasFisio),
            'horarios' => $horarios,
            'ausencias' => $ausencias
        ];

        $this->view('therapist/detail', $data);
    }

    /**
     * Edita los datos de un terapeuta o personal.
     */
    public function edit()
    {
        $userModel = $this->model('User');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['usuario_id'];

            $data = [
                'nombre' => htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8'),
                'apellidos' => htmlspecialchars($_POST['apellidos'] ?? '', ENT_QUOTES, 'UTF-8'),
                'telefono' => htmlspecialchars($_POST['telefono'] ?? '', ENT_QUOTES, 'UTF-8'),
                'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
                'direccion' => htmlspecialchars($_POST['direccion'] ?? '', ENT_QUOTES, 'UTF-8'),
                'provincia' => htmlspecialchars($_POST['provincia'] ?? '', ENT_QUOTES, 'UTF-8'),
                'municipio' => htmlspecialchars($_POST['municipio'] ?? '', ENT_QUOTES, 'UTF-8'),
                'cp' => htmlspecialchars($_POST['cp'] ?? '', ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'),
                'rol' => $_POST['rol'] ?? 'Fisioterapeuta',
                'genero' => $_POST['genero'] ?? 'Otro',
                'nss' => htmlspecialchars($_POST['nss'] ?? '', ENT_QUOTES, 'UTF-8'),
                'iban' => htmlspecialchars($_POST['iban'] ?? '', ENT_QUOTES, 'UTF-8'),
                'grupo_cotizacion' => (int)($_POST['grupo_cotizacion'] ?? 1)
            ];

            if (!empty($_POST['pass'])) {
                $data['pass'] = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            }

            if ($userModel->update($id, $data)) {
                header('Location: ' . PROJECT_ROOT . '/terapeutas?alert=success&message=Facultativo actualizado correctamente');
                $this->exitApp();
            } else {
                header('Location: ' . PROJECT_ROOT . '/terapeutas/editar?id=' . $id . '&alert=danger&message=Error al actualizar el facultativo');
                $this->exitApp();
            }
        } else {
            $id = $_GET['id'] ?? '';
            $therapist = $userModel->getByusuario_id($id);

            if (!$therapist || $therapist['rol'] === 'Paciente') {
                header('Location: ' . PROJECT_ROOT . '/terapeutas');
                $this->exitApp();
            }

            $this->view('therapist/form', ['therapist' => $therapist]);
        }
    }

    /**
     * Elimina un terapeuta o empleado.
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            $id = $_POST['id'] ?? '';

            if (!empty($id) && $userModel->delete($id)) {
                header('Location: ' . PROJECT_ROOT . '/terapeutas?alert=success&message=Facultativo eliminado correctamente');
                $this->exitApp();
            } else {
                header('Location: ' . PROJECT_ROOT . '/terapeutas?alert=danger&message=Error al eliminar el facultativo');
                $this->exitApp();
            }
        }
    }
}
