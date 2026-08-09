<?php
namespace App\Controllers;

use App\Core\Controller;

class SettingController extends Controller
{
    /**
     * Muestra la página de configuración con listados de horarios, ausencias, bonos e información de la clínica.
     *
     * @return void
     */
    public function index()
    {
        $settingModel = $this->model('Setting');
        $usuario_id = $_SESSION['usuario_id'];
        $email_admin = $_SESSION['email'] ?? '';
        
        $data = [
            'horarios' => $settingModel->getHorariosFisios(),
            'ausencias' => $settingModel->getAusenciasFisios(),
            'bonos' => $settingModel->getBonos(),
            'clinica' => $settingModel->getClinica(),
            'tarjeta' => $settingModel->getMetodoPagoByUsuario($usuario_id),
            'cuenta' => $settingModel->getCuentaClienteByEmail($email_admin)
        ];
        
        $this->view('setting/index', $data);
    }

    /**
     * Crea un nuevo horario de trabajo para un fisioterapeuta.
     *
     * Si la petición es POST, guarda el horario en la base de datos y redirige a configuración.
     * Si es GET, muestra el formulario de creación de horarios con la lista de fisioterapeutas.
     *
     * @return void
     */
    public function createHorario()
    {
        $settingModel = $this->model('Setting');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'fisioterapeuta_id' => $_POST['fisioterapeuta_id'],
                'dia_semana' => $_POST['dia_semana'],
                'hora_inicio' => $_POST['hora_inicio'],
                'hora_fin' => $_POST['hora_fin']
            ];
            if ($settingModel->saveHorario($data)) {
                header('Location: ' . PROJECT_ROOT . '/configuracion');
                $this->exitApp();
            }
        } else {
            $data = ['fisios' => $settingModel->getFisios()];
            $this->view('setting/horarios_form', $data);
        }
    }

    /**
     * Crea una nueva ausencia de trabajo para un fisioterapeuta.
     *
     * Si la petición es POST, guarda la ausencia en la base de datos y redirige a configuración.
     * Si es GET, muestra el formulario de creación de ausencias con la lista de fisioterapeutas.
     *
     * @return void
     */
    public function createAusencia()
    {
        $settingModel = $this->model('Setting');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'fisioterapeuta_id' => $_POST['fisioterapeuta_id'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'motivo' => htmlspecialchars($_POST['motivo'] ?? '', ENT_QUOTES, 'UTF-8')
            ];
            if ($settingModel->saveAusencia($data)) {
                header('Location: ' . PROJECT_ROOT . '/configuracion');
                $this->exitApp();
            }
        } else {
            $data = ['fisios' => $settingModel->getFisios()];
            $this->view('setting/ausencias_form', $data);
        }
    }


    /**
     * Crea un nuevo bono de sesiones para la tienda.
     *
     * Si la petición es POST, guarda el bono en la base de datos y redirige a configuración.
     * Si es GET, muestra el formulario de creación de bonos.
     *
     * @return void
     */
    public function createBono()
    {
        $settingModel = $this->model('Setting');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8'),
                'numero_sesiones' => (int)$_POST['numero_sesiones'],
                'precio' => (float)$_POST['precio'],
                'estado' => $_POST['estado'] ?? 'Activo'
            ];
            if ($settingModel->saveBono($data)) {
                header('Location: ' . PROJECT_ROOT . '/configuracion');
                $this->exitApp();
            }
        } else {
            $this->view('setting/bonos_form');
        }
    }

    /**
     * Actualiza los datos de contacto y facturación de la clínica.
     *
     * Si la petición es POST, guarda los datos en la base de datos y redirige a configuración con un mensaje de éxito o error.
     *
     * @return void
     */
    public function updateClinica()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = $this->model('Setting');
            $data = [
                'id_clinica' => $_POST['id_clinica'] ?? null,
                'nombre_comercial' => htmlspecialchars($_POST['nombre_comercial'] ?? '', ENT_QUOTES, 'UTF-8'),
                'razon_social' => htmlspecialchars($_POST['razon_social'] ?? '', ENT_QUOTES, 'UTF-8'),
                'direccion_calle' => htmlspecialchars($_POST['direccion_calle'] ?? '', ENT_QUOTES, 'UTF-8'),
                'ciudad' => htmlspecialchars($_POST['ciudad'] ?? '', ENT_QUOTES, 'UTF-8'),
                'provincia_estado' => htmlspecialchars($_POST['provincia_estado'] ?? '', ENT_QUOTES, 'UTF-8'),
                'codigo_postal' => htmlspecialchars($_POST['codigo_postal'] ?? '', ENT_QUOTES, 'UTF-8'),
                'pais' => htmlspecialchars($_POST['pais'] ?? 'España', ENT_QUOTES, 'UTF-8'),
                'telefono_contacto' => htmlspecialchars($_POST['telefono_contacto'] ?? '', ENT_QUOTES, 'UTF-8'),
                'email_contacto' => htmlspecialchars($_POST['email_contacto'] ?? '', ENT_QUOTES, 'UTF-8'),
                'sitio_web' => htmlspecialchars($_POST['sitio_web'] ?? '', ENT_QUOTES, 'UTF-8')
            ];
            
            if ($settingModel->saveClinica($data)) {
                $_SESSION['success_message'] = "Datos de la clínica guardados correctamente.";
            } else {
                $_SESSION['error_message'] = "Error al guardar los datos de la clínica.";
            }
            header('Location: ' . PROJECT_ROOT . '/configuracion');
            $this->exitApp();
        }
    }

    /**
     * Actualiza los datos de la tarjeta de pago de la suscripción del autónomo.
     *
     * @return void
     */
    public function updateTarjeta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = $this->model('Setting');
            $usuario_id = $_SESSION['usuario_id'];
            
            $data = [
                'nombre_titular' => htmlspecialchars($_POST['card_holder'] ?? '', ENT_QUOTES, 'UTF-8'),
                'numero_completo' => htmlspecialchars($_POST['card_number'] ?? '', ENT_QUOTES, 'UTF-8'),
                'fecha_expiracion' => htmlspecialchars($_POST['card_expiry'] ?? '', ENT_QUOTES, 'UTF-8'),
                'cvv' => htmlspecialchars($_POST['card_cvv'] ?? '', ENT_QUOTES, 'UTF-8')
            ];

            if (empty($data['nombre_titular']) || empty($data['numero_completo']) || empty($data['fecha_expiracion']) || empty($data['cvv'])) {
                $_SESSION['error_message'] = "Todos los campos de la tarjeta son obligatorios.";
            } else {
                if ($settingModel->updateMetodoPago($usuario_id, $data)) {
                    $_SESSION['success_message'] = "Datos de la tarjeta de pago actualizados correctamente.";
                } else {
                    $_SESSION['error_message'] = "Error al actualizar los datos de la tarjeta.";
                }
            }
            
            header('Location: ' . PROJECT_ROOT . '/configuracion');
            $this->exitApp();
        }
    }

    /**
     * Actualiza el plan de suscripción contratado de la clínica.
     *
     * @return void
     */
    public function updatePlan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = $this->model('Setting');
            $email_admin = $_SESSION['email'] ?? '';
            $plan = $_POST['plan_suscripcion'] ?? 'Basico';

            if ($settingModel->updatePlanSuscripcion($email_admin, $plan)) {
                $_SESSION['success_message'] = "Plan de suscripción actualizado correctamente a " . htmlspecialchars($plan) . ".";
            } else {
                $_SESSION['error_message'] = "Error al actualizar el plan de suscripción.";
            }
            
            header('Location: ' . PROJECT_ROOT . '/configuracion');
            $this->exitApp();
        }
    }

    /**
     * Edita un horario de trabajo existente de un fisioterapeuta.
     *
     * Si la petición es POST, actualiza el horario en la base de datos y redirige a configuración.
     * Si es GET, muestra el formulario de edición con los detalles actuales del horario.
     *
     * @return void
     */
    public function editHorario()
    {
        $settingModel = $this->model('Setting');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['horario_id'];
            $data = [
                'fisioterapeuta_id' => $_POST['fisioterapeuta_id'],
                'dia_semana' => $_POST['dia_semana'],
                'hora_inicio' => $_POST['hora_inicio'],
                'hora_fin' => $_POST['hora_fin']
            ];
            if ($settingModel->updateHorario($id, $data)) {
                header('Location: ' . PROJECT_ROOT . '/configuracion');
                $this->exitApp();
            }
        } else {
            $id = $_GET['id'];
            $data = [
                'horario' => $settingModel->getHorarioById($id),
                'fisios' => $settingModel->getFisios()
            ];
            $this->view('setting/horarios_form', $data);
        }
    }

    /**
     * Edita una ausencia existente de un fisioterapeuta.
     *
     * Si la petición es POST, actualiza la ausencia en la base de datos y redirige a configuración.
     * Si es GET, muestra el formulario de edición con los detalles actuales de la ausencia.
     *
     * @return void
     */
    public function editAusencia()
    {
        $settingModel = $this->model('Setting');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ausencia_id'];
            $data = [
                'fisioterapeuta_id' => $_POST['fisioterapeuta_id'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin' => $_POST['fecha_fin'],
                'motivo' => htmlspecialchars($_POST['motivo'] ?? '', ENT_QUOTES, 'UTF-8')
            ];
            if ($settingModel->updateAusencia($id, $data)) {
                header('Location: ' . PROJECT_ROOT . '/configuracion');
                $this->exitApp();
            }
        } else {
            $id = $_GET['id'];
            $data = [
                'ausencia' => $settingModel->getAusenciaById($id),
                'fisios' => $settingModel->getFisios()
            ];
            $this->view('setting/ausencias_form', $data);
        }
    }


    /**
     * Edita un bono de sesiones existente.
     *
     * Si la petición es POST, actualiza los datos del bono en la base de datos y redirige a configuración.
     * Si es GET, muestra el formulario de edición con los detalles actuales del bono.
     *
     * @return void
     */
    public function editBono()
    {
        $settingModel = $this->model('Setting');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['bono_id'];
            $data = [
                'nombre' => htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8'),
                'numero_sesiones' => (int)$_POST['numero_sesiones'],
                'precio' => (float)$_POST['precio'],
                'estado' => $_POST['estado'] ?? 'Activo'
            ];
            if ($settingModel->updateBono($id, $data)) {
                header('Location: ' . PROJECT_ROOT . '/configuracion');
                $this->exitApp();
            }
        } else {
            $id = $_GET['id'];
            $data = ['bono' => $settingModel->getBonoById($id)];
            $this->view('setting/bonos_form', $data);
        }
    }

    /**
     * Guarda o actualiza los datos fiscales de la clínica y la configuración de Verifactu.
     *
     * @return void
     */
    public function saveClinica()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = $this->model('Setting');
            $clinicaActual = $settingModel->getClinica();
            $certPath = $clinicaActual['verifactu_cert_path'] ?? null;

            // Procesar la subida del certificado digital si se ha adjuntado un archivo
            if (isset($_FILES['verifactu_cert_file']) && $_FILES['verifactu_cert_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES['verifactu_cert_file']['tmp_name'];
                $fileName = $_FILES['verifactu_cert_file']['name'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($fileExt, ['p12', 'pfx', 'pem'])) {
                    $storageDir = dirname(__DIR__) . '/storage/certificates';
                    if (!is_dir($storageDir)) {
                        mkdir($storageDir, 0755, true);
                        file_put_contents($storageDir . '/.htaccess', "Require all denied\n");
                    }

                    $safeFileName = 'cert_verifactu_' . time() . '.' . $fileExt;
                    $targetPath = $storageDir . '/' . $safeFileName;

                    if (move_uploaded_file($fileTmp, $targetPath)) {
                        $certPath = $targetPath;
                    }
                }
            }

            $data = [
                'id_clinica' => $_POST['id_clinica'] ?? null,
                'nombre_comercial' => htmlspecialchars($_POST['nombre_comercial'] ?? '', ENT_QUOTES, 'UTF-8'),
                'razon_social' => htmlspecialchars($_POST['razon_social'] ?? '', ENT_QUOTES, 'UTF-8'),
                'nif_cif' => htmlspecialchars($_POST['nif_cif'] ?? 'B12345678', ENT_QUOTES, 'UTF-8'),
                'direccion_calle' => htmlspecialchars($_POST['direccion_calle'] ?? '', ENT_QUOTES, 'UTF-8'),
                'ciudad' => htmlspecialchars($_POST['ciudad'] ?? '', ENT_QUOTES, 'UTF-8'),
                'provincia_estado' => htmlspecialchars($_POST['provincia_estado'] ?? '', ENT_QUOTES, 'UTF-8'),
                'codigo_postal' => htmlspecialchars($_POST['codigo_postal'] ?? '', ENT_QUOTES, 'UTF-8'),
                'pais' => htmlspecialchars($_POST['pais'] ?? 'España', ENT_QUOTES, 'UTF-8'),
                'telefono_contacto' => htmlspecialchars($_POST['telefono_contacto'] ?? '', ENT_QUOTES, 'UTF-8'),
                'email_contacto' => htmlspecialchars($_POST['email_contacto'] ?? '', ENT_QUOTES, 'UTF-8'),
                'sitio_web' => htmlspecialchars($_POST['sitio_web'] ?? '', ENT_QUOTES, 'UTF-8'),
                'verifactu_env' => $_POST['verifactu_env'] ?? 'pruebas',
                'verifactu_cert_path' => $certPath,
                'verifactu_cert_password' => $_POST['verifactu_cert_password'] ?? '',
                'verifactu_activo' => isset($_POST['verifactu_activo']) ? 1 : 0
            ];

            $settingModel->saveClinica($data);
        }
        header('Location: ' . PROJECT_ROOT . '/configuracion');
        $this->exitApp();
    }
}
