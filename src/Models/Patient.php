<?php
namespace App\Models;

use App\Core\DataBase;
use PDO;

class Patient
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    /**
     * Obtiene la ficha del paciente por el usuario_id asociado.
     */
    public function getByUsuarioId($usuario_id)
    {
        $query = "SELECT p.* 
                  FROM pacientes p 
                  WHERE p.usuario_id = :usuario_id 
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':usuario_id', (int)$usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene la ficha por su ID de paciente.
     */
    public function getById($paciente_id)
    {
        $query = "SELECT p.*, u.dni, u.nombre, u.apellidos, u.telefono, u.email, u.fecha_nacimiento, u.direccion, u.municipio, u.provincia, u.cp
                  FROM pacientes p 
                  JOIN usuarios u ON p.usuario_id = u.usuario_id 
                  WHERE p.paciente_id = :paciente_id 
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':paciente_id', (int)$paciente_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Guarda o crea una nueva ficha de paciente.
     */
    public function save($data)
    {
        $query = "INSERT INTO pacientes (
                    cuenta_id, usuario_id, numero_expediente, 
                    nombre_tutor, dni_tutor, telefono_tutor, 
                    contacto_emergencia_nombre, contacto_emergencia_telefono, 
                    compania_seguro, numero_poliza, observaciones_administrativas, alergias_alertas, creado_por
                  ) VALUES (
                    :cuenta_id, :usuario_id, :numero_expediente, 
                    :nombre_tutor, :dni_tutor, :telefono_tutor, 
                    :contacto_emergencia_nombre, :contacto_emergencia_telefono, 
                    :compania_seguro, :numero_poliza, :observaciones_administrativas, :alergias_alertas, :creado_por
                  )";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':cuenta_id', (int)($data['cuenta_id'] ?? 1), PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', (int)$data['usuario_id'], PDO::PARAM_INT);
        $stmt->bindValue(':numero_expediente', !empty($data['numero_expediente']) ? $data['numero_expediente'] : null);
        $stmt->bindValue(':nombre_tutor', !empty($data['nombre_tutor']) ? $data['nombre_tutor'] : null);
        $stmt->bindValue(':dni_tutor', !empty($data['dni_tutor']) ? $data['dni_tutor'] : null);
        $stmt->bindValue(':telefono_tutor', !empty($data['telefono_tutor']) ? $data['telefono_tutor'] : null);
        $stmt->bindValue(':contacto_emergencia_nombre', !empty($data['contacto_emergencia_nombre']) ? $data['contacto_emergencia_nombre'] : null);
        $stmt->bindValue(':contacto_emergencia_telefono', !empty($data['contacto_emergencia_telefono']) ? $data['contacto_emergencia_telefono'] : null);
        $stmt->bindValue(':compania_seguro', !empty($data['compania_seguro']) ? $data['compania_seguro'] : null);
        $stmt->bindValue(':numero_poliza', !empty($data['numero_poliza']) ? $data['numero_poliza'] : null);
        $stmt->bindValue(':observaciones_administrativas', !empty($data['observaciones_administrativas']) ? $data['observaciones_administrativas'] : null);
        $stmt->bindValue(':alergias_alertas', !empty($data['alergias_alertas']) ? $data['alergias_alertas'] : null);
        $stmt->bindValue(':creado_por', !empty($data['creado_por']) ? $data['creado_por'] : null);

        if ($stmt->execute()) {
            return (int)$this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Actualiza o crea la ficha del paciente si no existía.
     */
    public function upsertByUsuarioId($usuario_id, $data)
    {
        $existing = $this->getByUsuarioId($usuario_id);
        if ($existing) {
            $query = "UPDATE pacientes SET 
                        numero_expediente = :numero_expediente,
                        nombre_tutor = :nombre_tutor,
                        dni_tutor = :dni_tutor,
                        telefono_tutor = :telefono_tutor,
                        contacto_emergencia_nombre = :contacto_emergencia_nombre,
                        contacto_emergencia_telefono = :contacto_emergencia_telefono,
                        compania_seguro = :compania_seguro,
                        numero_poliza = :numero_poliza,
                        observaciones_administrativas = :observaciones_administrativas,
                        alergias_alertas = :alergias_alertas,
                        modificado_por = :modificado_por
                      WHERE usuario_id = :usuario_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':usuario_id', (int)$usuario_id, PDO::PARAM_INT);
            $stmt->bindValue(':numero_expediente', !empty($data['numero_expediente']) ? $data['numero_expediente'] : null);
            $stmt->bindValue(':nombre_tutor', !empty($data['nombre_tutor']) ? $data['nombre_tutor'] : null);
            $stmt->bindValue(':dni_tutor', !empty($data['dni_tutor']) ? $data['dni_tutor'] : null);
            $stmt->bindValue(':telefono_tutor', !empty($data['telefono_tutor']) ? $data['telefono_tutor'] : null);
            $stmt->bindValue(':contacto_emergencia_nombre', !empty($data['contacto_emergencia_nombre']) ? $data['contacto_emergencia_nombre'] : null);
            $stmt->bindValue(':contacto_emergencia_telefono', !empty($data['contacto_emergencia_telefono']) ? $data['contacto_emergencia_telefono'] : null);
            $stmt->bindValue(':compania_seguro', !empty($data['compania_seguro']) ? $data['compania_seguro'] : null);
            $stmt->bindValue(':numero_poliza', !empty($data['numero_poliza']) ? $data['numero_poliza'] : null);
            $stmt->bindValue(':observaciones_administrativas', !empty($data['observaciones_administrativas']) ? $data['observaciones_administrativas'] : null);
            $stmt->bindValue(':alergias_alertas', !empty($data['alergias_alertas']) ? $data['alergias_alertas'] : null);
            $stmt->bindValue(':modificado_por', !empty($data['modificado_por']) ? $data['modificado_por'] : null);
            return $stmt->execute();
        } else {
            $data['usuario_id'] = $usuario_id;
            return (bool)$this->save($data);
        }
    }
}
