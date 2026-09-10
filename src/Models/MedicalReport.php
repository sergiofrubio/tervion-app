<?php

namespace App\Models;

use App\Core\DataBase;
use PDO;

class MedicalReport
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getById($id)
    {
        $query = "SELECT hm.*, 
                         p.nombre as paciente_nombre, p.apellidos as paciente_apellidos, p.fecha_nacimiento as paciente_fecha_nacimiento, p.genero as paciente_genero,
                         f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos
                  FROM historiales_medicos hm
                  JOIN usuarios p ON hm.paciente_id = p.usuario_id
                  JOIN usuarios f ON hm.terapeuta_id = f.usuario_id
                  WHERE hm.historial_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data)
    {
        $query = "INSERT INTO historiales_medicos (paciente_id, terapeuta_id, fecha_consulta, motivo_consulta, diagnostico, tratamiento, observaciones, creado_por) 
                  VALUES (:paciente_id, :terapeuta_id, :fecha_consulta, :motivo_consulta, :diagnostico, :tratamiento, :observaciones, :creado_por)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':paciente_id', $data['paciente_id']);
        $stmt->bindParam(':terapeuta_id', $data['terapeuta_id']);
        $stmt->bindParam(':fecha_consulta', $data['fecha_consulta']);
        $stmt->bindParam(':motivo_consulta', $data['motivo_consulta']);
        $stmt->bindParam(':diagnostico', $data['diagnostico']);
        $stmt->bindParam(':tratamiento', $data['tratamiento']);
        $stmt->bindParam(':observaciones', $data['observaciones']);
        $stmt->bindParam(':creado_por', $data['creado_por']);
        return $stmt->execute();
    }

    public function getByPaciente($paciente_id)
    {
        $query = "SELECT hm.*
                  FROM historiales_medicos hm
                  WHERE hm.paciente_id = :paciente_id
                  ORDER BY hm.fecha_consulta DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
