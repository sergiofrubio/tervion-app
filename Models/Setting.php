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
        $query = "SELECT usuario_id, nombre, apellidos 
                  FROM usuarios 
                  WHERE rol = 'Fisioterapeuta'";
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
                        nif_cif = :nif_cif,
                        direccion_calle = :direccion_calle,
                        ciudad = :ciudad,
                        provincia_estado = :provincia_estado,
                        codigo_postal = :codigo_postal,
                        pais = :pais,
                        telefono_contacto = :telefono_contacto,
                        email_contacto = :email_contacto,
                        sitio_web = :sitio_web,
                        verifactu_env = :verifactu_env,
                        verifactu_cert_path = :verifactu_cert_path,
                        verifactu_cert_password = :verifactu_cert_password,
                        verifactu_activo = :verifactu_activo
                      WHERE id_clinica = :id_clinica";
        } else {
            unset($data['id_clinica']);
            $query = "INSERT INTO clinicas (
                        nombre_comercial, razon_social, nif_cif, direccion_calle, ciudad, 
                        provincia_estado, codigo_postal, pais, telefono_contacto, 
                        email_contacto, sitio_web, verifactu_env, verifactu_cert_path, 
                        verifactu_cert_password, verifactu_activo
                      ) VALUES (
                        :nombre_comercial, :razon_social, :nif_cif, :direccion_calle, :ciudad, 
                        :provincia_estado, :codigo_postal, :pais, :telefono_contacto, 
                        :email_contacto, :sitio_web, :verifactu_env, :verifactu_cert_path, 
                        :verifactu_cert_password, :verifactu_activo
                      )";
        }
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':nombre_comercial' => $data['nombre_comercial'],
            ':razon_social' => $data['razon_social'] ?? null,
            ':nif_cif' => $data['nif_cif'] ?? 'B12345678',
            ':direccion_calle' => $data['direccion_calle'],
            ':ciudad' => $data['ciudad'],
            ':provincia_estado' => $data['provincia_estado'] ?? null,
            ':codigo_postal' => $data['codigo_postal'] ?? null,
            ':pais' => $data['pais'] ?? 'España',
            ':telefono_contacto' => $data['telefono_contacto'],
            ':email_contacto' => $data['email_contacto'] ?? null,
            ':sitio_web' => $data['sitio_web'] ?? null,
            ':verifactu_env' => $data['verifactu_env'] ?? 'pruebas',
            ':verifactu_cert_path' => $data['verifactu_cert_path'] ?? null,
            ':verifactu_cert_password' => $data['verifactu_cert_password'] ?? null,
            ':verifactu_activo' => isset($data['verifactu_activo']) ? (int)$data['verifactu_activo'] : 1,
            ':id_clinica' => $data['id_clinica'] ?? null
        ]);
    }

    public function getMetodoPagoByUsuario($usuario_id)
    {
        $query = "SELECT * FROM metodos_pago WHERE usuario_id = :usuario_id AND tipo = 'Tarjeta' ORDER BY es_predeterminado DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':usuario_id' => $usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMetodoPago($usuario_id, $data)
    {
        $card = $this->getMetodoPagoByUsuario($usuario_id);
        $last4 = substr(str_replace(' ', '', $data['numero_completo']), -4);
        
        if ($card) {
            $query = "UPDATE metodos_pago SET 
                        nombre_titular = :nombre_titular, 
                        numero_completo = :numero_completo, 
                        last4 = :last4, 
                        fecha_expiracion = :fecha_expiracion, 
                        cvv = :cvv,
                        modificado_por = :usuario_id
                      WHERE metodo_id = :metodo_id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':nombre_titular' => $data['nombre_titular'],
                ':numero_completo' => $data['numero_completo'],
                ':last4' => $last4,
                ':fecha_expiracion' => $data['fecha_expiracion'],
                ':cvv' => $data['cvv'],
                ':usuario_id' => $usuario_id,
                ':metodo_id' => $card['metodo_id']
            ]);
        } else {
            $query = "INSERT INTO metodos_pago (usuario_id, tipo, proveedor, last4, fecha_expiracion, token_externo, es_predeterminado, nombre_titular, numero_completo, cvv, creado_por) 
                      VALUES (:usuario_id, 'Tarjeta', 'Visa', :last4, :fecha_expiracion, :token_externo, 1, :nombre_titular, :numero_completo, :cvv, :usuario_id)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':last4' => $last4,
                ':fecha_expiracion' => $data['fecha_expiracion'],
                ':token_externo' => 'tok_' . bin2hex(random_bytes(8)),
                ':nombre_titular' => $data['nombre_titular'],
                ':numero_completo' => $data['numero_completo'],
                ':cvv' => $data['cvv']
            ]);
        }
    }

    public function getCuentaClienteByEmail($email)
    {
        $query = "SELECT * FROM cuentas_clientes WHERE email_admin = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePlanSuscripcion($email_admin, $plan)
    {
        // En Upgrade o cambio directo, limpiamos cualquier downgrade pendiente
        $query = "UPDATE cuentas_clientes 
                  SET plan_suscripcion = :plan, 
                      plan_proximo = NULL 
                  WHERE email_admin = :email";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':plan' => $plan,
            ':email' => $email_admin
        ]);
    }

    public function scheduleDowngrade($email_admin, $plan_proximo)
    {
        $query = "UPDATE cuentas_clientes 
                  SET plan_proximo = :plan_proximo 
                  WHERE email_admin = :email";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':plan_proximo' => $plan_proximo,
            ':email' => $email_admin
        ]);
    }

    public function cancelDowngrade($email_admin)
    {
        $query = "UPDATE cuentas_clientes 
                  SET plan_proximo = NULL 
                  WHERE email_admin = :email";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':email' => $email_admin
        ]);
    }
}
