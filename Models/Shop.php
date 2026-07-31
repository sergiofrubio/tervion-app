<?php
namespace App\Models;

use App\Core\DataBase;
use PDO;

class Shop
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    /**
     * Registra la compra de un bono por parte de un paciente
     * 
     * @param string $usuario_id
     * @param int $bono_id
     * @return int|bool Retorna el ID de la factura generada o false en caso de error
     */
    public function registrarCompraBono($usuario_id, $bono_id)
    {
        try {
            $this->db->beginTransaction();

            // 1. Obtener detalles del bono
            $queryBono = "SELECT * FROM bonos WHERE bono_id = :bono_id LIMIT 1";
            $stmtBono = $this->db->prepare($queryBono);
            $stmtBono->execute([':bono_id' => $bono_id]);
            $bono = $stmtBono->fetch(PDO::FETCH_ASSOC);

            if (!$bono) {
                $this->db->rollBack();
                return false;
            }

            // 2. Insertar en bonos_pacientes
            $queryBP = "INSERT INTO bonos_pacientes (paciente_id, bono_id, sesiones_restantes, fecha_compra, creado_por) 
                        VALUES (:paciente_id, :bono_id, :sesiones_restantes, :fecha_compra, :creado_por)";
            $stmtBP = $this->db->prepare($queryBP);
            $stmtBP->execute([
                ':paciente_id' => $usuario_id,
                ':bono_id' => $bono_id,
                ':sesiones_restantes' => $bono['numero_sesiones'],
                ':fecha_compra' => date('Y-m-d'),
                ':creado_por' => $usuario_id
            ]);

            // 3. Crear factura
            $invoiceModel = new Invoice($this->db);
            $impuesto = 21.00; // 21% IVA en España
            $precioBase = (float)$bono['precio'] / (1 + ($impuesto / 100));

            $invoiceData = [
                'paciente_id'   => $usuario_id,
                'serie'         => 'A',
                'tipo_factura'  => 'F1', // Ordinaria
                'fecha_emision' => date('Y-m-d'),
                'estado'        => 'Pagada',
                'descripcion'   => 'Compra de ' . $bono['nombre'],
                'precio'        => $precioBase,
                'impuesto'      => $impuesto,
                'creado_por'    => $usuario_id
            ];

            if ($invoiceModel->save($invoiceData)) {
                $invoiceId = $this->db->lastInsertId();
                $this->db->commit();
                return $invoiceId;
            }

            $this->db->rollBack();
            return false;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error al registrar la compra del bono: " . $e->getMessage());
            return false;
        }
    }
}
