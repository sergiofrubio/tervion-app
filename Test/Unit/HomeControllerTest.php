<?php

namespace Tests\Unit;

use App\Controllers\HomeController;
use App\Models\Dashboard;

class HomeControllerTest extends ControllerTestCase
{
    public function testIndexRedirectsToLoginIfNoSession()
    {
        $controller = $this->getControllerMock(HomeController::class);
        $this->expectException(TestExitException::class);
        $controller->index();
    }

    public function testIndexShowsPatientViewIfRoleIsPatient()
    {
        $_SESSION['email'] = 'patient@example.com';
        $_SESSION['rol'] = 'Paciente';
        $_SESSION['nombre'] = 'Juan';

        $controller = $this->getControllerMock(HomeController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('patient-view/index', ['nombrePaciente' => 'Juan']);

        $controller->index();
    }

    public function testIndexShowsDashboardForNonPatients()
    {
        $_SESSION['email'] = 'admin@example.com';
        $_SESSION['rol'] = 'Administrador';

        $dashboardMock = $this->getMockBuilder(Dashboard::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getTotalPatients', 'getMonthlyRevenue', 'getTodayAppointmentsCount', 'getUpcomingAppointments', 'getRecentPatients'])
            ->getMock();

        $dashboardMock->method('getTotalPatients')->willReturn(10);
        $dashboardMock->method('getMonthlyRevenue')->willReturn(500.0);
        $dashboardMock->method('getTodayAppointmentsCount')->willReturn(3);
        $dashboardMock->method('getUpcomingAppointments')->willReturn([]);
        $dashboardMock->method('getRecentPatients')->willReturn([]);

        $controller = $this->getControllerMock(HomeController::class);
        $controller->method('model')->with('Dashboard')->willReturn($dashboardMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('index', [
                'totalPatients' => 10,
                'monthlyRevenue' => 500.0,
                'todayAppointmentsCount' => 3,
                'upcomingAppointments' => [],
                'recentPatients' => []
            ]);

        $controller->index();
    }
}
