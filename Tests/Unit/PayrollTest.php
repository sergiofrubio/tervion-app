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

    public function testCreatePayroll()
    {
        $payrollData = [
            'contrato_id' => 5,
            'mes' => 5,
            'anio' => 2026,
            'liquido_percepcion' => 1450.00,
            'bruto' => 1700.00,
            'deduccion_ss' => 80.00,
            'deduccion_irpf' => 170.00,
            'coste_empresa_ss' => 500.00,
            'pagada' => 0
        ];

        $this->stmtMock->expects($this->exactly(9))
            ->method('bindParam');

        $this->stmtMock->expects($this->once())
            ->method('execute')
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
