<?php
namespace App\Controllers;
use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
        
        if (!isset($_SESSION['email'])) {
            header('Location: ' . PROJECT_ROOT . '/login');
            exit();
        }

        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Paciente') {
            $data = [
                'nombrePaciente' => $_SESSION['nombre'] ?? 'Paciente'
            ];
            $this->view('vista-pacientes/inicio', $data);
        } else {
            $dashboardModel = $this->model('Dashboard');
            
            $data = [
                'totalPatients' => $dashboardModel->getTotalPatients(),
                'monthlyRevenue' => $dashboardModel->getMonthlyRevenue(),
                'todayAppointmentsCount' => $dashboardModel->getTodayAppointmentsCount(),
                'upcomingAppointments' => $dashboardModel->getUpcomingAppointments(4),
                'recentPatients' => $dashboardModel->getRecentPatients(3)
            ];

            $this->view('inicio', $data);
        }
    }
}
