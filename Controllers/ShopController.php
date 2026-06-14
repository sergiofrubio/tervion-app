<?php
namespace App\Controllers;

use App\Core\Controller;

class ShopController extends Controller
{
    /**
     * Muestra la lista de bonos activos disponibles para compra en la tienda.
     *
     * @return void
     */
    public function list()
    {   
        $configModel = $this->model('Setting');
        
        // Obtener bonos activos para mostrar en la tienda
        $bonos = $configModel->getBonos();
        
        // Filtrar solo los bonos activos
        $bonosActivos = array_filter($bonos, function($bono) {
            return $bono['estado'] === 'Activo';
        });

        $data = [
            'bonosActivos' => $bonosActivos
        ];

        $this->view('patient-view/shop/list', $data);
    }


    /**
     * Procesa la transacción de pago para la compra de un bono.
     *
     * Permite opcionalmente guardar el método de pago (tarjeta) en la base de datos
     * y redirige al panel de citas con un mensaje de éxito.
     *
     * @return void
     */
    // public function procesarPago()
    // {       
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //         $usuario_id = $_SESSION['usuario_id'];
            
    //         // Si el usuario marcó "Guardar tarjeta" y no está usando una guardada
    //         if (isset($_POST['save_method']) && $_POST['payment_source'] === 'new') {
    //             $numeroTarjeta = $_POST['card_number'] ?? '';
    //             $last4 = substr($numeroTarjeta, -4);
                
    //             $dataPM = [
    //                 'usuario_id' => $usuario_id,
    //                 'tipo' => 'Tarjeta',
    //                 'proveedor' => 'Visa', // Placeholder
    //                 'last4' => $last4,
    //                 'fecha_expiracion' => $_POST['expiry'] ?? '',
    //                 'token_externo' => bin2hex(random_bytes(16)),
    //                 'es_predeterminado' => 0
    //             ];
                
    //             $metodoPagoModel->save($dataPM);
    //         }
            
    //         // Lógica de creación de factura y pago (Simulada para este paso)
    //         // ...
            
    //         header('Location: ' . PROJECT_ROOT . '/paciente/citas?success=purchased');
    //         $this->exitApp();
    //     }
    // }
}
