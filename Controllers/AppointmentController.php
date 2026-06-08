<?php
namespace App\Controllers;
use App\Core\Controller;

class AppointmentController extends Controller
{

    public function list()
    {
        $appointment = $this->model('Appointment');
        
        if ($_SESSION['rol'] === 'Paciente') {
            $data = ['appointments' => $appointment->getByPatient($_SESSION['usuario_id'])];
            $this->view('vista-pacientes/citas/list', $data);
        } else {
            $data = ['appointments' => $appointment->getAll()];
            $this->view('citas/list', $data);
        }
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointment = $this->model('Appointment');

            $paciente_id = ($_SESSION['rol'] === 'Paciente') ? $_SESSION['usuario_id'] : ($_POST['paciente_id'] ?? '');
            $fisioterapeuta_id = $_POST['fisioterapeuta_id'] ?? '';
            $fecha_hora = $_POST['fecha_hora'] ?? '';
            $estado = $_POST['estado'] ?? 'Programada';
            $especialidad_id = $_POST['especialidad_id'] ?? '';

            if (!empty($paciente_id) && !empty($fisioterapeuta_id) && !empty($fecha_hora) && $appointment->save($paciente_id, $fisioterapeuta_id, $fecha_hora, $estado, $especialidad_id)) {
                $redirect = ($_SESSION['rol'] === 'Paciente') ? '/vista-pacientes/citas' : '/citas';
                header('Location: ' . PROJECT_ROOT . $redirect . '?alert=success&message=Cita programada correctamente');
                exit();
            } else {
                echo "Error al guardar la cita o datos inválidos.";
            }
        } else {
            $userModel = $this->model('User');
            $data = [
                'especialidades' => $userModel->getSpecialties()
            ];
            
            if ($_SESSION['rol'] === 'Paciente') {
                $this->view('vista-pacientes/citas/create', $data);
            } else {
                $this->view('citas/form', $data);
            }
        }
    }

    public function delete()
    {
        $appointment = $this->model('Appointment');
        $id = $_POST['id'];

        // Si es paciente, verificar que la cita le pertenece
        if ($_SESSION['rol'] === 'Paciente') {
            $cita = $appointment->getById($id);
            if (!$cita || $cita['paciente_id'] !== $_SESSION['usuario_id']) {
                header('Location: ' . PROJECT_ROOT . '/vista-pacientes/citas?alert=danger&message=No tienes permiso para eliminar esta cita');
                exit();
            }
        }

        if ($appointment->delete($id)) {
            $redirect = ($_SESSION['rol'] === 'Paciente') ? '/vista-pacientes/citas' : '/citas';
            header('Location: ' . PROJECT_ROOT . $redirect . '?alert=success&message=Cita eliminada correctamente');
            exit();
        } else {
            echo "Error while deleting appointment.";
        }
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointment = $this->model('Appointment');
            $id = $_POST['cita_id'];
            
            // Verificación de propiedad para pacientes
            if ($_SESSION['rol'] === 'Paciente') {
                $citaExistente = $appointment->getById($id);
                if (!$citaExistente || $citaExistente['paciente_id'] !== $_SESSION['usuario_id']) {
                    header('Location: ' . PROJECT_ROOT . '/vista-pacientes/citas?alert=danger&message=No tienes permiso para editar esta cita');
                    exit();
                }
                $paciente_id = $_SESSION['usuario_id'];
            } else {
                $paciente_id = $_POST['paciente_id'];
            }

            $fisioterapeuta_id = $_POST['fisioterapeuta_id'];
            $fecha_hora = $_POST['fecha_hora'];
            $estado = $_POST['estado'];
            $especialidad_id = $_POST['especialidad_id'];

            if ($appointment->update($id, $paciente_id, $fisioterapeuta_id, $fecha_hora, $estado, $especialidad_id)) {
                $redirect = ($_SESSION['rol'] === 'Paciente') ? '/vista-pacientes/citas' : '/citas';
                header('Location: ' . PROJECT_ROOT . $redirect . '?alert=success&message=Cita actualizada correctamente');
                exit();
            } else {
                echo "Error while updating appointment.";
            }
        } else {
            $id = $_GET['id'];
            $appointment = $this->model('Appointment');
            $userModel = $this->model('User');
            $data = [
                'appointment' => $appointment->getById($id),
                'especialidades' => $userModel->getSpecialties()
            ];
            
            if ($_SESSION['rol'] === 'Paciente') {
                // Verificar propiedad
                if (!$data['appointment'] || $data['appointment']['paciente_id'] !== $_SESSION['usuario_id']) {
                    header('Location: ' . PROJECT_ROOT . '/vista-pacientes/citas?alert=danger&message=No tienes permiso para ver esta cita');
                    exit();
                }
                $this->view('vista-pacientes/citas/edit', $data);
            } else {
                $this->view('citas/form', $data);
            }
        }
    }
    public function getSlots()
    {
        $fisio_id = $_GET['fisio_id'] ?? '';
        $fecha = $_GET['fecha'] ?? '';
        $servicio_id = $_GET['servicio_id'] ?? '';

        if (empty($fisio_id) || empty($fecha)) {
            echo json_encode([]);
            exit();
        }

        $duracion = 60; // Default
        if (!empty($servicio_id)) {
            $configModel = $this->model('Configuracion');
            $servicio = $configModel->getServicioById($servicio_id);
            if ($servicio) {
                $duracion = $servicio['duracion_minutos'];
            }
        }

        $appointment = $this->model('Appointment');
        $slots = $appointment->getAvailableSlots($fisio_id, $fecha, $duracion);
        header('Content-Type: application/json');
        echo json_encode(array_values($slots)); // array_values para reindexar tras array_unique
        exit();
    }
}
