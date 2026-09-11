<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio o el panel de control (dashboard) correspondiente según el rol de usuario.
     *
     * Redirige al inicio de sesión si no hay una sesión activa.
     *
     * @return void
     */
    public function index()
    {
        if (!isset($_SESSION['email'])) {
            header('Location: ' . PROJECT_ROOT . '/login');
            $this->exitApp();
        }

        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Paciente') {
            $data = [
                'nombrePaciente' => $_SESSION['nombre'] ?? 'Paciente'
            ];
            $this->view('patient-portal/index', $data);
        }
        if (isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Administrador' || $_SESSION['rol'] === 'Fisioterapeuta' || $_SESSION['rol'] === 'Secretario')) {
            $dashboardModel = $this->model('Dashboard');

            $data = [
                'totalPatients' => $dashboardModel->getTotalPatients(),
                'monthlyRevenue' => $dashboardModel->getMonthlyRevenue(),
                'todayAppointmentsCount' => $dashboardModel->getTodayAppointmentsCount(),
                'upcomingAppointments' => $dashboardModel->getUpcomingAppointments(4),
                'recentPatients' => $dashboardModel->getRecentPatients(3)
            ];

            $this->view('index', $data);
        }
    }
}
