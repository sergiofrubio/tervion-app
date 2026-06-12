<?php
namespace App\Controllers;

use App\Core\Controller;

class AccountingController extends Controller
{
    public function __construct()
    {
        // Asegurar autenticación general
        $this->checkAuth();
        
        // Solo administradores pueden ver la contabilidad
        $rol = $_SESSION['rol'] ?? '';
        if ($rol !== 'Administrador') {
            header('Location: ' . PROJECT_ROOT . '/inicio');
            $this->exitApp();
        }
    }

    /**
     * Muestra el cuadro de mando contable (Pérdidas y Ganancias, Resúmenes).
     */
    public function dashboard()
    {
        $year = $_GET['anio'] ?? date('Y');
        $accountingModel = $this->model('Accounting');

        $report = $accountingModel->getProfitLoss($year);

        $data = [
            'report' => $report,
            'year' => $year,
            'pageTitle' => 'Cuadro de Mando Contable - Velion'
        ];

        $this->view('accounting/dashboard', $data);
    }

    /**
     * Muestra y gestiona el listado de gastos (facturas recibidas).
     */
    public function expenses()
    {
        $gastoModel = $this->model('Gasto');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nif_proveedor'    => $_POST['nif_proveedor'],
                'nombre_proveedor' => $_POST['nombre_proveedor'],
                'numero_factura'   => $_POST['numero_factura'],
                'fecha_emision'    => $_POST['fecha_emision'],
                'concepto'         => $_POST['concepto'],
                'base_imponible'   => (float)$_POST['base_imponible'],
                'tipo_iva'         => (float)$_POST['tipo_iva'],
                'retencion_irpf'   => (float)($_POST['retencion_irpf'] ?? 0.00),
                'categoria'        => $_POST['categoria']
            ];

