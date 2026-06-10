<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Appointment;
use PDO;
use PDOStatement;

class AppointmentTest extends TestCase
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
        $citaData = [
            'cita_id' => 1,
            'paciente_id' => 'P123',
            'paciente_nombre' => 'Jane',
            'fisioterapeuta_id' => 'F456',
            'fecha_hora' => '2026-05-22 10:00:00',
            'estado' => 'Programada'
        ];

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':cita_id', 1, PDO::PARAM_INT);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($citaData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT c.*'))
            ->willReturn($this->stmtMock);

        $appointmentModel = new Appointment($this->dbMock);
        $result = $appointmentModel->getById(1);

        $this->assertEquals($citaData, $result);
    }

    public function testSave()
    {
        $this->stmtMock->expects($this->exactly(6))
            ->method('bindParam');

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO citas'))
            ->willReturn($this->stmtMock);

        $appointmentModel = new Appointment($this->dbMock);
        $result = $appointmentModel->save('P123', 'F456', '2026-05-22 10:00:00', 'Programada', 1, null);

        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':cita_id', 1, PDO::PARAM_INT);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('DELETE FROM citas'))
            ->willReturn($this->stmtMock);

        $appointmentModel = new Appointment($this->dbMock);
        $result = $appointmentModel->delete(1);

        $this->assertTrue($result);
    }

    public function testGetAvailableSlotsNoHorarios()
    {
        // If there are no horarios, getAvailableSlots should return []
        $stmtHorarios = $this->createMock(PDOStatement::class);
        $stmtHorarios->method('execute')->willReturn(true);
        $stmtHorarios->method('fetchAll')->willReturn([]); // Empty schedules

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('FROM horarios_terapeutas'))
            ->willReturn($stmtHorarios);

        $appointmentModel = new Appointment($this->dbMock);
        // We test with a Lunes (Monday)
        $result = $appointmentModel->getAvailableSlots('F456', '2026-05-25'); // 2026-05-25 is Monday

        $this->assertEquals([], $result);
    }

    public function testGetAvailableSlotsWithAbsence()
    {
        // Therapist has schedule but is absent
        $stmtHorarios = $this->createMock(PDOStatement::class);
        $stmtHorarios->method('execute')->willReturn(true);
        $stmtHorarios->method('fetchAll')->willReturn([
            ['hora_inicio' => '09:00:00', 'hora_fin' => '14:00:00']
        ]);

        $stmtAusencias = $this->createMock(PDOStatement::class);
        $stmtAusencias->method('execute')->willReturn(true);
        $stmtAusencias->method('fetch')->willReturn(['1']); // Therapist is absent

        $this->dbMock->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnCallback(function($query) use ($stmtHorarios, $stmtAusencias) {
                if (strpos($query, 'horarios_terapeutas') !== false) {
                    return $stmtHorarios;
                }
                if (strpos($query, 'ausencias_terapeutas') !== false) {
                    return $stmtAusencias;
                }
                return null;
            });

        $appointmentModel = new Appointment($this->dbMock);
        $result = $appointmentModel->getAvailableSlots('F456', '2026-05-25');

        $this->assertEquals([], $result);
    }

    public function testGetAvailableSlotsSuccessfulCalculation()
    {
        // Monday, 09:00 - 12:00
        $stmtHorarios = $this->createMock(PDOStatement::class);
        $stmtHorarios->method('execute')->willReturn(true);
        $stmtHorarios->method('fetchAll')->willReturn([
            ['hora_inicio' => '09:00:00', 'hora_fin' => '12:00:00']
        ]);

        // No absences
        $stmtAusencias = $this->createMock(PDOStatement::class);
        $stmtAusencias->method('execute')->willReturn(true);
        $stmtAusencias->method('fetch')->willReturn(false);

        // One appointment from 10:00 to 11:00 (duration 60)
        $stmtCitas = $this->createMock(PDOStatement::class);
        $stmtCitas->method('execute')->willReturn(true);
        $stmtCitas->method('fetchAll')->willReturn([
            ['fecha_hora' => '2026-05-25 10:00:00', 'duracion_minutos' => 60]
        ]);

        $this->dbMock->expects($this->exactly(3))
            ->method('prepare')
            ->willReturnCallback(function($query) use ($stmtHorarios, $stmtAusencias, $stmtCitas) {
                if (strpos($query, 'horarios_terapeutas') !== false) {
                    return $stmtHorarios;
                }
                if (strpos($query, 'ausencias_terapeutas') !== false) {
                    return $stmtAusencias;
                }
                if (strpos($query, 'citas') !== false) {
                    return $stmtCitas;
                }
                return null;
            });

        $appointmentModel = new Appointment($this->dbMock);
        $result = $appointmentModel->getAvailableSlots('F456', '2026-05-25', 60);

        // Expect: 09:00 is free, 11:00 is free
        // 09:30 overlaps with (10:00-11:00) => occupied
        // 10:00 is occupied
        // 10:30 overlaps with (10:00-11:00) => occupied
        $this->assertEquals(['09:00', '11:00'], array_values($result));
    }
}
