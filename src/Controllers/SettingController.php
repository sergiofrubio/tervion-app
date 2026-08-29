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
            'cuenta' => $settingModel->getCuentaClienteByEmail($email_admin),
            'tipos_citas' => $settingModel->getTiposCitas(),
            'despachos' => $settingModel->getDespachos(),
            'descuentos' => $settingModel->getCodigosDescuento()
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
     * Si es un Upgrade (subir de plan), se aplica inmediatamente.
     * Si es un Downgrade (bajar de plan), se programa para entrar en vigor al finalizar el ciclo de facturación actual.
     *
     * @return void
     */
    public function updatePlan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = $this->model('Setting');
            $email_admin = $_SESSION['email'] ?? '';
            $nuevo_plan = $_POST['plan_suscripcion'] ?? 'Basico';

            // Jerarquía y precios de planes
            $planRanks = ['Basico' => 1, 'Profesional' => 2, 'Premium' => 3];

            if (!isset($planRanks[$nuevo_plan])) {
                $_SESSION['error_message'] = "Plan seleccionado no válido.";
                header('Location: ' . PROJECT_ROOT . '/configuracion');
                $this->exitApp();
            }

            $cuenta = $settingModel->getCuentaClienteByEmail($email_admin);
            $planActual = $cuenta['plan_suscripcion'] ?? 'Basico';

            $rankActual = $planRanks[$planActual] ?? 1;
            $rankNuevo = $planRanks[$nuevo_plan] ?? 1;

            if ($rankNuevo > $rankActual) {
                // UPGRADE: Aplicación inmediata
                if ($settingModel->updatePlanSuscripcion($email_admin, $nuevo_plan)) {
                    $_SESSION['success_message'] = "¡Upgrade realizado con éxito! Tu suscripción ahora es " . htmlspecialchars($nuevo_plan) . ".";
                } else {
                    $_SESSION['error_message'] = "Error al aplicar el upgrade de suscripción.";
                }
            } elseif ($rankNuevo < $rankActual) {
                // DOWNGRADE: Programado para el siguiente ciclo
                if ($settingModel->scheduleDowngrade($email_admin, $nuevo_plan)) {
                    $fechaRenovacion = !empty($cuenta['fecha_renovacion']) ? date('d/m/Y', strtotime($cuenta['fecha_renovacion'])) : 'tu próxima fecha de facturación';
                    $_SESSION['success_message'] = "Cambio programado: tu plan pasará a " . htmlspecialchars($nuevo_plan) . " el " . $fechaRenovacion . " al terminar el ciclo pagado.";
                } else {
                    $_SESSION['error_message'] = "Error al programar el cambio de plan.";
                }
            } else {
                // Mismo plan: Si tenía un downgrade pendiente y vuelve a seleccionar el actual, se cancela
                if (!empty($cuenta['plan_proximo'])) {
                    $settingModel->cancelDowngrade($email_admin);
                    $_SESSION['success_message'] = "Se ha cancelado el cambio diferido. Continuarás con el plan " . htmlspecialchars($planActual) . ".";
                } else {
                    $_SESSION['success_message'] = "Ya tienes contratado el plan " . htmlspecialchars($planActual) . ".";
                }
            }
            
            header('Location: ' . PROJECT_ROOT . '/configuracion');
            $this->exitApp();
        }
    }

    /**
     * Cancela una solicitud de cambio de plan pendiente (downgrade programado).
     *
     * @return void
     */
    public function cancelPlanDowngrade()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = $this->model('Setting');
            $email_admin = $_SESSION['email'] ?? '';

            if ($settingModel->cancelDowngrade($email_admin)) {
                $_SESSION['success_message'] = "El cambio de plan pendiente ha sido cancelado con éxito.";
            } else {
                $_SESSION['error_message'] = "No se pudo cancelar el cambio de plan.";
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

    // ==========================================
    // TIPOS DE CITAS
    // ==========================================
    public function saveTipoCita()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = $this->model('Setting');
            $id = !empty($_POST['tipo_cita_id']) ? (int)$_POST['tipo_cita_id'] : null;
            
            $data = [
                'nombre' => trim(htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8')),
                'descripcion' => trim(htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES, 'UTF-8')),
                'duracion_minutos' => (int)($_POST['duracion_minutos'] ?? 60),
                'precio' => (float)($_POST['precio'] ?? 0.00),
                'color' => htmlspecialchars($_POST['color'] ?? '#3b82f6', ENT_QUOTES, 'UTF-8'),
                'estado' => in_array($_POST['estado'] ?? '', ['Activo', 'Inactivo']) ? $_POST['estado'] : 'Activo'
            ];

            if (empty($data['nombre'])) {
                $_SESSION['error_message'] = "El nombre del tipo de cita es obligatorio.";
            } else {
                if ($id) {
                    if ($settingModel->updateTipoCita($id, $data)) {
                        $_SESSION['success_message'] = "Tipo de cita actualizado correctamente.";
                    } else {
                        $_SESSION['error_message'] = "Error al actualizar el tipo de cita.";
                    }
                } else {
                    if ($settingModel->saveTipoCita($data)) {
                        $_SESSION['success_message'] = "Tipo de cita creado con éxito.";
                    } else {
                        $_SESSION['error_message'] = "Error al registrar el tipo de cita.";
                    }
                }
            }
        }
        header('Location: ' . PROJECT_ROOT . '/configuracion');
        $this->exitApp();
    }

    public function deleteTipoCita()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['tipo_cita_id'] ?? 0);
            if ($id > 0) {
                $settingModel = $this->model('Setting');
                if ($settingModel->deleteTipoCita($id)) {
                    $_SESSION['success_message'] = "Tipo de cita eliminado correctamente.";
                } else {
                    $_SESSION['error_message'] = "No se pudo eliminar el tipo de cita.";
                }
            }
        }
        header('Location: ' . PROJECT_ROOT . '/configuracion');
        $this->exitApp();
    }

    // ==========================================
    // DESPACHOS
    // ==========================================
    public function saveDespacho()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = $this->model('Setting');
            $id = !empty($_POST['despacho_id']) ? (int)$_POST['despacho_id'] : null;

            $data = [
                'nombre' => trim(htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8')),
                'ubicacion' => trim(htmlspecialchars($_POST['ubicacion'] ?? '', ENT_QUOTES, 'UTF-8')),
                'capacidad' => max(1, (int)($_POST['capacidad'] ?? 1)),
                'equipamiento' => trim(htmlspecialchars($_POST['equipamiento'] ?? '', ENT_QUOTES, 'UTF-8')),
                'color' => htmlspecialchars($_POST['color'] ?? '#6366f1', ENT_QUOTES, 'UTF-8'),
                'estado' => in_array($_POST['estado'] ?? '', ['Activo', 'Inactivo']) ? $_POST['estado'] : 'Activo'
            ];

            if (empty($data['nombre'])) {
                $_SESSION['error_message'] = "El nombre del despacho/sala es obligatorio.";
            } else {
                if ($id) {
                    if ($settingModel->updateDespacho($id, $data)) {
                        $_SESSION['success_message'] = "Despacho o sala actualizado correctamente.";
                    } else {
                        $_SESSION['error_message'] = "Error al actualizar el despacho.";
                    }
                } else {
                    if ($settingModel->saveDespacho($data)) {
                        $_SESSION['success_message'] = "Despacho o sala registrado correctamente.";
                    } else {
                        $_SESSION['error_message'] = "Error al crear el despacho.";
                    }
                }
            }
        }
        header('Location: ' . PROJECT_ROOT . '/configuracion');
        $this->exitApp();
    }

    public function deleteDespacho()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['despacho_id'] ?? 0);
            if ($id > 0) {
                $settingModel = $this->model('Setting');
                if ($settingModel->deleteDespacho($id)) {
                    $_SESSION['success_message'] = "Despacho eliminado correctamente.";
                } else {
                    $_SESSION['error_message'] = "No se pudo eliminar el despacho.";
                }
            }
        }
        header('Location: ' . PROJECT_ROOT . '/configuracion');
        $this->exitApp();
    }

    // ==========================================
    // CÓDIGOS DE DESCUENTO
    // ==========================================
    public function saveDescuento()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = $this->model('Setting');
            $id = !empty($_POST['codigo_id']) ? (int)$_POST['codigo_id'] : null;

            $codigo = strtoupper(trim(preg_replace('/[^A-Za-z0-9_-]/', '', $_POST['codigo'] ?? '')));
            $tipo_descuento = in_array($_POST['tipo_descuento'] ?? '', ['porcentaje', 'fijo']) ? $_POST['tipo_descuento'] : 'porcentaje';
            $usos_ilimitados = isset($_POST['usos_ilimitados']) && $_POST['usos_ilimitados'] == '1';
            $usos_maximos = $usos_ilimitados ? null : (!empty($_POST['usos_maximos']) ? (int)$_POST['usos_maximos'] : null);

            $fecha_inicio = !empty($_POST['fecha_inicio']) ? date('Y-m-d H:i:s', strtotime($_POST['fecha_inicio'])) : null;
            $fecha_fin = !empty($_POST['fecha_fin']) ? date('Y-m-d H:i:s', strtotime($_POST['fecha_fin'])) : null;

            $data = [
                'codigo' => $codigo,
                'descripcion' => trim(htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES, 'UTF-8')),
                'tipo_descuento' => $tipo_descuento,
                'valor' => (float)($_POST['valor'] ?? 0.00),
                'monto_minimo' => (float)($_POST['monto_minimo'] ?? 0.00),
                'usos_maximos' => $usos_maximos,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'estado' => in_array($_POST['estado'] ?? '', ['Activo', 'Inactivo']) ? $_POST['estado'] : 'Activo'
            ];

            if (empty($data['codigo'])) {
                $_SESSION['error_message'] = "El código de descuento es obligatorio.";
            } elseif ($data['valor'] <= 0) {
                $_SESSION['error_message'] = "El valor del descuento debe ser mayor a cero.";
            } else {
                if ($id) {
                    if ($settingModel->updateCodigoDescuento($id, $data)) {
                        $_SESSION['success_message'] = "Código de descuento actualizado correctamente.";
                    } else {
                        $_SESSION['error_message'] = "Error al actualizar el cupón de descuento.";
                    }
                } else {
                    if ($settingModel->saveCodigoDescuento($data)) {
                        $_SESSION['success_message'] = "Cupón de descuento creado con éxito.";
                    } else {
                        $_SESSION['error_message'] = "Error al registrar el cupón de descuento (comprueba que el código no exista).";
                    }
                }
            }
        }
        header('Location: ' . PROJECT_ROOT . '/configuracion');
        $this->exitApp();
    }

    public function deleteDescuento()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['codigo_id'] ?? 0);
            if ($id > 0) {
                $settingModel = $this->model('Setting');
                if ($settingModel->deleteCodigoDescuento($id)) {
                    $_SESSION['success_message'] = "Cupón de descuento eliminado correctamente.";
                } else {
                    $_SESSION['error_message'] = "No se pudo eliminar el cupón de descuento.";
                }
            }
        }
        header('Location: ' . PROJECT_ROOT . '/configuracion');
        $this->exitApp();
    }
}

