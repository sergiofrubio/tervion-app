<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\MedicalHistory;
use PDO;
use PDOStatement;

class MedicalHistoryTest extends TestCase
{
    private $dbMock;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->dbMock = $this->createMock(PDO::class);
    }

    public function testGetById()
    {
        $historyData = [
            'historial_id' => 1,
            'paciente_id' => 'P123',
            'motivo_consulta' => 'Dolor de espalda',
            'diagnostico' => 'Contractura'
        ];

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1, PDO::PARAM_INT);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($historyData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT hm.*'))
            ->willReturn($this->stmtMock);

        $historyModel = new MedicalHistory($this->dbMock);
        $result = $historyModel->getById(1);

        $this->assertEquals($historyData, $result);
    }

    public function testSave()
    {
        $data = [
            'paciente_id' => 'P123',
            'fisioterapeuta_id' => 'F456',
            'fecha_consulta' => '2026-05-20',
            'motivo_consulta' => 'Dolor muscular',
            'diagnostico' => 'Sobrecarga',
            'tratamiento' => 'Masaje descontracturante',
            'observaciones' => 'Reposo 24h',
            'creado_por' => 'Admin'
        ];

        $this->stmtMock->expects($this->exactly(8))
            ->method('bindParam');

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO historiales_medicos'))
            ->willReturn($this->stmtMock);

        $historyModel = new MedicalHistory($this->dbMock);
        $result = $historyModel->save($data);

        $this->assertTrue($result);
    }

    public function testGetByPaciente()
    {
        $paciente_id = 'P123';
        $records = [
            ['historial_id' => 1, 'paciente_id' => $paciente_id]
        ];

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':paciente_id', $paciente_id);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($records);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('WHERE hm.paciente_id = :paciente_id'))
            ->willReturn($this->stmtMock);

        $historyModel = new MedicalHistory($this->dbMock);
        $result = $historyModel->getByPaciente($paciente_id);

        $this->assertEquals($records, $result);
    }
}
