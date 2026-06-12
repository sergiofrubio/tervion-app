<?php
namespace App\Controllers;
use App\Core\Controller;
use Fpdf\Fpdf;

class PatientController extends Controller
{
    /**
     * Muestra la lista de pacientes de la aplicación.
     */
    public function list()
    {
        $userModel = $this->model('User');
        $data = ['patients' => $userModel->getByRol('Paciente')];
        $this->view('patient/list', $data);
    }

    /**
     * Crea un nuevo paciente.
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
                'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? '',
                'direccion' => htmlspecialchars($_POST['direccion'] ?? '', ENT_QUOTES, 'UTF-8'),
                'provincia' => htmlspecialchars($_POST['provincia'] ?? '', ENT_QUOTES, 'UTF-8'),
                'municipio' => htmlspecialchars($_POST['municipio'] ?? '', ENT_QUOTES, 'UTF-8'),
                'cp' => htmlspecialchars($_POST['cp'] ?? '', ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'),
                'pass' => password_hash($_POST['pass'] ?? '123456', PASSWORD_DEFAULT),
                'rol' => 'Paciente',
                'genero' => $_POST['genero'] ?? 'Otro'
            ];

            if (!empty($data['usuario_id']) && !empty($data['nombre']) && $userModel->save($data)) {
                header('Location: ' . PROJECT_ROOT . '/pacientes');
                $this->exitApp();
            } else {
                echo "Error al guardar el paciente o datos inválidos.";
            }
        } else {
            $this->view('patient/form');
        }
    }

    /**
     * Elimina un paciente por su identificador.
     */
    public function delete()
    {
        $userModel = $this->model('User');
        $id = $_POST['id'];

        if ($userModel->delete($id)) {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        } else {
            echo "Error al eliminar el paciente.";
        }
    }

    /**
     * Edita los detalles de un paciente existente.
     */
    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            $id = $_POST['usuario_id'];
            
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
                'rol' => 'Paciente',
                'genero' => $_POST['genero'] ?? 'Otro'
            ];

            if (!empty($_POST['pass'])) {
                $data['pass'] = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            }

            if ($userModel->update($id, $data)) {
                header('Location: ' . PROJECT_ROOT . '/pacientes');
                $this->exitApp();
            } else {
                echo "Error al actualizar el paciente.";
            }
        } else {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header('Location: ' . PROJECT_ROOT . '/pacientes');
                $this->exitApp();
            }
            $userModel = $this->model('User');
            $data = [
                'usuario' => $userModel->getByusuario_id($id)
            ];
            $this->view('patient/form', $data);
        }
    }

    /**
     * Muestra la vista detallada de un paciente (información personal, informes médicos y citas).
     */
    public function detail()
    {
        $id = $_GET['usuario_id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        }

        $userModel = $this->model('User');
        $usuario = $userModel->getByusuario_id($id);

        if (!$usuario || $usuario['rol'] !== 'Paciente') {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        }

        $historyModel = $this->model('MedicalReport');
        $appointmentModel = $this->model('Appointment');

        $data = [
            'usuario' => $usuario,
            'rol' => $_SESSION['rol'] ?? 'Administrador',
            'informes' => $historyModel->getByPaciente($id),
            'citas' => $appointmentModel->getByPatient($id)
        ];

        $this->view('patient/detail', $data);
    }

    /**
     * Genera y descarga un reporte en PDF con el listado general de pacientes.
     */
    public function createPDF()
    {
        $userModel = $this->model('User');
        $pacientes = $userModel->getByRol('Paciente');

        if (ob_get_length()) ob_end_clean();

        $pdf = new Fpdf();
        $pdf->AddPage('L');
        $pdf->SetFont('Arial', 'B', 16);
        
        $pdf->Cell(0, 15, iconv('UTF-8', 'windows-1252', 'Reporte General de Pacientes - Velion'), 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(25, 10, 'ID/DNI', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Nombre Completo', 1, 0, 'C', true);
        $pdf->Cell(80, 10, 'Email', 1, 0, 'C', true);
        $pdf->Cell(35, 10, iconv('UTF-8', 'windows-1252', 'Teléfono'), 1, 0, 'C', true);
        $pdf->Cell(35, 10, iconv('UTF-8', 'windows-1252', 'Género'), 1, 0, 'C', true);
        $pdf->Cell(40, 10, iconv('UTF-8', 'windows-1252', 'Fecha Nac.'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10);
        foreach ($pacientes as $p) {
            $pdf->Cell(25, 8, $p['usuario_id'], 1, 0, 'C');
            $pdf->Cell(60, 8, iconv('UTF-8', 'windows-1252', $p['nombre'] . ' ' . $p['apellidos']), 1);
            $pdf->Cell(80, 8, $p['email'], 1);
            $pdf->Cell(35, 8, $p['telefono'], 1, 0, 'C');
            $pdf->Cell(35, 8, $p['genero'], 1, 0, 'C');
            $pdf->Cell(40, 8, date('d/m/Y', strtotime($p['fecha_nacimiento'])), 1, 1, 'C');
        }

        $pdf->Output('D', 'Reporte_Pacientes_Velion.pdf');
    }

    /**
     * Busca pacientes por un término de búsqueda. Retorna JSON.
     */
    public function search()
    {
        $query = $_GET['q'] ?? '';
        $userModel = $this->model('User');
        $results = $userModel->searchByRol('Paciente', $query);
        
        header('Content-Type: application/json');
        echo json_encode($results);
        $this->exitApp();
    }

    /**
     * Busca trabajadores (fisios, secretarios, admins) por un término de búsqueda. Retorna JSON.
     */
    public function searchWorkers()
    {
        $query = $_GET['q'] ?? '';
        $userModel = $this->model('User');
        $results = array_merge(
            $userModel->searchByRol('Fisioterapeuta', $query),
            $userModel->searchByRol('Secretario', $query),
            $userModel->searchByRol('Administrador', $query)
        );
        
        header('Content-Type: application/json');
        echo json_encode($results);
        $this->exitApp();
    }
}
