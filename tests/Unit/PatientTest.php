<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use PDO;
use PDOStatement;

use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

#[AllowMockObjectsWithoutExpectations]
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
            'dni' => '12345678A',
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
        $this->dbMock->expects($this->once())->method('lastInsertId')->willReturn('1');

        // Solo se inserta en usuarios para rol Paciente
        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $userModel = new User($this->dbMock);
        $result = $userModel->save($data);

        $this->assertEquals(1, $result);
    }

    public function testSavePatientWithoutDniSuccess()
    {
        // Caso menor de edad sin DNI
        $data = [
            'dni' => null,
            'nombre' => 'Pedrito',
            'apellidos' => 'Perez',
            'fecha_nacimiento' => '2018-05-10',
            'rol' => 'Paciente'
        ];

        $this->dbMock->expects($this->once())->method('beginTransaction');
        $this->dbMock->expects($this->once())->method('commit');
        $this->dbMock->expects($this->once())->method('lastInsertId')->willReturn('5');

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $userModel = new User($this->dbMock);
        $result = $userModel->save($data);

        $this->assertEquals(5, $result);
    }

    public function testPatientModelFicha()
    {
        $patientModel = new \App\Models\Patient($this->dbMock);

        $fichaData = [
            'paciente_id' => 1,
            'cuenta_id' => 1,
            'usuario_id' => 2,
            'numero_expediente' => 'EXP-2026-0001',
            'nombre_tutor' => 'Padre Test',
            'dni_tutor' => '12345678Z',
            'telefono_tutor' => '600000000',
            'contacto_emergencia_nombre' => 'Madre Test',
            'contacto_emergencia_telefono' => '600000001',
            'compania_seguro' => 'Sanitas',
            'numero_poliza' => 'POL-999',
            'observaciones_administrativas' => 'Ficha administrativa de prueba',
            'alergias_alertas' => 'Polen'
        ];

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with(['usuario_id' => 2])
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($fichaData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT * FROM pacientes WHERE usuario_id = :usuario_id'))
            ->willReturn($this->stmtMock);

        $result = $patientModel->getByUsuarioId(2);
        $this->assertEquals($fichaData, $result);
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
            'grupo_cotizacion' => 2
        ];

        $this->dbMock->expects($this->once())->method('beginTransaction');
        $this->dbMock->expects($this->once())->method('commit');

        // Se inserta en usuarios y en empleados
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
            ->with($this->stringContains('SELECT u.* FROM usuarios u WHERE u.rol = :rol'))
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
            ->with($this->stringContains('SELECT u.usuario_id, u.nombre, u.apellidos'))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(2))
            ->method('bindParam');

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
