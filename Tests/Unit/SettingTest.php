<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Setting;
use PDO;
use PDOStatement;

class SettingTest extends TestCase
{
    private $dbMock;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->dbMock = $this->createMock(PDO::class);
    }

    public function testGetHorariosFisios()
    {
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['horario_id' => 1, 'fisioterapeuta_id' => 'F1']]);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('FROM horarios_terapeutas'))
            ->willReturn($this->stmtMock);

        $settingModel = new Setting($this->dbMock);
        $result = $settingModel->getHorariosFisios();

        $this->assertCount(1, $result);
    }

    public function testSaveHorario()
    {
        $data = [
            'fisioterapeuta_id' => 'F1',
            'dia_semana' => 'Lunes',
            'hora_inicio' => '09:00',
            'hora_fin' => '14:00'
        ];

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($data)
            ->willReturn(true);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO horarios_terapeutas'))
            ->willReturn($this->stmtMock);

        $settingModel = new Setting($this->dbMock);
        $result = $settingModel->saveHorario($data);

        $this->assertTrue($result);
    }

    public function testSaveEspecialidad()
    {
        $descripcion = 'Pediatría';

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':descripcion', $descripcion);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO especialidades'))
            ->willReturn($this->stmtMock);

        $settingModel = new Setting($this->dbMock);
        $result = $settingModel->saveEspecialidad($descripcion);

        $this->assertTrue($result);
    }

    public function testGetClinica()
    {
        $clinicaData = ['id_clinica' => 1, 'nombre_comercial' => 'Tervion Clinica'];

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($clinicaData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('FROM clinicas LIMIT 1'))
            ->willReturn($this->stmtMock);

        $settingModel = new Setting($this->dbMock);
        $result = $settingModel->getClinica();

        $this->assertEquals($clinicaData, $result);
    }
}
