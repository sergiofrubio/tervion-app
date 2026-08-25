<?php
namespace App\Models;
use App\Core\DataBase;
use PDO;

class TimeRecord
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    /**
     * Obtiene el registro activo (entrada sin salida) del usuario para un día.
     */
    public function getActiveRecord($usuario_id, $fecha)
    {
        $query = "SELECT * FROM registro_horario 
                  WHERE usuario_id = :usuario_id 
                    AND fecha = :fecha 
                    AND salida IS NULL 
                  ORDER BY entrada DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el registro por ID.
     */
    public function getById($registro_id)
    {
        $query = "SELECT r.*, u.nombre, u.apellidos 
                  FROM registro_horario r
                  JOIN usuarios u ON r.usuario_id = u.usuario_id
                  WHERE r.registro_id = :registro_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':registro_id', $registro_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registra la entrada (Clock In).
     */
    public function clockIn($usuario_id, $entradaTime = null, $notas = null)
    {
        $fecha = date('Y-m-d');
        $entrada = $entradaTime ?: date('Y-m-d H:i:s');
        
        $query = "INSERT INTO registro_horario (usuario_id, fecha, entrada, notas, creado_por) 
                  VALUES (:usuario_id, :fecha, :entrada, :notas, :creado_por)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':entrada', $entrada);
        $stmt->bindParam(':notas', $notas);
        $stmt->bindParam(':creado_por', $usuario_id);
        return $stmt->execute();
    }

    /**
     * Registra la salida (Clock Out).
     */
    public function clockOut($registro_id, $salidaTime = null)
    {
        $salida = $salidaTime ?: date('Y-m-d H:i:s');
        $query = "UPDATE registro_horario 
                  SET salida = :salida 
                  WHERE registro_id = :registro_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':salida', $salida);
        $stmt->bindParam(':registro_id', $registro_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Obtiene el historial del usuario.
     */
    public function getHistoryByUsuario($usuario_id, $limit = 31)
    {
        $query = "SELECT * FROM registro_horario 
                  WHERE usuario_id = :usuario_id 
                  ORDER BY fecha DESC, entrada DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los registros con filtros (para administración).
     */
    public function getAllRecords($filters = [])
    {
        $sql = "SELECT r.*, u.nombre, u.apellidos, u.rol, e.nss 
                FROM registro_horario r
                JOIN usuarios u ON r.usuario_id = u.usuario_id 
                LEFT JOIN empleados e ON r.usuario_id = e.usuario_id
                WHERE 1=1";
        
        $params = [];

        if (!empty($filters['usuario_id'])) {
            $sql .= " AND r.usuario_id = :usuario_id";
            $params[':usuario_id'] = $filters['usuario_id'];
        }

        if (!empty($filters['fecha_inicio'])) {
            $sql .= " AND r.fecha >= :fecha_inicio";
            $params[':fecha_inicio'] = $filters['fecha_inicio'];
        }

        if (!empty($filters['fecha_fin'])) {
            $sql .= " AND r.fecha <= :fecha_fin";
            $params[':fecha_fin'] = $filters['fecha_fin'];
        }

        $sql .= " ORDER BY r.fecha DESC, r.entrada DESC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Guarda (Inserta o Actualiza) un registro manualmente (Admin).
     */
    public function saveRecord($data)
    {
        if (!empty($data['registro_id'])) {
            // Actualización
            $query = "UPDATE registro_horario 
                      SET usuario_id = :usuario_id, 
                          fecha = :fecha, 
                          entrada = :entrada, 
                          salida = :salida, 
                          notas = :notas,
                          modificado_por = :modificado_por
                      WHERE registro_id = :registro_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':registro_id', $data['registro_id'], PDO::PARAM_INT);
        } else {
            // Inserción
            $query = "INSERT INTO registro_horario (usuario_id, fecha, entrada, salida, notas, creado_por) 
                      VALUES (:usuario_id, :fecha, :entrada, :salida, :notas, :creado_por)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':creado_por', $data['admin_usuario_id']);
        }

        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':fecha', $data['fecha']);
        $stmt->bindParam(':entrada', $data['entrada']);
        
        if (empty($data['salida'])) {
            $stmt->bindValue(':salida', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':salida', $data['salida']);
        }
        
        $stmt->bindParam(':notas', $data['notas']);
        
        if (!empty($data['registro_id'])) {
            $stmt->bindParam(':modificado_por', $data['admin_usuario_id']);
        }

        return $stmt->execute();
    }

    /**
     * Elimina un registro (Admin).
     */
    public function delete($registro_id)
    {
        $query = "DELETE FROM registro_horario WHERE registro_id = :registro_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':registro_id', $registro_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
