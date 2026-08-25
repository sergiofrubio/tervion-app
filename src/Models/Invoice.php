<?php
namespace App\Models;

use App\Core\DataBase;
use App\Services\Verifactu\VerifactuConfig;
use App\Services\Verifactu\VerifactuHashGenerator;
use App\Services\Verifactu\VerifactuQRGenerator;
use PDO;

class Invoice
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

        if (!empty($filters['estado_verifactu'])) {
            $where[] = "f.estado_verifactu = :estado_verifactu";
            $params[':estado_verifactu'] = $filters['estado_verifactu'];
        }

        if (!empty($filters['q'])) {
            $where[] = "(u.nombre LIKE :q OR u.apellidos LIKE :q OR f.factura_id LIKE :q OR f.numero LIKE :q)";
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
        $clinica = $this->getClinica() ?: [];
        $config = new VerifactuConfig($clinica);

        // 1. Obtener datos de encadenamiento Verifactu
        $ultima = $this->getUltimaFactura($data['serie'] ?? 'A');
        $data['huella_anterior'] = $ultima ? $ultima['huella'] : null;
        $data['numero'] = $ultima ? ($ultima['numero'] + 1) : 1;
        $data['fecha_hora_emision'] = date('Y-m-d H:i:s');
        $data['fecha_hora_huso'] = date('c', strtotime($data['fecha_hora_emision']));
        $data['nif_emisor'] = $config->nifEmisor;
        
        // 2. Cálculos económicos
        $data['cuota_iva'] = round((float)$data['precio'] * ((float)$data['impuesto'] / 100), 2);
        $data['total'] = round((float)$data['precio'] + $data['cuota_iva'], 2);

        // 3. Generación de Huella y QR Verifactu
        $fechaExpedicionDate = date('d-m-Y', strtotime($data['fecha_emision']));
        $numSerie = ($data['serie'] ?? 'A') . '-' . $data['numero'];

        $data['huella'] = VerifactuHashGenerator::generateHash(
            $config->nifEmisor,
            $numSerie,
            $fechaExpedicionDate,
            $data['tipo_factura'] ?? 'F1',
            $data['cuota_iva'],
            $data['total'],
            $data['huella_anterior'],
            $data['fecha_hora_huso']
        );

        $data['qr_url'] = VerifactuQRGenerator::generateUrl(
            $config,
            $numSerie,
            $fechaExpedicionDate,
            $data['total']
        );

        $data['estado_verifactu'] = 'Pendiente';

        $query = "INSERT INTO facturas (
                    paciente_id, serie, numero, tipo_factura, nif_emisor, fecha_emision, 
                    fecha_hora_emision, fecha_hora_huso, estado, descripcion, precio, impuesto, 
                    cuota_iva, total, huella, huella_anterior, qr_url, estado_verifactu, creado_por
                  ) VALUES (
                    :paciente_id, :serie, :numero, :tipo_factura, :nif_emisor, :fecha_emision, 
                    :fecha_hora_emision, :fecha_hora_huso, :estado, :descripcion, :precio, :impuesto, 
                    :cuota_iva, :total, :huella, :huella_anterior, :qr_url, :estado_verifactu, :creado_por
                  )";
        
        $stmt = $this->db->prepare($query);
        $res = $stmt->execute([
            ':paciente_id' => $data['paciente_id'],
            ':serie' => $data['serie'] ?? 'A',
            ':numero' => $data['numero'],
            ':tipo_factura' => $data['tipo_factura'] ?? 'F1',
            ':nif_emisor' => $data['nif_emisor'],
            ':fecha_emision' => $data['fecha_emision'],
            ':fecha_hora_emision' => $data['fecha_hora_emision'],
            ':fecha_hora_huso' => $data['fecha_hora_huso'],
            ':estado' => $data['estado'] ?? 'Pendiente',
            ':descripcion' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':impuesto' => $data['impuesto'],
            ':cuota_iva' => $data['cuota_iva'],
            ':total' => $data['total'],
            ':huella' => $data['huella'],
            ':huella_anterior' => $data['huella_anterior'],
            ':qr_url' => $data['qr_url'],
            ':estado_verifactu' => $data['estado_verifactu'],
            ':creado_por' => $data['creado_por'] ?? null
        ]);

        return $res ? (int)$this->db->lastInsertId() : false;
    }

    public function updateVerifactuData($data)
    {
        $query = "UPDATE facturas SET 
                    nif_emisor = :nif_emisor,
                    fecha_hora_huso = :fecha_hora_huso,
                    huella = :huella,
                    qr_url = :qr_url,
                    estado_verifactu = :estado_verifactu,
                    csv_verifactu = :csv_verifactu,
                    codigo_error_verifactu = :codigo_error_verifactu,
                    mensaje_verifactu = :mensaje_verifactu,
                    fecha_envio_verifactu = :fecha_envio_verifactu,
                    xml_peticion = :xml_peticion,
                    xml_respuesta = :xml_respuesta
                  WHERE factura_id = :factura_id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':nif_emisor' => $data['nif_emisor'],
            ':fecha_hora_huso' => $data['fecha_hora_huso'],
            ':huella' => $data['huella'],
            ':qr_url' => $data['qr_url'],
            ':estado_verifactu' => $data['estado_verifactu'],
            ':csv_verifactu' => $data['csv_verifactu'],
            ':codigo_error_verifactu' => $data['codigo_error_verifactu'],
            ':mensaje_verifactu' => $data['mensaje_verifactu'],
            ':fecha_envio_verifactu' => $data['fecha_envio_verifactu'],
            ':xml_peticion' => $data['xml_peticion'],
            ':xml_respuesta' => $data['xml_respuesta'],
            ':factura_id' => $data['factura_id']
        ]);
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
        $query = "SELECT usuario_id, nombre, apellidos 
                  FROM usuarios
                  WHERE rol = 'Paciente'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
