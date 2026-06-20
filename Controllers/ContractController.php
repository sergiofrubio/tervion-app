<?php
namespace App\Controllers;
use App\Core\Controller;

class ContractController extends Controller
{
    public function __construct()
    {
        // Solo administradores pueden gestionar contratos
        $rol = $_SESSION['rol'] ?? '';
        if ($rol !== 'Administrador') {
            header('Location: ' . PROJECT_ROOT . '/inicio');
            $this->exitApp();
        }
    }

    /**
     * Muestra la lista de todos los contratos laborales existentes.
     *
     * @return void
     */
    public function list()
    {
        $contractModel = $this->model('Contract');
        $data = ['contratos' => $contractModel->getAllContracts()];
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
    public function create()
    {
        $contractModel = $this->model('Contract');
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
                'grupo_cotizacion' => !empty($_POST['grupo_cotizacion']) ? (int)$_POST['grupo_cotizacion'] : 1
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

            if ($contractModel->saveContract($data)) {
                header('Location: ' . PROJECT_ROOT . '/nominas/contratos');
                $this->exitApp();
            }
        } else {
            $this->view('payroll/contract_form');
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
    public function edit()
    {
        $contractModel = $this->model('Contract');
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
            $contract = $contractModel->getContract($id);
            $contractModel->saveEmployee([
                'usuario_id' => $contract['usuario_id'],
                'nss' => $_POST['nss'],
                'iban' => $_POST['iban'],
                'grupo_cotizacion' => $_POST['grupo_cotizacion'] ?? 1
            ]);

            if ($contractModel->saveContract($data)) {
                header('Location: ' . PROJECT_ROOT . '/nominas/contratos');
                $this->exitApp();
            }
        } else {
            $contract = $contractModel->getContract($id);
            $employee = $contractModel->getEmployeeData($contract['usuario_id']);
            $data = [
                'contrato' => $contract,
                'empleado' => $employee
            ];
            $this->view('payroll/contract_form', $data);
        }
    }
}
