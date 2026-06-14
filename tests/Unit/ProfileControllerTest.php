<?php

namespace Tests\Unit;

use App\Controllers\ProfileController;
use App\Models\User;

class ProfileControllerTest extends ControllerTestCase
{
    private function setPrivateProperty($object, $propertyName, $value)
    {
        $ref = new \ReflectionClass($object);
        $prop = $ref->getProperty($propertyName);
        $prop->setAccessible(true);
        $prop->setValue($object, $value);
    }

    public function testIndexRedirectsIfUserNotFound()
    {
        $_SESSION['usuario_id'] = 'U123';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByusuario_id'])
            ->getMock();
        $userModelMock->method('getByusuario_id')->with('U123')->willReturn(null);

        $controller = $this->getControllerMock(ProfileController::class);
        $controller->method('model')->willReturnMap([
            ['User', $userModelMock]
        ]);

        $this->expectException(TestExitException::class);
        $controller->index();
    }

    public function testIndexSuccess()
    {
        $_SESSION['usuario_id'] = 'U123';
        $usuario = ['usuario_id' => 'U123', 'nombre' => 'John'];

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByusuario_id'])
            ->getMock();
        $userModelMock->method('getByusuario_id')->with('U123')->willReturn($usuario);

        $controller = $this->getControllerMock(ProfileController::class);
        $controller->method('model')->willReturnMap([
            ['User', $userModelMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient-view/profile/index', [
                'usuario' => $usuario,
                'metodosPago' => [],
                'pageTitle' => 'Mi Perfil - Velion'
            ]);

        $controller->index();
    }

    public function testEditPostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['usuario_id'] = 'U123';
        $_POST['nombre'] = 'Johnny';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['update'])
            ->getMock();
        $userModelMock->method('update')->with('U123', $this->callback(function($data) {
            return $data['nombre'] === 'Johnny';
        }))->willReturn(true);

        $controller = $this->getControllerMock(ProfileController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        $this->expectException(TestExitException::class);
        $controller->edit();
        
        $this->assertEquals('Johnny', $_SESSION['nombre']);
    }
}
