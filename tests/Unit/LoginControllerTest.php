<?php

namespace Tests\Unit;

use App\Controllers\LoginController;
use App\Models\Login;
use App\Core\Csrf;

class LoginControllerTest extends ControllerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Generar un token CSRF válido para las pruebas por defecto
        $_SESSION['_csrf_token'] = 'valid_test_csrf_token';
        $_POST['csrf_token'] = 'valid_test_csrf_token';
    }

    private function setPrivateProperty($object, $propertyName, $value)
    {
        $ref = new \ReflectionClass(LoginController::class);
        $prop = $ref->getProperty($propertyName);
        $prop->setAccessible(true);
        $prop->setValue($object, $value);
    }

    public function testIndexRedirectsIfLoggedIn()
    {
        $_SESSION['email'] = 'test@example.com';
        $controller = $this->getControllerMock(LoginController::class);

        $this->expectException(TestExitException::class);
        $controller->index();
    }

    public function testIndexShowsLoginView()
    {
        $controller = $this->getControllerMock(LoginController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('login/login');

        $controller->index();
    }

    public function testIniciarSesionFailsWhenCsrfInvalid()
    {
        $_POST['csrf_token'] = 'invalid_csrf';
        $_POST['email'] = 'user@example.com';
        $_POST['pass'] = 'correctpass';

        $controller = $this->getControllerMock(LoginController::class);

        $this->expectException(TestExitException::class);
        $controller->iniciarSesion();
    }


    public function testIniciarSesionMissingCredentials()
    {
        $controller = $this->getControllerMock(LoginController::class);

        $this->expectException(TestExitException::class);
        $controller->iniciarSesion();
    }

    public function testIniciarSesionUserNotFound()
    {
        $_POST['email'] = 'notfound@example.com';
        $_POST['pass'] = 'password123';

        $loginModelMock = $this->getMockBuilder(Login::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByEmail'])
            ->getMock();
        $loginModelMock->method('getByEmail')->willReturn(null);

        $controller = $this->getControllerMock(LoginController::class);
        $this->setPrivateProperty($controller, 'loginModel', $loginModelMock);

        $this->expectException(TestExitException::class);
        $controller->iniciarSesion();
    }

    public function testIniciarSesionIncorrectPassword()
    {
        $_POST['email'] = 'user@example.com';
        $_POST['pass'] = 'wrongpass';

        $user = [
            'email' => 'user@example.com',
            'pass' => password_hash('correctpass', PASSWORD_DEFAULT)
        ];

        $loginModelMock = $this->getMockBuilder(Login::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByEmail'])
            ->getMock();
        $loginModelMock->method('getByEmail')->willReturn($user);

        $controller = $this->getControllerMock(LoginController::class);
        $this->setPrivateProperty($controller, 'loginModel', $loginModelMock);

        $this->expectException(TestExitException::class);
        $controller->iniciarSesion();
    }

    public function testIniciarSesionSuccess()
    {
        $_POST['email'] = 'user@example.com';
        $_POST['pass'] = 'correctpass';

        $user = [
            'usuario_id' => 'U123',
            'email' => 'user@example.com',
            'pass' => password_hash('correctpass', PASSWORD_DEFAULT),
            'rol' => 'Paciente',
            'nombre' => 'Juan'
        ];

        $loginModelMock = $this->getMockBuilder(Login::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByEmail'])
            ->getMock();
        $loginModelMock->method('getByEmail')->willReturn($user);

        $controller = $this->getControllerMock(LoginController::class);
        $this->setPrivateProperty($controller, 'loginModel', $loginModelMock);

        $this->expectException(TestExitException::class);
        $controller->iniciarSesion();

        $this->assertEquals('U123', $_SESSION['usuario_id']);
    }

    public function testFinishSession()
    {
        $controller = $this->getControllerMock(LoginController::class);

        $this->expectException(TestExitException::class);
        $controller->finishSesion();
    }

    public function testGeneratePasswordResetTokenMissingEmail()
    {
        $controller = $this->getControllerMock(LoginController::class);

        $this->expectException(TestExitException::class);
        $controller->generatePasswordResetToken();
    }

    public function testGeneratePasswordResetTokenMissingOrInvalidCsrf()
    {
        $_POST['csrf_token'] = 'invalid_csrf';
        $_POST['resetEmail'] = 'user@example.com';

        $controller = $this->getControllerMock(LoginController::class);

        $this->expectException(TestExitException::class);
        $controller->generatePasswordResetToken();
    }

    public function testGeneratePasswordResetTokenSuccess()
    {
        $_POST['resetEmail'] = 'user@example.com';
        $_SERVER['HTTP_HOST'] = 'localhost';

        $user = [
            'nombre' => 'Juan',
            'email' => 'user@example.com'
        ];

        $loginModelMock = $this->getMockBuilder(Login::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByEmail', 'saveResetToken'])
            ->getMock();
        $loginModelMock->method('getByEmail')->willReturn($user);
        $loginModelMock->method('saveResetToken')->willReturn(true);

        $controller = $this->getControllerMock(LoginController::class);
        $this->setPrivateProperty($controller, 'loginModel', $loginModelMock);

        $this->expectException(TestExitException::class);
        $controller->generatePasswordResetToken();
    }

    public function testShowResetFormInvalidToken()
    {
        $_GET['token'] = 'invalid';

        $loginModelMock = $this->getMockBuilder(Login::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByToken'])
            ->getMock();
        $loginModelMock->method('getByToken')->willReturn(null);

        $controller = $this->getControllerMock(LoginController::class);
        $this->setPrivateProperty($controller, 'loginModel', $loginModelMock);

        $this->expectException(TestExitException::class);
        $controller->showResetForm();
    }

    public function testShowResetFormSuccess()
    {
        $_GET['token'] = 'valid';

        $loginModelMock = $this->getMockBuilder(Login::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByToken'])
            ->getMock();
        $loginModelMock->method('getByToken')->willReturn(['email' => 'user@example.com']);

        $controller = $this->getControllerMock(LoginController::class);
        $this->setPrivateProperty($controller, 'loginModel', $loginModelMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('login/resetPassword', ['token' => 'valid']);

        $controller->showResetForm();
    }

    public function testUpdatePasswordIncomplete()
    {
        $controller = $this->getControllerMock(LoginController::class);

        $this->expectException(TestExitException::class);
        $controller->updatePassword();
    }

    public function testUpdatePasswordSuccess()
    {
        $_POST['token'] = 'token';
        $_POST['pass'] = 'newpassword';
        $_POST['confirmPassword'] = 'newpassword';

        $loginModelMock = $this->getMockBuilder(Login::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByToken', 'updateUserPassword', 'deleteResetToken'])
            ->getMock();
        
        $loginModelMock->method('getByToken')->willReturn(['email' => 'user@example.com']);
        $loginModelMock->method('updateUserPassword')->willReturn(true);
        $loginModelMock->expects($this->once())->method('deleteResetToken');

        $controller = $this->getControllerMock(LoginController::class);
        $this->setPrivateProperty($controller, 'loginModel', $loginModelMock);

        $this->expectException(TestExitException::class);
        $controller->updatePassword();
    }
}
