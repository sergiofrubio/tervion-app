<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Dashboard;
use PDO;
use PDOStatement;

class DashboardTest extends TestCase
{
    private $dbMock;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->dbMock = $this->createMock(PDO::class);
    }

    public function testGetTotalPatients()
    {
        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(['total' => 150]);

        $this->dbMock->expects($this->once())
            ->method('query')
            ->with($this->stringContains('SELECT COUNT(*) as total FROM usuarios WHERE rol = \'Paciente\''))
            ->willReturn($this->stmtMock);

        $dashboardModel = new Dashboard($this->dbMock);
        $result = $dashboardModel->getTotalPatients();

        $this->assertEquals(150, $result);
    }

    public function testGetMonthlyRevenue()
    {
        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(['total' => 2500.50]);

        $this->dbMock->expects($this->once())
            ->method('query')
            ->with($this->stringContains('SUM(total) as total'))
            ->willReturn($this->stmtMock);

        $dashboardModel = new Dashboard($this->dbMock);
        $result = $dashboardModel->getMonthlyRevenue();

        $this->assertEquals(2500.50, $result);
    }

    public function testGetTodayAppointmentsCount()
    {
        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn(['total' => 8]);

        $this->dbMock->expects($this->once())
            ->method('query')
            ->with($this->stringContains('citas WHERE DATE(fecha_hora) = CURRENT_DATE()'))
            ->willReturn($this->stmtMock);

        $dashboardModel = new Dashboard($this->dbMock);
        $result = $dashboardModel->getTodayAppointmentsCount();

        $this->assertEquals(8, $result);
    }

    public function testGetUpcomingAppointments()
    {
        $upcoming = [
            ['cita_id' => 1, 'paciente_nombre' => 'John', 'especialidad' => 'General']
        ];

        $this->stmtMock->expects($this->once())
            ->method('bindValue')
            ->with(':limit', 4, PDO::PARAM_INT);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($upcoming);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT c.*'))
            ->willReturn($this->stmtMock);

        $dashboardModel = new Dashboard($this->dbMock);
        $result = $dashboardModel->getUpcomingAppointments(4);

        $this->assertEquals($upcoming, $result);
    }
}
