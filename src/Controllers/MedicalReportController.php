<?php

namespace App\Controllers;

use App\Core\Controller;
use Fpdf\Fpdf;

class MedicalReportController extends Controller
{
    /**
     * Crea un nuevo informe médico para un paciente.
     *
     * Si la petición es POST, guarda los datos del informe en la base de datos y redirige al detalle del usuario.
     * Si es GET, muestra la vista del formulario de creación con los datos del paciente.
     *
     * @return void
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $historyModel = $this->model('MedicalReport');

            $data = [
                'paciente_id' => $_POST['paciente_id'],
                'terapeuta_id' => $_SESSION['usuario_id'], // El fisio logueado
                'fecha_consulta' => date('Y-m-d H:i:s'),
                'motivo_consulta' => htmlspecialchars($_POST['motivo_consulta'], ENT_QUOTES, 'UTF-8'),
                'diagnostico' => htmlspecialchars($_POST['diagnostico'], ENT_QUOTES, 'UTF-8'),
                'tratamiento' => htmlspecialchars($_POST['tratamiento'], ENT_QUOTES, 'UTF-8'),
                'observaciones' => htmlspecialchars($_POST['observaciones'], ENT_QUOTES, 'UTF-8'),
                'creado_por' => $_SESSION['usuario_id']
            ];

            if ($historyModel->save($data)) {
                header('Location: ' . PROJECT_ROOT . '/users/detail?id=' . $data['paciente_id']);
                $this->exitApp();
            } else {
                echo "Error al guardar el historial médico.";
            }
        } else {
            $paciente_id = $_GET['paciente_id'] ?? null;
            $userModel = $this->model('User');
            $paciente = $userModel->getByusuario_id($paciente_id);

            $this->view('medical-report/create', ['paciente' => $paciente]);
        }
    }

    /**
     * Muestra la vista de detalle de un informe médico específico en formato Document Studio.
     *
     * @return void
     */
    public function detail()
    {
        $id = $_GET['id'] ?? ($_GET['historial_id'] ?? null);
        $historyModel = $this->model('MedicalReport');
        $report = $historyModel->getById($id);

        if (!$report) {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        }

        $userModel = $this->model('User');
        $paciente = $userModel->getByusuario_id($report['paciente_id']);

        $this->view('medical-report/detail', [
            'report' => $report,
            'paciente' => $paciente
        ]);
    }

    /**
     * Muestra el listado de informes médicos de un paciente.
     *
     * @return void
     */
    public function list()
    {
        $paciente_id = $_GET['paciente_id'] ?? ($_GET['id'] ?? null);
        $userModel = $this->model('User');
        $historyModel = $this->model('MedicalReport');
        $appointmentModel = $this->model('Appointment');

        $paciente = null;
        $informes = [];
        $citas = [];
        $totalPacientes = 0;

        if ($paciente_id) {
            $paciente = $userModel->getByusuario_id($paciente_id);
            if ($paciente && ($paciente['rol'] ?? '') === 'Paciente') {
                $informes = $historyModel->getByPaciente($paciente_id);
                $citas = $appointmentModel->getByPatient($paciente_id);
            } else {
                $paciente = null;
            }
        }

        $this->view('medical-report/list', [
            'paciente' => $paciente,
            'informes' => $informes,
            'citas' => $citas,
            'paciente_id' => $paciente_id
        ]);
    }

    /**
     * Genera y descarga un documento PDF con los detalles del informe médico utilizando FPDF.
     *
     * @return void
     */
    public function pdf()
    {
        $id = $_GET['id'] ?? null;
        $historyModel = $this->model('MedicalReport');
        $report = $historyModel->getById($id);

        if (!$report) {
            echo "Informe no encontrado.";
            return;
        }

        // Crear una instancia de FPDF
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Título del informe
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Informe Médico'), 0, 1, 'C');
        $pdf->Ln(10);

        // Datos del paciente
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Información del Paciente'), 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', 'Nombre: ' . $report['paciente_nombre'] . ' ' . $report['paciente_apellidos']), 0, 0);
        $pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', 'ID: ' . $report['paciente_id']), 0, 1);
        $pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', 'F. Nacimiento: ' . $report['paciente_fecha_nacimiento']), 0, 0);
        $pdf->Cell(95, 8, iconv('UTF-8', 'windows-1252', 'Género: ' . $report['paciente_genero']), 0, 1);
        $pdf->Ln(5);

        // Datos de la consulta
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Detalles de la Consulta'), 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, iconv('UTF-8', 'windows-1252', 'Fecha: ' . date('d/m/Y H:i', strtotime($report['fecha_consulta']))), 0, 1);
        $pdf->Cell(0, 8, iconv('UTF-8', 'windows-1252', 'Fisioterapeuta: ' . $report['fisioterapeuta_nombre'] . ' ' . $report['fisioterapeuta_apellidos']), 0, 1);
        $pdf->Ln(5);

        // Contenido Clínico
        $sections = [
            'Motivo de Consulta' => $report['motivo_consulta'],
            'Diagnóstico' => $report['diagnostico'],
            'Tratamiento' => $report['tratamiento'],
            'Observaciones' => $report['observaciones']
        ];

        foreach ($sections as $title => $content) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', $title), 0, 1, 'L', true);
            $pdf->SetFont('Arial', '', 11);
            $pdf->MultiCell(0, 8, iconv('UTF-8', 'windows-1252', $content));
            $pdf->Ln(3);
        }

        // Salida del PDF
        $pdf->Output('I', 'Informe_' . $report['paciente_id'] . '_' . date('dmY') . '.pdf');
    }
}
