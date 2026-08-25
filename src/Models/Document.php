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

    /**
     * Obtiene todas las plantillas disponibles de la clínica junto con el estado de firma para un paciente específico.
     */
    public function getByPacienteWithTemplates($paciente_id, $cuenta_id = 1)
    {
        $sql = "SELECT 
                    d.documento_id,
                    d.cuenta_id,
                    d.titulo,
                    d.descripcion,
                    d.contenido as plantilla_contenido,
                    d.fecha_creacion as plantilla_fecha_creacion,
                    dp.id as paciente_doc_id,
                    dp.contenido_firmado,
                    dp.firma_paciente,
                    dp.firmado,
                    dp.fecha_firma,
                    dp.creado_por as firmado_creado_por,
                    dp.fecha_creacion as asignado_fecha_creacion
                FROM documentos d
                LEFT JOIN documentos_pacientes dp 
                    ON d.documento_id = dp.documento_id 
                    AND dp.paciente_id = :paciente_id 
                    AND dp.cuenta_id = :cuenta_id
                WHERE d.cuenta_id = :cuenta_id
                ORDER BY d.fecha_creacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':paciente_id', $paciente_id, PDO::PARAM_STR);
        $stmt->bindValue(':cuenta_id', $cuenta_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el registro de un documento cumplimentado/firmado por paciente_id y documento_id.
     */
    public function getPacienteDocumento($paciente_id, $documento_id, $cuenta_id = 1)
    {
        $sql = "SELECT 
                    dp.*,
                    d.titulo,
                    d.descripcion,
                    d.contenido as plantilla_contenido
                FROM documentos_pacientes dp
                JOIN documentos d ON dp.documento_id = d.documento_id
                WHERE dp.paciente_id = :paciente_id 
                  AND dp.documento_id = :documento_id 
                  AND dp.cuenta_id = :cuenta_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':paciente_id', $paciente_id, PDO::PARAM_STR);
        $stmt->bindValue(':documento_id', $documento_id, PDO::PARAM_INT);
        $stmt->bindValue(':cuenta_id', $cuenta_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un documento de paciente por su ID primario.
     */
    public function getPacienteDocumentoById($id, $cuenta_id = 1)
    {
        $sql = "SELECT 
                    dp.*,
                    d.titulo,
                    d.descripcion,
                    d.contenido as plantilla_contenido
                FROM documentos_pacientes dp
                JOIN documentos d ON dp.documento_id = d.documento_id
                WHERE dp.id = :id AND dp.cuenta_id = :cuenta_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':cuenta_id', $cuenta_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Guarda o actualiza un documento de paciente cumplimentado/firmado.
     */
    public function savePacienteDocumento($data)
    {
        $cuenta_id = $data['cuenta_id'] ?? 1;
        $documento_id = $data['documento_id'];
        $paciente_id = $data['paciente_id'];
        $contenido_firmado = $data['contenido_firmado'];
        $firma_paciente = $data['firma_paciente'] ?? null;
        $firmado = !empty($firma_paciente) ? 1 : ($data['firmado'] ?? 0);
        $fecha_firma = $firmado ? ($data['fecha_firma'] ?? date('Y-m-d H:i:s')) : null;
        $usuario_actual = $data['usuario_id'] ?? null;

        // Comprobar si ya existe
        $existing = $this->getPacienteDocumento($paciente_id, $documento_id, $cuenta_id);

        if ($existing) {
            $sql = "UPDATE documentos_pacientes 
                    SET contenido_firmado = :contenido_firmado,
                        firma_paciente = :firma_paciente,
                        firmado = :firmado,
                        fecha_firma = :fecha_firma,
                        modificado_por = :modificado_por
                    WHERE id = :id AND cuenta_id = :cuenta_id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':contenido_firmado', $contenido_firmado);
            $stmt->bindValue(':firma_paciente', $firma_paciente);
            $stmt->bindValue(':firmado', $firmado, PDO::PARAM_INT);
            $stmt->bindValue(':fecha_firma', $fecha_firma);
            $stmt->bindValue(':modificado_por', $usuario_actual);
            $stmt->bindValue(':id', $existing['id'], PDO::PARAM_INT);
            $stmt->bindValue(':cuenta_id', $cuenta_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $existing['id'];
            }
            return false;
        } else {
            $sql = "INSERT INTO documentos_pacientes 
                    (cuenta_id, documento_id, paciente_id, contenido_firmado, firma_paciente, firmado, fecha_firma, creado_por)
                    VALUES 
                    (:cuenta_id, :documento_id, :paciente_id, :contenido_firmado, :firma_paciente, :firmado, :fecha_firma, :creado_por)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':cuenta_id', $cuenta_id, PDO::PARAM_INT);
            $stmt->bindValue(':documento_id', $documento_id, PDO::PARAM_INT);
            $stmt->bindValue(':paciente_id', $paciente_id, PDO::PARAM_STR);
            $stmt->bindValue(':contenido_firmado', $contenido_firmado);
            $stmt->bindValue(':firma_paciente', $firma_paciente);
            $stmt->bindValue(':firmado', $firmado, PDO::PARAM_INT);
            $stmt->bindValue(':fecha_firma', $fecha_firma);
            $stmt->bindValue(':creado_por', $usuario_actual);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        }
    }
}

