<?php
namespace App\Models;

use App\Core\DataBase;
use PDO;

class Factura
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getAll($filters = [])
    {
        $query = "SELECT f.*, u.nombre, u.apellidos 
                  FROM facturas f 
                  JOIN usuarios u ON f.paciente_id = u.usuario_id";
        
        $where = [];
        $params = [];

        if (!empty($filters['paciente_id'])) {
            $where[] = "f.paciente_id = :paciente_id";
            $params[':paciente_id'] = $filters['paciente_id'];
        }

        if (!empty($filters['estado'])) {
            $where[] = "f.estado = :estado";
            $params[':estado'] = $filters['estado'];
        }

        if (!empty($filters['q'])) {
            $where[] = "(u.nombre LIKE :q OR u.apellidos LIKE :q OR f.factura_id LIKE :q)";
            $params[':q'] = "%" . $filters['q'] . "%";
        }

        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }

        $query .= " ORDER BY f.fecha_hora_emision DESC, f.numero DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT f.*, u.nombre, u.apellidos, u.direccion, u.municipio, u.provincia, u.cp 
                  FROM facturas f 
                  JOIN usuarios u ON f.paciente_id = u.usuario_id 
                  WHERE f.factura_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUltimaFactura($serie = 'A')
    {
        $query = "SELECT * FROM facturas WHERE serie = :serie ORDER BY numero DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':serie' => $serie]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getClinica()
    {
        $query = "SELECT * FROM clinicas LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data)
    {
        // 1. Obtener datos de encadenamiento Verifactu
        $ultima = $this->getUltimaFactura($data['serie']);
        $data['huella_anterior'] = $ultima ? $ultima['huella'] : null;
        $data['numero'] = $ultima ? ($ultima['numero'] + 1) : 1;
        $data['fecha_hora_emision'] = date('Y-m-d H:i:s');
        
        // 2. Cálculos económicos
        $data['cuota_iva'] = $data['precio'] * ($data['impuesto'] / 100);
        $data['total'] = $data['precio'] + $data['cuota_iva'];

        // 3. Generación de Huella Verifactu
        // Se concatena: NIF Emisor (fijo por ahora), Serie, Número, Fecha ISO, Total y Huella Anterior
        $nif_emisor = "B12345678"; // Debería venir de la tabla clinicas
        $string_to_hash = $nif_emisor . "|" . $data['serie'] . "|" . $data['numero'] . "|" . 
                          $data['fecha_hora_emision'] . "|" . number_format($data['total'], 2, '.', '') . "|" . 
                          ($data['huella_anterior'] ?? '');
        $data['huella'] = hash('sha256', $string_to_hash);

        $query = "INSERT INTO facturas (paciente_id, serie, numero, tipo_factura, fecha_emision, fecha_hora_emision, 
                    estado, descripcion, precio, impuesto, cuota_iva, total, huella, huella_anterior, creado_por) 
                  VALUES (:paciente_id, :serie, :numero, :tipo_factura, :fecha_emision, :fecha_hora_emision, 
                    :estado, :descripcion, :precio, :impuesto, :cuota_iva, :total, :huella, :huella_anterior, :creado_por)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateStatus($id, $estado, $modificado_por)
    {
        $query = "UPDATE facturas SET estado = :estado, modificado_por = :modificado_por WHERE factura_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':estado' => $estado, 
            ':modificado_por' => $modificado_por, 
            ':id' => $id
        ]);
    }

    // public function delete($id)
    // {
    //     // En Verifactu real no se borran, pero dejamos la función para desarrollo
    //     // Idealmente solo permitir borrar la última si no hay encadenamiento posterior
    //     $query = "DELETE FROM facturas WHERE factura_id = :id";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bindParam(':id', $id);
    //     return $stmt->execute();
    // }

    public function getByPaciente($paciente_id)
    {
        $query = "SELECT f.*, u.nombre, u.apellidos 
                  FROM facturas f 
                  JOIN usuarios u ON f.paciente_id = u.usuario_id 
                  WHERE f.paciente_id = :paciente_id
                  ORDER BY f.fecha_hora_emision DESC, f.numero DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPacientes()
    {
        $query = "SELECT u.usuario_id, u.nombre, u.apellidos 
                  FROM usuarios u 
                  JOIN pacientes p ON u.usuario_id = p.usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
