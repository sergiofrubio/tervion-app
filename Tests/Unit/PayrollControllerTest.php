<?php

namespace Tests\Unit;

use App\Controllers\PayrollController;
use App\Models\Payroll;
use App\Models\User;

class PayrollControllerTest extends ControllerTestCase
{
    public function testList()
    {
        $_GET['mes'] = 6;
        $_GET['anio'] = 2026;

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllPayrolls'])
            ->getMock();
        $payrollMock->method('getAllPayrolls')->willReturn([]);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->with('Payroll')->willReturn($payrollMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('therapist/payroll/list', [
                'nominas' => [],
                'mes' => 6,
                'anio' => 2026
            ]);

        $controller->list();
    }

    public function testListContracts()
    {
        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllContracts'])
            ->getMock();
        $payrollMock->method('getAllContracts')->willReturn([]);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->with('Payroll')->willReturn($payrollMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('therapist/payroll/contracts_list', ['contratos' => []]);

        $controller->listContracts();
    }

    public function testCreateContractGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSpecialties'])
            ->getMock();
        $userMock->method('getSpecialties')->willReturn([]);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->willReturnMap([
            ['Payroll', $this->createMock(Payroll::class)],
            ['User', $userMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('therapist/payroll/contract_form', ['especialidades' => []]);

        $controller->createContract();
    }

    public function testCreateContractPostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = 'F123';
        $_POST['nombre'] = 'John';
        $_POST['apellidos'] = 'Doe';
        $_POST['email'] = 'john@example.com';
        $_POST['fecha_inicio'] = '2026-06-01';
        $_POST['tipo_contrato'] = 'Indefinido';
        $_POST['salario_base_mensual'] = 2000;

        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();
        $userMock->method('save')->willReturn(true);

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['saveContract'])
            ->getMock();
        $payrollMock->method('saveContract')->willReturn(true);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->willReturnMap([
            ['Payroll', $payrollMock],
            ['User', $userMock]
        ]);

        $this->expectException(TestExitException::class);
        $controller->createContract();
    }

    public function testEditContractGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['id'] = 5;

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getContract', 'getEmployeeData'])
            ->getMock();
        $payrollMock->method('getContract')->with(5)->willReturn(['contrato_id' => 5, 'usuario_id' => 'F123']);
        $payrollMock->method('getEmployeeData')->with('F123')->willReturn(['usuario_id' => 'F123']);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->with('Payroll')->willReturn($payrollMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('therapist/payroll/contract_form', [
                'contrato' => ['contrato_id' => 5, 'usuario_id' => 'F123'],
                'empleado' => ['usuario_id' => 'F123']
            ]);

        $controller->editContract();
    }

    public function testEditContractPostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['contrato_id'] = 5;
        $_POST['fecha_inicio'] = '2026-06-01';
        $_POST['tipo_contrato'] = 'Indefinido';
        $_POST['salario_base_mensual'] = 2200;
        $_POST['nss'] = '12345';
        $_POST['iban'] = 'ES999';

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getContract', 'saveEmployee', 'saveContract'])
            ->getMock();
        $payrollMock->method('getContract')->with(5)->willReturn(['usuario_id' => 'F123']);
        $payrollMock->expects($this->once())->method('saveEmployee');
        $payrollMock->method('saveContract')->willReturn(true);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->with('Payroll')->willReturn($payrollMock);

        $this->expectException(TestExitException::class);
        $controller->editContract();
    }

    public function testGenerateGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllContracts'])
            ->getMock();
        $payrollMock->method('getAllContracts')->willReturn([]);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->with('Payroll')->willReturn($payrollMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('therapist/payroll/generate', ['contratos' => []]);

        $controller->generate();
    }

    public function testGeneratePostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['mes'] = 6;
        $_POST['anio'] = 2026;
        $_POST['contrato_id'] = 5;

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getContract', 'createPayroll'])
            ->getMock();
        $payrollMock->method('getContract')->with(5)->willReturn([
            'salario_base_mensual' => 2000,
            'complementos_mensuales' => 100,
            'irpf_porcentaje' => 15
        ]);
        $payrollMock->method('createPayroll')->willReturn(true);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->with('Payroll')->willReturn($payrollMock);

        $this->expectException(TestExitException::class);
        $controller->generate();
    }

    public function testDetail()
    {
        $_GET['id'] = 8;

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getPayroll'])
            ->getMock();
        $payrollMock->method('getPayroll')->with(8)->willReturn(['id' => 8]);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->with('Payroll')->willReturn($payrollMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('therapist/payroll/detail', ['nomina' => ['id' => 8]]);

        $controller->detail();
    }

    public function testPdfSuccess()
    {
        $_GET['id'] = 8;
        $nomina = [
            'id' => 8,
            'mes' => 6,
            'anio' => 2026,
            'nombre' => 'John',
            'apellidos' => 'Doe',
            'dni' => '12345678A',
            'nss' => '1212',
            'grupo_cotizacion' => 1,
            'devengos_base' => 2000,
            'devengos_complementos' => 100,
            'deduccion_seguridad_social_trabajador' => 130,
            'deduccion_irpf' => 300,
            'devengos_total_bruto' => 2100,
            'deducciones_total' => 430,
            'irpf_porcentaje' => 15,
            'liquido_a_percibir' => 1670,
            'coste_seguridad_social_empresa' => 693
        ];

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getPayroll'])
            ->getMock();
        $payrollMock->method('getPayroll')->with(8)->willReturn($nomina);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->with('Payroll')->willReturn($payrollMock);

        ob_start();
        try {
            $controller->pdf();
        } finally {
            ob_end_clean();
        }
    }
}
