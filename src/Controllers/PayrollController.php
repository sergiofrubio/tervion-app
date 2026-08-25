<?php

namespace App\Controllers;

use App\Core\Controller;
use Fpdf\Fpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PayrollController extends Controller
{
    private $ss_trabajador_rate = 0.065; // 6.5% aprox (Contingencias, Desempleo, FP, MEI 2026)
    private $ss_empresa_rate = 0.33;    // 33% aprox


    /**
     * Genera una nueva nómina para un contrato y periodo determinados.
     *
     * Si la petición es POST, calcula los devengos, deducciones e importe líquido, guarda la nómina y redirige.
     * Si es GET, muestra la vista para seleccionar el contrato y mes/año a generar.
     *
     * @return void
     */
    public function generate()
    {
        $payrollModel = $this->model('Payroll');
        $contractModel = $this->model('Contract');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mes = $_POST['mes'];
            $anio = $_POST['anio'];
            $contrato_id = $_POST['contrato_id'];

            $contract = $contractModel->getContract($contrato_id);

            // Cálculos
            $bruto_mensual = $contract['salario_base_mensual'] + $contract['complementos_mensuales'];

            // Deducciones
            $deduccion_ss = round($bruto_mensual * $this->ss_trabajador_rate, 2);
            $deduccion_irpf = round($bruto_mensual * ($contract['irpf_porcentaje'] / 100), 2);
            $total_deducciones = $deduccion_ss + $deduccion_irpf;

            $liquido = $bruto_mensual - $total_deducciones;
            $coste_empresa_ss = round($bruto_mensual * $this->ss_empresa_rate, 2);

            $data = [
                'contrato_id' => $contrato_id,
                'mes' => $mes,
                'anio' => $anio,
                'fecha_emision' => date('Y-m-d'),
                'devengos_base' => $contract['salario_base_mensual'],
                'devengos_complementos' => $contract['complementos_mensuales'],
                'devengos_total_bruto' => $bruto_mensual,
                'deduccion_seguridad_social_trabajador' => $deduccion_ss,
                'deduccion_irpf' => $deduccion_irpf,
                'deducciones_total' => $total_deducciones,
                'liquido_a_percibir' => $liquido,
                'coste_seguridad_social_empresa' => $coste_empresa_ss,
                'estado' => 'Pendiente'
            ];

            if ($payrollModel->createPayroll($data)) {
                header('Location: ' . PROJECT_ROOT . '/nominas?mes=' . $mes . '&anio=' . $anio);
                $this->exitApp();
            }
        } else {
            $data = [
                'contratos' => $contractModel->getAllContracts()
            ];
            $this->view('therapist/payroll/generate', $data);
        }
    }

    /**
     * Muestra el detalle completo de una nómina generada.
     *
     * @return void
     */
    public function detail()
    {
        $payrollModel = $this->model('Payroll');
        $id = $_GET['id'] ?? null;
        $data = ['nomina' => $payrollModel->getPayroll($id)];
        $this->view('therapist/payroll/detail', $data);
    }

    /**
     * Genera y descarga un recibo individual de salarios (nómina) en formato PDF usando FPDF.
     *
     * @return void
     */
    public function pdf()
    {
        $payrollModel = $this->model('Payroll');
        $id = $_GET['id'] ?? null;
        $nomina = $payrollModel->getPayroll($id);

        if (!$nomina) {
            die("Nómina no encontrada");
        }

        // Limpiar buffer
        if (ob_get_length()) ob_end_clean();

        $pdf = $this->generatePayrollPDFContent($nomina);
        $pdf->Output('I', 'Nomina_' . $nomina['nombre'] . '_' . $nomina['mes'] . '_' . $nomina['anio'] . '.pdf');
    }

    /**
     * Genera un objeto FPDF para una nómina.
     *
     * @param array $nomina
     * @return Fpdf
     */
    private function generatePayrollPDFContent($nomina)
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Cabecera
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'RECIBO INDIVIDUAL DE SALARIOS (NÓMINA)'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Periodo: ' . $nomina['mes'] . '/' . $nomina['anio'], 0, 1, 'C');
        $pdf->Ln(10);

        // Datos Empresa
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 7, 'EMPRESA', 1, 0);
        $pdf->Cell(95, 7, 'TRABAJADOR', 1, 1);
        $pdf->SetFont('Arial', '', 9);

        $y_start = $pdf->GetY();
        $pdf->MultiCell(95, 5, "Tervion Physiotherapy Clinic\nCIF: B12345678\nDirección: Calle Falsa 123\nCiudad: Madrid", 1);
        $y_end_empresa = $pdf->GetY();

        $pdf->SetXY(105, $y_start);
        $pdf->MultiCell(95, 5, iconv('UTF-8', 'windows-1252', $nomina['nombre'] . " " . $nomina['apellidos'] . "\nDNI: " . $nomina['dni'] . "\nNSS: " . $nomina['nss'] . "\nGrupo Cotización: " . $nomina['grupo_cotizacion']), 1);
        $y_end_trabajador = $pdf->GetY();

        $pdf->SetY(max($y_end_empresa, $y_end_trabajador) + 10);

        // Conceptos
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(130, 7, 'CONCEPTOS', 1, 0, 'C');
        $pdf->Cell(30, 7, 'DEVENGOS', 1, 0, 'C');
        $pdf->Cell(30, 7, 'DEDUCCIONES', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(130, 7, 'Salario Base', 1);
        $pdf->Cell(30, 7, number_format($nomina['devengos_base'], 2) . ' €', 1, 0, 'R');
        $pdf->Cell(30, 7, '', 1, 1);

        $pdf->Cell(130, 7, 'Complementos', 1);
        $pdf->Cell(30, 7, number_format($nomina['devengos_complementos'], 2) . ' €', 1, 0, 'R');
        $pdf->Cell(30, 7, '', 1, 1);

        $pdf->Cell(130, 7, 'Seguridad Social Trabajador (' . ($this->ss_trabajador_rate * 100) . '%)', 1);
        $pdf->Cell(30, 7, '', 1, 0);
        $pdf->Cell(30, 7, number_format($nomina['deduccion_seguridad_social_trabajador'], 2) . ' €', 1, 1, 'R');

        $pdf->Cell(130, 7, 'IRPF (' . $nomina['irpf_porcentaje'] . '%)', 1);
        $pdf->Cell(30, 7, '', 1, 0);
        $pdf->Cell(30, 7, number_format($nomina['deduccion_irpf'], 2) . ' €', 1, 1, 'R');

        // Totales
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(130, 10, 'TOTALES', 1, 0, 'R');
        $pdf->Cell(30, 10, number_format($nomina['devengos_total_bruto'], 2) . ' €', 1, 0, 'R');
        $pdf->Cell(30, 10, number_format($nomina['deducciones_total'], 2) . ' €', 1, 1, 'R');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(160, 10, iconv('UTF-8', 'windows-1252', 'LÍQUIDO A PERCIBIR:'), 0, 0, 'R');
        $pdf->Cell(30, 10, number_format($nomina['liquido_a_percibir'], 2) . ' €', 1, 1, 'R');

        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Coste Seguridad Social Empresa: ' . number_format($nomina['coste_seguridad_social_empresa'], 2) . ' €'), 0, 1);

        return $pdf;
    }

    /**
     * Envía una nómina en formato PDF por correo electrónico al trabajador utilizando PHPMailer.
     *
     * @param string $emailDestinatario
     * @param string $nombreCompleto
     * @param int $mes
     * @param int $anio
     * @param string $pdfContent Contenido binario del PDF
     * @return bool
     */
    public function sendPayrollEmail($emailDestinatario, $nombreCompleto, $mes, $anio, $pdfContent)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor local Mailpit
            $mail->isSMTP();
            $mail->Host       = 'mailpit'; // Host del servicio dentro de la red Docker
            $mail->Port       = 1025;      // Puerto SMTP de Mailpit
            $mail->SMTPAuth   = false;     // Sin autenticación obligatoria para local
            $mail->SMTPAutoTLS = false;

            // Destinatarios
            $mail->setFrom('noreply@tervion.local', 'Tervion');
            $mail->addAddress($emailDestinatario);

            // Adjuntar PDF desde memoria
            $nombreMeses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
            $mesNombre = $nombreMeses[(int)$mes] ?? $mes;
            $filename = 'Nomina_' . $nombreCompleto . '_' . $mesNombre . '_' . $anio . '.pdf';
            $filename = str_replace(' ', '_', $filename);
            $mail->addStringAttachment($pdfContent, $filename);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = iconv('UTF-8', 'windows-1252', "Tervion - Tu Nómina de $mesNombre de $anio");
            $mail->Body    = "Hola $nombreCompleto,<br><br>Adjuntamos a este correo tu recibo de salarios correspondiente al periodo de $mesNombre de $anio.<br><br>Un saludo,<br>El equipo de Tervion.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar nómina a $emailDestinatario: " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Obtiene todos los contratos activos, genera sus nóminas para el mes/año indicados si no existen,
     * genera el PDF de cada una y las envía por correo al trabajador.
     *
     * @param int $mes
     * @param int $anio
     * @return array Resumen del proceso
     */
    public function processAllMonthlyPayrolls($mes, $anio)
    {
        $payrollModel = $this->model('Payroll');
        $contractModel = $this->model('Contract');
        $activeContracts = $contractModel->getActiveContracts();

        $results = [
            'total' => count($activeContracts),
            'generated' => 0,
            'sent' => 0,
            'errors' => []
        ];

        // Obtener nóminas ya existentes en el mes/año para evitar duplicados
        $existingPayrolls = $payrollModel->getAllPayrolls(['mes' => $mes, 'anio' => $anio]);
        $existingContractIds = array_column($existingPayrolls, 'contrato_id');

        foreach ($activeContracts as $contract) {
            $contrato_id = $contract['contrato_id'];
            $nombreCompleto = $contract['nombre'] . ' ' . $contract['apellidos'];
            $email = $contract['email'];

            if (empty($email)) {
                $results['errors'][] = "El trabajador $nombreCompleto no tiene correo electrónico configurado.";
                continue;
            }

            try {
                $nomina = null;
                // Si ya existe la nómina para este contrato y periodo, la recuperamos
                if (in_array($contrato_id, $existingContractIds)) {
                    foreach ($existingPayrolls as $p) {
                        if ($p['contrato_id'] == $contrato_id) {
                            $nomina = $payrollModel->getPayroll($p['nomina_id']);
                            break;
                        }
                    }
                } else {
                    // Si no existe, la generamos
                    $bruto_mensual = $contract['salario_base_mensual'] + $contract['complementos_mensuales'];

                    // Deducciones
                    $deduccion_ss = round($bruto_mensual * $this->ss_trabajador_rate, 2);
                    $deduccion_irpf = round($bruto_mensual * ($contract['irpf_porcentaje'] / 100), 2);
                    $total_deducciones = $deduccion_ss + $deduccion_irpf;

                    $liquido = $bruto_mensual - $total_deducciones;
                    $coste_empresa_ss = round($bruto_mensual * $this->ss_empresa_rate, 2);

                    $data = [
                        'contrato_id' => $contrato_id,
                        'mes' => $mes,
                        'anio' => $anio,
                        'fecha_emision' => date('Y-m-d'),
                        'devengos_base' => $contract['salario_base_mensual'],
                        'devengos_complementos' => $contract['complementos_mensuales'],
                        'devengos_total_bruto' => $bruto_mensual,
                        'deduccion_seguridad_social_trabajador' => $deduccion_ss,
                        'deduccion_irpf' => $deduccion_irpf,
                        'deducciones_total' => $total_deducciones,
                        'liquido_a_percibir' => $liquido,
                        'coste_seguridad_social_empresa' => $coste_empresa_ss,
                        'estado' => 'Pendiente'
                    ];

                    if ($payrollModel->createPayroll($data)) {
                        $results['generated']++;
                        // Buscar la nómina recién creada para tener toda la información formateada/completa
                        $latestPayrolls = $payrollModel->getAllPayrolls(['mes' => $mes, 'anio' => $anio]);
                        foreach ($latestPayrolls as $lp) {
                            if ($lp['contrato_id'] == $contrato_id) {
                                $nomina = $payrollModel->getPayroll($lp['nomina_id']);
                                break;
                            }
                        }
                    } else {
                        $results['errors'][] = "No se pudo insertar la nómina en base de datos para $nombreCompleto.";
                        continue;
                    }
                }

                if ($nomina) {
                    // Generar PDF
                    $pdf = $this->generatePayrollPDFContent($nomina);
                    $pdfContent = $pdf->Output('S');
                    // Enviar por correo
                    if ($this->sendPayrollEmail($email, $nombreCompleto, $mes, $anio, $pdfContent)) {
                        $results['sent']++;
                    } else {
                        $results['errors'][] = "Error al enviar el correo con la nómina a $nombreCompleto ($email).";
                    }
                } else {
                    $results['errors'][] = "No se pudo recuperar la nómina generada para $nombreCompleto.";
                }
            } catch (\Exception $e) {
                $results['errors'][] = "Excepción procesando nómina de $nombreCompleto: " . $e->getMessage();
            }
        }

        return $results;
    }
}
