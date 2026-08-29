<?php

namespace Tests\Unit;

use App\Core\Csrf;
use App\Core\Recaptcha;
use PHPUnit\Framework\TestCase;

class SecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function testCsrfGenerateAndValidate()
    {
        $token = Csrf::getToken();
        $this->assertNotEmpty($token);
        $this->assertEquals(64, strlen($token)); // 32 bytes hex = 64 chars

        $this->assertTrue(Csrf::validateToken($token));
        $this->assertFalse(Csrf::validateToken('invalid-token'));
        $this->assertFalse(Csrf::validateToken(''));
        $this->assertFalse(Csrf::validateToken(null));
    }

    public function testCsrfRegenerateToken()
    {
        $token1 = Csrf::getToken();
        $token2 = Csrf::regenerateToken();

        $this->assertNotEquals($token1, $token2);
        $this->assertTrue(Csrf::validateToken($token2));
        $this->assertFalse(Csrf::validateToken($token1));
    }

    public function testRecaptchaVerifyWithCustomClient()
    {
        Recaptcha::setClient(function ($token) {
            return $token === 'valid_token';
        });

        $this->assertTrue(Recaptcha::verify('valid_token'));
        $this->assertFalse(Recaptcha::verify('invalid_token'));

        Recaptcha::setClient(null);
    }
}
