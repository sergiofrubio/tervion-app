<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\PaymentMethod;
use PDO;
use PDOStatement;

use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

#[AllowMockObjectsWithoutExpectations]
class PaymentMethodTest extends TestCase
{
    private $dbMock;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->dbMock = $this->createMock(PDO::class);
    }

    public function testGetByUsuario()
    {
        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':usuario_id', 'U123');

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['metodo_id' => 1, 'usuario_id' => 'U123']]);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('FROM metodos_pago WHERE usuario_id = :usuario_id'))
            ->willReturn($this->stmtMock);

        $pagoModel = new PaymentMethod($this->dbMock);
        $result = $pagoModel->getByUsuario('U123');

        $this->assertCount(1, $result);
    }

    public function testSaveNonDefault()
    {
        $data = [
            'usuario_id' => 'U123',
            'tipo' => 'tarjeta',
            'proveedor' => 'stripe',
            'last4' => '4242',
            'fecha_expiracion' => '12/28',
            'token_externo' => 'tok_123',
            'es_predeterminado' => 0
        ];

        // Should prepare exactly one statement (the insert statement)
        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO metodos_pago'))
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->exactly(8))
            ->method('bindParam');

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $pagoModel = new PaymentMethod($this->dbMock);
        $result = $pagoModel->save($data);

        $this->assertTrue($result);
    }

    public function testSaveDefaultUnsetsPrevious()
    {
        $data = [
            'usuario_id' => 'U123',
            'tipo' => 'tarjeta',
            'proveedor' => 'stripe',
            'last4' => '4242',
            'fecha_expiracion' => '12/28',
            'token_externo' => 'tok_123',
            'es_predeterminado' => 1
        ];

        $stmtUnset = $this->createMock(PDOStatement::class);
        $stmtUnset->expects($this->once())
            ->method('bindParam')
            ->with(':usuario_id', 'U123');
        $stmtUnset->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmtInsert = $this->createMock(PDOStatement::class);
        $stmtInsert->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->dbMock->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnCallback(function($query) use ($stmtUnset, $stmtInsert) {
                if (strpos($query, 'UPDATE metodos_pago SET es_predeterminado = 0') !== false) {
                    return $stmtUnset;
                }
                if (strpos($query, 'INSERT INTO metodos_pago') !== false) {
                    return $stmtInsert;
                }
                return null;
            });

        $pagoModel = new PaymentMethod($this->dbMock);
        $result = $pagoModel->save($data);

        $this->assertTrue($result);
    }

    public function testSetPredeterminado()
    {
        $stmtUnset = $this->createMock(PDOStatement::class);
        $stmtUnset->method('execute')->willReturn(true);

        $stmtUpdate = $this->createMock(PDOStatement::class);
        $stmtUpdate->expects($this->exactly(2))
            ->method('bindParam')
            ->with($this->logicalOr(':metodo_id', ':usuario_id'));
        $stmtUpdate->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->dbMock->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnCallback(function($query) use ($stmtUnset, $stmtUpdate) {
                if (strpos($query, 'UPDATE metodos_pago SET es_predeterminado = 0') !== false) {
                    return $stmtUnset;
                }
                if (strpos($query, 'UPDATE metodos_pago SET es_predeterminado = 1') !== false) {
                    return $stmtUpdate;
                }
                return null;
            });

        $pagoModel = new PaymentMethod($this->dbMock);
        $result = $pagoModel->setPredeterminado(1, 'U123');

        $this->assertTrue($result);
    }
}
