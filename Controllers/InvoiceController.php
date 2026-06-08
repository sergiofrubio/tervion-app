<?php
namespace App\Controllers;

use App\Core\Controller;
use Fpdf\Fpdf;

class InvoiceController extends Controller
{
    public function list()
    {
        $facturaModel = $this->model('Factura');
        $usuario_id = $_SESSION['usuario_id'];
        $rol = $_SESSION['rol'] ?? 'Administrador';

        if ($rol === 'Paciente') {
            $data = [
                'facturas' => $facturaModel->getByPaciente($usuario_id),
                'pageTitle' => 'Mis Facturas - Velion'
            ];
            $this->view('vista-pacientes/facturas/list', $data);
        } else {
            $filters = [
                'paciente_id' => $_GET['paciente_id'] ?? null,
                'estado' => $_GET['estado'] ?? null,
                'q' => $_GET['q'] ?? null
            ];

            $data = [
                'facturas' => $facturaModel->getAll($filters),
                'pacientes' => $facturaModel->getPacientes(),
                'filters' => $filters,
                'pageTitle' => 'Gestión de Facturas - Velion'
            ];
            $this->view('facturas/list', $data);
        }
    }

    public function create()
    {
        $facturaModel = $this->model('Factura');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'paciente_id'   => $_POST['paciente_id'],
                'serie'         => $_POST['serie'] ?? 'A',
                'tipo_factura'  => $_POST['tipo_factura'] ?? 'F1',
                'fecha_emision' => $_POST['fecha_emision'],
                'estado'        => $_POST['estado'],
                'descripcion'   => htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'),
                'precio'        => (float)$_POST['precio'],
                'impuesto'      => (float)$_POST['impuesto'],
                'creado_por'    => $_SESSION['usuario_id'] ?? null
            ];
            
