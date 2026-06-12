<?php

namespace Tests\Unit;

use App\Controllers\AccountingController;
use App\Models\Accounting;
use App\Models\Gasto;
use App\Models\Invoice;

class AccountingControllerTest extends ControllerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION['usuario_id'] = 'U1';
        $_SESSION['rol'] = 'Administrador';
    }

    public function testDashboard()
    {
        $_GET['anio'] = '2026';

        $accountingMock = $this->getMockBuilder(Accounting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getProfitLoss'])
            ->getMock();
        $accountingMock->method('getProfitLoss')->with('2026')->willReturn([]);

        $controller = $this->getControllerMock(AccountingController::class);
        $controller->method('model')->with('Accounting')->willReturn($accountingMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('accounting/dashboard', $this->callback(function($data) {
                return $data['year'] === '2026';
            }));

        $controller->dashboard();
    }

    public function testExpensesGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $gastoMock = $this->getMockBuilder(Gasto::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAll'])
            ->getMock();
        $gastoMock->method('getAll')->willReturn([]);

        $controller = $this->getControllerMock(AccountingController::class);
        $controller->method('model')->with('Gasto')->willReturn($gastoMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('accounting/expenses_list', $this->callback(function($data) {
                return is_array($data['gastos']);
            }));

        $controller->expenses();
    }

    public function testExpensesPostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['nif_proveedor'] = 'A123';
        $_POST['nombre_proveedor'] = 'Supplies';
        $_POST['numero_factura'] = 'F-1';
        $_POST['fecha_emision'] = '2026-06-01';
        $_POST['concepto'] = 'Paper';
        $_POST['base_imponible'] = 100;
        $_POST['tipo_iva'] = 21;
        $_POST['categoria'] = 'Office';

        $gastoMock = $this->getMockBuilder(Gasto::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();
        $gastoMock->method('save')->willReturn(true);

        $controller = $this->getControllerMock(AccountingController::class);
        $controller->method('model')->with('Gasto')->willReturn($gastoMock);

        $this->expectException(TestExitException::class);
        $controller->expenses();
    }

    public function testDeleteExpenseSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['gasto_id'] = 5;

        $gastoMock = $this->getMockBuilder(Gasto::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();
        $gastoMock->expects($this->once())->method('delete')->with(5)->willReturn(true);

        $controller = $this->getControllerMock(AccountingController::class);
        $controller->method('model')->with('Gasto')->willReturn($gastoMock);

        $this->expectException(TestExitException::class);
        $controller->deleteExpense();
    }

    public function testTaxes()
    {
        $_GET['trimestre'] = 2;
        $_GET['anio'] = '2026';

        $accountingMock = $this->getMockBuilder(Accounting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getModelo303', 'getModelo130', 'getModelo111'])
            ->getMock();
        
        $accountingMock->method('getModelo303')->willReturn([]);
        $accountingMock->method('getModelo130')->willReturn([]);
        $accountingMock->method('getModelo111')->willReturn([]);

        $controller = $this->getControllerMock(AccountingController::class);
        $controller->method('model')->with('Accounting')->willReturn($accountingMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('accounting/tax_models');

        $controller->taxes();
    }

    public function testExportLibroEmitidas()
    {
        $_GET['anio'] = '2026';

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAll'])
            ->getMock();
        
        $invoiceMock->method('getAll')->willReturn([
            [
                'serie' => 'F', 'numero' => 1, 'fecha_emision' => '2026-06-10', 'paciente_id' => 'P1',
                'nombre' => 'John', 'apellidos' => 'Doe', 'precio' => 100, 'impuesto' => 21,
                'cuota_iva' => 21, 'total' => 121, 'huella' => 'abc'
            ]
        ]);

        $controller = $this->getControllerMock(AccountingController::class);
        $controller->method('model')->with('Invoice')->willReturn($invoiceMock);

        $this->expectException(TestExitException::class);
        ob_start();
        try {
            $controller->exportLibroEmitidas();
        } finally {
            ob_end_clean();
        }
    }
}
