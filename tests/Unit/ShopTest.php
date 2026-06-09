<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Shop;
use PDO;

class ShopTest extends TestCase
{
    public function testRegistrarCompraBono()
    {
        $dbMock = $this->createMock(PDO::class);
        $tiendaModel = new Shop($dbMock);
        
        $result = $tiendaModel->registrarCompraBono('U123', 5);
        $this->assertTrue($result);
    }
}
