<?php
namespace App\Controllers;

use App\Core\Controller;
use Redsys\Merchant;
use Redsys\Parameters;
use Redsys\Redirect;

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
     * Muestra la vista de revisión del bono seleccionado para pagar.
     *
     * @return void
     */
    public function pago()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . PROJECT_ROOT . '/paciente/tienda');
            $this->exitApp();
        }

        $configModel = $this->model('Setting');
        $bono = $configModel->getBonoById($id);

        if (!$bono || $bono['estado'] !== 'Activo') {
            header('Location: ' . PROJECT_ROOT . '/paciente/tienda');
            $this->exitApp();
        }

        $this->view('patient-view/shop/pago', [
            'bono' => $bono
        ]);
    }

    /**
     * Procesa la transacción de pago para la compra de un bono con Redsys.
     *
     * @return void
     */
    public function procesarPago()
    {       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bono_id = $_POST['bono_id'] ?? null;
            if (!$bono_id) {
                header('Location: ' . PROJECT_ROOT . '/paciente/tienda');
                $this->exitApp();
            }

            $configModel = $this->model('Setting');
            $bono = $configModel->getBonoById($bono_id);

            if (!$bono || $bono['estado'] !== 'Activo') {
                header('Location: ' . PROJECT_ROOT . '/paciente/tienda');
                $this->exitApp();
            }

            $usuario_id = $_SESSION['usuario_id'];
            $order = time(); // Genera un ID de pedido único y numérico de 10 dígitos

            // Inicializar Merchant desde la variable de entorno
            $redsysApiKey = getenv('REDSYS_API_KEY') ?: '';
            $merchant = Merchant::initWithApiKey($redsysApiKey);
            
            // Construir los parámetros del pago
            $params = new Parameters();
            
            // Redsys requiere el importe en céntimos enteros sin decimales
            $params->amount = intval(round($bono['precio'] * 100));
            $params->order = $order;
            
            // Definir base URL
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $baseUrl = "$protocol://$host" . PROJECT_ROOT;

            // URLs de callback y redirección
            $params->merchantUrl = "$baseUrl/paciente/tienda/notificacion?usuario_id=" . $usuario_id . "&bono_id=" . $bono['bono_id'];
            $params->urlOk = "$baseUrl/paciente/tienda/confirmacion?order=" . $order . "&bono_id=" . $bono['bono_id'] . "&usuario_id=" . $usuario_id;
            $params->urlKo = "$baseUrl/paciente/tienda/error";

            // Redirigir al usuario al entorno de Redsys
            Redirect::authorisation($merchant, $params);
            $this->exitApp();
        }
    }

    /**
     * Recibe la notificación asíncrona online (IPN) desde los servidores de Redsys.
     *
     * @return void
     */
    public function notificacion()
    {
        $receivedParams = array_merge($_GET, $_POST, json_decode(file_get_contents('php://input'), true) ?: []);

        try {
            $redsysApiKey = getenv('REDSYS_API_KEY') ?: '';
            $merchant = Merchant::initWithApiKey($redsysApiKey);
            $params = Parameters::digest($merchant, $receivedParams);
            
            $responseCode = (int)$params->response;
            if ($responseCode >= 0 && $responseCode <= 99) {
                // Pago aceptado por el banco
                $usuario_id = $_GET['usuario_id'] ?? null;
                $bono_id = $_GET['bono_id'] ?? null;

                if ($usuario_id && $bono_id) {
                    $shopModel = $this->model('Shop');
                    $shopModel->registrarCompraBono($usuario_id, $bono_id);
                }
            }
        } catch (\Exception $e) {
            error_log("Error procesando notificación Redsys: " . $e->getMessage());
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => $e->getMessage()]);
            $this->exitApp();
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'ok']);
        $this->exitApp();
    }

    /**
     * Muestra la pantalla de confirmación de compra exitosa al usuario.
     *
     * @return void
     */
    public function confirmacion()
    {
        $usuario_id = $_SESSION['usuario_id'];
        $bono_id = $_GET['bono_id'] ?? null;
        $order = $_GET['order'] ?? null;

        if ($usuario_id && $bono_id) {
            $configModel = $this->model('Setting');
            $bono = $configModel->getBonoById($bono_id);

            if ($bono) {
                $db = (new \App\Core\DataBase())->connect();
                $desc = 'Compra de ' . $bono['nombre'];
                
                // Buscar factura en la base de datos
                $stmt = $db->prepare("SELECT * FROM facturas WHERE paciente_id = :paciente_id AND descripcion = :desc AND fecha_emision = :fecha ORDER BY factura_id DESC LIMIT 1");
                $stmt->execute([
                    ':paciente_id' => $usuario_id,
                    ':desc' => $desc,
                    ':fecha' => date('Y-m-d')
                ]);
                $factura = $stmt->fetch(\PDO::FETCH_ASSOC);

                // Fallback para entornos de desarrollo local (el webhook IPN de Redsys no puede llamar a localhost)
                if (!$factura) {
                    $shopModel = $this->model('Shop');
                    $invoiceId = $shopModel->registrarCompraBono($usuario_id, $bono_id);
                    if ($invoiceId) {
                        $stmt = $db->prepare("SELECT * FROM facturas WHERE factura_id = :id LIMIT 1");
                        $stmt->execute([':id' => $invoiceId]);
                        $factura = $stmt->fetch(\PDO::FETCH_ASSOC);
                    }
                }

                $this->view('patient-view/shop/confirmacion', [
                    'bono' => $bono,
                    'factura' => $factura,
                    'order' => $order
                ]);
                return;
            }
        }

        header('Location: ' . PROJECT_ROOT . '/paciente/tienda');
        $this->exitApp();
    }

    /**
     * Muestra la vista de error en la pasarela de pagos.
     *
     * @return void
     */
    public function errorPago()
    {
        $this->view('patient-view/shop/error');
    }
}
