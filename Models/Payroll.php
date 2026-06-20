<?php
namespace App\Models;
use App\Core\DataBase;
use PDO;

class Payroll
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    // --- Empleados ---
    public function getEmployeeData($usuario_id)
    {
        $query = "SELECT e.*, u.nombre, u.apellidos, u.email, u.direccion, u.cp, u.municipio, u.provincia 
                  FROM empleados e 
                  JOIN usuarios u ON e.usuario_id = u.usuario_id 
                  WHERE e.usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveEmployee($data)
    {
        $query = "INSERT INTO empleados (usuario_id, nss, iban, grupo_cotizacion) 
                  VALUES (:usuario_id, :nss, :iban, :grupo_cotizacion)
                  ON DUPLICATE KEY UPDATE nss = :nss, iban = :iban, grupo_cotizacion = :grupo_cotizacion";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':usuario_id' => $data['usuario_id'],
            ':nss' => $data['nss'],
            ':iban' => $data['iban'],
            ':grupo_cotizacion' => $data['grupo_cotizacion']
        ]);
    }

    // --- Contratos ---
    public function getAllContracts()
    {
        $query = "SELECT c.*, u.nombre, u.apellidos FROM contratos c 
                  JOIN usuarios u ON c.usuario_id = u.usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveContracts()
    {
        $query = "SELECT c.*, u.nombre, u.apellidos, u.email, u.usuario_id as dni, u.direccion, u.cp, u.municipio, u.provincia,
                         e.nss, e.iban, e.grupo_cotizacion
                  FROM contratos c
                  JOIN usuarios u ON c.usuario_id = u.usuario_id
                  LEFT JOIN empleados e ON u.usuario_id = e.usuario_id
                  WHERE c.activo = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContract($contrato_id)
    {
        $query = "SELECT c.*, u.nombre, u.apellidos FROM contratos c 
                  JOIN usuarios u ON c.usuario_id = u.usuario_id 
                  WHERE c.contrato_id = :contrato_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':contrato_id', $contrato_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getActiveContract($usuario_id)
    {
        $query = "SELECT * FROM contratos WHERE usuario_id = :usuario_id AND activo = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveContract($data)
    {
        if (isset($data['contrato_id'])) {
            $query = "UPDATE contratos SET 
                        fecha_inicio = :fecha_inicio, 
                        fecha_fin = :fecha_fin, 
                        tipo_contrato = :tipo_contrato, 
                        salario_base_mensual = :salario_base_mensual, 
                        complementos_mensuales = :complementos_mensuales, 
                        pagas_extra = :pagas_extra, 
                        irpf_porcentaje = :irpf_porcentaje, 
                        activo = :activo 
                      WHERE contrato_id = :contrato_id";
        } else {
            $query = "INSERT INTO contratos (usuario_id, fecha_inicio, fecha_fin, tipo_contrato, salario_base_mensual, complementos_mensuales, pagas_extra, irpf_porcentaje, activo) 
                      VALUES (:usuario_id, :fecha_inicio, :fecha_fin, :tipo_contrato, :salario_base_mensual, :complementos_mensuales, :pagas_extra, :irpf_porcentaje, :activo)";
        }
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    // --- Nóminas ---
    public function getAllPayrolls($filters = [])
    {
        $query = "SELECT n.*, u.nombre, u.apellidos, c.tipo_contrato 
                  FROM nominas n 
                  JOIN contratos c ON n.contrato_id = c.contrato_id 
                  JOIN usuarios u ON c.usuario_id = u.usuario_id";
        
        $where = [];
        $params = [];
        if (!empty($filters['mes'])) {
            $where[] = "n.mes = :mes";
            $params[':mes'] = $filters['mes'];
        }
        if (!empty($filters['anio'])) {
            $where[] = "n.anio = :anio";
            $params[':anio'] = $filters['anio'];
        }
        
        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }
        
        $query .= " ORDER BY n.anio DESC, n.mes DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPayroll($nomina_id)
    {
        $query = "SELECT n.*, u.nombre, u.apellidos, u.email, u.usuario_id as dni, u.direccion, u.cp, u.municipio, u.provincia,
                         e.nss, e.iban, e.grupo_cotizacion,
                         c.tipo_contrato, c.salario_base_mensual, c.complementos_mensuales, c.irpf_porcentaje
                  FROM nominas n 
                  JOIN contratos c ON n.contrato_id = c.contrato_id 
                  JOIN usuarios u ON c.usuario_id = u.usuario_id 
                  LEFT JOIN empleados e ON u.usuario_id = e.usuario_id
                  WHERE n.nomina_id = :nomina_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nomina_id', $nomina_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPayroll($data)
    {
        $query = "INSERT INTO nominas (contrato_id, mes, anio, fecha_emision, devengos_base, devengos_complementos, devengos_total_bruto, 
                                      deduccion_seguridad_social_trabajador, deduccion_irpf, deducciones_total, liquido_a_percibir, 
                                      coste_seguridad_social_empresa, estado) 
                  VALUES (:contrato_id, :mes, :anio, :fecha_emision, :devengos_base, :devengos_complementos, :devengos_total_bruto, 
                          :deduccion_seguridad_social_trabajador, :deduccion_irpf, :deducciones_total, :liquido_a_percibir, 
                          :coste_seguridad_social_empresa, :estado)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function deletePayroll($nomina_id)
    {
        $query = "DELETE FROM nominas WHERE nomina_id = :nomina_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nomina_id', $nomina_id);
        return $stmt->execute();
    }
}
