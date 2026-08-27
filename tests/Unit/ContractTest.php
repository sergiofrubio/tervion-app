<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Contract;
use PDO;
use PDOStatement;

class ContractTest extends TestCase
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

        $contractModel = new Contract($this->dbMock);
        $result = $contractModel->getEmployeeData('E1');

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

        $contractModel = new Contract($this->dbMock);
        $result = $contractModel->saveEmployee($data);

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

        $contractModel = new Contract($this->dbMock);
        $result = $contractModel->getActiveContract('E1');

        $this->assertEquals($contractData, $result);
    }
}
