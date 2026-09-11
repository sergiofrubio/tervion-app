<?php

namespace Tests\Unit;

use App\Controllers\RegisterController;
use PDO;
use PDOStatement;

class RegisterTest extends ControllerTestCase
{
    public function testIndexRedirectsIfSessionExists()
    {
        $_SESSION['email'] = 'admin@example.com';
        $controller = $this->getControllerMock(RegisterController::class);

        $this->expectException(TestExitException::class);
        $controller->index();
    }

    public function testIndexShowsRegisterView()
    {
        $controller = $this->getControllerMock(RegisterController::class);

        $controller->expects($this->once())
            ->method('view')
            ->with('landing/registro', ['data' => [], 'error' => null]);

        $controller->index();
    }

    public function testRegisterRedirectsIfNotPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $controller = $this->getControllerMock(RegisterController::class);

        $this->expectException(TestExitException::class);
        $controller->register();
    }

    public function testRegisterValidationEmptyNombre()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['nombre'] = '';
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = '12345678';
        $_POST['confirm_pass'] = '12345678';

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('landing/registro', $this->callback(function ($args) {
                return isset($args['error']) && strpos($args['error'], 'introduce tu nombre') !== false;
            }));

        $controller->register();
    }

    public function testRegisterValidationEmailInvalid()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['nombre'] = 'Admin Test';
        $_POST['email'] = 'invalid-email';

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('landing/registro', $this->callback(function ($args) {
                return isset($args['error']) && strpos($args['error'], 'correo electrónico') !== false;
            }));

        $controller->register();
    }

    public function testRegisterValidationPasswordTooShort()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['nombre'] = 'Admin Test';
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = '123';

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('landing/registro', $this->callback(function ($args) {
                return isset($args['error']) && strpos($args['error'], 'contraseña debe tener al menos') !== false;
            }));

        $controller->register();
    }

    public function testRegisterValidationPasswordsDoNotMatch()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['nombre'] = 'Admin Test';
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = '12345678';
        $_POST['confirm_pass'] = '87654321';

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('landing/registro', $this->callback(function ($args) {
                return isset($args['error']) && strpos($args['error'], 'no coinciden') !== false;
            }));

        $controller->register();
    }

    public function testRegisterSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['nombre'] = 'TestAdmin';
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = 'securepass123';
        $_POST['confirm_pass'] = 'securepass123';

        $stmtMock = $this->getMockBuilder(PDOStatement::class)
            ->onlyMethods(['execute', 'fetch'])
            ->getMock();
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn(false);

        $dbMock = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepare', 'beginTransaction', 'commit'])
            ->getMock();
        $dbMock->method('prepare')->willReturn($stmtMock);
        $dbMock->expects($this->once())->method('beginTransaction');
        $dbMock->expects($this->once())->method('commit');

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->db = $dbMock;

        $this->expectException(TestExitException::class);
        $controller->register();
    }
}
