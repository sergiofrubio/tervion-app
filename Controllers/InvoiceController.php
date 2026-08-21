<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\Verifactu\VerifactuService;
use Fpdf\Fpdf;

class InvoiceController extends Controller
{
    /**
     * Muestra la lista de facturas.
     *
     * Si el rol del usuario es Paciente, muestra solo sus facturas.
     * Si es Administrador/Staff, muestra todas las facturas permitiendo filtrarlas.
     *
     * @return void
     */
    public function list()
    {
        $facturaModel = $this->model('Invoice');
        $usuario_id = $_SESSION['usuario_id'];
        $rol = $_SESSION['rol'] ?? 'Administrador';

        if ($rol === 'Paciente') {
            $data = [
                'facturas' => $facturaModel->getByPaciente($usuario_id),
                'pageTitle' => 'Mis Facturas - Velion'
            ];
            $this->view('patient-view/invoice/list', $data);
        } else {
            $filters = [
                'paciente_id' => $_GET['paciente_id'] ?? null,
                'estado' => $_GET['estado'] ?? null,
                'estado_verifactu' => $_GET['estado_verifactu'] ?? null,
                'q' => $_GET['q'] ?? null
            ];

            $data = [
                'facturas' => $facturaModel->getAll($filters),
                'pacientes' => $facturaModel->getPacientes(),
                'filters' => $filters,
                'pageTitle' => 'Gestión de Facturas - Velion'
            ];
            $this->view('invoice/list', $data);
        }
    }

