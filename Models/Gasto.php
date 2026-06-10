<?php
namespace App\Models;

use App\Core\DataBase;
use PDO;

class Gasto
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getAll($filters = [])
    {
        $query = "SELECT * FROM gastos";
        $where = [];
        $params = [];

        if (!empty($filters['categoria'])) {
            $where[] = "categoria = :categoria";
            $params[':categoria'] = $filters['categoria'];
        }

        if (!empty($filters['fecha_desde'])) {
            $where[] = "fecha_emision >= :fecha_desde";
            $params[':fecha_desde'] = $filters['fecha_desde'];
        }

        if (!empty($filters['fecha_hasta'])) {
            $where[] = "fecha_emision <= :fecha_hasta";
            $params[':fecha_hasta'] = $filters['fecha_hasta'];
        }

        if (!empty($filters['q'])) {
            $where[] = "(nombre_proveedor LIKE :q OR nif_proveedor LIKE :q OR concepto LIKE :q)";
            $params[':q'] = "%" . $filters['q'] . "%";
        }

        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }

        $query .= " ORDER BY fecha_emision DESC, gasto_id DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM gastos WHERE gasto_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data)
    {
        // 1. Cálculos de IVA e IRPF
        $base = (float)$data['base_imponible'];
        $tipo_iva = (float)($data['tipo_iva'] ?? 21.00);
        $retencion_irpf = (float)($data['retencion_irpf'] ?? 0.00);

        $data['cuota_iva'] = $base * ($tipo_iva / 100);
        $data['cuota_irpf'] = $base * ($retencion_irpf / 100);
        $data['total'] = $base + $data['cuota_iva'] - $data['cuota_irpf'];

        $query = "INSERT INTO gastos (nif_proveedor, nombre_proveedor, numero_factura, fecha_emision, concepto, 
                    base_imponible, tipo_iva, cuota_iva, retencion_irpf, cuota_irpf, total, categoria) 
                  VALUES (:nif_proveedor, :nombre_proveedor, :numero_factura, :fecha_emision, :concepto, 
                    :base_imponible, :tipo_iva, :cuota_iva, :retencion_irpf, :cuota_irpf, :total, :categoria)";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':nif_proveedor'     => $data['nif_proveedor'],
            ':nombre_proveedor'  => $data['nombre_proveedor'],
            ':numero_factura'    => $data['numero_factura'],
            ':fecha_emision'     => $data['fecha_emision'],
            ':concepto'          => $data['concepto'],
            ':base_imponible'    => $data['base_imponible'],
            ':tipo_iva'          => $data['tipo_iva'],
            ':cuota_iva'         => $data['cuota_iva'],
            ':retencion_irpf'    => $data['retencion_irpf'],
            ':cuota_irpf'        => $data['cuota_irpf'],
            ':total'             => $data['total'],
            ':categoria'         => $data['categoria'] ?? 'Otros'
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM gastos WHERE gasto_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Motor de Pre-categorización e Impuestos automático.
     */
    public static function autoCategorize($concepto, $proveedor = '')
    {
        $texto = mb_strtolower($concepto . ' ' . $proveedor);
        
        if (str_contains($texto, 'alquiler') || str_contains($texto, 'arrendamiento') || str_contains($texto, 'renta') || str_contains($texto, 'local')) {
            return ['categoria' => 'Alquileres', 'tipo_iva' => 21.00, 'retencion_irpf' => 19.00];
        }
        if (str_contains($texto, 'luz') || str_contains($texto, 'electricidad') || str_contains($texto, 'agua') || str_contains($texto, 'gas natural') || str_contains($texto, 'internet') || str_contains($texto, 'fibra') || str_contains($texto, 'telefono') || str_contains($texto, 'móvil') || str_contains($texto, 'iberdrola') || str_contains($texto, 'endesa') || str_contains($texto, 'movistar')) {
            return ['categoria' => 'Suministros', 'tipo_iva' => 21.00, 'retencion_irpf' => 0.00];
        }
        if (str_contains($texto, 'gestor') || str_contains($texto, 'asesor') || str_contains($texto, 'abogado') || str_contains($texto, 'abogacía') || str_contains($texto, 'limpieza') || str_contains($texto, 'mantenimiento')) {
            return ['categoria' => 'Servicios profesionales', 'tipo_iva' => 21.00, 'retencion_irpf' => 15.00];
        }
        if (str_contains($texto, 'camilla') || str_contains($texto, 'laser') || str_contains($texto, 'aparato') || str_contains($texto, 'ecografo') || str_contains($texto, 'inversion') || str_contains($texto, 'mobiliario')) {
            return ['categoria' => 'Bienes de inversión', 'tipo_iva' => 21.00, 'retencion_irpf' => 0.00];
        }
        if (str_contains($texto, 'nomina') || str_contains($texto, 'sueldo') || str_contains($texto, 'seguridad social') || str_contains($texto, 'tgss') || str_contains($texto, 'mutua')) {
            return ['categoria' => 'Personal', 'tipo_iva' => 0.00, 'retencion_irpf' => 0.00];
        }
        
        return ['categoria' => 'Otros', 'tipo_iva' => 21.00, 'retencion_irpf' => 0.00];
    }

    /**
     * Lector Inteligente de Facturas (Simulación OCR/IA).
     */
    public function autoParseInvoice($filename)
    {
        $name = mb_strtolower($filename);
        $data = [
            'nif_proveedor' => 'B00000000',
            'nombre_proveedor' => 'Proveedor Genérico',
            'numero_factura' => 'F-' . date('Y') . '-' . rand(100, 999),
            'fecha_emision' => date('Y-m-d'),
            'concepto' => 'Gasto automático registrado por IA',
            'base_imponible' => 50.00
        ];

        if (str_contains($name, 'iberdrola') || str_contains($name, 'luz') || str_contains($name, 'electricidad')) {
            $data['nif_proveedor'] = 'A95075578';
            $data['nombre_proveedor'] = 'Iberdrola Clientes S.A.U.';
            $data['concepto'] = 'Factura de suministro eléctrico clínica';
            $data['base_imponible'] = (float)rand(85, 220);
        } elseif (str_contains($name, 'gestoria') || str_contains($name, 'asesoria') || str_contains($name, 'mensualidad')) {
            $data['nif_proveedor'] = 'B82345678';
            $data['nombre_proveedor'] = 'Gestores Asociados Rivas S.L.';
            $data['concepto'] = 'Asesoramiento fiscal, contable y laboral mensual';
            $data['base_imponible'] = 75.00;
        } elseif (str_contains($name, 'alquiler') || str_contains($name, 'arrendamiento') || str_contains($name, 'local')) {
            $data['nif_proveedor'] = 'F91122334';
            $data['nombre_proveedor'] = 'Patrimonial Centro Histórico S.A.';
            $data['concepto'] = 'Mensualidad alquiler local comercial clínica';
            $data['base_imponible'] = 1200.00;
        } elseif (str_contains($name, 'material') || str_contains($name, 'cremas') || str_contains($name, 'vendas')) {
            $data['nif_proveedor'] = 'B99221100';
            $data['nombre_proveedor'] = 'FisioDistribuciones España S.L.';
            $data['concepto'] = 'Compra de cremas de masaje, electrodos y vendas';
            $data['base_imponible'] = 145.50;
        }

        $rules = self::autoCategorize($data['concepto'], $data['nombre_proveedor']);
        $data['tipo_iva'] = $rules['tipo_iva'];
        $data['retencion_irpf'] = $rules['retencion_irpf'];
        $data['categoria'] = $rules['categoria'];

        return $this->save($data);
    }
}
