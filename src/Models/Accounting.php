<?php
namespace App\Models;

use App\Core\DataBase;
use PDO;

class Accounting
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    /**
     * Devuelve las fechas de inicio y fin para un trimestre y año específicos.
     */
    public function getQuarterDates($quarter, $year)
    {
        switch ($quarter) {
            case 1:
                return ["$year-01-01", "$year-03-31"];
            case 2:
                return ["$year-04-01", "$year-06-30"];
            case 3:
                return ["$year-07-01", "$year-09-30"];
            case 4:
                return ["$year-10-01", "$year-12-31"];
            default:
                return ["$year-01-01", "$year-12-31"];
        }
    }

    /**
     * Calcula los datos del borrador del Modelo 303 (IVA).
     */
    public function getModelo303($quarter, $year)
    {
        list($start, $end) = $this->getQuarterDates($quarter, $year);

        // 1. IVA Repercutido (Facturas Emitidas)
        $queryEmitidas = "SELECT impuesto as tipo, SUM(precio) as base, SUM(cuota_iva) as cuota 
                          FROM facturas 
                          WHERE fecha_emision BETWEEN :start AND :end 
                          GROUP BY impuesto";
        $stmt = $this->db->prepare($queryEmitidas);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $repercutido = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. IVA Soportado (Facturas Recibidas / Gastos)
        $queryRecibidas = "SELECT tipo_iva as tipo, SUM(base_imponible) as base, SUM(cuota_iva) as cuota 
                           FROM gastos 
                           WHERE fecha_emision BETWEEN :start AND :end 
                           GROUP BY tipo_iva";
        $stmt = $this->db->prepare($queryRecibidas);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $soportado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalRepercutidoCuota = array_sum(array_column($repercutido, 'cuota'));
        $totalRepercutidoBase = array_sum(array_column($repercutido, 'base'));
        $totalSoportadoCuota = array_sum(array_column($soportado, 'cuota'));
        $totalSoportadoBase = array_sum(array_column($soportado, 'base'));

        $resultado = $totalRepercutidoCuota - $totalSoportadoCuota;

        return [
            'trimestre' => $quarter,
            'anio' => $year,
            'repercutido' => $repercutido,
            'total_repercutido_base' => $totalRepercutidoBase,
            'total_repercutido_cuota' => $totalRepercutidoCuota,
            'soportado' => $soportado,
            'total_soportado_base' => $totalSoportadoBase,
            'total_soportado_cuota' => $totalSoportadoCuota,
            'resultado' => $resultado
        ];
    }

    /**
     * Calcula los datos del borrador del Modelo 130 (IRPF Fraccionado Autónomos).
     * Nota: En España, el Modelo 130 es acumulativo desde el 1 de enero hasta el final del trimestre.
     */
    public function getModelo130($quarter, $year)
    {
        // Acumulado desde enero del año en curso
        $start = "$year-01-01";
        list($_, $end) = $this->getQuarterDates($quarter, $year);

        // 1. Ingresos acumulados (Bases de facturas emitidas)
        $queryIngresos = "SELECT SUM(precio) as total_ingresos FROM facturas WHERE fecha_emision BETWEEN :start AND :end";
        $stmt = $this->db->prepare($queryIngresos);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $ingresos = (float)($stmt->fetch(PDO::FETCH_ASSOC)['total_ingresos'] ?? 0.00);

        // 2. Gastos acumulados deducibles
        $queryGastos = "SELECT SUM(base_imponible) as total_gastos FROM gastos WHERE fecha_emision BETWEEN :start AND :end";
        $stmt = $this->db->prepare($queryGastos);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $gastosBase = (float)($stmt->fetch(PDO::FETCH_ASSOC)['total_gastos'] ?? 0.00);

        // Nóminas acumuladas (gasto deducible para autónomos con trabajadores: bruto + SS empresa)
        $queryNominas = "SELECT SUM(devengos_total_bruto) as bruto, SUM(coste_seguridad_social_empresa) as ss 
                         FROM nominas 
                         WHERE fecha_emision BETWEEN :start AND :end";
        $stmt = $this->db->prepare($queryNominas);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $nominasInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        $gastosPersonal = (float)($nominasInfo['bruto'] ?? 0.00) + (float)($nominasInfo['ss'] ?? 0.00);

        $totalGastos = $gastosBase + $gastosPersonal;
        $rendimientoNeto = $ingresos - $totalGastos;

        // 3. Liquidación (20% del rendimiento neto si es positivo)
        $pagoFraccionado = $rendimientoNeto > 0 ? $rendimientoNeto * 0.20 : 0.00;

        // 4. Pagos a cuenta realizados en trimestres anteriores (estimado o acumulado)
        $pagosAnteriores = 0.00;
        if ($quarter > 1) {
            for ($q = 1; $q < $quarter; $q++) {
                $prev = $this->getModelo130($q, $year);
                $pagosAnteriores += $prev['cuota_ingresar'];
            }
        }

        $cuotaIngresar = max(0.00, $pagoFraccionado - $pagosAnteriores);

        return [
            'trimestre' => $quarter,
            'anio' => $year,
            'ingresos' => $ingresos,
            'gastos' => $totalGastos,
            'rendimiento_neto' => $rendimientoNeto,
            'pago_fraccionado' => $pagoFraccionado,
            'pagos_anteriores' => $pagosAnteriores,
            'cuota_ingresar' => $cuotaIngresar
        ];
    }

    /**
     * Calcula los datos del borrador del Modelo 111 (Retenciones IRPF en Nóminas y Profesionales).
     */
    public function getModelo111($quarter, $year)
    {
        list($start, $end) = $this->getQuarterDates($quarter, $year);

        // 1. Retenciones de Rendimientos del Trabajo (Nóminas)
        $queryTrabajo = "SELECT COUNT(DISTINCT contrato_id) as perceptores, 
                                SUM(devengos_total_bruto) as base, 
                                SUM(deduccion_irpf) as retenciones 
                         FROM nominas 
                         WHERE fecha_emision BETWEEN :start AND :end";
        $stmt = $this->db->prepare($queryTrabajo);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $trabajo = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Retenciones de Actividades Profesionales (Gastos con IRPF)
        $queryPro = "SELECT COUNT(DISTINCT nif_proveedor) as perceptores, 
                            SUM(base_imponible) as base, 
                            SUM(cuota_irpf) as retenciones 
                     FROM gastos 
                     WHERE fecha_emision BETWEEN :start AND :end AND retencion_irpf > 0";
        $stmt = $this->db->prepare($queryPro);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $profesionales = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalPerceptores = (int)($trabajo['perceptores'] ?? 0) + (int)($profesionales['perceptores'] ?? 0);
        $totalBase = (float)($trabajo['base'] ?? 0.00) + (float)($profesionales['base'] ?? 0.00);
        $totalRetenciones = (float)($trabajo['retenciones'] ?? 0.00) + (float)($profesionales['retenciones'] ?? 0.00);

        return [
            'trimestre' => $quarter,
            'anio' => $year,
            'trabajo' => [
                'perceptores' => (int)($trabajo['perceptores'] ?? 0),
                'base' => (float)($trabajo['base'] ?? 0.00),
                'retenciones' => (float)($trabajo['retenciones'] ?? 0.00)
            ],
            'profesionales' => [
                'perceptores' => (int)($profesionales['perceptores'] ?? 0),
                'base' => (float)($profesionales['base'] ?? 0.00),
                'retenciones' => (float)($profesionales['retenciones'] ?? 0.00)
            ],
            'total_perceptores' => $totalPerceptores,
            'total_base' => $totalBase,
            'total_retenciones' => $totalRetenciones
        ];
    }

    /**
     * Cuenta de Pérdidas y Ganancias (P&L) y Balance de Situación simplificado.
     */
    public function getProfitLoss($year)
    {
        $start = "$year-01-01";
        $end = "$year-12-31";

        // Ingresos (Facturas)
        $queryIngresos = "SELECT SUM(precio) as ingresos FROM facturas WHERE fecha_emision BETWEEN :start AND :end";
        $stmt = $this->db->prepare($queryIngresos);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $ingresos = (float)($stmt->fetch(PDO::FETCH_ASSOC)['ingresos'] ?? 0.00);

        // Gastos por Categoria
        $queryGastos = "SELECT categoria, SUM(base_imponible) as total 
                        FROM gastos 
                        WHERE fecha_emision BETWEEN :start AND :end 
                        GROUP BY categoria";
        $stmt = $this->db->prepare($queryGastos);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $gastos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formatear gastos en un array indexado por categoría
        $gastosCategorias = [
            'Alquileres' => 0.00,
            'Suministros' => 0.00,
            'Personal' => 0.00,
            'Servicios profesionales' => 0.00,
            'Bienes de inversión' => 0.00,
            'Otros' => 0.00
        ];
        foreach ($gastos as $g) {
            $gastosCategorias[$g['categoria']] = (float)$g['total'];
        }

        // Incorporar nóminas en gastos de Personal
        $queryNominas = "SELECT SUM(devengos_total_bruto) as bruto, SUM(coste_seguridad_social_empresa) as ss 
                         FROM nominas 
                         WHERE fecha_emision BETWEEN :start AND :end";
        $stmt = $this->db->prepare($queryNominas);
        $stmt->execute([':start' => $start, ':end' => $end]);
        $nominasInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalNominas = (float)($nominasInfo['bruto'] ?? 0.00) + (float)($nominasInfo['ss'] ?? 0.00);

        $gastosCategorias['Personal'] += $totalNominas;

        $totalGastos = array_sum($gastosCategorias);
        $beneficioBruto = $ingresos - $totalGastos;

        // Impuesto de Sociedades estimado (25%) para S.L.
        $impuestoSociedades = $beneficioBruto > 0 ? $beneficioBruto * 0.25 : 0.00;
        $beneficioNeto = $beneficioBruto - $impuestoSociedades;

        return [
            'anio' => $year,
            'ingresos' => $ingresos,
            'gastos_detalle' => $gastosCategorias,
            'total_gastos' => $totalGastos,
            'beneficio_antes_impuestos' => $beneficioBruto,
            'impuesto_sociedades_est' => $impuestoSociedades,
            'beneficio_neto_est' => $beneficioNeto
        ];
    }
}
