<?php

namespace Tests\Unit;

use App\Controllers\InvoiceController;
use App\Models\Invoice;

class InvoiceControllerTest extends ControllerTestCase
{
    public function testListAsPatient()
    {
        $_SESSION['rol'] = 'Paciente';
        $_SESSION['usuario_id'] = 'U123';

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByPaciente'])
            ->getMock();
        $invoiceMock->method('getByPaciente')->with('U123')->willReturn([]);

        $controller = $this->getControllerMock(InvoiceController::class);
        $controller->method('model')->with('Invoice')->willReturn($invoiceMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient-view/invoice/list', ['facturas' => [], 'pageTitle' => 'Mis Facturas - Velion']);

        $controller->list();
    }

    public function testListAsAdmin()
    {
        $_SESSION['rol'] = 'Administrador';
        $_SESSION['usuario_id'] = 'U1';

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAll', 'getPacientes'])
            ->getMock();
        $invoiceMock->method('getAll')->willReturn([]);
        $invoiceMock->method('getPacientes')->willReturn([]);

        $controller = $this->getControllerMock(InvoiceController::class);
        $controller->method('model')->with('Invoice')->willReturn($invoiceMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('invoice/list', $this->callback(function($data) {
                return isset($data['facturas']) && is_array($data['facturas']);
            }));

        $controller->list();
    }

    public function testCreateGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getPacientes'])
            ->getMock();
        $invoiceMock->method('getPacientes')->willReturn([]);

        $controller = $this->getControllerMock(InvoiceController::class);
        $controller->method('model')->with('Invoice')->willReturn($invoiceMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('invoice/create', ['pacientes' => []]);

        $controller->create();
    }

    public function testCreatePostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['paciente_id'] = 'U123';
        $_POST['fecha_emision'] = '2026-06-01';
        $_POST['estado'] = 'Emitida';
        $_POST['precio'] = 100;
        $_POST['impuesto'] = 21;

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();
        $invoiceMock->method('save')->willReturn(true);

        $controller = $this->getControllerMock(InvoiceController::class);
        $controller->method('model')->with('Invoice')->willReturn($invoiceMock);

        $this->expectException(TestExitException::class);
        $controller->create();
    }

    public function testEditPostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['factura_id'] = 5;
        $_POST['estado'] = 'Pagada';

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['updateStatus'])
            ->getMock();
        $invoiceMock->method('updateStatus')->with(5, 'Pagada', $this->anything())->willReturn(true);

        $controller = $this->getControllerMock(InvoiceController::class);
        $controller->method('model')->with('Invoice')->willReturn($invoiceMock);

        $this->expectException(TestExitException::class);
        $controller->edit();
    }

    public function testEditGetRedirectIfNoId()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_GET['id']);

        $controller = $this->getControllerMock(InvoiceController::class);

        $this->expectException(TestExitException::class);
        $controller->edit();
    }

    public function testEditGetSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['id'] = 5;

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById', 'getPacientes'])
            ->getMock();
        $invoiceMock->method('getById')->with(5)->willReturn(['id' => 5]);
        $invoiceMock->method('getPacientes')->willReturn([]);

        $controller = $this->getControllerMock(InvoiceController::class);
        $controller->method('model')->with('Invoice')->willReturn($invoiceMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('invoice/edit', [
                'factura' => ['id' => 5],
                'pacientes' => []
            ]);

        $controller->edit();
    }

    public function testPdfRedirectIfNoId()
    {
        unset($_GET['id']);

        $controller = $this->getControllerMock(InvoiceController::class);

        $this->expectException(TestExitException::class);
        $controller->pdf();
    }

    public function testPdfNotFound()
    {
        $_GET['id'] = 999;

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById', 'getClinica'])
            ->getMock();
        $invoiceMock->method('getById')->with(999)->willReturn(null);
        $invoiceMock->method('getClinica')->willReturn([]);

        $controller = $this->getControllerMock(InvoiceController::class);
        $controller->method('model')->with('Invoice')->willReturn($invoiceMock);

        ob_start();
        $controller->pdf();
        $output = ob_get_clean();

        $this->assertStringContainsString('Factura no encontrada', $output);
    }

    public function testPdfSuccess()
    {
        $_GET['id'] = 5;

        $factura = [
            'id' => 5,
            'serie' => 'A',
            'numero' => 12,
            'fecha_emision' => '2026-06-01',
            'estado' => 'Pagada',
            'total' => 121.00,
            'precio' => 100.00,
            'impuesto' => 21.00,
            'cuota_iva' => 21.00,
            'descripcion' => 'Sessio',
            'nombre' => 'John',
            'apellidos' => 'Doe',
            'paciente_id' => 'U123',
            'direccion' => 'Calle 1',
            'cp' => '28001',
            'municipio' => 'Madrid',
            'tipo_factura' => 'F1',
            'huella' => 'hashabc',
            'huella_anterior' => 'hashprev'
        ];

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById', 'getClinica'])
            ->getMock();
        $invoiceMock->method('getById')->with(5)->willReturn($factura);
        $invoiceMock->method('getClinica')->willReturn([
            'nombre_comercial' => 'VELION CLINIC',
            'razon_social' => 'VELION S.L.',
            'direccion_calle' => 'Calle 1',
            'codigo_postal' => '28001',
            'ciudad' => 'Madrid',
            'telefono_contacto' => '912345678'
        ]);

        $controller = $this->getControllerMock(InvoiceController::class);
        $controller->method('model')->with('Invoice')->willReturn($invoiceMock);

        // We wrap it in try-finally because Fpdf::Output() will throw TestExitException
        // (as it calls exitApp internally if we use $this->exitApp() but wait!
        // In the controller, the last statement is $pdf->Output(...); which calls exit internally.
        // Wait, does Fpdf::Output exit? No, but pdf() calls $pdf->Output('I', ...);
        // During tests, since Output writes to stdout, we capture it.
        ob_start();
        try {
            $controller->pdf();
        } finally {
            ob_end_clean();
        }
    }
}
