<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Invoice;
use PDO;
use PDOStatement;

use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

#[AllowMockObjectsWithoutExpectations]
class InvoiceTest extends TestCase
{
    private $dbMock;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->dbMock = $this->createMock(PDO::class);
    }

    public function testGetAll()
    {
        $facturasData = [
            ['factura_id' => 1, 'paciente_id' => 'P1', 'nombre' => 'Jane', 'apellidos' => 'Doe', 'total' => 100.00]
        ];

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn($facturasData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT f.*'))
            ->willReturn($this->stmtMock);

        $facturaModel = new Invoice($this->dbMock);
        $result = $facturaModel->getAll(['estado' => 'Pagada']);

        $this->assertEquals($facturasData, $result);
    }

    public function testGetById()
    {
        $facturaData = [
            'factura_id' => 1,
            'paciente_id' => 'P1',
            'total' => 121.00
        ];

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->willReturn($facturaData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('WHERE f.factura_id = :id'))
            ->willReturn($this->stmtMock);

        $facturaModel = new Invoice($this->dbMock);
        $result = $facturaModel->getById(1);

        $this->assertEquals($facturaData, $result);
    }

    public function testSaveVerifactuCalculations()
    {
        // 1. Simular la respuesta de getUltimaFactura
        // Para esto, prepare() se llamará dos veces:
        // - Primera para la query de getUltimaFactura en save()
        // - Segunda para el INSERT final
        $stmtUltima = $this->createMock(PDOStatement::class);
        $stmtUltima->method('execute')->willReturn(true);
        $stmtUltima->method('fetch')->willReturn([
            'numero' => 42,
            'huella' => 'oldhash123abc'
        ]);

        $stmtInsert = $this->createMock(PDOStatement::class);
        
        // Assert that the final query receives the calculated parameters
        $stmtInsert->expects($this->once())
            ->method('execute')
            ->with($this->callback(function($params) {
                // Check invoice numbering incremented
                if ($params[':numero'] !== 43) {
                    return false;
                }
                // Check previous signature chain
                if ($params[':huella_anterior'] !== 'oldhash123abc') {
                    return false;
                }
                // Check economic calculations: price=100, tax=21%
                if ($params[':cuota_iva'] !== 21.00 || $params[':total'] !== 121.00) {
                    return false;
                }
                return !empty($params[':huella']) && strlen($params[':huella']) === 64;
            }))
            ->willReturn(true);

        $stmtClinica = $this->createMock(PDOStatement::class);
        $stmtClinica->method('execute')->willReturn(true);
        $stmtClinica->method('fetch')->willReturn([
            'nif_cif' => 'B12345678',
            'nombre_comercial' => 'Clinica Test',
            'verifactu_activo' => 0
        ]);

        $this->dbMock->expects($this->exactly(3))
            ->method('prepare')
            ->willReturnCallback(function($query) use ($stmtClinica, $stmtUltima, $stmtInsert) {
                if (strpos($query, 'FROM clinicas') !== false) {
                    return $stmtClinica;
                }
                if (strpos($query, 'SELECT * FROM facturas WHERE serie = :serie') !== false) {
                    return $stmtUltima;
                }
                if (strpos($query, 'INSERT INTO facturas') !== false) {
                    return $stmtInsert;
                }
                return null;
            });

        $this->dbMock->method('lastInsertId')->willReturn('43');

        $facturaModel = new Invoice($this->dbMock);
        
        $inputData = [
            'paciente_id' => 'P123',
            'serie' => 'A',
            'tipo_factura' => 'F1',
            'fecha_emision' => '2026-05-20',
            'estado' => 'Emitida',
            'descripcion' => 'Sesión Fisioterapia',
            'precio' => 100.00,
            'impuesto' => 21.00,
            'creado_por' => 'Admin'
        ];

        $result = $facturaModel->save($inputData);
        $this->assertEquals(43, $result);
    }
}
