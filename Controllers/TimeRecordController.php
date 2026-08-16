<?php
namespace App\Controllers;

use App\Core\Controller;

class TimeRecordController extends Controller
{
    /**
     * Vista de registro horario para el empleado
     */
    public function index()
    {
        $timeRecordModel = $this->model('TimeRecord');
        $usuario_id = $_SESSION['usuario_id'];
        $fechaHoy = date('Y-m-d');

        // Registro activo de hoy (para determinar si está trabajando)
        $activeRecord = $timeRecordModel->getActiveRecord($usuario_id, $fechaHoy);
        
        // Historial de fichajes propio del mes
        $history = $timeRecordModel->getHistoryByUsuario($usuario_id);

        $data = [
            'activeRecord' => $activeRecord,
            'history' => $history
        ];

        $this->view('time-record/index', $data);
    }

    /**
     * Procesa la entrada/salida (fichar)
     */
    public function fichar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $timeRecordModel = $this->model('TimeRecord');
            $usuario_id = $_SESSION['usuario_id'];
            $fechaHoy = date('Y-m-d');

            $activeRecord = $timeRecordModel->getActiveRecord($usuario_id, $fechaHoy);
            $notas = isset($_POST['notas']) ? htmlspecialchars($_POST['notas'], ENT_QUOTES, 'UTF-8') : null;

            if ($activeRecord) {
                // Registrar Salida
                if ($timeRecordModel->clockOut($activeRecord['registro_id'])) {
                    $_SESSION['success_message'] = "¡Salida registrada con éxito!";
                } else {
                    $_SESSION['error_message'] = "Error al registrar la salida.";
                }
            } else {
                // Registrar Entrada
                if ($timeRecordModel->clockIn($usuario_id, null, $notas)) {
                    $_SESSION['success_message'] = "¡Entrada registrada con éxito! Buen día de trabajo.";
                } else {
                    $_SESSION['error_message'] = "Error al registrar la entrada.";
                }
            }
        }
        header('Location: ' . PROJECT_ROOT . '/control-horario');
        $this->exitApp();
    }

    /**
     * Vista de administración de registros de todos los empleados
     */
    public function adminIndex()
    {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: ' . PROJECT_ROOT . '/inicio');
            $this->exitApp();
        }

        $timeRecordModel = $this->model('TimeRecord');
        $userModel = $this->model('User');

        $workers = $userModel->getWorkers();

        // Filtros
        $filters = [
            'usuario_id' => $_GET['usuario_id'] ?? '',
            'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
            'fecha_fin' => $_GET['fecha_fin'] ?? ''
        ];

        $records = $timeRecordModel->getAllRecords($filters);

        $data = [
            'workers' => $workers,
            'records' => $records,
            'filters' => $filters
        ];

        $this->view('time-record/admin', $data);
    }

    /**
     * Crea o edita un registro manualmente por administración
     */
    public function guardar()
    {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: ' . PROJECT_ROOT . '/inicio');
            $this->exitApp();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $timeRecordModel = $this->model('TimeRecord');

            $registro_id = $_POST['registro_id'] ?? null;
            $usuario_id = $_POST['usuario_id'];
            $fecha = $_POST['fecha'];
            $hora_entrada = $_POST['hora_entrada'];
            $hora_salida = $_POST['hora_salida'] ?? '';
            $notas = htmlspecialchars($_POST['notas'] ?? '', ENT_QUOTES, 'UTF-8');

            $entradaDatetime = $fecha . ' ' . $hora_entrada;
            $salidaDatetime = !empty($hora_salida) ? ($fecha . ' ' . $hora_salida) : null;

            // Validación básica
            if ($salidaDatetime && strtotime($salidaDatetime) < strtotime($entradaDatetime)) {
                $_SESSION['error_message'] = "La hora de salida no puede ser anterior a la hora de entrada.";
                header('Location: ' . PROJECT_ROOT . '/control-horario/admin');
                $this->exitApp();
            }

            $data = [
                'registro_id' => $registro_id,
                'usuario_id' => $usuario_id,
                'fecha' => $fecha,
                'entrada' => $entradaDatetime,
                'salida' => $salidaDatetime,
                'notas' => $notas,
                'admin_usuario_id' => $_SESSION['usuario_id']
            ];

            if ($timeRecordModel->saveRecord($data)) {
                $_SESSION['success_message'] = "Registro de control horario guardado correctamente.";
            } else {
                $_SESSION['error_message'] = "Error al guardar el registro.";
            }
        }

        header('Location: ' . PROJECT_ROOT . '/control-horario/admin');
        $this->exitApp();
    }
}
