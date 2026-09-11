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
        $query = "SELECT c.*, p.usuario_id as paciente_usuario_id, p.nombre as paciente_nombre, p.apellidos as paciente_apellidos, p.telefono as paciente_telefono, 
                         f.usuario_id as fisioterapeuta_usuario_id, f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos, 
                         tc.nombre as tipo_cita_nombre, tc.color as tipo_cita_color, tc.duracion_minutos as tipo_cita_duracion, tc.precio as tipo_cita_precio,
                         d.nombre as despacho_nombre, d.color as despacho_color, d.ubicacion as despacho_ubicacion
                  FROM citas c 
                  LEFT JOIN usuarios p ON (c.paciente_id = p.usuario_id OR c.paciente_id = p.dni) 
                  LEFT JOIN usuarios f ON (c.terapeuta_id = f.usuario_id OR c.terapeuta_id = f.dni) 
                  LEFT JOIN tipos_citas tc ON c.tipo_cita_id = tc.tipo_cita_id 
                  LEFT JOIN despachos d ON c.despacho_id = d.despacho_id
                  WHERE c.cita_id = :cita_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($paciente_id, $terapeuta_id, $fecha_hora, $estado = "Programada", $tipo_cita_id = null, $despacho_id = null, $fecha_hora_fin = null)
    {
        $query = "INSERT INTO citas (paciente_id, terapeuta_id, tipo_cita_id, despacho_id, fecha_hora, fecha_hora_fin, estado) 
                  VALUES (:paciente_id, :terapeuta_id, :tipo_cita_id, :despacho_id, :fecha_hora, :fecha_hora_fin, :estado)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':terapeuta_id', $terapeuta_id);
        $stmt->bindValue(':tipo_cita_id', $tipo_cita_id ? $tipo_cita_id : null, PDO::PARAM_INT);
        $stmt->bindValue(':despacho_id', $despacho_id ? $despacho_id : null, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_hora', $fecha_hora);
        $stmt->bindValue(':fecha_hora_fin', $fecha_hora_fin ? $fecha_hora_fin : null);
        $stmt->bindParam(':estado', $estado);
        return $stmt->execute();
    }

    public function getAll()
    {
        $query = "SELECT c.*, p.usuario_id as paciente_usuario_id, p.nombre as paciente_nombre, p.apellidos as paciente_apellidos, p.telefono as paciente_telefono, 
                         f.usuario_id as fisioterapeuta_usuario_id, f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos, 
                         tc.nombre as tipo_cita_nombre, tc.color as tipo_cita_color,
                         d.nombre as despacho_nombre, d.color as despacho_color, d.ubicacion as despacho_ubicacion
                  FROM citas c 
                  LEFT JOIN usuarios p ON (c.paciente_id = p.usuario_id OR c.paciente_id = p.dni) 
                  LEFT JOIN usuarios f ON (c.terapeuta_id = f.usuario_id OR c.terapeuta_id = f.dni) 
                  LEFT JOIN tipos_citas tc ON c.tipo_cita_id = tc.tipo_cita_id 
                  LEFT JOIN despachos d ON c.despacho_id = d.despacho_id
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

    public function update($cita_id, $paciente_id, $terapeuta_id, $fecha_hora, $estado = "Programada", $tipo_cita_id = null, $despacho_id = null, $fecha_hora_fin = null)
    {
        $query = "UPDATE citas SET paciente_id = :paciente_id, terapeuta_id = :terapeuta_id, tipo_cita_id = :tipo_cita_id, 
                                   despacho_id = :despacho_id, fecha_hora = :fecha_hora, fecha_hora_fin = :fecha_hora_fin, estado = :estado
                  WHERE cita_id = :cita_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cita_id', $cita_id, PDO::PARAM_INT);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->bindParam(':terapeuta_id', $terapeuta_id);
        $stmt->bindValue(':tipo_cita_id', $tipo_cita_id ? $tipo_cita_id : null, PDO::PARAM_INT);
        $stmt->bindValue(':despacho_id', $despacho_id ? $despacho_id : null, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_hora', $fecha_hora);
        $stmt->bindValue(':fecha_hora_fin', $fecha_hora_fin ? $fecha_hora_fin : null);
        $stmt->bindParam(':estado', $estado);
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
        $query = "SELECT c.*, f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos,
                         tc.nombre as tipo_cita_nombre, tc.color as tipo_cita_color,
                         d.nombre as despacho_nombre, d.color as despacho_color, d.ubicacion as despacho_ubicacion
                  FROM citas c 
                  LEFT JOIN usuarios f ON c.terapeuta_id = f.usuario_id 
                  LEFT JOIN tipos_citas tc ON c.tipo_cita_id = tc.tipo_cita_id 
                  LEFT JOIN despachos d ON c.despacho_id = d.despacho_id
                  WHERE c.paciente_id = :paciente_id
                  ORDER BY c.fecha_hora DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':paciente_id', $paciente_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca y asigna automáticamente el primer despacho físico activo y libre para el intervalo de tiempo dado.
     *
     * @param string $fecha_hora_inicio Formato Y-m-d H:i:s
     * @param string $fecha_hora_fin Formato Y-m-d H:i:s
     * @param int|null $exclude_cita_id ID de cita a excluir (para ediciones)
     * @return int|null ID del despacho libre asignado o null si no hay ninguno disponible
     */
    public function findAvailableDespacho($fecha_hora_inicio, $fecha_hora_fin, $exclude_cita_id = null)
    {
        $queryDespachos = "SELECT despacho_id, nombre, capacidad FROM despachos WHERE estado = 'Activo' ORDER BY despacho_id ASC";
        $stmtD = $this->db->prepare($queryDespachos);
        $stmtD->execute();
        $despachos = $stmtD->fetchAll(PDO::FETCH_ASSOC);

        if (empty($despachos)) {
            return null;
        }

        // Consultar citas presenciales con despacho asignado que coincidan o se solapen en el rango
        $sqlCitas = "SELECT c.despacho_id, c.fecha_hora, COALESCE(c.fecha_hora_fin, DATE_ADD(c.fecha_hora, INTERVAL COALESCE(tc.duracion_minutos, 60) MINUTE)) as fecha_fin_calculada
                     FROM citas c
                     LEFT JOIN tipos_citas tc ON c.tipo_cita_id = tc.tipo_cita_id
                     WHERE c.despacho_id IS NOT NULL
                       AND c.estado != 'Cancelada'
                       AND c.fecha_hora < :fecha_fin
                       AND COALESCE(c.fecha_hora_fin, DATE_ADD(c.fecha_hora, INTERVAL COALESCE(tc.duracion_minutos, 60) MINUTE)) > :fecha_inicio";

        if ($exclude_cita_id) {
            $sqlCitas .= " AND c.cita_id != :exclude_cita_id";
        }

        $stmtC = $this->db->prepare($sqlCitas);
        $params = [
            ':fecha_inicio' => $fecha_hora_inicio,
            ':fecha_fin' => $fecha_hora_fin
        ];
        if ($exclude_cita_id) {
            $params[':exclude_cita_id'] = (int)$exclude_cita_id;
        }
        $stmtC->execute($params);
        $citasOcupadas = $stmtC->fetchAll(PDO::FETCH_ASSOC);

        // Contabilizar ocupación por despacho_id
        $ocupacionPorDespacho = [];
        foreach ($citasOcupadas as $co) {
            $dId = (int)$co['despacho_id'];
            $ocupacionPorDespacho[$dId] = ($ocupacionPorDespacho[$dId] ?? 0) + 1;
        }

        // Asignar el primer despacho que no supere su capacidad
        foreach ($despachos as $d) {
            $dId = (int)$d['despacho_id'];
            $capacidad = max(1, (int)($d['capacidad'] ?? 1));
            $ocupados = $ocupacionPorDespacho[$dId] ?? 0;

            if ($ocupados < $capacidad) {
                return $dId;
            }
        }

        return null;
    }

    /**
     * Obtiene los slots de inicio disponibles para un terapeuta, considerando su horario laboral, ausencias,
     * citas agendadas y la disponibilidad de despachos libres si la cita es presencial.
     *
     * @param string $terapeuta_id
     * @param string $fecha Y-m-d
     * @param int $duracion_minutos
     * @param string $ubicacion_tipo 'presencial' o 'telematica'
     * @param int|null $exclude_cita_id
     * @return array Lista de strings con horas libres ('09:00', '09:30', etc.)
     */
    public function getAvailableSlots($terapeuta_id, $fecha, $duracion_minutos = 60, $ubicacion_tipo = 'presencial', $exclude_cita_id = null)
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

        $queryHorarios = "SELECT hora_inicio, hora_fin FROM horarios_terapeutas WHERE terapeuta_id = :fisio_id AND dia_semana = :dia";
        $stmtH = $this->db->prepare($queryHorarios);
        $stmtH->execute([':fisio_id' => $terapeuta_id, ':dia' => $dia_nombre]);
        $horarios = $stmtH->fetchAll(PDO::FETCH_ASSOC);

        if (empty($horarios)) return [];

        $queryAusencias = "SELECT 1 FROM ausencias_terapeutas WHERE terapeuta_id = :fisio_id AND :fecha BETWEEN fecha_inicio AND fecha_fin";
        $stmtA = $this->db->prepare($queryAusencias);
        $stmtA->execute([':fisio_id' => $terapeuta_id, ':fecha' => $fecha]);
        if ($stmtA->fetch()) return [];

        // Citas del terapeuta para este día
        $queryCitas = "SELECT c.cita_id, c.fecha_hora, COALESCE(tc.duracion_minutos, 60) as duracion_minutos 
                      FROM citas c 
                      LEFT JOIN tipos_citas tc ON c.tipo_cita_id = tc.tipo_cita_id 
                      WHERE c.terapeuta_id = :fisio_id 
                      AND DATE(c.fecha_hora) = :fecha 
                      AND c.estado != 'Cancelada'";
        if ($exclude_cita_id) {
            $queryCitas .= " AND c.cita_id != " . (int)$exclude_cita_id;
        }
        $stmtC = $this->db->prepare($queryCitas);
        $stmtC->execute([':fisio_id' => $terapeuta_id, ':fecha' => $fecha]);
        $citas = $stmtC->fetchAll(PDO::FETCH_ASSOC);

        // Si es presencial, obtener despachos activos para verificar disponibilidad de salas físicas
        $despachosActivos = [];
        $citasDespachosDelDia = [];
        $esPresencial = ($ubicacion_tipo === 'presencial');

        if ($esPresencial) {
            $stmtD = $this->db->prepare("SELECT despacho_id, capacidad FROM despachos WHERE estado = 'Activo'");
            $stmtD->execute();
            $despachosActivos = $stmtD->fetchAll(PDO::FETCH_ASSOC);

            // Si hay despachos configurados, comprobaremos la ocupación
            if (!empty($despachosActivos)) {
                $sqlCitasDespachos = "SELECT c.cita_id, c.despacho_id, c.fecha_hora, 
                                             COALESCE(c.fecha_hora_fin, DATE_ADD(c.fecha_hora, INTERVAL COALESCE(tc.duracion_minutos, 60) MINUTE)) as fecha_fin_calculada
                                      FROM citas c
                                      LEFT JOIN tipos_citas tc ON c.tipo_cita_id = tc.tipo_cita_id
                                      WHERE c.despacho_id IS NOT NULL
                                        AND DATE(c.fecha_hora) = :fecha
                                        AND c.estado != 'Cancelada'";
                if ($exclude_cita_id) {
                    $sqlCitasDespachos .= " AND c.cita_id != " . (int)$exclude_cita_id;
                }
                $stmtCD = $this->db->prepare($sqlCitasDespachos);
                $stmtCD->execute([':fecha' => $fecha]);
                $citasDespachosDelDia = $stmtCD->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        $availableSlots = [];
        $duracion_segundos = $duracion_minutos * 60;

        foreach ($horarios as $h) {
            $current = strtotime($fecha . ' ' . $h['hora_inicio']);
            $end = strtotime($fecha . ' ' . $h['hora_fin']);

            while ($current + $duracion_segundos <= $end) {
                $slotStart = $current;
                $slotEnd = $current + $duracion_segundos;

                // 1. Verificar si el terapeuta está ocupado
                $isOccupiedTerapeuta = false;
                foreach ($citas as $cita) {
                    $citaStart = strtotime($cita['fecha_hora']);
                    $citaDur = ($cita['duracion_minutos'] ?? 60) * 60;
                    $citaEnd = $citaStart + $citaDur;

                    if ($slotStart < $citaEnd && $citaStart < $slotEnd) {
                        $isOccupiedTerapeuta = true;
                        break;
                    }
                }

                // 2. Si el terapeuta está libre y la cita es presencial, verificar si hay al menos un despacho libre
                $hasDespachoLibre = true;
                if (!$isOccupiedTerapeuta && $esPresencial && !empty($despachosActivos)) {
                    $ocupacionPorDespacho = [];
                    foreach ($citasDespachosDelDia as $cd) {
                        $cdStart = strtotime($cd['fecha_hora']);
                        $cdEnd = strtotime($cd['fecha_fin_calculada']);

                        if ($slotStart < $cdEnd && $cdStart < $slotEnd) {
                            $dId = (int)$cd['despacho_id'];
                            $ocupacionPorDespacho[$dId] = ($ocupacionPorDespacho[$dId] ?? 0) + 1;
                        }
                    }

                    $despachoDisponibleEncontrado = false;
                    foreach ($despachosActivos as $desp) {
                        $dId = (int)$desp['despacho_id'];
                        $cap = max(1, (int)($desp['capacidad'] ?? 1));
                        $actuales = $ocupacionPorDespacho[$dId] ?? 0;
                        if ($actuales < $cap) {
                            $despachoDisponibleEncontrado = true;
                            break;
                        }
                    }

                    if (!$despachoDisponibleEncontrado) {
                        $hasDespachoLibre = false;
                    }
                }

                if (!$isOccupiedTerapeuta && $hasDespachoLibre) {
                    $availableSlots[] = date('H:i', $slotStart);
                }

                // Avanzamos en bloques de 30 minutos para ofrecer más flexibilidad de inicio
                $current = strtotime('+30 minutes', $current);
            }
        }

        return array_values(array_unique($availableSlots));
    }

    public function getAvailableDays($terapeuta_id, $duracion_minutos = 60, $ubicacion_tipo = 'presencial', $exclude_cita_id = null)
    {
        $availableDays = [];
        $today = new \DateTime('today');

        for ($i = 0; $i < 60; $i++) {
            $currentDate = clone $today;
            $currentDate->modify("+$i days");
            $fechaStr = $currentDate->format('Y-m-d');

            $slots = $this->getAvailableSlots($terapeuta_id, $fechaStr, $duracion_minutos, $ubicacion_tipo, $exclude_cita_id);
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
            1 => 'Ene',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dic'
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
        $query = "SELECT c.*, p.usuario_id as paciente_usuario_id, p.nombre as paciente_nombre, p.apellidos as paciente_apellidos, p.email as paciente_email, p.telefono as paciente_telefono,
                         f.usuario_id as fisioterapeuta_usuario_id, f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos 
                  FROM citas c 
                  LEFT JOIN usuarios p ON (c.paciente_id = p.usuario_id OR c.paciente_id = p.dni) 
                  LEFT JOIN usuarios f ON (c.terapeuta_id = f.usuario_id OR c.terapeuta_id = f.dni) 
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
        $query = "SELECT c.*, p.usuario_id as paciente_usuario_id, p.nombre as paciente_nombre, p.apellidos as paciente_apellidos, p.email as paciente_email, p.telefono as paciente_telefono,
                         f.usuario_id as fisioterapeuta_usuario_id, f.nombre as fisioterapeuta_nombre, f.apellidos as fisioterapeuta_apellidos 
                  FROM citas c 
                  LEFT JOIN usuarios p ON (c.paciente_id = p.usuario_id OR c.paciente_id = p.dni) 
                  LEFT JOIN usuarios f ON (c.terapeuta_id = f.usuario_id OR c.terapeuta_id = f.dni) 
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
