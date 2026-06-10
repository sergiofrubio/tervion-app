<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Gasto;
use App\Models\Accounting;
use PDO;
use PDOStatement;

class AccountingTest extends TestCase
{
    private $dbMock;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->dbMock = $this->createMock(PDO::class);
    }

    public function testGastoSaveCalculations()
    {
        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with($this->callback(function($params) {
                // Base: 1000.00, IVA: 21%, IRPF: 15%
                // cuota_iva = 1000 * 0.21 = 210.00
                // cuota_irpf = 1000 * 0.15 = 150.00
                // total = 1000 + 210 - 150 = 1060.00
                return $params[':cuota_iva'] == 210.00 &&
                       $params[':cuota_irpf'] == 150.00 &&
                       $params[':total'] == 1060.00;
            }))
            ->willReturn(true);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $gastoModel = new Gasto($this->dbMock);
        
        $data = [
            'nif_proveedor' => 'A12345678',
            'nombre_proveedor' => 'Medical Supps',
            'numero_factura' => 'EXP-999',
            'fecha_emision' => '2026-06-01',
            'concepto' => 'Alquiler camillas',
            'base_imponible' => 1000.00,
            'tipo_iva' => 21.00,
            'retencion_irpf' => 15.00,
            'categoria' => 'Alquileres'
        ];

        $this->assertTrue($gastoModel->save($data));
    }

    public function testModelo303Calculations()
    {
        // Mock getQuarterDates to return fixed dates
        $accountingModel = $this->getMockBuilder(Accounting::class)
            ->setConstructorArgs([$this->dbMock])
            ->onlyMethods(['getQuarterDates'])
            ->getMock();

        $accountingModel->method('getQuarterDates')
            ->willReturn(['2026-04-01', '2026-06-30']);

        $stmtEmitidas = $this->createMock(PDOStatement::class);
        $stmtEmitidas->method('execute')->willReturn(true);
        $stmtEmitidas->method('fetchAll')->willReturn([
            ['tipo' => 21.00, 'base' => 2000.00, 'cuota' => 420.00]
        ]);

        $stmtRecibidas = $this->createMock(PDOStatement::class);
        $stmtRecibidas->method('execute')->willReturn(true);
        $stmtRecibidas->method('fetchAll')->willReturn([
            ['tipo' => 21.00, 'base' => 1000.00, 'cuota' => 210.00]
        ]);

        $this->dbMock->method('prepare')
            ->willReturnCallback(function($query) use ($stmtEmitidas, $stmtRecibidas) {
                if (strpos($query, 'facturas') !== false) {
                    return $stmtEmitidas;
                }
                if (strpos($query, 'gastos') !== false) {
                    return $stmtRecibidas;
                }
                return null;
            });

        $result = $accountingModel->getModelo303(2, 2026);

        $this->assertEquals(420.00, $result['total_repercutido_cuota']);
        $this->assertEquals(210.00, $result['total_soportado_cuota']);
        // 420 - 210 = 210
        $this->assertEquals(210.00, $result['resultado']);
    }

    public function testModelo130Calculations()
    {
        // Mock getQuarterDates to return fixed dates
        $accountingModel = $this->getMockBuilder(Accounting::class)
            ->setConstructorArgs([$this->dbMock])
            ->onlyMethods(['getQuarterDates'])
            ->getMock();

        $accountingModel->method('getQuarterDates')
            ->willReturn(['2026-01-01', '2026-03-31']);

        // 1. Ingresos
        $stmtIngresos = $this->createMock(PDOStatement::class);
        $stmtIngresos->method('execute')->willReturn(true);
        $stmtIngresos->method('fetch')->willReturn(['total_ingresos' => 5000.00]);

        // 2. Gastos
        $stmtGastos = $this->createMock(PDOStatement::class);
        $stmtGastos->method('execute')->willReturn(true);
        $stmtGastos->method('fetch')->willReturn(['total_gastos' => 2000.00]);

        // 3. Nominas
        $stmtNominas = $this->createMock(PDOStatement::class);
        $stmtNominas->method('execute')->willReturn(true);
        $stmtNominas->method('fetch')->willReturn(['bruto' => 1000.00, 'ss' => 300.00]);

        $this->dbMock->method('prepare')
            ->willReturnCallback(function($query) use ($stmtIngresos, $stmtGastos, $stmtNominas) {
                if (strpos($query, 'facturas') !== false) {
                    return $stmtIngresos;
                }
                if (strpos($query, 'gastos') !== false) {
                    return $stmtGastos;
                }
                if (strpos($query, 'nominas') !== false) {
                    return $stmtNominas;
                }
                return null;
            });

        $result = $accountingModel->getModelo130(1, 2026);

        // Ingresos: 5000.00
        // Gastos: 2000 (gastos base) + 1300 (nominas/personal) = 3300.00
        // Rendimiento Neto: 5000 - 3300 = 1700.00
        // Pago fraccionado (20%): 340.00
        $this->assertEquals(5000.00, $result['ingresos']);
        $this->assertEquals(3300.00, $result['gastos']);
        $this->assertEquals(1700.00, $result['rendimiento_neto']);
        $this->assertEquals(340.00, $result['pago_fraccionado']);
        $this->assertEquals(340.00, $result['cuota_ingresar']);
    }

    public function testAutoCategorization()
    {
        $res1 = Gasto::autoCategorize('Alquiler de local de junio');
        $this->assertEquals('Alquileres', $res1['categoria']);
        $this->assertEquals(19.00, $res1['retencion_irpf']);

        $res2 = Gasto::autoCategorize('Factura de electricidad Iberdrola');
        $this->assertEquals('Suministros', $res2['categoria']);
        $this->assertEquals(21.00, $res2['tipo_iva']);

        $res3 = Gasto::autoCategorize('Gastos de gestoria mensual');
        $this->assertEquals('Servicios profesionales', $res3['categoria']);
        $this->assertEquals(15.00, $res3['retencion_irpf']);
    }

    public function testBankFeedReconciliation()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $this->dbMock->method('prepare')->willReturn($stmtMock);

        $gastoModel = new Gasto($this->dbMock);
        $bankFeed = new \App\Models\BankFeed($gastoModel);

        // Alquiler local de 1452 € total
        // Base = 1452 / (1 + 0.21 - 0.19) = 1452 / 1.02 = 1423.53
        $transaction = [
            'fecha' => '2026-06-05',
            'concepto' => 'TRANSFERENCIA ALQUILER LOCAL JUNIO',
            'importe' => -1452.00
        ];

        $res = $bankFeed->reconcileTransaction($transaction);
        $this->assertEquals('created', $res['status']);
        $this->assertEquals(1423.53, $res['data']['base_imponible']);
    }

    public function testAutoParseInvoice()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $this->dbMock->method('prepare')->willReturn($stmtMock);

        $gastoModel = new Gasto($this->dbMock);

        // Subida de factura Iberdrola
        $res = $gastoModel->autoParseInvoice('factura_iberdrola_junio.pdf');
        $this->assertTrue($res);
    }
}


