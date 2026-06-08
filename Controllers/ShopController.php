<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\MetodoPago;

class ShopController extends Controller
{
    public function list()
    {
        $this->checkAuth();
        
        $configModel = $this->model('Configuracion');
        
        // Obtener bonos activos para mostrar en la tienda
        $bonos = $configModel->getBonos();
        
        // Filtrar solo los bonos activos
        $bonosActivos = array_filter($bonos, function($bono) {
            return $bono['estado'] === 'Activo';
        });

        $data = [
            'bonosActivos' => $bonosActivos
        ];

        $this->view('vista-pacientes/tienda/list', $data);
    }

    public function pago()
    {
        $this->checkAuth();
        
        $idBono = $_GET['id'] ?? 0;
        $metodoPagoModel = new MetodoPago();
        $configModel = $this->model('Configuracion');
        
        $metodosPago = $metodoPagoModel->getByUsuario($_SESSION['usuario_id']);
        $bono = $configModel->getBonoById($idBono);
        
        if (!$bono) {
            header('Location: ' . PROJECT_ROOT . '/vista-pacientes/tienda');
            exit();
        }

        $data = [
            'idBono' => $idBono,
            'bono' => $bono,
            'metodosPago' => $metodosPago
        ];

        $this->view('vista-pacientes/tienda/pago', $data);
    }

    public function procesarPago()
    {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $metodoPagoModel = new MetodoPago();
            $usuario_id = $_SESSION['usuario_id'];
            
            // Si el usuario marcó "Guardar tarjeta" y no está usando una guardada
            if (isset($_POST['save_method']) && $_POST['payment_source'] === 'new') {
                $numeroTarjeta = $_POST['card_number'] ?? '';
                $last4 = substr($numeroTarjeta, -4);
                
                $dataPM = [
                    'usuario_id' => $usuario_id,
                    'tipo' => 'Tarjeta',
                    'proveedor' => 'Visa', // Placeholder
                    'last4' => $last4,
                    'fecha_expiracion' => $_POST['expiry'] ?? '',
                    'token_externo' => bin2hex(random_bytes(16)),
                    'es_predeterminado' => 0
                ];
                
                $metodoPagoModel->save($dataPM);
            }
            
            // Lógica de creación de factura y pago (Simulada para este paso)
            // ...
            
            header('Location: ' . PROJECT_ROOT . '/vista-pacientes/citas?success=purchased');
            exit();
        }
    }
}
