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
        $query = "SELECT u.*, e.nss, e.iban, e.grupo_cotizacion
                  FROM usuarios u 
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

            $rgpdAceptado = !empty($data['rgpd_aceptado']) ? 1 : 0;
            $fechaConsentimiento = !empty($data['fecha_consentimiento']) ? $data['fecha_consentimiento'] : ($rgpdAceptado ? date('Y-m-d H:i:s') : null);
            $firmaPaciente = !empty($data['firma_paciente']) ? $data['firma_paciente'] : null;

            $query = "INSERT INTO usuarios (usuario_id, nombre, apellidos, telefono, fecha_nacimiento, direccion, provincia, municipio, cp, email, pass, genero, rol, rgpd_aceptado, fecha_consentimiento, firma_paciente) 
                      VALUES (:usuario_id, :nombre, :apellidos, :telefono, :fecha_nacimiento, :direccion, :provincia, :municipio, :cp, :email, :pass, :genero, :rol, :rgpd_aceptado, :fecha_consentimiento, :firma_paciente)";
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
            $stmt->bindParam(':rol', $data['rol']);
            $stmt->bindParam(':rgpd_aceptado', $rgpdAceptado, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_consentimiento', $fechaConsentimiento);
            $stmt->bindParam(':firma_paciente', $firmaPaciente);
            $stmt->execute();

            if ($data['rol'] !== 'Paciente') {
                // Es un trabajador (Fisioterapeuta, Secretario, Administrador)
                $qEmp = "INSERT INTO empleados (usuario_id, nss, iban, grupo_cotizacion) VALUES (:usuario_id, :nss, :iban, :grupo_cotizacion)";
                $sEmp = $this->db->prepare($qEmp);
                $sEmp->bindParam(':usuario_id', $data['usuario_id']);
                $sEmp->bindParam(':nss', $data['nss']);
                $sEmp->bindParam(':iban', $data['iban']);
                $sEmp->bindValue(':grupo_cotizacion', $data['grupo_cotizacion'] ?? 1, PDO::PARAM_INT);
                $sEmp->execute();
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
        $query = "SELECT * FROM usuarios";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByRol($rol)
    {
        $query = "SELECT u.* FROM usuarios u WHERE u.rol = :rol";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWorkers()
    {
        // Todos los usuarios excepto pacientes
        $query = "SELECT u.*, e.nss, e.iban, e.grupo_cotizacion
                  FROM usuarios u
                  LEFT JOIN empleados e ON u.usuario_id = e.usuario_id
                  WHERE u.rol != 'Paciente'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByRol($rol, $query)
    {
        if ($rol === 'Administrador') {
            $sql = "SELECT u.usuario_id, u.nombre, u.apellidos 
                    FROM usuarios u 
                    WHERE u.rol = 'Administrador'";
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

        $sql = "SELECT u.usuario_id, u.nombre, u.apellidos 
                FROM usuarios u 
                WHERE u.rol = :rol";
        
        if (!empty($query)) {
            $sql .= " AND (u.nombre LIKE :q OR u.apellidos LIKE :q OR u.usuario_id LIKE :q)";
        }
        $sql .= " ORDER BY u.nombre ASC LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':rol', $rol);
        if (!empty($query)) {
            $searchTerm = "%$query%";
            $stmt->bindParam(':q', $searchTerm);
        }
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

            $rgpdAceptado = isset($data['rgpd_aceptado']) ? (!empty($data['rgpd_aceptado']) ? 1 : 0) : null;
            $fechaConsentimiento = !empty($data['fecha_consentimiento']) ? $data['fecha_consentimiento'] : null;
            $firmaPaciente = isset($data['firma_paciente']) ? $data['firma_paciente'] : null;

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
                        genero = :genero,
                        rol = :rol";
            
            if ($rgpdAceptado !== null) {
                $query .= ", rgpd_aceptado = :rgpd_aceptado";
                if ($rgpdAceptado === 1 && empty($fechaConsentimiento)) {
                    $fechaConsentimiento = date('Y-m-d H:i:s');
                }
                $query .= ", fecha_consentimiento = :fecha_consentimiento";
            }
            if ($firmaPaciente !== null) {
                $query .= ", firma_paciente = :firma_paciente";
            }

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
            $stmt->bindParam(':rol', $data['rol']);
            
            if ($rgpdAceptado !== null) {
                $stmt->bindParam(':rgpd_aceptado', $rgpdAceptado, PDO::PARAM_INT);
                $stmt->bindParam(':fecha_consentimiento', $fechaConsentimiento);
            }
            if ($firmaPaciente !== null) {
                $stmt->bindParam(':firma_paciente', $firmaPaciente);
            }
            if (!empty($data['pass'])) {
                $stmt->bindParam(':pass', $data['pass']);
            }
            $stmt->execute();

            if ($data['rol'] === 'Paciente') {
                // Si pasa a paciente, eliminar sus datos de empleado
                $this->db->prepare("DELETE FROM empleados WHERE usuario_id = :id")->execute([':id' => $usuario_id]);
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
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}