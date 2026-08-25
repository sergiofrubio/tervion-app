<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Shop;
use PDO;
use PDOStatement;

class ShopTest extends TestCase
{
    public function testRegistrarCompraBono()
    {
        $stmtBono = $this->createMock(PDOStatement::class);
        $stmtBono->method('execute')->willReturn(true);
        $stmtBono->method('fetch')->willReturn([
            'bono_id' => 5,
            'nombre' => 'Bono 5',
            'precio' => 100,
            'numero_sesiones' => 5
        ]);

        $stmtBP = $this->createMock(PDOStatement::class);
        $stmtBP->method('execute')->willReturn(true);

        $stmtInvoice = $this->createMock(PDOStatement::class);
        $stmtInvoice->method('execute')->willReturn(true);
        $stmtInvoice->method('fetch')->willReturn(false);

        $dbMock = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepare', 'beginTransaction', 'commit', 'rollBack', 'lastInsertId', 'inTransaction'])
            ->getMock();

        $dbMock->method('prepare')->willReturnCallback(function($query) use ($stmtBono, $stmtBP, $stmtInvoice) {
            if (strpos($query, 'FROM bonos') !== false) {
                return $stmtBono;
            }
            if (strpos($query, 'bonos_pacientes') !== false) {
                return $stmtBP;
            }
            return $stmtInvoice;
        });

        $dbMock->method('lastInsertId')->willReturn('42');
        $dbMock->method('inTransaction')->willReturn(true);

        $tiendaModel = new Shop($dbMock);
        
        $result = $tiendaModel->registrarCompraBono('U123', 5);
        $this->assertEquals(42, $result);
    }
}
