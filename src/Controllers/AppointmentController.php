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
            $userModel = $this->model('User');
            $data = [
                'appointments' => $appointment->getAll(),
                'fisioterapeutas' => $userModel->getByRol('Fisioterapeuta')
            ];
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
            $terapeuta_id = $_POST['terapeuta_id'] ?? '';
            $tipo_cita_id = !empty($_POST['tipo_cita_id']) ? $_POST['tipo_cita_id'] : null;
            $fecha_hora = $_POST['fecha_hora'] ?? '';
            $estado = 'Programada';

            if (!empty($paciente_id) && !empty($terapeuta_id) && !empty($fecha_hora) && $appointment->save($paciente_id, $terapeuta_id, $fecha_hora, $estado, $tipo_cita_id)) {
                $redirect = ($_SESSION['rol'] === 'Paciente') ? '/patient-view/appointment' : '/citas';
                header('Location: ' . PROJECT_ROOT . $redirect . '?alert=success&message=Cita programada correctamente');
                $this->exitApp();
            } else {
                echo "Error al guardar la cita o datos inválidos.";
            }
        } else {
            $userModel = $this->model('User');
            $typeModel = $this->model('AppointmentType');
            $data = [
                'fisioterapeutas' => $userModel->getByRol('Fisioterapeuta'),
                'tiposCitas' => $typeModel->getAllActive()
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
     * Actualiza el estado de una cita rápidamente desde el listado.
     *
     * @return void
     */
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointment = $this->model('Appointment');
            $id = $_POST['cita_id'] ?? '';
            $estado = $_POST['estado'] ?? '';

            $estadosValidos = ['Programada', 'Confirmada', 'Pendiente', 'Realizada', 'Cancelada'];
            if (!empty($id) && in_array($estado, $estadosValidos, true)) {
                if ($appointment->updateStatus($id, $estado)) {
                    header('Location: ' . PROJECT_ROOT . '/citas?alert=success&message=Estado de la cita actualizado a ' . $estado);
                    $this->exitApp();
                }
            }

            header('Location: ' . PROJECT_ROOT . '/citas?alert=danger&message=Error al actualizar el estado de la cita');
            $this->exitApp();
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

            $terapeuta_id = $_POST['terapeuta_id'];
            $tipo_cita_id = !empty($_POST['tipo_cita_id']) ? $_POST['tipo_cita_id'] : null;
            $fecha_hora = $_POST['fecha_hora'];
            $estado = 'Programada';

            if ($appointment->update($id, $paciente_id, $terapeuta_id, $fecha_hora, $estado, $tipo_cita_id)) {
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
            $typeModel = $this->model('AppointmentType');
            $data = [
                'appointment' => $appointment->getById($id),
                'fisioterapeutas' => $userModel->getByRol('Fisioterapeuta'),
                'tiposCitas' => $typeModel->getAllActive()
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
        $tipo_cita_id = $_GET['tipo_cita_id'] ?? $_GET['servicio_id'] ?? '';

        if (empty($fisio_id) || empty($fecha)) {
            echo json_encode([]);
            $this->exitApp();
        }

        $duracion = 60; // Default
        if (!empty($tipo_cita_id)) {
            $typeModel = $this->model('AppointmentType');
            $tipoCita = $typeModel->getById($tipo_cita_id);
            if ($tipoCita) {
                $duracion = $tipoCita['duracion_minutos'];
            }
        }

        $appointment = $this->model('Appointment');
        $slots = $appointment->getAvailableSlots($fisio_id, $fecha, $duracion);
        header('Content-Type: application/json');
        echo json_encode(array_values($slots));
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
        $tipo_cita_id = $_GET['tipo_cita_id'] ?? $_GET['servicio_id'] ?? '';

        if (empty($fisio_id)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            $this->exitApp();
        }

        $duracion = 60; // Default
        if (!empty($tipo_cita_id)) {
            $typeModel = $this->model('AppointmentType');
            $tipoCita = $typeModel->getById($tipo_cita_id);
            if ($tipoCita) {
                $duracion = $tipoCita['duracion_minutos'];
            }
        }

        $appointment = $this->model('Appointment');
        $days = $appointment->getAvailableDays($fisio_id, $duracion);
        header('Content-Type: application/json');
        echo json_encode($days);
        $this->exitApp();
    }

    public function enviarRecordatorios()
    {
        $appointmentModel = $this->model('Appointment');
        $upcoming = $appointmentModel->getUpcomingAppointmentsWithoutReminder(1); // Mañana
        $notificationController = new NotificationController();

        $enviadosEmail = 0;

        foreach ($upcoming as $cita) {
            if (empty($cita['paciente_email'])) {
                continue;
            }

            $token = bin2hex(random_bytes(32));
            if ($appointmentModel->setConfirmationToken($cita['cita_id'], $token)) {
                $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $confirmLink = $scheme . '://' . $host . PROJECT_ROOT . '/citas/confirmar?token=' . $token;

                $fecha_formateada = date('d/m/Y H:i', strtotime($cita['fecha_hora']));
                $subject = "Recordatorio de cita - Tervion";
                $body = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff;'>
                        <h2 style='color: #0f172a;'>Recordatorio de tu cita</h2>
                        <p>Hola, <strong>" . htmlspecialchars($cita['paciente_nombre']) . " " . htmlspecialchars($cita['paciente_apellidos']) . "</strong>,</p>
                        <p>Te recordamos que tienes una cita programada en Tervion:</p>
                        <div style='background-color: #f8fafc; padding: 15px; border-radius: 6px; margin: 20px 0;'>
                            <p style='margin: 5px 0;'><strong>Fecha y Hora:</strong> " . $fecha_formateada . "</p>
                            <p style='margin: 5px 0;'><strong>Fisioterapeuta:</strong> " . htmlspecialchars($cita['fisioterapeuta_nombre']) . " " . htmlspecialchars($cita['fisioterapeuta_apellidos']) . "</p>
                        </div>
                        <p>Por favor, confirma tu asistencia haciendo clic en el siguiente botón:</p>
                        <p style='text-align: center; margin: 30px 0;'>
                            <a href='{$confirmLink}' style='background-color: #2563eb; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;'>Confirmar Asistencia</a>
                        </p>
                        <p style='font-size: 0.875rem; color: #64748b; line-height: 1.5;'>Si no puedes asistir, por favor ponte en contacto con la clínica lo antes posible.</p>
                    </div>
                ";

                if ($notificationController->sendEmail($cita['paciente_email'], $subject, $body)) {
                    $enviadosEmail++;
                }
            }
        }

        if (defined('STDIN') || (php_sapi_name() === 'cli')) {
            echo "Recordatorios enviados - Email: $enviadosEmail\n";
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'enviados_email' => $enviadosEmail
            ]);
            $this->exitApp();
        }
    }

    public function confirmarAsistencia()
    {
        $token = $_GET['token'] ?? null;
        if (!$token) {
            $this->view('appointment/confirmar', ['success' => false, 'message' => 'Token no proporcionado.']);
            return;
        }

        $appointmentModel = $this->model('Appointment');
        $cita = $appointmentModel->getByConfirmationToken($token);

        if (!$cita) {
            $this->view('appointment/confirmar', ['success' => false, 'message' => 'El enlace no es válido o ha expirado.']);
            return;
        }

        if ($cita['estado'] === 'Confirmada') {
            $this->view('appointment/confirmar', ['success' => true, 'already' => true, 'appointment' => $cita]);
            return;
        }

        if ($appointmentModel->confirmAppointment($cita['cita_id'])) {
            $cita['estado'] = 'Confirmada';
            $this->view('appointment/confirmar', ['success' => true, 'already' => false, 'appointment' => $cita]);
        } else {
            $this->view('appointment/confirmar', ['success' => false, 'message' => 'Hubo un error al confirmar tu asistencia. Por favor, inténtalo de nuevo.']);
        }
    }
}
