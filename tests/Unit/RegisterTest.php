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
            ->with('register/register', ['data' => [], 'error' => null]);

        $controller->index();
    }

    public function testRegisterRedirectsIfNotPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $controller = $this->getControllerMock(RegisterController::class);

        $this->expectException(TestExitException::class);
        $controller->register();
    }

    public function testRegisterValidationNifInvalid()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = '123'; // invalid NIF length
        
        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('register/register', $this->callback(function($args) {
                return isset($args['error']) && strpos($args['error'], 'NIF/DNI') !== false;
            }));

        $controller->register();
    }

    public function testRegisterValidationEmailInvalid()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = '12345678A';
        $_POST['email'] = 'invalid-email';

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('register/register', $this->callback(function($args) {
                return isset($args['error']) && strpos($args['error'], 'correo electrónico') !== false;
            }));

        $controller->register();
    }

    public function testRegisterValidationPasswordTooShort()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = '12345678A';
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = '123';

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('register/register', $this->callback(function($args) {
                return isset($args['error']) && strpos($args['error'], 'contraseña debe tener al menos') !== false;
            }));

        $controller->register();
    }

    public function testRegisterValidationPasswordsDoNotMatch()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = '12345678A';
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = '12345678';
        $_POST['confirm_pass'] = '87654321';

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('register/register', $this->callback(function($args) {
                return isset($args['error']) && strpos($args['error'], 'no coinciden') !== false;
            }));

        $controller->register();
    }

    public function testRegisterValidationEmptyRequiredFields()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = '12345678A';
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = '12345678';
        $_POST['confirm_pass'] = '12345678';
        $_POST['nombre'] = ''; // empty

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('register/register', $this->callback(function($args) {
                return isset($args['error']) && strpos($args['error'], 'obligatorios del administrador') !== false;
            }));

        $controller->register();
    }

    public function testRegisterValidationEmptyClinicFields()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = '12345678A';
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = '12345678';
        $_POST['confirm_pass'] = '12345678';
        $_POST['nombre'] = 'John';
        $_POST['apellidos'] = 'Doe';
        $_POST['fecha_nacimiento'] = '1990-01-01';
        $_POST['nombre_comercial'] = ''; // empty clinic field

        $controller = $this->getControllerMock(RegisterController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('register/register', $this->callback(function($args) {
                return isset($args['error']) && strpos($args['error'], 'obligatorios de la clínica') !== false;
            }));

        $controller->register();
    }

    public function testRegisterSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = '12345678A';
        $_POST['nombre'] = 'TestAdmin';
        $_POST['apellidos'] = 'LastName';
        $_POST['telefono'] = '123456789';
        $_POST['fecha_nacimiento'] = '1985-05-15';
        $_POST['email'] = 'admin@example.com';
        $_POST['pass'] = 'securepass123';
        $_POST['confirm_pass'] = 'securepass123';
        $_POST['nombre_comercial'] = 'Clinic Test';
        $_POST['telefono_contacto'] = '912345678';
        $_POST['direccion_calle'] = 'Street 45';
        $_POST['ciudad'] = 'Madrid';

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
