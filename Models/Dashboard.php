<?php
namespace App\Models;
use App\Core\DataBase;
use PDO;

class Dashboard
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getTotalPatients()
    {
        $query = "SELECT COUNT(*) as total FROM pacientes";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getMonthlyRevenue()
    {
        $query = "SELECT SUM(total) as total 
                  FROM facturas 
                  WHERE MONTH(fecha_emision) = MONTH(CURRENT_DATE()) 
                  AND YEAR(fecha_emision) = YEAR(CURRENT_DATE()) 
                  AND estado = 'Pagada'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getTodayAppointmentsCount()
    {
        $query = "SELECT COUNT(*) as total FROM citas WHERE DATE(fecha_hora) = CURRENT_DATE()";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getUpcomingAppointments($limit = 4)
    {
        $query = "SELECT c.*, u.nombre as paciente_nombre, u.apellidos as paciente_apellidos, e.descripcion as especialidad 
                  FROM citas c 
                  JOIN usuarios u ON c.paciente_id = u.usuario_id 
                  JOIN especialidades e ON c.especialidad_id = e.especialidad_id 
                  WHERE DATE(c.fecha_hora) >= CURRENT_DATE() 
                  AND c.estado != 'Cancelada'
                  ORDER BY c.fecha_hora ASC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentPatients($limit = 3)
    {
        $query = "SELECT u.* 
                  FROM usuarios u 
                  JOIN pacientes p ON u.usuario_id = p.usuario_id 
                  ORDER BY u.fecha_creacion DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