            if ($facturaModel->save($data)) {
                header('Location: ' . PROJECT_ROOT . '/facturas');
                exit();
            }
        } else {
            $data = [
                'pacientes' => $facturaModel->getPacientes()
            ];
            $this->view('facturas/create', $data);
        }
    }

    public function edit()
    {
        $facturaModel = $this->model('Factura');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // En Verifactu, solo permitimos actualizar el estado del pago
            $id = $_POST['factura_id'];
            $estado = $_POST['estado'];
            $modificado_por = $_SESSION['usuario_id'] ?? null;
            
            if ($facturaModel->updateStatus($id, $estado, $modificado_por)) {
                header('Location: ' . PROJECT_ROOT . '/facturas');
                exit();
            }
        } else {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header('Location: ' . PROJECT_ROOT . '/facturas');
                exit();
            }
            $data = [
                'factura' => $facturaModel->getById($id),
                'pacientes' => $facturaModel->getPacientes()
            ];
            $this->view('facturas/edit', $data);
        }
    }

    // public function delete()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $id = $_POST['factura_id'] ?? null;
    //         if ($id) {
    //             $facturaModel = $this->model('Factura');
    //             $facturaModel->delete($id);
    //         }
    //     }
    //     header('Location: ' . PROJECT_ROOT . '/facturas');
    //     exit();
    // }

    public function pdf()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . PROJECT_ROOT . '/facturas');
            exit();
        }

        $facturaModel = $this->model('Factura');
        $factura = $facturaModel->getById($id);
        $clinica = $facturaModel->getClinica();

        if (!$factura) {
            echo "Factura no encontrada.";
            return;
        }

        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 15);

        // Header - Clinic Info & Verifactu Badge
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(51, 122, 183);
        $pdf->Cell(120, 10, iconv('UTF-8', 'windows-1252', $clinica['nombre_comercial'] ?? 'VELION CLINIC'), 0, 0, 'L');
        
        // QR Code generation (using api.qrserver.com for simplicity)
        $nif_emisor = "B12345678"; // Debería ser dinámico
        $fecha_qr = date('d-m-Y', strtotime($factura['fecha_emision']));
        $total_qr = number_format($factura['total'], 2, '.', '');
        $url_aeat = "https://www2.agenciatributaria.gob.es/wlpl/TIKE-CONT/v1/qr/?nif=$nif_emisor&serie={$factura['serie']}&numero={$factura['numero']}&fecha=$fecha_qr&importe=$total_qr";
        $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($url_aeat);
        $pdf->Image($qr_url, 165, 10, 35, 35, 'PNG');

        $pdf->SetY(45);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 102, 204);
        $pdf->Cell(0, 8, 'VERIFACTU', 0, 1, 'R');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Factura verificable en la sede electrónica de la AEAT'), 0, 1, 'R');

        $pdf->SetY(20);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $clinica['razon_social'] ?? ''), 0, 1, 'L');
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $clinica['direccion_calle'] ?? ''), 0, 1, 'L');
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ($clinica['codigo_postal'] ?? '') . ' ' . ($clinica['ciudad'] ?? '')), 0, 1, 'L');
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Tel: ' . ($clinica['telefono_contacto'] ?? '')), 0, 1, 'L');

        $pdf->Line(10, 65, 200, 65);
        $pdf->Ln(25);

        // Invoice Info & Patient Info
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(95, 10, iconv('UTF-8', 'windows-1252', 'DATOS DEL PACIENTE'), 0, 0, 'L');
        $pdf->Cell(95, 10, iconv('UTF-8', 'windows-1252', 'FACTURA'), 0, 1, 'R');

        $pdf->SetFont('Arial', '', 10);
        $y = $pdf->GetY();
        $pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', $factura['nombre'] . ' ' . $factura['apellidos']), 0, 1, 'L');
        $pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', 'DNI/NIE: ' . $factura['paciente_id']), 0, 1, 'L');
        $pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', $factura['direccion']), 0, 1, 'L');
        $pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', $factura['cp'] . ' ' . $factura['municipio']), 0, 1, 'L');

        $pdf->SetY($y);
        $pdf->Cell(190, 5, iconv('UTF-8', 'windows-1252', 'Nº Factura: ' . $factura['serie'] . '-' . str_pad($factura['numero'], 6, '0', STR_PAD_LEFT)), 0, 1, 'R');
        $pdf->SetX(105);
        $pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', 'Fecha: ' . date('d/m/Y', strtotime($factura['fecha_emision']))), 0, 1, 'R');
        $pdf->SetX(105);
        $pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', 'Tipo: ' . $factura['tipo_factura']), 0, 1, 'R');
        $pdf->SetX(105);
        $pdf->Cell(95, 5, iconv('UTF-8', 'windows-1252', 'Estado: ' . $factura['estado']), 0, 1, 'R');

        $pdf->Ln(15);

        // Table Header
        $pdf->SetFillColor(51, 122, 183);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(110, 10, iconv('UTF-8', 'windows-1252', 'DESCRIPCIÓN'), 1, 0, 'L', true);
        $pdf->Cell(25, 10, iconv('UTF-8', 'windows-1252', 'BASE'), 1, 0, 'C', true);
        $pdf->Cell(25, 10, iconv('UTF-8', 'windows-1252', 'IVA %'), 1, 0, 'C', true);
        $pdf->Cell(30, 10, iconv('UTF-8', 'windows-1252', 'TOTAL'), 1, 1, 'C', true);

        // Table Body
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(110, 10, iconv('UTF-8', 'windows-1252', $factura['descripcion']), 1, 0, 'L');
        $pdf->Cell(25, 10, number_format($factura['precio'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 1, 0, 'C');
        $pdf->Cell(25, 10, number_format($factura['impuesto'], 0) . '%', 1, 0, 'C');
        $pdf->Cell(30, 10, number_format($factura['total'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 1, 1, 'C');

        // Totals
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetX(130);
        $pdf->Cell(40, 8, iconv('UTF-8', 'windows-1252', 'Total Base:'), 0, 0, 'R');
        $pdf->Cell(30, 8, number_format($factura['precio'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 0, 1, 'R');
        $pdf->SetX(130);
        $pdf->Cell(40, 8, iconv('UTF-8', 'windows-1252', 'Total IVA:'), 0, 0, 'R');
        $pdf->Cell(30, 8, number_format($factura['cuota_iva'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 0, 1, 'R');
        $pdf->SetX(130);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(51, 122, 183);
        $pdf->Cell(40, 12, iconv('UTF-8', 'windows-1252', 'TOTAL:'), 0, 0, 'R');
        $pdf->Cell(30, 12, number_format($factura['total'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 0, 1, 'R');

        // Footer - Verifactu Hash Chaining
        $pdf->SetY(-45);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'INFORMACIÓN DE REGISTRO (VERIFACTU):'), 0, 1, 'L');
        $pdf->SetFont('Courier', '', 6);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->MultiCell(0, 3, iconv('UTF-8', 'windows-1252', 'HUELLA: ' . $factura['huella']), 0, 'L');
        if ($factura['huella_anterior']) {
            $pdf->MultiCell(0, 3, iconv('UTF-8', 'windows-1252', 'HUELLA ANTERIOR: ' . $factura['huella_anterior']), 0, 'L');
        }

        $pdf->SetY(-20);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Esta factura ha sido emitida mediante un sistema informático Verificable.'), 0, 1, 'C');

        $pdf->Output('I', 'Factura_' . $factura['serie'] . '-' . str_pad($factura['numero'], 6, '0', STR_PAD_LEFT) . '.pdf');
    }

}
