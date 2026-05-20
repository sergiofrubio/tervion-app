<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Payroll;
use PDO;
use PDOStatement;

class PayrollTest extends TestCase
{
    private $dbMock;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->dbMock = $this->createMock(PDO::class);
    }

    public function testGetEmployeeData()
    {
        $empData = ['usuario_id' => 'E1', 'nombre' => 'John', 'nss' => '12345'];

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':usuario_id', 'E1');

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($empData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('FROM empleados e'))
            ->willReturn($this->stmtMock);

        $payrollModel = new Payroll($this->dbMock);
        $result = $payrollModel->getEmployeeData('E1');

        $this->assertEquals($empData, $result);
    }

    public function testSaveEmployee()
    {
        $data = [
            'usuario_id' => 'E1',
            'nss' => '12345',
            'iban' => 'ES12345',
            'grupo_cotizacion' => '1'
        ];

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with([
                ':usuario_id' => 'E1',
                ':nss' => '12345',
                ':iban' => 'ES12345',
                ':grupo_cotizacion' => '1'
            ])
            ->willReturn(true);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO empleados'))
            ->willReturn($this->stmtMock);

        $payrollModel = new Payroll($this->dbMock);
        $result = $payrollModel->saveEmployee($data);

        $this->assertTrue($result);
    }

    public function testGetActiveContract()
    {
        $contractData = ['contrato_id' => 5, 'usuario_id' => 'E1', 'activo' => 1];

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':usuario_id', 'E1');

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($contractData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('FROM contratos WHERE usuario_id = :usuario_id AND activo = 1'))
            ->willReturn($this->stmtMock);

        $payrollModel = new Payroll($this->dbMock);
        $result = $payrollModel->getActiveContract('E1');

        $this->assertEquals($contractData, $result);
    }

    public function testCreatePayroll()
    {
        $payrollData = [
            'contrato_id' => 5,
            'mes' => 5,
            'anio' => 2026,
            'fecha_emision' => '2026-05-31',
            'devengos_base' => 1500.00,
            'devengos_complementos' => 200.00,
            'devengos_total_bruto' => 1700.00,
            'deduccion_seguridad_social_trabajador' => 80.00,
            'deduccion_irpf' => 170.00,
            'deducciones_total' => 250.00,
            'liquido_a_percibir' => 1450.00,
            'coste_seguridad_social_empresa' => 500.00,
            'estado' => 'Borrador'
        ];

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($payrollData)
            ->willReturn(true);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO nominas'))
            ->willReturn($this->stmtMock);

        $payrollModel = new Payroll($this->dbMock);
        $result = $payrollModel->createPayroll($payrollData);

        $this->assertTrue($result);
    }
}
