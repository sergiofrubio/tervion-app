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
        $query = "INSERT INTO nominas (contrato_id, mes, anio, liquido_percepcion, bruto, deduccion_ss, deduccion_irpf, coste_empresa_ss, pagada) 
                  VALUES (:contrato_id, :mes, :anio, :liquido_percepcion, :bruto, :deduccion_ss, :deduccion_irpf, :coste_empresa_ss, :pagada)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':contrato_id', $data['contrato_id']);
        $stmt->bindParam(':mes', $data['mes']);
        $stmt->bindParam(':anio', $data['anio']);
        $stmt->bindParam(':liquido_percepcion', $data['liquido_percepcion']);
        $stmt->bindParam(':bruto', $data['bruto']);
        $stmt->bindParam(':deduccion_ss', $data['deduccion_ss']);
        $stmt->bindParam(':deduccion_irpf', $data['deduccion_irpf']);
        $stmt->bindParam(':coste_empresa_ss', $data['coste_empresa_ss']);
        $stmt->bindParam(':pagada', $data['pagada'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getPayrollsByWorker($usuario_id)
    {
        $query = "SELECT n.*, u.nombre, u.apellidos, c.tipo_contrato 
                  FROM nominas n 
                  JOIN contratos c ON n.contrato_id = c.contrato_id 
                  JOIN usuarios u ON c.usuario_id = u.usuario_id
                  WHERE u.usuario_id = :usuario_id
                  ORDER BY n.anio DESC, n.mes DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePayroll($nomina_id)
    {
        $query = "DELETE FROM nominas WHERE nomina_id = :nomina_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nomina_id', $nomina_id);
        return $stmt->execute();
    }
}
