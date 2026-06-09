<?php
namespace App\Controllers;
use App\Core\Controller;
use Fpdf\Fpdf;


class UserController extends Controller
{
    /**
     * Muestra la lista general de usuarios de la aplicación.
     *
     * @return void
     */
    public function list()
    {
        $user = $this->model('User');
        $data = ['users' => $user->getAll()];  // Empaquetamos los datos
        $this->view('user/list', $data);      // Pasamos los datos a la vista
    }

    /**
     * Crea un nuevo usuario.
     *
     * Si la petición es POST, guarda los datos del usuario tras sanitizarlos y redirige al listado.
     * Si es GET, muestra el formulario de creación de usuario junto con las especialidades disponibles.
     *
     * @return void
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->model('User');

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
                'rol' => $_POST['rol'] ?? 'Paciente',
                'genero' => $_POST['genero'] ?? 'Otro',
                'especialidad' => !empty($_POST['especialidad']) ? (int)$_POST['especialidad'] : null
            ];

            if (!empty($data['usuario_id']) && !empty($data['nombre']) && $user->save($data)) {
                header('Location: ' . PROJECT_ROOT . '/usuarios');
                exit();
            } else {
                echo "Error al guardar el usuario o datos inválidos.";
            }
        } else {
            $userModel = $this->model('User');
            $data = ['especialidades' => $userModel->getSpecialties()];
            $this->view('user/form', $data);
        }
    }

    /**
     * Elimina un usuario por su identificador.
     *
     * @return void
     */
    public function delete()
    {
        $user = $this->model('User');
        $id = $_POST['id'];

        if ($user->delete($id)) {
            header('Location: ' . PROJECT_ROOT . '/usuarios');
            exit();
        } else {
            echo "Error while deleting user.";
        }
    }

    /**
     * Edita los detalles de un usuario existente.
     *
     * Si la petición es POST, actualiza los datos del usuario en la base de datos.
     * Si es GET, muestra el formulario de edición con los datos del usuario y especialidades.
     *
     * @return void
     */
    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->model('User');
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
                'rol' => $_POST['rol'] ?? 'Paciente',
                'genero' => $_POST['genero'] ?? 'Otro',
                'especialidad' => !empty($_POST['especialidad']) ? (int)$_POST['especialidad'] : null
            ];

            if (!empty($_POST['pass'])) {
                $data['pass'] = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            }

            if ($user->update($id, $data)) {
                header('Location: ' . PROJECT_ROOT . '/usuarios');
                exit();
            } else {
                echo "Error while updating user.";
            }
        } else {
            $id = $_GET['id'];
            $user = $this->model('User');
            $data = [
                'usuario' => $user->getByusuario_id($id),
                'especialidades' => $user->getSpecialties()
            ];
            $this->view('user/form', $data);
        }
    }

    /**
     * Muestra la vista detallada de un usuario (información personal, informes médicos y citas).
     *
     * @return void
     */
    public function detail()
    {
        $id = $_GET['usuario_id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: ' . PROJECT_ROOT . '/usuarios');
            exit();
        }

        $user = $this->model('User');
        $usuario = $user->getByusuario_id($id);

        if (!$usuario) {
            header('Location: ' . PROJECT_ROOT . '/usuarios');
            exit();
        }

        $historyModel = $this->model('MedicalHistory');
        $appointmentModel = $this->model('Appointment');

        $data = [
            'usuario' => $usuario,
            'rol' => $_SESSION['rol'] ?? 'Administrador',
            'informes' => $historyModel->getByPaciente($id),
            'citas' => $appointmentModel->getByPatient($id)
        ];

        $this->view('user/detail', $data);
    }

    /**
     * Genera y descarga un reporte en PDF (en orientación horizontal) con el listado general de usuarios usando FPDF.
     *
     * @return void
     */
    public function createPDF()
    {
        $userModel = $this->model('User');
        $usuarios = $userModel->getAll();

        // Limpiar cualquier salida previa para evitar errores de cabecera PDF
        if (ob_get_length()) ob_end_clean();

        $pdf = new Fpdf();
        $pdf->AddPage('L'); // Orientación horizontal
        $pdf->SetFont('Arial', 'B', 16);
        
        // Título del documento
        $pdf->Cell(0, 15, iconv('UTF-8', 'windows-1252', 'Reporte General de Usuarios - Velion'), 0, 1, 'C');
        $pdf->Ln(5);

        // Cabecera de la tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(25, 10, 'ID', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Nombre Completo', 1, 0, 'C', true);
        $pdf->Cell(80, 10, 'Email', 1, 0, 'C', true);
        $pdf->Cell(35, 10, iconv('UTF-8', 'windows-1252', 'Teléfono'), 1, 0, 'C', true);
        $pdf->Cell(35, 10, iconv('UTF-8', 'windows-1252', 'Género'), 1, 0, 'C', true);
        $pdf->Cell(40, 10, iconv('UTF-8', 'windows-1252', 'Fecha Nac.'), 1, 1, 'C', true);

        // Cuerpo de la tabla
        $pdf->SetFont('Arial', '', 10);
        foreach ($usuarios as $u) {
            $pdf->Cell(25, 8, $u['usuario_id'], 1, 0, 'C');
            $pdf->Cell(60, 8, iconv('UTF-8', 'windows-1252', $u['nombre'] . ' ' . $u['apellidos']), 1);
            $pdf->Cell(80, 8, $u['email'], 1);
            $pdf->Cell(35, 8, $u['telefono'], 1, 0, 'C');
            $pdf->Cell(35, 8, $u['genero'], 1, 0, 'C');
            $pdf->Cell(40, 8, date('d/m/Y', strtotime($u['fecha_nacimiento'])), 1, 1, 'C');
        }

        // Descarga el PDF
        $pdf->Output('D', 'Reporte_Usuarios_Velion.pdf');
    }

    /**
     * Busca usuarios por rol y un término de búsqueda (query) específico.
     *
     * Retorna la respuesta en formato JSON.
     *
     * @return void
     */
    public function search()
    {
        $rol = $_GET['rol'] ?? '';

        $query = $_GET['q'] ?? '';
        
        $userModel = $this->model('User');
        $results = $userModel->searchByRol($rol, $query);
        
        header('Content-Type: application/json');
        echo json_encode($results);
        exit();
    }
}