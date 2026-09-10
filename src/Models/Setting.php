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
                  JOIN usuarios u ON h.terapeuta_id = u.usuario_id 
                  ORDER BY h.dia_semana, h.hora_inicio";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorariosByFisio($terapeuta_id)
    {
        $query = "SELECT h.*, u.nombre, u.apellidos 
                  FROM horarios_terapeutas h 
                  JOIN usuarios u ON h.terapeuta_id = u.usuario_id 
                  WHERE h.terapeuta_id = :terapeuta_id
                  ORDER BY FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), h.hora_inicio";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':terapeuta_id' => $terapeuta_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ausencias
    public function getAusenciasFisios()
    {
        $query = "SELECT a.*, u.nombre, u.apellidos 
                  FROM ausencias_terapeutas a 
                  JOIN usuarios u ON a.terapeuta_id = u.usuario_id 
                  ORDER BY a.fecha_inicio DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAusenciasByFisio($terapeuta_id)
    {
        $query = "SELECT a.*, u.nombre, u.apellidos 
                  FROM ausencias_terapeutas a 
                  JOIN usuarios u ON a.terapeuta_id = u.usuario_id 
                  WHERE a.terapeuta_id = :terapeuta_id
                  ORDER BY a.fecha_inicio DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':terapeuta_id' => $terapeuta_id]);
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
        $query = "INSERT INTO horarios_terapeutas (terapeuta_id, dia_semana, hora_inicio, hora_fin) 
                  VALUES (:terapeuta_id, :dia_semana, :hora_inicio, :hora_fin)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function saveAusencia($data)
    {
        $query = "INSERT INTO ausencias_terapeutas (terapeuta_id, fecha_inicio, fecha_fin, motivo) 
                  VALUES (:terapeuta_id, :fecha_inicio, :fecha_fin, :motivo)";
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
        $query = "UPDATE horarios_terapeutas SET terapeuta_id = :terapeuta_id, dia_semana = :dia_semana, 
                  hora_inicio = :hora_inicio, hora_fin = :hora_fin WHERE horario_id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateAusencia($id, $data)
    {
        $query = "UPDATE ausencias_terapeutas SET terapeuta_id = :terapeuta_id, fecha_inicio = :fecha_inicio, 
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
        if (isset($data['clinica_id']) && !empty($data['clinica_id'])) {
            $query = "UPDATE clinicas SET 
                        nombre_comercial = :nombre_comercial,
                        razon_social = :razon_social,
                        nif_cif = :nif_cif,
                        direccion = :direccion,
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
                      WHERE clinica_id = :clinica_id";
        } else {
            unset($data['clinica_id']);
            $query = "INSERT INTO clinicas (
                        nombre_comercial, razon_social, nif_cif, direccion, ciudad, 
                        provincia_estado, codigo_postal, pais, telefono_contacto, 
                        email_contacto, sitio_web, verifactu_env, verifactu_cert_path, 
                        verifactu_cert_password, verifactu_activo
                      ) VALUES (
                        :nombre_comercial, :razon_social, :nif_cif, :direccion, :ciudad, 
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
            ':direccion' => $data['direccion'],
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
            ':clinica_id' => $data['clinica_id'] ?? null
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

    // ==========================================
    // TIPOS DE CITAS
    // ==========================================
    public function getTiposCitas()
    {
        $query = "SELECT * FROM tipos_citas ORDER BY nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTipoCitaById($id)
    {
        $query = "SELECT * FROM tipos_citas WHERE tipo_cita_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveTipoCita($data)
    {
        $query = "INSERT INTO tipos_citas (nombre, descripcion, duracion_minutos, precio, color, estado) 
                  VALUES (:nombre, :descripcion, :duracion_minutos, :precio, :color, :estado)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateTipoCita($id, $data)
    {
        $query = "UPDATE tipos_citas SET 
                    nombre = :nombre, 
                    descripcion = :descripcion, 
                    duracion_minutos = :duracion_minutos, 
                    precio = :precio, 
                    color = :color, 
                    estado = :estado 
                  WHERE tipo_cita_id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function deleteTipoCita($id)
    {
        $query = "DELETE FROM tipos_citas WHERE tipo_cita_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // ==========================================
    // DESPACHOS / SALAS
    // ==========================================
    public function getDespachos()
    {
        // Creamos la tabla si aún no existiese en caliente
        $this->db->exec("CREATE TABLE IF NOT EXISTS `despachos` (
            `despacho_id` int NOT NULL AUTO_INCREMENT,
            `cuenta_id` int NOT NULL DEFAULT '1',
            `nombre` varchar(150) NOT NULL,
            `ubicacion` varchar(255) DEFAULT NULL,
            `capacidad` int NOT NULL DEFAULT '1',
            `equipamiento` text DEFAULT NULL,
            `color` varchar(20) DEFAULT '#6366f1',
            `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
            `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `modificado_por` varchar(9) DEFAULT NULL,
            `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`despacho_id`),
            KEY `idx_despachos_cuenta` (`cuenta_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $query = "SELECT * FROM despachos ORDER BY nombre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDespachoById($id)
    {
        $query = "SELECT * FROM despachos WHERE despacho_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveDespacho($data)
    {
        $this->getDespachos(); // asegura creación de tabla si es primera ejecución
        $query = "INSERT INTO despachos (nombre, ubicacion, capacidad, equipamiento, color, estado) 
                  VALUES (:nombre, :ubicacion, :capacidad, :equipamiento, :color, :estado)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateDespacho($id, $data)
    {
        $query = "UPDATE despachos SET 
                    nombre = :nombre, 
                    ubicacion = :ubicacion, 
                    capacidad = :capacidad, 
                    equipamiento = :equipamiento, 
                    color = :color, 
                    estado = :estado 
                  WHERE despacho_id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function deleteDespacho($id)
    {
        $query = "DELETE FROM despachos WHERE despacho_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // ==========================================
    // CÓDIGOS DE DESCUENTO
    // ==========================================
    public function getCodigosDescuento()
    {
        $query = "SELECT * FROM codigos_descuento ORDER BY fecha_creacion DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCodigoDescuentoById($id)
    {
        $query = "SELECT * FROM codigos_descuento WHERE codigo_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveCodigoDescuento($data)
    {
        $query = "INSERT INTO codigos_descuento (codigo, descripcion, tipo_descuento, valor, monto_minimo, usos_maximos, fecha_inicio, fecha_fin, estado) 
                  VALUES (:codigo, :descripcion, :tipo_descuento, :valor, :monto_minimo, :usos_maximos, :fecha_inicio, :fecha_fin, :estado)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function updateCodigoDescuento($id, $data)
    {
        $query = "UPDATE codigos_descuento SET 
                    codigo = :codigo, 
                    descripcion = :descripcion, 
                    tipo_descuento = :tipo_descuento, 
                    valor = :valor, 
                    monto_minimo = :monto_minimo, 
                    usos_maximos = :usos_maximos, 
                    fecha_inicio = :fecha_inicio, 
                    fecha_fin = :fecha_fin, 
                    estado = :estado 
                  WHERE codigo_id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function deleteCodigoDescuento($id)
    {
        $query = "DELETE FROM codigos_descuento WHERE codigo_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