            if ($gastoModel->save($data)) {
                header('Location: ' . PROJECT_ROOT . '/contabilidad/gastos');
                $this->exitApp();
            }
        }

        $filters = [
            'categoria'   => $_GET['categoria'] ?? null,
            'fecha_desde' => $_GET['fecha_desde'] ?? null,
            'fecha_hasta' => $_GET['fecha_hasta'] ?? null,
            'q'           => $_GET['q'] ?? null
        ];

        $data = [
            'gastos' => $gastoModel->getAll($filters),
            'filters' => $filters,
            'pageTitle' => 'Libro Registro de Gastos - Velion'
        ];

        $this->view('accounting/expenses_list', $data);
    }

    /**
     * Elimina un gasto.
     */
    public function deleteExpense()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['gasto_id'] ?? null;
            if ($id) {
                $gastoModel = $this->model('Gasto');
                $gastoModel->delete($id);
            }
        }
        header('Location: ' . PROJECT_ROOT . '/contabilidad/gastos');
        $this->exitApp();
    }

    /**
     * Muestra la simulación de modelos fiscales españoles (303, 130, 111).
     */
    public function taxes()
    {
        $quarter = $_GET['trimestre'] ?? $this->getCurrentQuarter();
        $year = $_GET['anio'] ?? date('Y');

        $accountingModel = $this->model('Accounting');

        $modelo303 = $accountingModel->getModelo303($quarter, $year);
        $modelo130 = $accountingModel->getModelo130($quarter, $year);
        $modelo111 = $accountingModel->getModelo111($quarter, $year);

        $data = [
            'quarter' => $quarter,
            'year' => $year,
            'modelo303' => $modelo303,
            'modelo130' => $modelo130,
            'modelo111' => $modelo111,
            'pageTitle' => 'Modelos Impositivos AEAT - Velion'
        ];

        $this->view('accounting/tax_models', $data);
    }

    /**
     * Exporta el Libro Registro de Facturas Emitidas en CSV.
     */
    public function exportLibroEmitidas()
    {
        $year = $_GET['anio'] ?? date('Y');
        $invoiceModel = $this->model('Invoice');
        
        // Obtenemos todas las facturas del año
        $facturas = $invoiceModel->getAll();
        // Filtrar por año de emisión
        $facturas = array_filter($facturas, function($f) use ($year) {
            return date('Y', strtotime($f['fecha_emision'])) == $year;
        });

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Libro_Registro_Facturas_Emitidas_' . $year . '.csv');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM para Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Cabecera oficial española
        fputcsv($output, [
            'Número Factura', 
            'Fecha Expedición', 
            'NIF/DNI Destinatario', 
            'Nombre/Razón Social Destinatario', 
            'Base Imponible (€)', 
            'Tipo IVA (%)', 
            'Cuota IVA (€)', 
            'Total Factura (€)',
            'Identificador Huella (Verifactu)'
        ], ';');

        foreach ($facturas as $f) {
            fputcsv($output, [
                $f['serie'] . '-' . str_pad($f['numero'], 6, '0', STR_PAD_LEFT),
                date('d/m/Y', strtotime($f['fecha_emision'])),
                $f['paciente_id'],
                $f['nombre'] . ' ' . $f['apellidos'],
                number_format($f['precio'], 2, ',', ''),
                number_format($f['impuesto'], 0, ',', ''),
                number_format($f['cuota_iva'], 2, ',', ''),
                number_format($f['total'], 2, ',', ''),
                $f['huella']
            ], ';');
        }
        fclose($output);
        $this->exitApp();
    }

    /**
     * Exporta el Libro Registro de Facturas Recibidas (Gastos) en CSV.
     */
    public function exportLibroRecibidas()
    {
        $year = $_GET['anio'] ?? date('Y');
        $gastoModel = $this->model('Gasto');
        
        $gastos = $gastoModel->getAll();
        $gastos = array_filter($gastos, function($g) use ($year) {
            return date('Y', strtotime($g['fecha_emision'])) == $year;
        });

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Libro_Registro_Facturas_Recibidas_' . $year . '.csv');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM para Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, [
            'ID Gasto',
            'Número Factura Proveedor',
            'Fecha Emisión',
            'NIF Proveedor',
            'Nombre/Razón Social Proveedor',
            'Concepto',
            'Base Imponible (€)',
            'Tipo IVA (%)',
            'Cuota IVA (€)',
            'Retención IRPF (%)',
            'Cuota Retención IRPF (€)',
            'Total Gasto (€)',
            'Categoría'
        ], ';');

        foreach ($gastos as $g) {
            fputcsv($output, [
                $g['gasto_id'],
                $g['numero_factura'],
                date('d/m/Y', strtotime($g['fecha_emision'])),
                $g['nif_proveedor'],
                $g['nombre_proveedor'],
                $g['concepto'],
                number_format($g['base_imponible'], 2, ',', ''),
                number_format($g['tipo_iva'], 2, ',', ''),
                number_format($g['cuota_iva'], 2, ',', ''),
                number_format($g['retencion_irpf'], 2, ',', ''),
                number_format($g['cuota_irpf'], 2, ',', ''),
                number_format($g['total'], 2, ',', ''),
                $g['categoria']
            ], ';');
        }
        fclose($output);
        $this->exitApp();
    }

    /**
     * Procesa la subida automática de facturas mediante OCR/IA simulado.
     */
    public function autoImport()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['invoice_file']['name'])) {
            $filename = $_FILES['invoice_file']['name'];
            $gastoModel = $this->model('Gasto');
            
            if ($gastoModel->autoParseInvoice($filename)) {
                $_SESSION['flash_success'] = "Factura '{$filename}' leída y procesada por IA con éxito.";
            } else {
                $_SESSION['flash_error'] = "No se pudo leer la factura '{$filename}'.";
            }
        }
        header('Location: ' . PROJECT_ROOT . '/contabilidad');
        $this->exitApp();
    }

    /**
     * Simula la descarga y conciliación automática de transacciones bancarias.
     */
    public function importBankFeed()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Instanciar el modelo de feed bancario y pasarle el modelo de gasto
            $gastoModel = $this->model('Gasto');
            $bankModel = new \App\Models\BankFeed($gastoModel);
            
            $results = $bankModel->importMockBankFeed();
            
            $createdCount = count(array_filter($results, function($r) { return $r['status'] === 'created'; }));
            $_SESSION['flash_success'] = "Sincronización bancaria finalizada: {$createdCount} nuevos gastos deducibles auto-conciliados.";
        }
        header('Location: ' . PROJECT_ROOT . '/contabilidad');
        $this->exitApp();
    }

    /**
     * Endpoint Cron programado para generar el informe y enviar un aviso de impuestos listos.
     */
    public function runQuarterlyCron()
    {
        $quarter = $this->getCurrentQuarter();
        $year = date('Y');
        
        $accountingModel = $this->model('Accounting');
        $modelo303 = $accountingModel->getModelo303($quarter, $year);
        $modelo130 = $accountingModel->getModelo130($quarter, $year);
        $modelo111 = $accountingModel->getModelo111($quarter, $year);

        // En un caso real, aquí se usaría PHPMailer para mandar un correo al administrador
        $subject = "Velion - Liquidación Trimestral Automatizada T{$quarter}/{$year}";
        $body = "Los borradores fiscales están listos para su presentación telemática:\n" .
                "- Modelo 303 (IVA): " . number_format($modelo303['resultado'], 2) . " €\n" .
                "- Modelo 130 (IRPF): " . number_format($modelo130['cuota_ingresar'], 2) . " €\n" .
                "- Modelo 111 (Retenciones): " . number_format($modelo111['total_retenciones'], 2) . " €\n" .
                "Acceda a su panel para exportar los libros oficiales.";

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'periodo' => "T{$quarter}/{$year}",
            'email_simulado' => [
                'asunto' => $subject,
                'cuerpo' => $body
            ]
        ], JSON_PRETTY_PRINT);
        $this->exitApp();
    }

    private function getCurrentQuarter()
    {
        $month = date('n');
        return ceil($month / 3);
    }
}
