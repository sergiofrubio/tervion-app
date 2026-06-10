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
                            WHEN s.usuario_id IS NOT NULL THEN 'Secretario'
                            ELSE 'Administrador' 
                        END as rol,
                        f.especialidad_id as especialidad,
                        e.nss, e.iban, e.grupo_cotizacion
                  FROM usuarios u 
                  LEFT JOIN pacientes p ON u.usuario_id = p.usuario_id 
                  LEFT JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id 
                  LEFT JOIN secretarios s ON u.usuario_id = s.usuario_id
                  LEFT JOIN empleados e ON u.usuario_id = e.usuario_id
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
            } else {
                // Es un trabajador (Fisioterapeuta, Secretario, Administrador)
                $qEmp = "INSERT INTO empleados (usuario_id, nss, iban, grupo_cotizacion) VALUES (:usuario_id, :nss, :iban, :grupo_cotizacion)";
                $sEmp = $this->db->prepare($qEmp);
                $sEmp->bindParam(':usuario_id', $data['usuario_id']);
                $sEmp->bindParam(':nss', $data['nss']);
                $sEmp->bindParam(':iban', $data['iban']);
                $sEmp->bindValue(':grupo_cotizacion', $data['grupo_cotizacion'] ?? 1, PDO::PARAM_INT);
                $sEmp->execute();

                if ($data['rol'] === 'Fisioterapeuta') {
                    $q2 = "INSERT INTO fisioterapeutas (usuario_id, especialidad_id) VALUES (:usuario_id, :especialidad_id)";
                    $s2 = $this->db->prepare($q2);
                    $s2->bindParam(':usuario_id', $data['usuario_id']);
                    $s2->bindParam(':especialidad_id', $data['especialidad'], PDO::PARAM_INT);
                    $s2->execute();
                } elseif ($data['rol'] === 'Secretario') {
                    $q2 = "INSERT INTO secretarios (usuario_id) VALUES (:usuario_id)";
                    $s2 = $this->db->prepare($q2);
                    $s2->bindParam(':usuario_id', $data['usuario_id']);
                    $s2->execute();
                }
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
                            WHEN s.usuario_id IS NOT NULL THEN 'Secretario'
                            ELSE 'Administrador' 
                        END as rol
                  FROM usuarios u 
                  LEFT JOIN pacientes p ON u.usuario_id = p.usuario_id 
                  LEFT JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id
                  LEFT JOIN secretarios s ON u.usuario_id = s.usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByRol($rol)
    {
        if ($rol === 'Paciente') {
            $query = "SELECT u.* FROM usuarios u JOIN pacientes p ON u.usuario_id = p.usuario_id";
        } elseif ($rol === 'Fisioterapeuta') {
            $query = "SELECT u.*, f.especialidad_id FROM usuarios u JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id";
        } elseif ($rol === 'Secretario') {
            $query = "SELECT u.* FROM usuarios u JOIN secretarios s ON u.usuario_id = s.usuario_id";
        } else {
            // Admin: no record in pacientes, fisioterapeutas, or secretarios
            $query = "SELECT u.* FROM usuarios u 
                      LEFT JOIN pacientes p ON u.usuario_id = p.usuario_id 
                      LEFT JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id
                      LEFT JOIN secretarios s ON u.usuario_id = s.usuario_id
                      WHERE p.usuario_id IS NULL AND f.usuario_id IS NULL AND s.usuario_id IS NULL";
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWorkers()
    {
        // Todos los usuarios excepto pacientes
        $query = "SELECT u.*, 
                        CASE 
                            WHEN f.usuario_id IS NOT NULL THEN 'Fisioterapeuta'
                            WHEN s.usuario_id IS NOT NULL THEN 'Secretario'
                            ELSE 'Administrador' 
                        END as rol,
                        e.nss, e.iban, e.grupo_cotizacion
                  FROM usuarios u
                  LEFT JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id
                  LEFT JOIN secretarios s ON u.usuario_id = s.usuario_id
                  LEFT JOIN pacientes p ON u.usuario_id = p.usuario_id
                  LEFT JOIN empleados e ON u.usuario_id = e.usuario_id
                  WHERE p.usuario_id IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByRol($rol, $query)
    {
        $table = '';
        if ($rol === 'Paciente') $table = 'pacientes';
        elseif ($rol === 'Fisioterapeuta') $table = 'fisioterapeutas';
        elseif ($rol === 'Secretario') $table = 'secretarios';
        
        if (empty($table)) {
            if ($rol === 'Administrador') {
                $sql = "SELECT u.usuario_id, u.nombre, u.apellidos 
                        FROM usuarios u 
                        LEFT JOIN pacientes p ON u.usuario_id = p.usuario_id 
                        LEFT JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id
                        LEFT JOIN secretarios s ON u.usuario_id = s.usuario_id
                        WHERE p.usuario_id IS NULL AND f.usuario_id IS NULL AND s.usuario_id IS NULL";
                if (!empty($query)) {
                    $sql .= " AND (u.nombre LIKE :q OR u.apellidos LIKE :q OR u.usuario_id LIKE :q)";
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
            return [];
        }

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

            // Eliminar de tablas de rol específicas
            $this->db->prepare("DELETE FROM pacientes WHERE usuario_id = :id")->execute([':id' => $usuario_id]);
            $this->db->prepare("DELETE FROM fisioterapeutas WHERE usuario_id = :id")->execute([':id' => $usuario_id]);
            $this->db->prepare("DELETE FROM secretarios WHERE usuario_id = :id")->execute([':id' => $usuario_id]);

            if ($data['rol'] === 'Paciente') {
                // Eliminar de empleados si existía
                $this->db->prepare("DELETE FROM empleados WHERE usuario_id = :id")->execute([':id' => $usuario_id]);

                $q2 = "INSERT INTO pacientes (usuario_id) VALUES (:usuario_id)";
                $s2 = $this->db->prepare($q2);
                $s2->bindParam(':usuario_id', $usuario_id);
                $s2->execute();
            } else {
                // Es un trabajador (Fisioterapeuta, Secretario, Administrador)
                $qEmp = "INSERT INTO empleados (usuario_id, nss, iban, grupo_cotizacion) 
                         VALUES (:usuario_id, :nss, :iban, :grupo_cotizacion)
                         ON DUPLICATE KEY UPDATE nss = :nss, iban = :iban, grupo_cotizacion = :grupo_cotizacion";
                $sEmp = $this->db->prepare($qEmp);
                $sEmp->bindParam(':usuario_id', $usuario_id);
                $sEmp->bindParam(':nss', $data['nss']);
                $sEmp->bindParam(':iban', $data['iban']);
                $sEmp->bindValue(':grupo_cotizacion', $data['grupo_cotizacion'] ?? 1, PDO::PARAM_INT);
                $sEmp->execute();

                if ($data['rol'] === 'Fisioterapeuta') {
                    $q2 = "INSERT INTO fisioterapeutas (usuario_id, especialidad_id) VALUES (:usuario_id, :especialidad_id)";
                    $s2 = $this->db->prepare($q2);
                    $s2->bindParam(':usuario_id', $usuario_id);
                    $s2->bindParam(':especialidad_id', $data['especialidad'], PDO::PARAM_INT);
                    $s2->execute();
                } elseif ($data['rol'] === 'Secretario') {
                    $q2 = "INSERT INTO secretarios (usuario_id) VALUES (:usuario_id)";
                    $s2 = $this->db->prepare($q2);
                    $s2->bindParam(':usuario_id', $usuario_id);
                    $s2->execute();
                }
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}