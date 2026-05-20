<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Tienda;
use PDO;

class TiendaTest extends TestCase
{
    public function testRegistrarCompraBono()
    {
        $dbMock = $this->createMock(PDO::class);
        $tiendaModel = new Tienda($dbMock);
        
        $result = $tiendaModel->registrarCompraBono('U123', 5);
        $this->assertTrue($result);
    }
}
