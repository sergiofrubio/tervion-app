<?php

namespace App\Models;

use App\Core\DataBase;
use PDO;

class Document
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getAll($cuenta_id = 1, $filtro = '')
    {
        $sql = "SELECT d.*, u.nombre as creador_nombre, u.apellidos as creador_apellidos
                FROM documentos d
                LEFT JOIN usuarios u ON d.creado_por = u.usuario_id
                WHERE d.cuenta_id = :cuenta_id";

        if (!empty($filtro)) {
            $sql .= " AND (d.titulo LIKE :filtro OR d.descripcion LIKE :filtro)";
        }

        $sql .= " ORDER BY d.fecha_creacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cuenta_id', $cuenta_id, PDO::PARAM_INT);

        if (!empty($filtro)) {
            $stmt->bindValue(':filtro', '%' . $filtro . '%');
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id, $cuenta_id = 1)
    {
        $sql = "SELECT d.*, u.nombre as creador_nombre, u.apellidos as creador_apellidos
                FROM documentos d
                LEFT JOIN usuarios u ON d.creado_por = u.usuario_id
                WHERE d.documento_id = :id AND d.cuenta_id = :cuenta_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':cuenta_id', $cuenta_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO documentos (cuenta_id, titulo, descripcion, contenido, creado_por)
                VALUES (:cuenta_id, :titulo, :descripcion, :contenido, :creado_por)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cuenta_id', $data['cuenta_id'] ?? 1, PDO::PARAM_INT);
        $stmt->bindValue(':titulo', $data['titulo']);
        $stmt->bindValue(':descripcion', $data['descripcion'] ?? null);
        $stmt->bindValue(':contenido', $data['contenido']);
        $stmt->bindValue(':creado_por', $data['creado_por'] ?? null);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function update($id, $data, $cuenta_id = 1)
    {
        $sql = "UPDATE documentos
                SET titulo = :titulo,
                    descripcion = :descripcion,
                    contenido = :contenido,
                    modificado_por = :modificado_por
                WHERE documento_id = :id AND cuenta_id = :cuenta_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':titulo', $data['titulo']);
        $stmt->bindValue(':descripcion', $data['descripcion'] ?? null);
        $stmt->bindValue(':contenido', $data['contenido']);
        $stmt->bindValue(':modificado_por', $data['modificado_por'] ?? null);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':cuenta_id', $cuenta_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id, $cuenta_id = 1)
    {
        $sql = "DELETE FROM documentos WHERE documento_id = :id AND cuenta_id = :cuenta_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':cuenta_id', $cuenta_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
