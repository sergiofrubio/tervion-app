<?php

namespace App\Controllers;

use App\Core\Controller;
use Exception;

class SaasAdminController extends Controller
{
    public function __construct()
    {
        // Enforzar autorización del rol SuperAdmin
        if (!isset($_SESSION['email']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'SuperAdmin') {
            header('Location: ' . PROJECT_ROOT . '/inicio');
            exit;
        }
    }

    /**
     * Dashboard Principal del SuperAdmin (Métricas globales de la plataforma SaaS)
     */
    public function dashboard()
    {
        $saasModel = $this->model('SaasAdmin');

        $metrics = $saasModel->getGlobalMetrics();
        $planDist = $saasModel->getPlanDistribution();
        $recentTenants = array_slice($saasModel->getAllTenants(), 0, 5);

        $data = [
            'pageTitle' => 'Panel de Control SaaS',
            'metrics' => $metrics,
            'planDistribution' => $planDist,
            'recentTenants' => $recentTenants
        ];

        $this->view('saas-admin/index', $data);
    }

    /**
     * Directorio y Administración de Clientes (Tenants)
     */
    public function tenants()
    {
        $saasModel = $this->model('SaasAdmin');
        $tenants = $saasModel->getAllTenants();

        $data = [
            'pageTitle' => 'Directorio de Clientes SaaS',
            'tenants' => $tenants,
            'statusMessage' => $_SESSION['saas_status_msg'] ?? null
        ];

        unset($_SESSION['saas_status_msg']);

        $this->view('saas-admin/customer/list', $data);
    }

    /**
     * Formulario y procesamiento de Alta de nuevo cliente / clínica en la plataforma
     */
    public function createTenant()
    {
        $saasModel = $this->model('SaasAdmin');
        $errorMessage = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombreEmpresa = trim($_POST['nombre_empresa'] ?? '');
            $nifCif = trim($_POST['nif_cif'] ?? '');
            $planSuscripcion = trim($_POST['plan_suscripcion'] ?? 'Profesional');
            $nombreComercial = trim($_POST['nombre_comercial'] ?? $nombreEmpresa);
            $ciudad = trim($_POST['ciudad'] ?? 'Madrid');
            $provincia = trim($_POST['provincia'] ?? 'Madrid');

            $adminNombre = trim($_POST['admin_nombre'] ?? '');
            $adminApellidos = trim($_POST['admin_apellidos'] ?? '');
            $adminEmail = trim($_POST['admin_email'] ?? '');
            $adminPass = trim($_POST['admin_pass'] ?? '');
            $adminDni = trim($_POST['admin_dni'] ?? '');

            if (empty($nombreEmpresa) || empty($nifCif) || empty($adminEmail) || empty($adminPass) || empty($adminDni)) {
                $errorMessage = 'Por favor, completa todos los campos obligatorios (*).';
            } else {
                try {
                    // Generar slug amigable de URL
                    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombreEmpresa), '-'));
                    if (empty($slug)) {
                        $slug = 'cliente-' . time();
                    }

                    $tenantData = [
                        'nombre_empresa' => $nombreEmpresa,
                        'nif_cif' => $nifCif,
                        'slug' => $slug,
                        'plan_suscripcion' => $planSuscripcion
                    ];

                    $clinicData = [
                        'nombre_comercial' => $nombreComercial,
                        'direccion_calle' => trim($_POST['direccion_calle'] ?? 'Calle Principal 1'),
                        'ciudad' => $ciudad,
                        'provincia' => $provincia,
                        'cp' => trim($_POST['cp'] ?? '28001'),
                        'telefono_contacto' => trim($_POST['telefono_contacto'] ?? '910000000')
                    ];

                    $adminData = [
                        'usuario_id' => $adminDni,
                        'nombre' => $adminNombre,
                        'apellidos' => $adminApellidos,
                        'email' => $adminEmail,
                        'pass' => $adminPass,
                        'telefono' => trim($_POST['telefono_contacto'] ?? null),
                        'genero' => $_POST['genero'] ?? 'Hombre'
                    ];

                    $cuentaId = $saasModel->createTenantAccount($tenantData, $clinicData, $adminData);

                    $_SESSION['saas_status_msg'] = [
                        'type' => 'success',
                        'text' => "¡Cliente '{$nombreEmpresa}' creado exitosamente! ID de Cuenta: {$cuentaId}"
                    ];

                    header('Location: ' . PROJECT_ROOT . '/saas-admin/customer/list');
                    exit;
                } catch (Exception $e) {
                    $errorMessage = 'Error al crear la cuenta cliente: ' . $e->getMessage();
                }
            }
        }

        $data = [
            'pageTitle' => 'Alta de Nuevo Cliente SaaS',
            'errorMessage' => $errorMessage
        ];

        $this->view('saas-admin/form', $data);
    }

    /**
     * Endpoint POST para cambiar estado de cuenta (Activo / Suspendido / Cancelado)
     */
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cuentaId = (int)($_POST['cuenta_id'] ?? 0);
            $status = trim($_POST['estado_cuenta'] ?? '');

            if ($cuentaId > 0 && !empty($status)) {
                $saasModel = $this->model('SaasAdmin');
                $success = $saasModel->updateTenantStatus($cuentaId, $status);

                if ($success) {
                    $_SESSION['saas_status_msg'] = [
                        'type' => 'success',
                        'text' => "Estado de la cuenta #{$cuentaId} actualizado a '{$status}'."
                    ];
                } else {
                    $_SESSION['saas_status_msg'] = [
                        'type' => 'error',
                        'text' => "No se pudo actualizar el estado de la cuenta #{$cuentaId}."
                    ];
                }
            }
        }

        header('Location: ' . PROJECT_ROOT . '/saas-admin/customer/list');
        exit;
    }

    /**
     * Endpoint POST para cambiar el plan de suscripción del cliente
     */
    public function updatePlan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cuentaId = (int)($_POST['cuenta_id'] ?? 0);
            $plan = trim($_POST['plan_suscripcion'] ?? '');
            $redirectUrl = trim($_POST['redirect'] ?? '/saas-admin/customer/list');

            if ($cuentaId > 0 && !empty($plan)) {
                $saasModel = $this->model('SaasAdmin');
                $success = $saasModel->updateTenantPlan($cuentaId, $plan);

                if ($success) {
                    $_SESSION['saas_status_msg'] = [
                        'type' => 'success',
                        'text' => "Plan de la cuenta #{$cuentaId} actualizado a '{$plan}'."
                    ];
                } else {
                    $_SESSION['saas_status_msg'] = [
                        'type' => 'error',
                        'text' => "No se pudo actualizar el plan de la cuenta #{$cuentaId}."
                    ];
                }
            }

            header('Location: ' . PROJECT_ROOT . $redirectUrl);
            exit;
        }

        header('Location: ' . PROJECT_ROOT . '/saas-admin/customer/list');
        exit;
    }

    /**
     * Gestión de Planes de Suscripción y Facturación Recurrente SaaS
     */
    // public function planes()
    // {
    //     $saasModel = $this->model('SaasAdmin');

    //     $metrics = $saasModel->getGlobalMetrics();
    //     $planDistribution = $saasModel->getPlanDistribution();
    //     $subscriptions = $saasModel->getSubscriptionBillingSummary();

    //     $data = [
    //         'pageTitle' => 'Administración de Planes y Suscripciones',
    //         'metrics' => $metrics,
    //         'planDistribution' => $planDistribution,
    //         'subscriptions' => $subscriptions,
    //         'statusMessage' => $_SESSION['saas_status_msg'] ?? null
    //     ];

    //     unset($_SESSION['saas_status_msg']);

    //     $this->view('superadmin/planes', $data);
    // }

    /**
     * Gestión y Listado de Facturas SaaS B2B emitidas a las Clínicas
     */
    public function invoices()
    {
        $saasModel = $this->model('SaasAdmin');

        $filters = [
            'cuenta_id' => $_GET['cuenta_id'] ?? null,
            'estado' => $_GET['estado'] ?? null,
            'q' => $_GET['q'] ?? null
        ];

        $invoices = $saasModel->getSaasInvoices($filters);
        $tenants = $saasModel->getAllTenants();

        $data = [
            'pageTitle' => 'Facturación B2B',
            'invoices' => $invoices,
            'tenants' => $tenants,
            'filters' => $filters,
            'statusMessage' => $_SESSION['saas_status_msg'] ?? null
        ];

        unset($_SESSION['saas_status_msg']);

        $this->view('saas-admin/invoice/list', $data);
    }

    /**
     * Formulario y procesamiento para emitir una nueva factura B2B a una clínica
     */
    public function createInvoice()
    {
        $saasModel = $this->model('SaasAdmin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cuentaId = (int)($_POST['cuenta_id'] ?? 0);
            $base = (float)($_POST['base_imponible'] ?? 0);
            $concepto = trim((string)($_POST['concepto'] ?? ''));

            if ($cuentaId <= 0 || $base <= 0 || $concepto === '') {
                $data = [
                    'pageTitle' => 'Emitir Factura SaaS a Clínica',
                    'tenants' => $saasModel->getAllTenants(),
                    'errorMessage' => 'Por favor, selecciona una clínica cliente, indica un concepto válido y un importe base mayor a 0.'
                ];
                $this->view('saas-admin/invoice/form', $data);
                return;
            }

            $newInvoiceId = $saasModel->createSaasInvoice([
                'cuenta_id' => $cuentaId,
                'concepto' => $concepto,
                'plan_suscripcion' => $_POST['plan_suscripcion'] ?? 'Profesional',
                'base_imponible' => $base,
                'tipo_iva' => (float)($_POST['tipo_iva'] ?? 21.00),
                'fecha_emision' => $_POST['fecha_emision'] ?? date('Y-m-d'),
                'fecha_vencimiento' => $_POST['fecha_vencimiento'] ?? date('Y-m-d', strtotime('+15 days')),
                'estado' => $_POST['estado'] ?? 'Pendiente',
                'metodo_pago' => $_POST['metodo_pago'] ?? 'Tarjeta',
                'notas' => $_POST['notas'] ?? ''
            ]);

            if ($newInvoiceId > 0) {
                $_SESSION['saas_status_msg'] = [
                    'type' => 'success',
                    'text' => "Factura SaaS #{$newInvoiceId} emitida correctamente a la clínica."
                ];
                header('Location: ' . PROJECT_ROOT . '/saas-admin/invoice/list');
                exit;
            } else {
                $data = [
                    'pageTitle' => 'Emitir Factura SaaS a Clínica',
                    'tenants' => $saasModel->getAllTenants(),
                    'errorMessage' => 'Ocurrió un error al guardar la factura SaaS.'
                ];
                $this->view('superadmin/create_invoice', $data);
                return;
            }
        }

        $tenants = $saasModel->getAllTenants();
        $selectedCuentaId = (int)($_GET['cuenta_id'] ?? 0);

        $data = [
            'pageTitle' => 'Emitir Nueva Factura SaaS a Clínica',
            'tenants' => $tenants,
            'selectedCuentaId' => $selectedCuentaId,
            'errorMessage' => null
        ];

        $this->view('saas-admin/customer/list', $data);
    }

    /**
     * Actualiza el estado de cobro de una factura SaaS emitida a una clínica
     */
    public function updateInvoiceStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['factura_saas_id'] ?? 0);
            $estado = trim((string)($_POST['estado'] ?? ''));

            if ($id > 0 && !empty($estado)) {
                $saasModel = $this->model('SaasAdmin');
                $success = $saasModel->updateSaasInvoiceStatus($id, $estado);

                if ($success) {
                    $_SESSION['saas_status_msg'] = [
                        'type' => 'success',
                        'text' => "Estado de la factura SaaS #{$id} actualizado a '{$estado}'."
                    ];
                }
            }
        }

        header('Location: ' . PROJECT_ROOT . '/saas-admin/invoice/list');
        exit;
    }
}
