<?php
namespace App\Models;

use App\Core\DataBase;
use PDO;

class AppointmentType
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getAllActive()
    {
        $query = "SELECT * FROM tipos_citas WHERE estado = 'Activo' ORDER BY nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($tipo_cita_id)
    {
        $query = "SELECT * FROM tipos_citas WHERE tipo_cita_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $tipo_cita_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
