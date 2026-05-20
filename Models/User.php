<?php
namespace App\Models;
use App\Core\DataBase;
use PDO;

class User
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getByusuario_id($usuario_id)
    {
        $query = "SELECT u.*, 
                        CASE 
                            WHEN p.usuario_id IS NOT NULL THEN 'Paciente'
                            WHEN f.usuario_id IS NOT NULL THEN 'Fisioterapeuta'
                            ELSE 'Administrador' 
                        END as rol,
                        f.especialidad_id as especialidad
                  FROM usuarios u 
                  LEFT JOIN pacientes p ON u.usuario_id = p.usuario_id 
                  LEFT JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id 
                  WHERE u.usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data)
    {
        try {
            $this->db->beginTransaction();

            $query = "INSERT INTO usuarios (usuario_id, nombre, apellidos, telefono, fecha_nacimiento, direccion, provincia, municipio, cp, email, pass, genero) 
                      VALUES (:usuario_id, :nombre, :apellidos, :telefono, :fecha_nacimiento, :direccion, :provincia, :municipio, :cp, :email, :pass, :genero)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':usuario_id', $data['usuario_id']);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':apellidos', $data['apellidos']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':fecha_nacimiento', $data['fecha_nacimiento']);
            $stmt->bindParam(':direccion', $data['direccion']);
            $stmt->bindParam(':provincia', $data['provincia']);
            $stmt->bindParam(':municipio', $data['municipio']);
            $stmt->bindParam(':cp', $data['cp']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':pass', $data['pass']);
            $stmt->bindParam(':genero', $data['genero']);
            $stmt->execute();

            if ($data['rol'] === 'Paciente') {
                $q2 = "INSERT INTO pacientes (usuario_id) VALUES (:usuario_id)";
                $s2 = $this->db->prepare($q2);
                $s2->bindParam(':usuario_id', $data['usuario_id']);
                $s2->execute();
            } elseif ($data['rol'] === 'Fisioterapeuta') {
                $q2 = "INSERT INTO fisioterapeutas (usuario_id, especialidad_id) VALUES (:usuario_id, :especialidad_id)";
                $s2 = $this->db->prepare($q2);
                $s2->bindParam(':usuario_id', $data['usuario_id']);
                $s2->bindParam(':especialidad_id', $data['especialidad'], PDO::PARAM_INT);
                $s2->execute();
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getAll()
    {
        $query = "SELECT u.*, 
                        CASE 
                            WHEN p.usuario_id IS NOT NULL THEN 'Paciente'
                            WHEN f.usuario_id IS NOT NULL THEN 'Fisioterapeuta'
                            ELSE 'Administrador' 
                        END as rol
                  FROM usuarios u 
                  LEFT JOIN pacientes p ON u.usuario_id = p.usuario_id 
                  LEFT JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByRol($rol)
    {
        if ($rol === 'Paciente') {
            $query = "SELECT u.* FROM usuarios u JOIN pacientes p ON u.usuario_id = p.usuario_id";
        } elseif ($rol === 'Fisioterapeuta') {
            $query = "SELECT u.* FROM usuarios u JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id";
        } else {
            return []; // O manejar administradores
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByRol($rol, $query)
    {
        $table = '';
        if ($rol === 'Paciente') $table = 'pacientes';
        elseif ($rol === 'Fisioterapeuta') $table = 'fisioterapeutas';
        
        if (empty($table)) return [];

        $sql = "SELECT u.usuario_id, u.nombre, u.apellidos 
                FROM usuarios u 
                JOIN $table r ON u.usuario_id = r.usuario_id";
        
        if (!empty($query)) {
            $sql .= " WHERE (u.nombre LIKE :q OR u.apellidos LIKE :q OR u.usuario_id LIKE :q)";
        }
        $sql .= " ORDER BY u.nombre ASC LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        if (!empty($query)) {
            $searchTerm = "%$query%";
            $stmt->bindParam(':q', $searchTerm);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSpecialties()
    {
        $query = "SELECT * FROM especialidades";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($usuario_id)
    {
        // On cascade delete should handle the other tables if FKs are set (they are)
        $query = "DELETE FROM usuarios WHERE usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }

    public function update($usuario_id, $data)
    {
        try {
            $this->db->beginTransaction();

            $query = "UPDATE usuarios SET 
                        nombre = :nombre, 
                        apellidos = :apellidos, 
                        telefono = :telefono, 
                        fecha_nacimiento = :fecha_nacimiento, 
                        direccion = :direccion, 
                        provincia = :provincia, 
                        municipio = :municipio, 
                        cp = :cp, 
                        email = :email, 
                        genero = :genero";
            
            if (!empty($data['pass'])) {
                $query .= ", pass = :pass";
            }
            
            $query .= " WHERE usuario_id = :usuario_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':apellidos', $data['apellidos']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':fecha_nacimiento', $data['fecha_nacimiento']);
            $stmt->bindParam(':direccion', $data['direccion']);
            $stmt->bindParam(':provincia', $data['provincia']);
            $stmt->bindParam(':municipio', $data['municipio']);
            $stmt->bindParam(':cp', $data['cp']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':genero', $data['genero']);
            
            if (!empty($data['pass'])) {
                $stmt->bindParam(':pass', $data['pass']);
            }
            $stmt->execute();

            // Handle Rol change or update
            // First remove from existing role tables (safe because we only have 2 roles)
            $this->db->prepare("DELETE FROM pacientes WHERE usuario_id = :id")->execute([':id' => $usuario_id]);
            $this->db->prepare("DELETE FROM fisioterapeutas WHERE usuario_id = :id")->execute([':id' => $usuario_id]);

            if ($data['rol'] === 'Paciente') {
                $q2 = "INSERT INTO pacientes (usuario_id) VALUES (:usuario_id)";
                $s2 = $this->db->prepare($q2);
                $s2->bindParam(':usuario_id', $usuario_id);
                $s2->execute();
            } elseif ($data['rol'] === 'Fisioterapeuta') {
                $q2 = "INSERT INTO fisioterapeutas (usuario_id, especialidad_id) VALUES (:usuario_id, :especialidad_id)";
                $s2 = $this->db->prepare($q2);
                $s2->bindParam(':usuario_id', $usuario_id);
                $s2->bindParam(':especialidad_id', $data['especialidad'], PDO::PARAM_INT);
                $s2->execute();
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}