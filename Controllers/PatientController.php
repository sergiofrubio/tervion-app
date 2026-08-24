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
                'pass' => password_hash($_POST['usuario_id'], PASSWORD_DEFAULT),
                'rol' => 'Paciente',
                'genero' => $_POST['genero'] ?? 'Otro',
                'rgpd_aceptado' => isset($_POST['rgpd_aceptado']) ? 1 : 0,
                'firma_paciente' => $_POST['firma_paciente'] ?? null,
                'fecha_consentimiento' => isset($_POST['rgpd_aceptado']) ? date('Y-m-d H:i:s') : null,
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
                'genero' => $_POST['genero'] ?? 'Otro',
                'rgpd_aceptado' => isset($_POST['rgpd_aceptado']) ? 1 : 0,
                'firma_paciente' => !empty($_POST['firma_paciente']) ? $_POST['firma_paciente'] : null
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
        $documentModel = $this->model('Document');
        $invoiceModel = $this->model('Invoice');
        $cuenta_id = $_SESSION['cuenta_id'] ?? 1;

        $data = [
            'usuario' => $usuario,
            'rol' => $_SESSION['rol'] ?? 'Administrador',
            'informes' => $historyModel->getByPaciente($id),
            'citas' => $appointmentModel->getByPatient($id),
            'documentos' => $documentModel->getByPacienteWithTemplates($id, $cuenta_id),
            'facturas' => $invoiceModel->getAll(['paciente_id' => $id])
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

        $pdf->Cell(0, 15, iconv('UTF-8', 'windows-1252', 'Reporte General de Pacientes - Tervion'), 0, 1, 'C');
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

        $pdf->Output('D', 'Reporte_Pacientes_Tervion.pdf');
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

    /**
     * Genera y descarga el PDF con el documento de consentimiento RGPD firmado por el paciente.
     */
    public function downloadConsent()
    {
        $id = $_GET['usuario_id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        }

        $userModel = $this->model('User');
        $usuario = $userModel->getByusuario_id($id);

        if (!$usuario) {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        }

        if (ob_get_length()) ob_end_clean();

        $pdf = new Fpdf();
        $pdf->AddPage('P');
        $pdf->SetMargins(15, 15, 15);

        // Encabezado
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'DOCUMENTO DE CONSENTIMIENTO INFORMADO Y RGPD'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Protección de Datos Personales y Tratamiento de Salud'), 0, 1, 'C');
        $pdf->Ln(5);

        // Bloque de datos del paciente
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(245, 247, 250);
        $pdf->Cell(0, 8, iconv('UTF-8', 'windows-1252', '  Datos del Paciente'), 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 10);
        $fechaNac = !empty($usuario['fecha_nacimiento']) ? date('d/m/Y', strtotime($usuario['fecha_nacimiento'])) : '-';

        $pdf->Cell(90, 7, iconv('UTF-8', 'windows-1252', '  Nombre: ' . ($usuario['nombre'] ?? '') . ' ' . ($usuario['apellidos'] ?? '')), 'L', 0);
        $pdf->Cell(90, 7, iconv('UTF-8', 'windows-1252', 'DNIentificador: ' . ($usuario['usuario_id'] ?? '')), 'R', 1);

        $pdf->Cell(90, 7, iconv('UTF-8', 'windows-1252', '  Teléfono: ' . ($usuario['telefono'] ?? '-')), 'L', 0);
        $pdf->Cell(90, 7, iconv('UTF-8', 'windows-1252', 'Email: ' . ($usuario['email'] ?? '-')), 'R', 1);

        $pdf->Cell(90, 7, iconv('UTF-8', 'windows-1252', '  Fecha de Nacimiento: ' . $fechaNac), 'L', 0);
        $pdf->Cell(90, 7, iconv('UTF-8', 'windows-1252', 'Dirección: ' . ($usuario['direccion'] ?? '-') . ' (' . ($usuario['cp'] ?? '') . ')'), 'R', 1);

        $pdf->Cell(180, 2, '', 'LBR', 1);
        $pdf->Ln(6);

        // Cláusula Legal
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', 'Información sobre Protección de Datos (RGPD / LOPD-GDD)'), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);

        $textoLegal = "De conformidad con el Reglamento General de Protección de Datos (RGPD UE 2016/679) y la Ley Orgánica 3/2018 de Protección de Datos Personales y garantía de los derechos digitales (LOPDGDD):\n\n"
            . "1. RESPONSABLE DEL TRATAMIENTO: Tervion / Velion Clinique.\n"
            . "2. FINALIDAD: Gestión de la historia clínica, prestación de servicios sanitarios de fisioterapia y salud, citación y facturación.\n"
            . "3. LEGITIMACIÓN: Ejecución del contrato de prestación de servicios de salud y consentimiento del interesado (Art. 6.1.a y 9.2.a RGPD).\n"
            . "4. CONSERVACIÓN: Los datos clínicos se conservarán durante los plazos legalmente previstos en la legislación sanitaria aplicable (mínimo 5 años).\n"
            . "5. DERECHOS: Puede ejercitar sus derechos de acceso, rectificación, supresión, limitación y oposición dirigiéndose a la clínica.\n\n"
            . "DECLARACIÓN DE CONSENTIMIENTO:\n"
            . "El paciente declara haber sido informado de forma clara y precisa sobre el tratamiento de sus datos personales y de salud, prestando de manera explícita y libre su consentimiento expreso para la apertura de su expediente clínico y el tratamiento de sus datos con fines asistenciales y administrativos.";

        $pdf->MultiCell(180, 5, iconv('UTF-8', 'windows-1252', $textoLegal), 1, 'J');
        $pdf->Ln(6);

        // Firma y fecha
        $fechaConsent = !empty($usuario['fecha_consentimiento']) ? date('d/m/Y H:i', strtotime($usuario['fecha_consentimiento'])) : date('d/m/Y H:i');

        $pdf->SetFont('Arial', 'B', 10);
        $estadoStr = !empty($usuario['rgpd_aceptado']) ? 'ACEPTADO Y FIRMADO' : 'PENDIENTE DE FIRMA';
        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Estado del Consentimiento: ' . $estadoStr), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Fecha y Hora de Consentimiento: ' . $fechaConsent), 0, 1, 'L');
        $pdf->Ln(4);

        if (!empty($usuario['firma_paciente']) && strpos($usuario['firma_paciente'], 'data:image') === 0) {
            $imageData = explode(',', $usuario['firma_paciente']);
            if (count($imageData) === 2) {
                $decodedImg = base64_decode($imageData[1]);
                $tempFile = tempnam(sys_get_temp_dir(), 'sig_') . '.png';
                file_put_contents($tempFile, $decodedImg);

                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Firma del Paciente / Tutor legal:'), 0, 1, 'L');
                $yBeforeSig = $pdf->GetY();
                $pdf->Image($tempFile, 15, $yBeforeSig + 2, 60, 25);
                unlink($tempFile);
            }
        }

        $pdf->Output('D', 'Consentimiento_RGPD_' . $usuario['usuario_id'] . '.pdf');
    }

    /**
     * Muestra la vista para cumplimentar y firmar digitalmente una plantilla de documento para un paciente.
     */
    public function signDocument()
    {
        $cuenta_id = $_SESSION['cuenta_id'] ?? 1;
        $documentModel = $this->model('Document');
        $userModel = $this->model('User');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paciente_id = $_POST['paciente_id'] ?? null;
            $documento_id = (int)($_POST['documento_id'] ?? 0);
            $contenido_firmado = $_POST['contenido'] ?? '';
            $firma_paciente = $_POST['firma_paciente'] ?? null;

            if (!$paciente_id || !$documento_id || empty($contenido_firmado)) {
                header('Location: ' . PROJECT_ROOT . '/pacientes/documentos/firmar?paciente_id=' . $paciente_id . '&documento_id=' . $documento_id . '&alert=danger&message=El contenido del documento es obligatorio.');
                $this->exitApp();
            }

            $data = [
                'cuenta_id' => $cuenta_id,
                'paciente_id' => $paciente_id,
                'documento_id' => $documento_id,
                'contenido_firmado' => $contenido_firmado,
                'firma_paciente' => !empty($firma_paciente) ? $firma_paciente : null,
                'firmado' => !empty($firma_paciente) ? 1 : 0,
                'fecha_firma' => !empty($firma_paciente) ? date('Y-m-d H:i:s') : null,
                'usuario_id' => $_SESSION['usuario_id'] ?? $_SESSION['user_id'] ?? null
            ];

            $savedId = $documentModel->savePacienteDocumento($data);

            if ($savedId) {
                $mensaje = !empty($firma_paciente) ? 'Documento firmado y guardado correctamente.' : 'Borrador de documento guardado correctamente.';
                header('Location: ' . PROJECT_ROOT . '/pacientes/detalle?usuario_id=' . $paciente_id . '&alert=success&message=' . urlencode($mensaje));
                $this->exitApp();
            } else {
                header('Location: ' . PROJECT_ROOT . '/pacientes/documentos/firmar?paciente_id=' . $paciente_id . '&documento_id=' . $documento_id . '&alert=danger&message=' . urlencode('Error al guardar el documento.'));
                $this->exitApp();
            }
        } else {
            $paciente_id = $_GET['paciente_id'] ?? null;
            $documento_id = (int)($_GET['documento_id'] ?? 0);

            if (!$paciente_id || !$documento_id) {
                header('Location: ' . PROJECT_ROOT . '/pacientes');
                $this->exitApp();
            }

            $paciente = $userModel->getByusuario_id($paciente_id);
            $plantilla = $documentModel->getById($documento_id, $cuenta_id);
            $docPaciente = $documentModel->getPacienteDocumento($paciente_id, $documento_id, $cuenta_id);

            if (!$paciente || !$plantilla) {
                header('Location: ' . PROJECT_ROOT . '/pacientes?alert=danger&message=' . urlencode('Paciente o plantilla no encontrados.'));
                $this->exitApp();
            }

            $contenido = $docPaciente ? $docPaciente['contenido_firmado'] : $plantilla['contenido'];
            $firma = $docPaciente ? $docPaciente['firma_paciente'] : ($paciente['firma_paciente'] ?? '');

            $data = [
                'paciente' => $paciente,
                'plantilla' => $plantilla,
                'docPaciente' => $docPaciente,
                'contenido' => $contenido,
                'firma' => $firma
            ];

            $this->view('document/document_sign', $data);
        }
    }

    /**
     * Muestra la vista de solo lectura o modal/detalle de un documento firmado.
     */
    public function viewSignedDocument()
    {
        $cuenta_id = $_SESSION['cuenta_id'] ?? 1;
        $documentModel = $this->model('Document');
        $userModel = $this->model('User');

        $paciente_id = $_GET['paciente_id'] ?? null;
        $documento_id = (int)($_GET['documento_id'] ?? 0);

        if (!$paciente_id || !$documento_id) {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        }

        $paciente = $userModel->getByusuario_id($paciente_id);
        $plantilla = $documentModel->getById($documento_id, $cuenta_id);
        $docPaciente = $documentModel->getPacienteDocumento($paciente_id, $documento_id, $cuenta_id);

        if (!$paciente || !$plantilla) {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        }

        $data = [
            'paciente' => $paciente,
            'plantilla' => $plantilla,
            'docPaciente' => $docPaciente,
            'contenido' => $docPaciente['contenido_firmado'] ?? $plantilla['contenido'],
            'firma' => $docPaciente['firma_paciente'] ?? null,
            'isReadOnly' => true
        ];

        $this->view('document/document_sign', $data);
    }

    /**
     * Genera y descarga en PDF el documento de un paciente con su firma digital.
     */
    public function downloadSignedDocumentPdf()
    {
        $cuenta_id = $_SESSION['cuenta_id'] ?? 1;
        $documentModel = $this->model('Document');
        $userModel = $this->model('User');

        $paciente_id = $_GET['paciente_id'] ?? null;
        $documento_id = (int)($_GET['documento_id'] ?? 0);

        if (!$paciente_id || !$documento_id) {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        }

        $paciente = $userModel->getByusuario_id($paciente_id);
        $plantilla = $documentModel->getById($documento_id, $cuenta_id);
        $docPaciente = $documentModel->getPacienteDocumento($paciente_id, $documento_id, $cuenta_id);

        if (!$paciente || !$plantilla) {
            header('Location: ' . PROJECT_ROOT . '/pacientes');
            $this->exitApp();
        }

        if (ob_get_length()) ob_end_clean();

        $pdf = new Fpdf();
        $pdf->AddPage('P');
        $pdf->SetMargins(15, 15, 15);

        // Encabezado
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', strtoupper($plantilla['titulo'])), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Documento Clínico Oficial — Clínica Tervion / Velion'), 0, 1, 'C');
        $pdf->Ln(4);

        // Ficha Paciente
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(245, 247, 250);
        $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', '  Datos del Paciente'), 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 6, iconv('UTF-8', 'windows-1252', '  Nombre: ' . ($paciente['nombre'] ?? '') . ' ' . ($paciente['apellidos'] ?? '')), 'L', 0);
        $pdf->Cell(90, 6, iconv('UTF-8', 'windows-1252', 'DNI/NIF: ' . ($paciente['usuario_id'] ?? '')), 'R', 1);

        $pdf->Cell(90, 6, iconv('UTF-8', 'windows-1252', '  Teléfono: ' . ($paciente['telefono'] ?? '-')), 'L', 0);
        $pdf->Cell(90, 6, iconv('UTF-8', 'windows-1252', 'Email: ' . ($paciente['email'] ?? '-')), 'R', 1);
        $pdf->Cell(180, 1, '', 'LBR', 1);
        $pdf->Ln(4);

        // Contenido del documento
        $contenidoRaw = $docPaciente ? $docPaciente['contenido_firmado'] : $plantilla['contenido'];
        $contenidoTexto = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '</h1>', '</h2>', '</h3>', '</li>'], ["\n", "\n", "\n", "\n\n", "\n\n", "\n\n", "\n\n", "\n"], $contenidoRaw));
        $contenidoTexto = html_entity_decode($contenidoTexto, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $contenidoTexto = trim(preg_replace("/\n{3,}/", "\n\n", $contenidoTexto));

        $pdf->SetFont('Arial', '', 9.5);
        $pdf->MultiCell(180, 5.5, iconv('UTF-8', 'windows-1252//TRANSLIT', $contenidoTexto), 0, 'J');
        $pdf->Ln(6);

        // Bloque de Firma
        $pdf->SetFont('Arial', 'B', 10);
        $isFirmado = !empty($docPaciente['firmado']);
        $fechaFirma = !empty($docPaciente['fecha_firma']) ? date('d/m/Y H:i', strtotime($docPaciente['fecha_firma'])) : '-';

        $pdf->Cell(0, 6, iconv('UTF-8', 'windows-1252', 'Estado de Firma: ' . ($isFirmado ? 'FIRMADO DIGITALMENTE' : 'PENDIENTE DE FIRMA')), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Fecha de la firma: ' . $fechaFirma), 0, 1, 'L');
        $pdf->Ln(3);

        $firmaImg = $docPaciente['firma_paciente'] ?? null;
        if (!empty($firmaImg) && strpos($firmaImg, 'data:image') === 0) {
            $imageData = explode(',', $firmaImg);
            if (count($imageData) === 2) {
                $decodedImg = base64_decode($imageData[1]);
                $tempFile = tempnam(sys_get_temp_dir(), 'sig_doc_') . '.png';
                file_put_contents($tempFile, $decodedImg);

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Firma del Paciente:'), 0, 1, 'L');
                $yPos = $pdf->GetY();
                $pdf->Image($tempFile, 15, $yPos + 1, 55, 22);
                unlink($tempFile);
            }
        }

        $sanitizedTitle = preg_replace('/[^A-Za-z0-9_-]/', '_', $plantilla['titulo']);
        $pdf->Output('D', 'Documento_' . $sanitizedTitle . '_' . $paciente['usuario_id'] . '.pdf');
    }
}
