<?php
namespace App\Controllers;
use App\Core\Controller;

class AppointmentController extends Controller
{

    /**
     * Muestra la lista de citas para el usuario.
     *
     * Si el rol del usuario es Paciente, muestra solo sus citas.
     * Si no, muestra todas las citas de la clínica.
     *
     * @return void
     */
    public function list()
    {
        $appointment = $this->model('Appointment');
        
        if ($_SESSION['rol'] === 'Paciente') {
            $data = ['appointments' => $appointment->getByPatient($_SESSION['usuario_id'])];
            $this->view('patient-view/appointment/list', $data);
        } else {
            $data = ['appointments' => $appointment->getAll()];
            $this->view('appointment/list', $data);
        }
    }

    /**
     * Crea una nueva cita.
     *
     * Si la petición es POST, guarda los datos de la cita y redirige a la lista de citas.
     * Si es GET, muestra el formulario de creación de citas.
     *
     * @return void
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointment = $this->model('Appointment');

            $paciente_id = ($_SESSION['rol'] === 'Paciente') ? $_SESSION['usuario_id'] : ($_POST['paciente_id'] ?? '');
            $fisioterapeuta_id = $_POST['fisioterapeuta_id'] ?? '';
            $fecha_hora = $_POST['fecha_hora'] ?? '';
            $estado = 'Programada';

            if (!empty($paciente_id) && !empty($fisioterapeuta_id) && !empty($fecha_hora) && $appointment->save($paciente_id, $fisioterapeuta_id, $fecha_hora, $estado)) {
                $redirect = ($_SESSION['rol'] === 'Paciente') ? '/patient-view/appointment' : '/citas';
                header('Location: ' . PROJECT_ROOT . $redirect . '?alert=success&message=Cita programada correctamente');
                $this->exitApp();
            } else {
                echo "Error al guardar la cita o datos inválidos.";
            }
        } else {
            $userModel = $this->model('User');
            $data = [
                'fisioterapeutas' => $userModel->getByRol('Fisioterapeuta')
            ];
            
            if ($_SESSION['rol'] === 'Paciente') {
                $this->view('patient-view/appointment/create', $data);
            } else {
                $this->view('appointment/form', $data);
            }
        }
    }

    /**
     * Elimina una cita específica.
     *
     * Si el rol del usuario es Paciente, verifica primero que la cita le pertenezca.
     * Redirige a la lista de citas tras completarse.
     *
     * @return void
     */
    public function delete()
    {
        $appointment = $this->model('Appointment');
        $id = $_POST['id'];

        // Si es paciente, verificar que la cita le pertenece
        if ($_SESSION['rol'] === 'Paciente') {
            $cita = $appointment->getById($id);
            if (!$cita || $cita['paciente_id'] !== $_SESSION['usuario_id']) {
                header('Location: ' . PROJECT_ROOT . '/patient-view/appointment?alert=danger&message=No tienes permiso para eliminar esta cita');
                $this->exitApp();
            }
        }

        if ($appointment->delete($id)) {
            $redirect = ($_SESSION['rol'] === 'Paciente') ? '/patient-view/appointment' : '/citas';
            header('Location: ' . PROJECT_ROOT . $redirect . '?alert=success&message=Cita eliminada correctamente');
            $this->exitApp();
        } else {
            echo "Error while deleting appointment.";
        }
    }

    /**
     * Edita una cita existente.
     *
     * Si la petición es POST, actualiza los datos de la cita tras validar los permisos (para pacientes).
     * Si es GET, muestra el formulario de edición con los detalles actuales de la cita.
     *
     * @return void
     */
    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointment = $this->model('Appointment');
            $id = $_POST['cita_id'];
            
            // Verificación de propiedad para pacientes
            if ($_SESSION['rol'] === 'Paciente') {
                $citaExistente = $appointment->getById($id);
                if (!$citaExistente || $citaExistente['paciente_id'] !== $_SESSION['usuario_id']) {
                    header('Location: ' . PROJECT_ROOT . '/patient-view/appointment?alert=danger&message=No tienes permiso para editar esta cita');
                    $this->exitApp();
                }
                $paciente_id = $_SESSION['usuario_id'];
            } else {
                $paciente_id = $_POST['paciente_id'];
            }

            $fisioterapeuta_id = $_POST['fisioterapeuta_id'];
            $fecha_hora = $_POST['fecha_hora'];
            $estado = 'Programada';

            if ($appointment->update($id, $paciente_id, $fisioterapeuta_id, $fecha_hora, $estado)) {
                $redirect = ($_SESSION['rol'] === 'Paciente') ? '/patient-view/appointment' : '/citas';
                header('Location: ' . PROJECT_ROOT . $redirect . '?alert=success&message=Cita actualizada correctamente');
                $this->exitApp();
            } else {
                echo "Error while updating appointment.";
            }
        } else {
            $id = $_GET['id'];
            $appointment = $this->model('Appointment');
            $userModel = $this->model('User');
            $data = [
                'appointment' => $appointment->getById($id),
                'fisioterapeutas' => $userModel->getByRol('Fisioterapeuta')
            ];
            
            if ($_SESSION['rol'] === 'Paciente') {
                // Verificar propiedad
                if (!$data['appointment'] || $data['appointment']['paciente_id'] !== $_SESSION['usuario_id']) {
                    header('Location: ' . PROJECT_ROOT . '/patient-view/appointment?alert=danger&message=No tienes permiso para ver esta cita');
                    $this->exitApp();
                }
                $this->view('patient-view/appointment/edit', $data);
            } else {
                $this->view('appointment/form', $data);
            }
        }
    }
    /**
     * Obtiene los horarios (slots) disponibles para un fisioterapeuta en una fecha específica.
     *
     * Retorna la información formateada en JSON.
     *
     * @return void
     */
    public function getSlots()
    {
        $fisio_id = $_GET['fisio_id'] ?? '';
        $fecha = $_GET['fecha'] ?? '';
        $servicio_id = $_GET['servicio_id'] ?? '';

        if (empty($fisio_id) || empty($fecha)) {
            echo json_encode([]);
            $this->exitApp();
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
        $this->exitApp();
    }

    /**
     * Obtiene los días disponibles para un fisioterapeuta.
     *
     * Retorna la información formateada en JSON.
     *
     * @return void
     */
    public function getAvailableDays()
    {
        $fisio_id = $_GET['fisio_id'] ?? '';
        $servicio_id = $_GET['servicio_id'] ?? '';

        if (empty($fisio_id)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            $this->exitApp();
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
        $days = $appointment->getAvailableDays($fisio_id, $duracion);
        header('Content-Type: application/json');
        echo json_encode($days);
        $this->exitApp();
    }
}
