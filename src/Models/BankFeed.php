<?php
namespace App\Models;

class BankFeed
{
    private $gastoModel;

    public function __construct($gastoModel = null)
    {
        $this->gastoModel = $gastoModel ?: new Gasto();
    }

    /**
     * Concilia una transacción bancaria y crea el gasto de forma automática.
     */
    public function reconcileTransaction($t)
    {
        $concepto = $t['concepto'];
        $importe = (float)$t['importe'];
        $fecha = $t['fecha'];

        // Si es un cargo (importe negativo)
        if ($importe < 0) {
            $importeAbs = abs($importe);
            
            // Ignorar cargos no deducibles o de transferencias internas
            if (str_contains(mb_strtolower($concepto), 'traspaso') || str_contains(mb_strtolower($concepto), 'efectivo cajero')) {
                return [
                    'status' => 'ignored',
                    'message' => 'Movimiento financiero interno / no deducible'
                ];
            }

            // Mapear NIF y Proveedor ficticio basado en el concepto
            $nif = 'B00000000';
            $proveedor = 'Proveedor conciliar banco';
            $facturaNum = 'BANC-' . date('Ymd', strtotime($fecha)) . '-' . rand(10, 99);

            $conceptoLower = mb_strtolower($concepto);
            if (str_contains($conceptoLower, 'iberdrola') || str_contains($conceptoLower, 'luz') || str_contains($conceptoLower, 'endesa')) {
                $nif = 'A95075578';
                $proveedor = 'Iberdrola Clientes S.A.U.';
            } elseif (str_contains($conceptoLower, 'gestor') || str_contains($conceptoLower, 'asesor')) {
                $nif = 'B82345678';
                $proveedor = 'Gestoría Rivas S.L.';
            } elseif (str_contains($conceptoLower, 'alquiler') || str_contains($conceptoLower, 'renta')) {
                $nif = 'F91122334';
                $proveedor = 'Patrimonial Centro Histórico S.A.';
            } elseif (str_contains($conceptoLower, 'nominas') || str_contains($conceptoLower, 'tgss') || str_contains($conceptoLower, 'seguridad social')) {
                $nif = 'TGSS0001';
                $proveedor = 'Tesorería General de la S.S.';
            } elseif (str_contains($conceptoLower, 'material') || str_contains($conceptoLower, 'fisio')) {
                $nif = 'B99221100';
                $proveedor = 'FisioDistribuciones España S.L.';
            }

            // Auto-categorización para obtener impuestos e IVA/IRPF
            $rules = Gasto::autoCategorize($concepto, $proveedor);
            
            // Calcular base imponible revertida según el tipo de IVA e IRPF
            // total = base + base*iva/100 - base*irpf/100
            // base = total / (1 + iva/100 - irpf/100)
            $factor = 1 + ($rules['tipo_iva'] / 100) - ($rules['retencion_irpf'] / 100);
            $base = $importeAbs / $factor;

            $data = [
                'nif_proveedor' => $nif,
                'nombre_proveedor' => $proveedor,
                'numero_factura' => $facturaNum,
                'fecha_emision' => $fecha,
                'concepto' => 'Conciliación bancaria: ' . $concepto,
                'base_imponible' => round($base, 2),
                'tipo_iva' => $rules['tipo_iva'],
                'retencion_irpf' => $rules['retencion_irpf'],
                'categoria' => $rules['categoria']
            ];

            if ($this->gastoModel->save($data)) {
                return [
                    'status' => 'created',
                    'message' => "Gasto creado automáticamente: {$proveedor} ({$rules['categoria']})",
                    'data' => $data
                ];
            }
        }

        // Si es un cobro (ingreso)
        return [
            'status' => 'info',
            'message' => 'Cobro / Ingreso de paciente conciliado con factura de venta'
        ];
    }

    /**
     * Simula la descarga y procesamiento automático del feed de transacciones bancarias.
     */
    public function importMockBankFeed()
    {
        $transactions = [
            ['fecha' => date('Y-m-d', strtotime('-1 days')), 'concepto' => 'PAGO CON TARJETA IBERDROLA', 'importe' => -143.50],
            ['fecha' => date('Y-m-d', strtotime('-3 days')), 'concepto' => 'RECIBO GESTORIA RIVAS CUOTA', 'importe' => -90.75],
            ['fecha' => date('Y-m-d', strtotime('-5 days')), 'concepto' => 'TRANSFERENCIA ALQUILER LOCAL JUNIO', 'importe' => -1452.00], // Incluye IVA e IRPF
            ['fecha' => date('Y-m-d', strtotime('-6 days')), 'concepto' => 'TRANSFERENCIA SEGUROS SOCIALES TGSS', 'importe' => -450.00],
            ['fecha' => date('Y-m-d', strtotime('-10 days')), 'concepto' => 'COMPRA MATERIAL SANITARIO FISIODIST', 'importe' => -176.05]
        ];

        $results = [];
        foreach ($transactions as $t) {
            $results[] = $this->reconcileTransaction($t);
        }

        return $results;
    }
}
