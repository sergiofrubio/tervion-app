<?php
namespace App\Controllers;
use App\Core\Controller;
use Fpdf\Fpdf;

class PayrollController extends Controller
{
    private $ss_trabajador_rate = 0.065; // 6.5% aprox (Contingencias, Desempleo, FP, MEI 2026)
    private $ss_empresa_rate = 0.33;    // 33% aprox

    /**
     * Muestra la lista de nóminas filtradas por mes y año.
     *
     * @return void
     */
    public function list()
    {
        $payrollModel = $this->model('Payroll');
        $mes = $_GET['mes'] ?? date('n');
        $anio = $_GET['anio'] ?? date('Y');
        
        $data = [
            'nominas' => $payrollModel->getAllPayrolls(['mes' => $mes, 'anio' => $anio]),
            'mes' => $mes,
            'anio' => $anio
        ];
        $this->view('payroll/list', $data);
    }

    /**
     * Muestra la lista de todos los contratos laborales existentes.
     *
     * @return void
     */
    public function listContracts()
    {
        $payrollModel = $this->model('Payroll');
        $data = ['contratos' => $payrollModel->getAllContracts()];
        $this->view('payroll/contracts_list', $data);
    }

    /**
     * Crea un nuevo contrato de trabajo para un empleado.
     *
     * Si la petición es POST, guarda los datos del contrato y la información del empleado en la base de datos.
     * Si es GET, muestra el formulario de creación de contratos con los usuarios disponibles.
     *
     * @return void
     */
    public function createContract()
    {
        $payrollModel = $this->model('Payroll');
        $userModel = $this->model('User');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = htmlspecialchars($_POST['usuario_id'] ?? '', ENT_QUOTES, 'UTF-8');
            
            $workerData = [
                'usuario_id' => $usuario_id,
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
                'rol' => $_POST['rol'] ?? 'Fisioterapeuta',
                'genero' => $_POST['genero'] ?? 'Otro',
                'nss' => htmlspecialchars($_POST['nss'] ?? '', ENT_QUOTES, 'UTF-8'),
                'iban' => htmlspecialchars($_POST['iban'] ?? '', ENT_QUOTES, 'UTF-8'),
                'grupo_cotizacion' => !empty($_POST['grupo_cotizacion']) ? (int)$_POST['grupo_cotizacion'] : 1,
                'especialidad' => !empty($_POST['especialidad']) ? (int)$_POST['especialidad'] : null
            ];

            if (!$userModel->save($workerData)) {
                die("Error al registrar el nuevo trabajador.");
            }

            $data = [
                'usuario_id' => $usuario_id,
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
                'tipo_contrato' => $_POST['tipo_contrato'],
                'salario_base_mensual' => $_POST['salario_base_mensual'],
                'complementos_mensuales' => $_POST['complementos_mensuales'] ?? 0,
                'pagas_extra' => $_POST['pagas_extra'] ?? 2,
                'irpf_porcentaje' => $_POST['irpf_porcentaje'] ?? 15,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            if ($payrollModel->saveContract($data)) {
                header('Location: ' . PROJECT_ROOT . '/nominas/contratos');
                $this->exitApp();
            }
        } else {
            $data = [
                'especialidades' => $userModel->getSpecialties()
            ];
            $this->view('payroll/contract_form', $data);
        }
    }

    /**
     * Edita un contrato de trabajo existente.
     *
     * Si la petición es POST, actualiza los datos del contrato y del empleado en la base de datos.
     * Si es GET, muestra el formulario de edición cargado con la información actual del contrato.
     *
     * @return void
     */
    public function editContract()
    {
        $payrollModel = $this->model('Payroll');
        $id = $_GET['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['contrato_id'];
            $data = [
                'contrato_id' => $id,
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
                'tipo_contrato' => $_POST['tipo_contrato'],
                'salario_base_mensual' => $_POST['salario_base_mensual'],
                'complementos_mensuales' => $_POST['complementos_mensuales'] ?? 0,
                'pagas_extra' => $_POST['pagas_extra'] ?? 2,
                'irpf_porcentaje' => $_POST['irpf_porcentaje'] ?? 15,
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];

            // Actualizar datos de empleado
            $contract = $payrollModel->getContract($id);
            $payrollModel->saveEmployee([
                'usuario_id' => $contract['usuario_id'],
                'nss' => $_POST['nss'],
                'iban' => $_POST['iban'],
                'grupo_cotizacion' => $_POST['grupo_cotizacion'] ?? 1
            ]);

            if ($payrollModel->saveContract($data)) {
                header('Location: ' . PROJECT_ROOT . '/nominas/contratos');
                $this->exitApp();
            }
        } else {
            $contract = $payrollModel->getContract($id);
            $employee = $payrollModel->getEmployeeData($contract['usuario_id']);
            $data = [
                'contrato' => $contract,
                'empleado' => $employee
            ];
            $this->view('payroll/contract_form', $data);
        }
    }

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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mes = $_POST['mes'];
            $anio = $_POST['anio'];
            $contrato_id = $_POST['contrato_id'];
            
            $contract = $payrollModel->getContract($contrato_id);
            
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
                'contratos' => $payrollModel->getAllContracts()
            ];
            $this->view('payroll/generate', $data);
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
        $this->view('payroll/detail', $data);
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

        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        
        // Cabecera
        $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'RECIBO INDIVIDUAL DE SALARIOS (NÓMINA)'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Periodo: ' . $nomina['mes'] . '/' . $nomina['anio'], 0, 1, 'C');
        $pdf->Ln(10);

        // Datos Empresa (Podríamos traerlos de la configuración de la clínica)
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 7, 'EMPRESA', 1, 0);
        $pdf->Cell(95, 7, 'TRABAJADOR', 1, 1);
        $pdf->SetFont('Arial', '', 9);
        
        $y_start = $pdf->GetY();
        $pdf->MultiCell(95, 5, "Velion Physiotherapy Clinic\nCIF: B12345678\nDirección: Calle Falsa 123\nCiudad: Madrid", 1);
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
        
        $pdf->Output('I', 'Nomina_' . $nomina['nombre'] . '_' . $nomina['mes'] . '_' . $nomina['anio'] . '.pdf');
    }
}
