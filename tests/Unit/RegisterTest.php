<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Controllers\RegisterController;

class RegisterTest extends TestCase
{
    public function testInstantiation()
    {
        $controller = new RegisterController();
        $this->assertInstanceOf(RegisterController::class, $controller);
    }
}
