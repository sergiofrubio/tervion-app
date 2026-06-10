<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use PDO;
use PDOStatement;

class PatientTest extends TestCase
{
    private $dbMock;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->dbMock = $this->createMock(PDO::class);
    }

    public function testGetByPacienteId()
    {
        $patientData = [
            'usuario_id' => '12345678A',
            'nombre' => 'John',
            'apellidos' => 'Doe',
            'email' => 'john@example.com',
            'rol' => 'Paciente',
            'especialidad' => null
        ];

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':usuario_id', '12345678A');

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($patientData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT u.*'))
            ->willReturn($this->stmtMock);

        $userModel = new User($this->dbMock);
        $result = $userModel->getByusuario_id('12345678A');

        $this->assertEquals($patientData, $result);
    }

    public function testSavePatientSuccess()
    {
        $data = [
            'usuario_id' => '12345678A',
            'nombre' => 'John',
            'apellidos' => 'Doe',
            'telefono' => '123456789',
            'fecha_nacimiento' => '1990-01-01',
            'direccion' => 'Calle Falsa 123',
            'provincia' => 'Madrid',
            'municipio' => 'Madrid',
            'cp' => '28001',
            'email' => 'john@example.com',
            'pass' => 'hash',
            'genero' => 'M',
            'rol' => 'Paciente'
        ];

        $this->dbMock->expects($this->once())->method('beginTransaction');
        $this->dbMock->expects($this->once())->method('commit');

        // We expect two prepare statements: 
        // 1. Insert into usuarios
        // 2. Insert into pacientes
        $this->dbMock->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(true);

        $userModel = new User($this->dbMock);
        $result = $userModel->save($data);

        $this->assertTrue($result);
    }

    public function testSaveWorkerSuccess()
    {
        $data = [
            'usuario_id' => '87654321B',
            'nombre' => 'Jane',
            'apellidos' => 'Staff',
            'telefono' => '987654321',
            'fecha_nacimiento' => '1985-05-05',
            'direccion' => 'Calle Trabajo 456',
            'provincia' => 'Madrid',
            'municipio' => 'Madrid',
            'cp' => '28002',
            'email' => 'jane@example.com',
            'pass' => 'hash',
            'genero' => 'F',
            'rol' => 'Fisioterapeuta',
            'nss' => '123456789012',
            'iban' => 'ES1234567890123456789012',
            'grupo_cotizacion' => 2,
            'especialidad' => 1
        ];

        $this->dbMock->expects($this->once())->method('beginTransaction');
        $this->dbMock->expects($this->once())->method('commit');

        // We expect three prepare statements:
        // 1. Insert into usuarios
        // 2. Insert into empleados
        // 3. Insert into fisioterapeutas
        $this->dbMock->expects($this->exactly(3))
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(3))
            ->method('execute')
            ->willReturn(true);

        $userModel = new User($this->dbMock);
        $result = $userModel->save($data);

        $this->assertTrue($result);
    }

    public function testSaveFailsOnException()
    {
        $data = [
            'usuario_id' => '12345678A',
            'nombre' => 'John',
            'rol' => 'Paciente'
        ];

        $this->dbMock->expects($this->once())->method('beginTransaction');
        $this->dbMock->expects($this->once())->method('rollBack');

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willThrowException(new \Exception("DB Error"));

        $userModel = new User($this->dbMock);
        $result = $userModel->save($data);

        $this->assertFalse($result);
    }

    public function testDelete()
    {
        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':usuario_id', '12345678A');

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('DELETE FROM usuarios'))
            ->willReturn($this->stmtMock);

        $userModel = new User($this->dbMock);
        $result = $userModel->delete('12345678A');

        $this->assertTrue($result);
    }

    public function testGetByRolPaciente()
    {
        $patients = [
            ['usuario_id' => 'P1', 'nombre' => 'Patient One']
        ];

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('JOIN pacientes'))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($patients);

        $userModel = new User($this->dbMock);
        $result = $userModel->getByRol('Paciente');

        $this->assertEquals($patients, $result);
    }

    public function testSearchByRol()
    {
        $patients = [
            ['usuario_id' => 'P1', 'nombre' => 'Patient One']
        ];

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('WHERE (u.nombre LIKE :q'))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':q', '%John%');

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($patients);

        $userModel = new User($this->dbMock);
        $result = $userModel->searchByRol('Paciente', 'John');

        $this->assertEquals($patients, $result);
    }
}
