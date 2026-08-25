<?php
namespace App\Models;
use App\Core\DataBase;
use PDO;

class Appointment
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getById($cita_id)
    {
        $query = "SELECT c.*, p.nombre as paciente_nombre, p.apellidos as paciente_apellidos, p.telefono as paciente_telefono, f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos, tc.nombre as tipo_cita_nombre, tc.color as tipo_cita_color, tc.duracion_minutos as tipo_cita_duracion, tc.precio as tipo_cita_precio 
                  FROM citas c 
                  LEFT JOIN usuarios p ON c.paciente_id = p.usuario_id 
                  LEFT JOIN usuarios f ON c.fisioterapeuta_id = f.usuario_id 
                  LEFT JOIN tipos_citas tc ON c.tipo_cita_id = tc.tipo_cita_id 
                  WHERE c.cita_id = :cita_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($paciente_id, $fisioterapeuta_id, $fecha_hora, $estado = "Programada", $tipo_cita_id = null)
    {
        $query = "INSERT INTO citas (paciente_id, fisioterapeuta_id, tipo_cita_id, fecha_hora, estado) 
                  VALUES (:paciente_id, :fisioterapeuta_id, :tipo_cita_id, :fecha_hora, :estado)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':fisioterapeuta_id', $fisioterapeuta_id);
        $stmt->bindValue(':tipo_cita_id', $tipo_cita_id ? $tipo_cita_id : null, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_hora', $fecha_hora);
        $stmt->bindParam(':estado', $estado);
        return $stmt->execute();
    }

    public function getAll()
    {
        $query = "SELECT c.*, p.nombre as paciente_nombre, p.apellidos as paciente_apellidos, p.telefono as paciente_telefono, f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos, tc.nombre as tipo_cita_nombre, tc.color as tipo_cita_color 
                  FROM citas c 
                  LEFT JOIN usuarios p ON c.paciente_id = p.usuario_id 
                  LEFT JOIN usuarios f ON c.fisioterapeuta_id = f.usuario_id 
                  LEFT JOIN tipos_citas tc ON c.tipo_cita_id = tc.tipo_cita_id 
                  ORDER BY c.fecha_hora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($cita_id)
    {
        $query = "DELETE FROM citas WHERE cita_id = :cita_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update($cita_id, $paciente_id, $fisioterapeuta_id, $fecha_hora, $estado = "Programada", $tipo_cita_id = null)
    {
        $query = "UPDATE citas SET paciente_id = :paciente_id, fisioterapeuta_id = :fisioterapeuta_id, tipo_cita_id = :tipo_cita_id, fecha_hora = :fecha_hora
                  WHERE cita_id = :cita_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':fisioterapeuta_id', $fisioterapeuta_id);
        $stmt->bindValue(':tipo_cita_id', $tipo_cita_id ? $tipo_cita_id : null, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_hora', $fecha_hora);
        return $stmt->execute();
    }

    public function updateStatus($cita_id, $estado)
    {
        $query = "UPDATE citas SET estado = :estado WHERE cita_id = :cita_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function getByPatient($paciente_id)
    {
        $query = "SELECT c.*, f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos 
                  FROM citas c 
                  LEFT JOIN usuarios f ON c.fisioterapeuta_id = f.usuario_id 
                  WHERE c.paciente_id = :paciente_id
                  ORDER BY c.fecha_hora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableSlots($fisioterapeuta_id, $fecha, $duracion_minutos = 60)
    {
        $dias_semana = [
            'Sunday' => 'Domingo',
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado'
        ];
        $dia_nombre = $dias_semana[date('l', strtotime($fecha))];

        $queryHorarios = "SELECT hora_inicio, hora_fin FROM horarios_terapeutas WHERE fisioterapeuta_id = :fisio_id AND dia_semana = :dia";
        $stmtH = $this->db->prepare($queryHorarios);
        $stmtH->execute([':fisio_id' => $fisioterapeuta_id, ':dia' => $dia_nombre]);
        $horarios = $stmtH->fetchAll(PDO::FETCH_ASSOC);

        if (empty($horarios)) return [];

        $queryAusencias = "SELECT 1 FROM ausencias_terapeutas WHERE fisioterapeuta_id = :fisio_id AND :fecha BETWEEN fecha_inicio AND fecha_fin";
        $stmtA = $this->db->prepare($queryAusencias);
        $stmtA->execute([':fisio_id' => $fisioterapeuta_id, ':fecha' => $fecha]);
        if ($stmtA->fetch()) return [];

        $queryCitas = "SELECT c.fecha_hora, COALESCE(tc.duracion_minutos, 60) as duracion_minutos 
                      FROM citas c 
                      LEFT JOIN tipos_citas tc ON c.tipo_cita_id = tc.tipo_cita_id 
                      WHERE c.fisioterapeuta_id = :fisio_id 
                      AND DATE(c.fecha_hora) = :fecha 
                      AND c.estado != 'Cancelada'";
        $stmtC = $this->db->prepare($queryCitas);
        $stmtC->execute([':fisio_id' => $fisioterapeuta_id, ':fecha' => $fecha]);
        $citas = $stmtC->fetchAll(PDO::FETCH_ASSOC);

        $availableSlots = [];
        $duracion_segundos = $duracion_minutos * 60;

        foreach ($horarios as $h) {
            $current = strtotime($fecha . ' ' . $h['hora_inicio']);
            $end = strtotime($fecha . ' ' . $h['hora_fin']);
            
            while ($current + $duracion_segundos <= $end) {
                $slotStart = $current;
                $slotEnd = $current + $duracion_segundos;
                
                $isOccupied = false;
                foreach ($citas as $cita) {
                    $citaStart = strtotime($cita['fecha_hora']);
                    $citaDur = ($cita['duracion_minutos'] ?? 60) * 60; // Default 60 si no hay servicio
                    $citaEnd = $citaStart + $citaDur;
                    
                    if ($slotStart < $citaEnd && $citaStart < $slotEnd) {
                        $isOccupied = true;
                        break;
                    }
                }
                
                if (!$isOccupied) {
                    $availableSlots[] = date('H:i', $slotStart);
                }
                
                // Avanzamos en bloques de 30 minutos para ofrecer más flexibilidad de inicio
                $current = strtotime('+30 minutes', $current);
            }
        }

        return array_unique($availableSlots);
    }

    public function getAvailableDays($fisioterapeuta_id, $duracion_minutos = 60)
    {
        $availableDays = [];
        $today = new \DateTime('today');
        
        for ($i = 0; $i < 60; $i++) {
            $currentDate = clone $today;
            $currentDate->modify("+$i days");
            $fechaStr = $currentDate->format('Y-m-d');
            
            $slots = $this->getAvailableSlots($fisioterapeuta_id, $fechaStr, $duracion_minutos);
            if (!empty($slots)) {
                $availableDays[] = [
                    'fecha' => $fechaStr,
                    'dia_semana' => $currentDate->format('N'),
                    'dia_mes' => $currentDate->format('d'),
                    'mes_corto' => $this->getMesCorto((int)$currentDate->format('n')),
                    'nombre_dia' => $this->getNombreDia($fechaStr),
                    'total_slots' => count($slots)
                ];
            }
        }
        return $availableDays;
    }

    private function getMesCorto($mesNum)
    {
        $meses = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];
        return $meses[$mesNum] ?? '';
    }

    private function getNombreDia($fecha)
    {
        $dias_semana = [
            'Sunday' => 'Dom',
            'Monday' => 'Lun',
            'Tuesday' => 'Mar',
            'Wednesday' => 'Mié',
            'Thursday' => 'Jue',
            'Friday' => 'Vie',
            'Saturday' => 'Sáb'
        ];
        return $dias_semana[date('l', strtotime($fecha))] ?? '';
    }

    public function getUpcomingAppointmentsWithoutReminder($days = 1)
    {
        $query = "SELECT c.*, p.nombre as paciente_nombre, p.apellidos as paciente_apellidos, p.email as paciente_email, p.telefono as paciente_telefono,
                         f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos 
                  FROM citas c 
                  LEFT JOIN usuarios p ON c.paciente_id = p.usuario_id 
                  LEFT JOIN usuarios f ON c.fisioterapeuta_id = f.usuario_id 
                  WHERE c.estado = 'Programada' 
                    AND DATE(c.fecha_hora) = DATE_ADD(CURDATE(), INTERVAL :days DAY)
                    AND c.token_confirmacion IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function setConfirmationToken($cita_id, $token)
    {
        $query = "UPDATE citas SET token_confirmacion = :token WHERE cita_id = :cita_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getByConfirmationToken($token)
    {
        $query = "SELECT c.*, p.nombre as paciente_nombre, p.apellidos as paciente_apellidos, p.email as paciente_email, p.telefono as paciente_telefono,
                         f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos 
                  FROM citas c 
                  LEFT JOIN usuarios p ON c.paciente_id = p.usuario_id 
                  LEFT JOIN usuarios f ON c.fisioterapeuta_id = f.usuario_id 
                  WHERE c.token_confirmacion = :token";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function confirmAppointment($cita_id)
    {
        $query = "UPDATE citas SET estado = 'Confirmada' WHERE cita_id = :cita_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}