<?php
namespace App\Models;

use App\Core\DataBase;
use PDO;

class Setting
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    // Horarios
    public function getHorariosFisios()
    {
        $query = "SELECT h.*, u.nombre, u.apellidos 
                  FROM horarios_terapeutas h 
                  JOIN usuarios u ON h.fisioterapeuta_id = u.usuario_id 
                  ORDER BY h.dia_semana, h.hora_inicio";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ausencias
    public function getAusenciasFisios()
    {
        $query = "SELECT a.*, u.nombre, u.apellidos 
                  FROM ausencias_terapeutas a 
                  JOIN usuarios u ON a.fisioterapeuta_id = u.usuario_id 
                  ORDER BY a.fecha_inicio DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEspecialidades()
    {
        $query = "SELECT * FROM especialidades ORDER BY descripcion";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Bonos
    public function getBonos()
    {
        $query = "SELECT * FROM bonos ORDER BY estado, nombre";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Physiotherapists
    public function getFisios()
    {
        $query = "SELECT u.usuario_id, u.nombre, u.apellidos 
                  FROM usuarios u 
                  JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorarioById($id)
    {
        $query = "SELECT * FROM horarios_terapeutas WHERE horario_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAusenciaById($id)
    {
        $query = "SELECT * FROM ausencias_terapeutas WHERE ausencia_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEspecialidadById($id)
    {
        $query = "SELECT * FROM especialidades WHERE especialidad_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBonoById($id)
    {
        $query = "SELECT * FROM bonos WHERE bono_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Save methods
    public function saveHorario($data)
    {
        $query = "INSERT INTO horarios_terapeutas (fisioterapeuta_id, dia_semana, hora_inicio, hora_fin) 
                  VALUES (:fisioterapeuta_id, :dia_semana, :hora_inicio, :hora_fin)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function saveAusencia($data)
    {
        $query = "INSERT INTO ausencias_terapeutas (fisioterapeuta_id, fecha_inicio, fecha_fin, motivo) 
                  VALUES (:fisioterapeuta_id, :fecha_inicio, :fecha_fin, :motivo)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function saveEspecialidad($descripcion)
    {
        $query = "INSERT INTO especialidades (descripcion) VALUES (:descripcion)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':descripcion', $descripcion);
        return $stmt->execute();
    }

    public function saveBono($data)
    {
        $query = "INSERT INTO bonos (nombre, numero_sesiones, precio, estado) 
                  VALUES (:nombre, :numero_sesiones, :precio, :estado)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateHorario($id, $data)
    {
        $query = "UPDATE horarios_terapeutas SET fisioterapeuta_id = :fisioterapeuta_id, dia_semana = :dia_semana, 
                  hora_inicio = :hora_inicio, hora_fin = :hora_fin WHERE horario_id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateAusencia($id, $data)
    {
        $query = "UPDATE ausencias_terapeutas SET fisioterapeuta_id = :fisioterapeuta_id, fecha_inicio = :fecha_inicio, 
                  fecha_fin = :fecha_fin, motivo = :motivo WHERE ausencia_id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateEspecialidad($id, $descripcion)
    {
        $query = "UPDATE especialidades SET descripcion = :descripcion WHERE especialidad_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':descripcion' => $descripcion, ':id' => $id]);
    }

    public function updateBono($id, $data)
    {
        $query = "UPDATE bonos SET nombre = :nombre, numero_sesiones = :numero_sesiones, 
                  precio = :precio, estado = :estado WHERE bono_id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    // Clínica
    public function getClinica()
    {
        $query = "SELECT * FROM clinicas LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveClinica($data)
    {
        if (isset($data['id_clinica']) && !empty($data['id_clinica'])) {
            $query = "UPDATE clinicas SET 
                        nombre_comercial = :nombre_comercial,
                        razon_social = :razon_social,
                        direccion_calle = :direccion_calle,
                        ciudad = :ciudad,
                        provincia_estado = :provincia_estado,
                        codigo_postal = :codigo_postal,
                        pais = :pais,
                        telefono_contacto = :telefono_contacto,
                        email_contacto = :email_contacto,
                        sitio_web = :sitio_web
                      WHERE id_clinica = :id_clinica";
        } else {
            unset($data['id_clinica']);
            $query = "INSERT INTO clinicas (
                        nombre_comercial, razon_social, direccion_calle, ciudad, 
                        provincia_estado, codigo_postal, pais, telefono_contacto, 
                        email_contacto, sitio_web
                      ) VALUES (
                        :nombre_comercial, :razon_social, :direccion_calle, :ciudad, 
                        :provincia_estado, :codigo_postal, :pais, :telefono_contacto, 
                        :email_contacto, :sitio_web
                      )";
        }
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }
}
