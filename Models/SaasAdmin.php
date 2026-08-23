<?php
namespace App\Models;

use App\Core\DataBase;
use PDO;
use Exception;

class SaasAdmin
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    /**
     * Obtiene métricas consolidadas de la plataforma SaaS (MRR, inquilinos, usuarios globales).
     */
    public function getGlobalMetrics()
    {
        // 1. Conteo de cuentas de clientes
        $stmtTenants = $this->db->query("SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN estado_cuenta = 'Activo' THEN 1 ELSE 0 END) as activos,
            SUM(CASE WHEN estado_cuenta = 'Suspendido' THEN 1 ELSE 0 END) as suspendidos,
            SUM(CASE WHEN estado_cuenta = 'Cancelado' THEN 1 ELSE 0 END) as cancelados
            FROM cuentas_clientes");
        $tenantMetrics = $stmtTenants->fetch(PDO::FETCH_ASSOC);

        // 2. Usuarios globales y pacientes
        $stmtUsers = $this->db->query("SELECT 
            COUNT(*) as total_usuarios,
            SUM(CASE WHEN rol = 'Paciente' THEN 1 ELSE 0 END) as total_pacientes,
            SUM(CASE WHEN rol = 'Fisioterapeuta' THEN 1 ELSE 0 END) as total_fisios
            FROM usuarios");
        $userMetrics = $stmtUsers->fetch(PDO::FETCH_ASSOC);

        // 3. Citas totales en plataforma
        $stmtCitas = $this->db->query("SELECT COUNT(*) as total_citas FROM citas");
        $citasMetrics = $stmtCitas->fetch(PDO::FETCH_ASSOC);

        // 4. Cálculo de MRR (Monthly Recurring Revenue) basado en los planes activos
        // Básico: 29€ | Profesional: 79€ | Premium: 199€
        $stmtMrr = $this->db->query("SELECT plan_suscripcion, COUNT(*) as cantidad 
            FROM cuentas_clientes 
            WHERE estado_cuenta = 'Activo' 
            GROUP BY plan_suscripcion");
        $mrrRows = $stmtMrr->fetchAll(PDO::FETCH_ASSOC);

        $mrrTotal = 0;
        $planPrices = [
            'Basico' => 29.00,
            'Profesional' => 79.00,
            'Premium' => 199.00
        ];

        foreach ($mrrRows as $row) {
            $price = $planPrices[$row['plan_suscripcion']] ?? 0;
            $mrrTotal += ($price * (int)$row['cantidad']);
        }

        return [
            'totalTenants' => (int)($tenantMetrics['total'] ?? 0),
            'activeTenants' => (int)($tenantMetrics['activos'] ?? 0),
            'suspendedTenants' => (int)($tenantMetrics['suspendidos'] ?? 0),
            'cancelTenants' => (int)($tenantMetrics['cancelados'] ?? 0),
            'totalUsers' => (int)($userMetrics['total_usuarios'] ?? 0),
            'totalPatients' => (int)($userMetrics['total_pacientes'] ?? 0),
            'totalPhysios' => (int)($userMetrics['total_fisios'] ?? 0),
            'totalAppointments' => (int)($citasMetrics['total_citas'] ?? 0),
            'mrr' => $mrrTotal
        ];
    }

    /**
     * Obtiene el desglose de clientes por plan de suscripción.
     */
    public function getPlanDistribution()
    {
        $stmt = $this->db->query("SELECT plan_suscripcion, COUNT(*) as total 
            FROM cuentas_clientes 
            GROUP BY plan_suscripcion");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [
            'Basico' => 0,
            'Profesional' => 0,
            'Premium' => 0
        ];

        foreach ($rows as $row) {
            if (isset($result[$row['plan_suscripcion']])) {
                $result[$row['plan_suscripcion']] = (int)$row['total'];
            }
        }

        return $result;
    }

    /**
     * Obtiene el listado completo de cuentas clientes (tenants) con su clínica y métricas clave.
     */
    public function getAllTenants()
    {
        $query = "SELECT cc.*, 
                    cl.nombre_comercial, cl.ciudad, cl.telefono_contacto,
                    (SELECT COUNT(*) FROM usuarios u WHERE u.cuenta_id = cc.cuenta_id) as total_usuarios,
                    (SELECT COUNT(*) FROM usuarios u WHERE u.cuenta_id = cc.cuenta_id AND u.rol = 'Paciente') as total_pacientes,
                    (SELECT COUNT(*) FROM citas c WHERE c.cuenta_id = cc.cuenta_id) as total_citas
                  FROM cuentas_clientes cc
                  LEFT JOIN clinicas cl ON cl.cuenta_id = cc.cuenta_id
                  ORDER BY cc.fecha_alta DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los detalles de una cuenta de cliente por su ID.
     */
    public function getTenantById($cuentaId)
    {
        $query = "SELECT cc.*, cl.* 
                  FROM cuentas_clientes cc
                  LEFT JOIN clinicas cl ON cl.cuenta_id = cc.cuenta_id
                  WHERE cc.cuenta_id = :cuenta_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':cuenta_id' => $cuentaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Alta de una nueva cuenta cliente (Tenant) con su clínica y administrador inicial.
     */
    public function createTenantAccount(array $tenantData, array $clinicData, array $adminData)
    {
        try {
            $this->db->beginTransaction();

            // 1. Insertar en cuentas_clientes
            $sqlTenant = "INSERT INTO cuentas_clientes (nombre_empresa, nif_cif, slug, plan_suscripcion, estado_cuenta, email_admin, fecha_alta, fecha_renovacion) 
                          VALUES (:nombre_empresa, :nif_cif, :slug, :plan_suscripcion, 'Activo', :email_admin, NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH))";
            $stmtTenant = $this->db->prepare($sqlTenant);
            $stmtTenant->execute([
                ':nombre_empresa' => $tenantData['nombre_empresa'],
                ':nif_cif' => $tenantData['nif_cif'],
                ':slug' => $tenantData['slug'],
                ':plan_suscripcion' => $tenantData['plan_suscripcion'] ?? 'Profesional',
                ':email_admin' => $adminData['email']
            ]);

            $cuentaId = (int)$this->db->lastInsertId();

            // 2. Insertar clínica principal
            $sqlClinic = "INSERT INTO clinicas (cuenta_id, nombre_comercial, razon_social, nif_cif, direccion_calle, ciudad, provincia_estado, codigo_postal, pais, telefono_contacto, email_contacto) 
                          VALUES (:cuenta_id, :nombre_comercial, :razon_social, :nif_cif, :direccion_calle, :ciudad, :provincia_estado, :codigo_postal, :pais, :telefono_contacto, :email_contacto)";
            $stmtClinic = $this->db->prepare($sqlClinic);
            $stmtClinic->execute([
                ':cuenta_id' => $cuentaId,
                ':nombre_comercial' => $clinicData['nombre_comercial'],
                ':razon_social' => $tenantData['nombre_empresa'],
                ':nif_cif' => $tenantData['nif_cif'],
                ':direccion_calle' => $clinicData['direccion_calle'] ?? 'Dirección por definir',
                ':ciudad' => $clinicData['ciudad'] ?? 'Ciudad',
                ':provincia_estado' => $clinicData['provincia'] ?? 'Provincia',
                ':codigo_postal' => $clinicData['cp'] ?? '28001',
                ':pais' => 'España',
                ':telefono_contacto' => $clinicData['telefono_contacto'] ?? '900000000',
                ':email_contacto' => $adminData['email']
            ]);

            // 3. Insertar usuario Administrador inicial para la clínica
            $sqlAdmin = "INSERT INTO usuarios (usuario_id, cuenta_id, nombre, apellidos, telefono, fecha_nacimiento, email, pass, genero, rol, rgpd_aceptado, fecha_consentimiento) 
                         VALUES (:usuario_id, :cuenta_id, :nombre, :apellidos, :telefono, :fecha_nacimiento, :email, :pass, :genero, 'Administrador', 1, NOW())";
            $stmtAdmin = $this->db->prepare($sqlAdmin);
            $stmtAdmin->execute([
                ':usuario_id' => $adminData['usuario_id'],
                ':cuenta_id' => $cuentaId,
                ':nombre' => $adminData['nombre'],
                ':apellidos' => $adminData['apellidos'],
                ':telefono' => $adminData['telefono'] ?? null,
                ':fecha_nacimiento' => $adminData['fecha_nacimiento'] ?? '1990-01-01',
                ':email' => $adminData['email'],
                ':pass' => password_hash($adminData['pass'], PASSWORD_BCRYPT),
                ':genero' => $adminData['genero'] ?? 'Otro'
            ]);

            $this->db->commit();
            return $cuentaId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Cambia el estado de una cuenta cliente (Activo, Suspendido, Cancelado).
     */
    public function updateTenantStatus($cuentaId, $status)
    {
        $allowed = ['Activo', 'Suspendido', 'Cancelado'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE cuentas_clientes SET estado_cuenta = :status WHERE cuenta_id = :cuenta_id");
        return $stmt->execute([
            ':status' => $status,
            ':cuenta_id' => $cuentaId
        ]);
    }

    /**
     * Cambia el plan de suscripción de una cuenta cliente (Basico, Profesional, Premium).
     */
    public function updateTenantPlan($cuentaId, $plan)
    {
        $allowed = ['Basico', 'Profesional', 'Premium'];
        if (!in_array($plan, $allowed, true)) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE cuentas_clientes SET plan_suscripcion = :plan WHERE cuenta_id = :cuenta_id");
        return $stmt->execute([
            ':plan' => $plan,
            ':cuenta_id' => $cuentaId
        ]);
    }

    /**
     * Obtiene las facturas SaaS emitidas a las clínicas (cuentas_clientes).
     */
    public function getSaasInvoices($filters = [])
    {
        $query = "SELECT fs.*, cc.nombre_empresa, cc.nif_cif, cc.email_admin 
                  FROM facturas_saas fs 
                  JOIN cuentas_clientes cc ON fs.cuenta_id = cc.cuenta_id";

        $where = [];
        $params = [];

        if (!empty($filters['cuenta_id'])) {
            $where[] = "fs.cuenta_id = :cuenta_id";
            $params[':cuenta_id'] = $filters['cuenta_id'];
        }

        if (!empty($filters['estado'])) {
            $where[] = "fs.estado = :estado";
            $params[':estado'] = $filters['estado'];
        }

        if (!empty($filters['q'])) {
            $where[] = "(cc.nombre_empresa LIKE :q OR cc.nif_cif LIKE :q OR fs.concepto LIKE :q OR fs.numero LIKE :q)";
            $params[':q'] = "%" . $filters['q'] . "%";
        }

        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }

        $query .= " ORDER BY fs.fecha_emision DESC, fs.factura_saas_id DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el detalle completo de una factura SaaS emitida a una clínica por su ID.
     */
    public function getSaasInvoiceById($id)
    {
        $query = "SELECT fs.*, cc.nombre_empresa, cc.nif_cif, cc.email_admin, cl.nombre_comercial, cl.direccion_calle, cl.ciudad, cl.provincia_estado, cl.codigo_postal
                  FROM facturas_saas fs
                  JOIN cuentas_clientes cc ON fs.cuenta_id = cc.cuenta_id
                  LEFT JOIN clinicas cl ON cl.cuenta_id = cc.cuenta_id
                  WHERE fs.factura_saas_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Emite una nueva factura SaaS a una clínica cliente.
     */
    public function createSaasInvoice(array $data)
    {
        // 1. Obtener número secuencial de factura para serie SAAS
        $stmtUltima = $this->db->query("SELECT MAX(numero) as max_num FROM facturas_saas WHERE serie = 'SAAS'");
        $rowUltima = $stmtUltima->fetch(PDO::FETCH_ASSOC);
        $siguienteNumero = ((int)($rowUltima['max_num'] ?? 0)) + 1;

        // 2. Cálculos económicos
        $base = round((float)$data['base_imponible'], 2);
        $tipoIva = round((float)($data['tipo_iva'] ?? 21.00), 2);
        $cuotaIva = round($base * ($tipoIva / 100), 2);
        $total = round($base + $cuotaIva, 2);

        $sql = "INSERT INTO facturas_saas (cuenta_id, serie, numero, fecha_emision, fecha_vencimiento, concepto, plan_suscripcion, base_imponible, tipo_iva, cuota_iva, total, estado, metodo_pago, notas) 
                VALUES (:cuenta_id, 'SAAS', :numero, :fecha_emision, :fecha_vencimiento, :concepto, :plan_suscripcion, :base_imponible, :tipo_iva, :cuota_iva, :total, :estado, :metodo_pago, :notas)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':cuenta_id' => $data['cuenta_id'],
            ':numero' => $siguienteNumero,
            ':fecha_emision' => $data['fecha_emision'] ?? date('Y-m-d'),
            ':fecha_vencimiento' => $data['fecha_vencimiento'] ?? date('Y-m-d', strtotime('+15 days')),
            ':concepto' => $data['concepto'],
            ':plan_suscripcion' => $data['plan_suscripcion'] ?? 'Profesional',
            ':base_imponible' => $base,
            ':tipo_iva' => $tipoIva,
            ':cuota_iva' => $cuotaIva,
            ':total' => $total,
            ':estado' => $data['estado'] ?? 'Pendiente',
            ':metodo_pago' => $data['metodo_pago'] ?? 'Tarjeta',
            ':notas' => $data['notas'] ?? null
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Cambia el estado de cobro de una factura SaaS emitida a una clínica.
     */
    public function updateSaasInvoiceStatus($facturaSaasId, $estado)
    {
        $allowed = ['Pendiente', 'Pagada', 'Vencida', 'Cancelada'];
        if (!in_array($estado, $allowed, true)) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE facturas_saas SET estado = :estado WHERE factura_saas_id = :id");
        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $facturaSaasId
        ]);
    }

    /**
     * Obtiene todos los planes de suscripción configurados en la base de datos.
     */
    public function getSaasPlans()
    {
        $stmt = $this->db->query("SELECT * FROM planes_suscripcion ORDER BY plan_id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Resumen de facturación de suscripciones SaaS activas por cliente.
     */
    public function getSubscriptionBillingSummary()
    {
        $query = "SELECT cc.cuenta_id, cc.nombre_empresa, cc.nif_cif, cc.plan_suscripcion, cc.estado_cuenta, cc.fecha_renovacion,
                         COALESCE(ps.precio_mensual, CASE 
                            WHEN cc.plan_suscripcion = 'Premium' THEN 199.00
                            WHEN cc.plan_suscripcion = 'Profesional' THEN 79.00
                            ELSE 29.00 
                          END) as precio_mensual
                  FROM cuentas_clientes cc
                  LEFT JOIN planes_suscripcion ps ON cc.plan_suscripcion = ps.codigo
                  ORDER BY cc.estado_cuenta ASC, cc.nombre_empresa ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
