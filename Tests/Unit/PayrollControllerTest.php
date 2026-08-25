<?php

namespace Tests\Unit;

use App\Controllers\PayrollController;
use App\Models\Payroll;
use App\Models\Contract;

class PayrollControllerTest extends ControllerTestCase
{
    public function testGenerateGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $contractMock = $this->getMockBuilder(Contract::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllContracts'])
            ->getMock();
        $contractMock->method('getAllContracts')->willReturn([['contrato_id' => 1]]);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->willReturnMap([
            ['Payroll', $this->createMock(Payroll::class)],
            ['Contract', $contractMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('therapist/payroll/generate', [
                'contratos' => [['contrato_id' => 1]]
            ]);

        $controller->generate();
    }

    public function testGeneratePostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'contrato_id' => 1,
            'mes' => 6,
            'anio' => 2026
        ];

        $contractMock = $this->getMockBuilder(Contract::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getContract'])
            ->getMock();
        $contractMock->method('getContract')->with(1)->willReturn([
            'contrato_id' => 1,
            'salario_base_mensual' => 2000,
            'complementos_mensuales' => 200,
            'irpf_porcentaje' => 15
        ]);

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createPayroll'])
            ->getMock();
        $payrollMock->method('createPayroll')->willReturn(true);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->willReturnMap([
            ['Contract', $contractMock],
            ['Payroll', $payrollMock]
        ]);

        $this->expectException(TestExitException::class);
        $controller->generate();
    }

    public function testDetail()
    {
        $_GET['id'] = 10;

        $payrollMock = $this->getMockBuilder(Payroll::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getPayroll'])
            ->getMock();
        $payrollMock->method('getPayroll')->with(10)->willReturn(['nomina_id' => 10, 'mes' => 6]);

        $controller = $this->getControllerMock(PayrollController::class);
        $controller->method('model')->with('Payroll')->willReturn($payrollMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('therapist/payroll/detail', ['nomina' => ['nomina_id' => 10, 'mes' => 6]]);

        $controller->detail();
    }

    public function testPdfSuccess()
    {
        $_GET['id'] = 8;
        $nomina = [
            'nomina_id' => 8,
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
