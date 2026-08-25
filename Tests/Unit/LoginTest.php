<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Login;
use PDO;
use PDOStatement;

class LoginTest extends TestCase
{
    private $dbMock;
    private $stmtMock;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->dbMock = $this->createMock(PDO::class);
    }

    public function testGetByEmail()
    {
        $email = 'admin@example.com';
        $userData = [
            'usuario_id' => '12345678A',
            'email' => $email,
            'rol' => 'Administrador'
        ];

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':email', $email, PDO::PARAM_STR);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($userData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT * FROM usuarios WHERE email = :email'))
            ->willReturn($this->stmtMock);

        $loginModel = new Login($this->dbMock);
        $result = $loginModel->getByEmail($email);

        $this->assertEquals($userData, $result);
    }

    public function testGetByToken()
    {
        $token = 'sometoken123';
        $tokenData = [
            'email' => 'admin@example.com',
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->stmtMock->expects($this->once())
            ->method('bindParam')
            ->with(':token', $token, PDO::PARAM_STR);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmtMock->expects($this->once())
            ->method('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($tokenData);

        $this->dbMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('FROM password_resets'))
            ->willReturn($this->stmtMock);

        $loginModel = new Login($this->dbMock);
        $result = $loginModel->getByToken($token);

        $this->assertEquals($tokenData, $result);
    }
}
