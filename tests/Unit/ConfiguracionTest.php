<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Configuracion;
use PDO;
use PDOStatement;

class ConfiguracionTest extends TestCase
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

        $configModel = new Configuracion($this->dbMock);
        $result = $configModel->getHorariosFisios();

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

        $configModel = new Configuracion($this->dbMock);
        $result = $configModel->saveHorario($data);

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

        $configModel = new Configuracion($this->dbMock);
        $result = $configModel->saveEspecialidad($descripcion);

        $this->assertTrue($result);
    }

    public function testGetClinica()
    {
        $clinicaData = ['id_clinica' => 1, 'nombre_comercial' => 'Velion Clinica'];

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

        $configModel = new Configuracion($this->dbMock);
        $result = $configModel->getClinica();

        $this->assertEquals($clinicaData, $result);
    }
}