    /**
     * Crea una nueva factura reglada (sistema Verifactu).
     *
     * Si la petición es POST, guarda la factura en la base de datos tras sanitizar y calcular impuestos,
     * e invoca inmediatamente la remisión automática a la AEAT vía VerifactuService.
     * Si es GET, muestra el formulario de creación de factura.
     *
     * @return void
     */
    public function create()
    {
        $facturaModel = $this->model('Invoice');
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

            $facturaId = $facturaModel->save($data);
            if ($facturaId) {
                // Envío automático a Verifactu (AEAT)
                try {
                    $verifactuService = new VerifactuService($facturaModel);
                    $verifactuService->procesarFactura($facturaId);
                } catch (\Throwable $e) {
                    // Log del error sin interrumpir el flujo principal de facturación
                    error_log('Verifactu error al emitir factura ' . $facturaId . ': ' . $e->getMessage());
                }

                header('Location: ' . PROJECT_ROOT . '/facturas');
                $this->exitApp();
            }
        } else {
            $data = [
                'pacientes' => $facturaModel->getPacientes()
            ];
            $this->view('invoice/create', $data);
        }
    }

    /**
     * Reenvía manualmente una factura a Verifactu (AEAT).
     *
     * @return void
     */
    public function reenviarVerifactu()
    {
        $id = $_GET['id'] ?? $_POST['factura_id'] ?? null;
        if ($id) {
            try {
                $facturaModel = $this->model('Invoice');
                $verifactuService = new VerifactuService($facturaModel);
                $verifactuService->procesarFactura((int)$id);
            } catch (\Throwable $e) {
                error_log('Verifactu retry error: ' . $e->getMessage());
            }
        }

        header('Location: ' . PROJECT_ROOT . '/facturas');
        $this->exitApp();
    }

    /**
     * Edita los detalles de una factura existente.
     *
     * En el flujo Verifactu, la edición se restringe a actualizar el estado del pago.
     * Si es POST, realiza la actualización; si es GET, muestra el formulario de edición.
     *
     * @return void
     */
    public function edit()
    {
        $facturaModel = $this->model('Invoice');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // En Verifactu, solo permitimos actualizar el estado del pago
            $id = $_POST['factura_id'];
            $estado = $_POST['estado'];
            $modificado_por = $_SESSION['usuario_id'] ?? null;

            if ($facturaModel->updateStatus($id, $estado, $modificado_por)) {
                header('Location: ' . PROJECT_ROOT . '/facturas');
                $this->exitApp();
            }
        } else {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header('Location: ' . PROJECT_ROOT . '/facturas');
                $this->exitApp();
            }
            $data = [
                'factura' => $facturaModel->getById($id),
                'pacientes' => $facturaModel->getPacientes()
            ];
            $this->view('invoice/edit', $data);
        }
    }

    /**
     * Genera y descarga el documento PDF de la factura (con código QR y datos Verifactu) usando FPDF.
     *
     * @return void
     */
    public function pdf()
    {
        $id = $_GET['id'] ?? null;
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        $rol = $_SESSION['rol'] ?? 'Administrador';

        if (!$id) {
            $redirectUrl = ($rol === 'Paciente') ? '/paciente/facturas' : '/facturas';
            header('Location: ' . PROJECT_ROOT . $redirectUrl);
            $this->exitApp();
        }

        $facturaModel = $this->model('Invoice');
        $factura = $facturaModel->getById($id);
        $clinica = $facturaModel->getClinica();

        if (!$factura) {
            echo "Factura no encontrada.";
            return;
        }

        // Seguridad: Los pacientes solo pueden acceder a sus propias facturas (previene IDOR)
        if ($rol === 'Paciente' && $factura['paciente_id'] !== $usuario_id) {
            echo "Acceso denegado. No tienes permisos para visualizar esta factura.";
            return;
        }

        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 15);

        // Header - Logo & QR Code
        $logoPath = __DIR__ . '/../public/custom/img/logo-tervion-sin-fondo.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 45); // width 45mm, height auto
        } else {
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->SetTextColor(0, 82, 217); // Royal Blue
            $pdf->Cell(120, 10, iconv('UTF-8', 'windows-1252', $clinica['nombre_comercial'] ?? 'VELION CLINIC'), 0, 0, 'L');
        }

        // QR Code generation (using api.qrserver.com for rendering in PDF)
        $qr_url_data = $factura['qr_url'] ?: "https://prewww1.aeat.es/vl/factura/qr?nif=" . ($clinica['nif_cif'] ?? 'B12345678') . "&serie=" . $factura['serie'] . '-' . $factura['numero'] . "&fecha=" . date('d-m-Y', strtotime($factura['fecha_emision'])) . "&importe=" . number_format($factura['total'], 2, '.', '');
        $qr_image_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qr_url_data);
        $pdf->Image($qr_image_url, 165, 10, 35, 35, 'PNG');

        $pdf->SetY(45);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor(0, 158, 255); // Cyan from logo
        $pdf->Cell(0, 8, 'VERIFACTU', 0, 1, 'R');
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Factura verificable en la sede electrónica de la AEAT'), 0, 1, 'R');

        $pdf->SetY(24);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(110, 110, 110);
        $pdf->Cell(120, 4.5, iconv('UTF-8', 'windows-1252', $clinica['razon_social'] ?? ''), 0, 1, 'L');
        $pdf->Cell(120, 4.5, iconv('UTF-8', 'windows-1252', $clinica['direccion_calle'] ?? ''), 0, 1, 'L');
        $pdf->Cell(120, 4.5, iconv('UTF-8', 'windows-1252', ($clinica['codigo_postal'] ?? '') . ' ' . ($clinica['ciudad'] ?? '')), 0, 1, 'L');
        $pdf->Cell(120, 4.5, iconv('UTF-8', 'windows-1252', 'Tel: ' . ($clinica['telefono_contacto'] ?? '')), 0, 1, 'L');

        // Draw a clean, thin, modern light grey line
        $pdf->SetDrawColor(226, 232, 240); // e2e8f0
        $pdf->Line(10, 58, 200, 58);
        $pdf->Ln(22);

        // Invoice Info & Patient Info
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 82, 217); // Royal Blue
        $pdf->Cell(95, 7, iconv('UTF-8', 'windows-1252', 'DATOS DEL PACIENTE'), 0, 0, 'L');
        $pdf->Cell(95, 7, iconv('UTF-8', 'windows-1252', 'INFORMACIÓN DE FACTURA'), 0, 1, 'R');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(71, 85, 105); // Slate
        $y = $pdf->GetY();
        $pdf->Cell(95, 5.5, iconv('UTF-8', 'windows-1252', $factura['nombre'] . ' ' . $factura['apellidos']), 0, 1, 'L');
        $pdf->Cell(95, 5.5, iconv('UTF-8', 'windows-1252', 'DNI/NIE: ' . $factura['paciente_id']), 0, 1, 'L');
        $pdf->Cell(95, 5.5, iconv('UTF-8', 'windows-1252', $factura['direccion']), 0, 1, 'L');
        $pdf->Cell(95, 5.5, iconv('UTF-8', 'windows-1252', ($factura['cp'] ?? '') . ' ' . ($factura['municipio'] ?? '')), 0, 1, 'L');

        $pdf->SetY($y);
        $pdf->Cell(190, 5.5, iconv('UTF-8', 'windows-1252', 'Nº Factura: ' . $factura['serie'] . '-' . str_pad($factura['numero'], 6, '0', STR_PAD_LEFT)), 0, 1, 'R');
        $pdf->SetX(105);
        $pdf->Cell(95, 5.5, iconv('UTF-8', 'windows-1252', 'Fecha: ' . date('d/m/Y', strtotime($factura['fecha_emision']))), 0, 1, 'R');
        $pdf->SetX(105);
        $pdf->Cell(95, 5.5, iconv('UTF-8', 'windows-1252', 'Tipo: ' . $factura['tipo_factura']), 0, 1, 'R');
        $pdf->SetX(105);
        $pdf->Cell(95, 5.5, iconv('UTF-8', 'windows-1252', 'Estado Pago: ' . $factura['estado']), 0, 1, 'R');
        if (!empty($factura['csv_verifactu'])) {
            $pdf->SetX(105);
            $pdf->Cell(95, 5.5, iconv('UTF-8', 'windows-1252', 'CSV AEAT: ' . $factura['csv_verifactu']), 0, 1, 'R');
        }

        $pdf->Ln(15);

        // Table Header
        $pdf->SetFillColor(248, 250, 252); // f8fafc - very light slate
        $pdf->SetTextColor(71, 85, 105); // Slate Text
        $pdf->SetDrawColor(226, 232, 240); // e2e8f0
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(110, 8, iconv('UTF-8', 'windows-1252', ' DESCRIPCIÓN'), 1, 0, 'L', true);
        $pdf->Cell(25, 8, iconv('UTF-8', 'windows-1252', 'BASE'), 1, 0, 'C', true);
        $pdf->Cell(25, 8, iconv('UTF-8', 'windows-1252', 'IVA %'), 1, 0, 'C', true);
        $pdf->Cell(30, 8, iconv('UTF-8', 'windows-1252', 'TOTAL'), 1, 1, 'C', true);

        // Table Body
        $pdf->SetTextColor(30, 41, 59); // Darker slate
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(110, 9, iconv('UTF-8', 'windows-1252', ' ' . $factura['descripcion']), 1, 0, 'L');
        $pdf->Cell(25, 9, number_format($factura['precio'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 1, 0, 'C');
        $pdf->Cell(25, 9, number_format($factura['impuesto'], 0) . '%', 1, 0, 'C');
        $pdf->Cell(30, 9, number_format($factura['total'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 1, 1, 'C');

        // Totals
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->SetX(130);
        $pdf->Cell(40, 6, iconv('UTF-8', 'windows-1252', 'Total Base:'), 0, 0, 'R');
        $pdf->Cell(30, 6, number_format($factura['precio'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 0, 1, 'R');

        $pdf->SetX(130);
        $pdf->Cell(40, 6, iconv('UTF-8', 'windows-1252', 'Total IVA:'), 0, 0, 'R');
        $pdf->Cell(30, 6, number_format($factura['cuota_iva'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 0, 1, 'R');

        $pdf->SetX(130);
        $pdf->Cell(70, 2, '', 'B', 1); // thin line under sub-totals
        $pdf->Ln(2);

        $pdf->SetX(130);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 82, 217); // Royal Blue
        $pdf->Cell(40, 10, iconv('UTF-8', 'windows-1252', 'TOTAL:'), 0, 0, 'R');
        $pdf->Cell(30, 10, number_format($factura['total'], 2, ',', '.') . iconv('UTF-8', 'windows-1252', ' €'), 0, 1, 'R');

        // Footer - Verifactu Hash Chaining
        $pdf->SetY(-45);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'INFORMACIÓN DE REGISTRO (VERIFACTU):'), 0, 1, 'L');
        $pdf->SetFont('Courier', '', 6);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->MultiCell(0, 3, iconv('UTF-8', 'windows-1252', 'HUELLA SHA-256: ' . $factura['huella']), 0, 'L');
        if ($factura['huella_anterior']) {
            $pdf->MultiCell(0, 3, iconv('UTF-8', 'windows-1252', 'HUELLA ANTERIOR: ' . $factura['huella_anterior']), 0, 'L');
        }

        $pdf->SetY(-20);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Esta factura ha sido emitida mediante un sistema informático Verificable (AEAT).'), 0, 1, 'C');

        $pdf->Output('I', 'Factura_' . $factura['serie'] . '-' . str_pad($factura['numero'], 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
