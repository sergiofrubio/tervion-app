<?php

namespace Tests\Unit;

use App\Controllers\PatientController;
use App\Models\User;
use App\Models\MedicalReport;
use App\Models\Appointment;

class PatientControllerTest extends ControllerTestCase
{
    public function testList()
    {
        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByRol'])
            ->getMock();
        $userModelMock->method('getByRol')->with('Paciente')->willReturn([]);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient/list', ['patients' => []]);

        $controller->list();
    }

    public function testCreateShowForm()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $controller = $this->getControllerMock(PatientController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('patient/form');

        $controller->create();
    }

    public function testCreatePostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = '12345678A';
        $_POST['nombre'] = 'John';
        $_POST['apellidos'] = 'Doe';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();
        $userModelMock->method('save')->willReturn(true);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        $this->expectException(TestExitException::class);
        $controller->create();
    }

    public function testCreatePostFailure()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = '';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        ob_start();
        $controller->create();
        $output = ob_get_clean();
        $this->assertStringContainsString('Error al guardar el paciente', $output);
    }

    public function testDeleteSuccess()
    {
        $_POST['id'] = 'U123';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();
        $userModelMock->method('delete')->with('U123')->willReturn(true);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        $this->expectException(TestExitException::class);
        $controller->delete();
    }

    public function testDeleteFailure()
    {
        $_POST['id'] = 'U123';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();
        $userModelMock->method('delete')->with('U123')->willReturn(false);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        ob_start();
        $controller->delete();
        $output = ob_get_clean();
        $this->assertStringContainsString('Error al eliminar el paciente', $output);
    }

    public function testEditShowForm()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['id'] = 'U123';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByusuario_id'])
            ->getMock();
        $userModelMock->method('getByusuario_id')->with('U123')->willReturn(['usuario_id' => 'U123']);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient/form', ['usuario' => ['usuario_id' => 'U123']]);

        $controller->edit();
    }

    public function testEditShowFormRedirectIfNoId()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_GET['id']);

        $controller = $this->getControllerMock(PatientController::class);
        
        $this->expectException(TestExitException::class);
        $controller->edit();
    }

    public function testEditPostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = 'U123';
        $_POST['nombre'] = 'John';
        $_POST['pass'] = 'newpassword';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['update'])
            ->getMock();
        $userModelMock->method('update')->with('U123', $this->callback(function($arg) { return is_array($arg) && isset($arg['pass']); }))->willReturn(true);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        $this->expectException(TestExitException::class);
        $controller->edit();
    }

    public function testEditPostFailure()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['usuario_id'] = 'U123';
        $_POST['nombre'] = 'John';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['update'])
            ->getMock();
        $userModelMock->method('update')->willReturn(false);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        ob_start();
        $controller->edit();
        $output = ob_get_clean();
        $this->assertStringContainsString('Error al actualizar', $output);
    }

    public function testDetailRedirectIfNoId()
    {
        unset($_GET['id']);
        unset($_GET['usuario_id']);

        $controller = $this->getControllerMock(PatientController::class);
        
        $this->expectException(TestExitException::class);
        $controller->detail();
    }

    public function testDetail()
    {
        $_GET['id'] = 'U123';
        $_SESSION['rol'] = 'Administrador';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByusuario_id'])
            ->getMock();
        $userModelMock->method('getByusuario_id')->with('U123')->willReturn(['usuario_id' => 'U123', 'rol' => 'Paciente']);

        $historyMock = $this->getMockBuilder(MedicalReport::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByPaciente'])
            ->getMock();
        $historyMock->method('getByPaciente')->willReturn([]);

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByPatient'])
            ->getMock();
        $appointmentMock->method('getByPatient')->willReturn([]);

        $documentMock = $this->getMockBuilder(\App\Models\Document::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByPacienteWithTemplates'])
            ->getMock();
        $documentMock->method('getByPacienteWithTemplates')->willReturn([]);

        $invoiceMock = $this->getMockBuilder(\App\Models\Invoice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAll'])
            ->getMock();
        $invoiceMock->method('getAll')->willReturn([]);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->willReturnMap([
            ['User', $userModelMock],
            ['MedicalReport', $historyMock],
            ['Appointment', $appointmentMock],
            ['Document', $documentMock],
            ['Invoice', $invoiceMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient/detail', $this->callback(function($data) {
                return $data['usuario']['usuario_id'] === 'U123' && $data['rol'] === 'Administrador';
            }));

        $controller->detail();
    }

    public function testDetailRedirectIfUserNotPatient()
    {
        $_GET['id'] = 'U123';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByusuario_id'])
            ->getMock();
        $userModelMock->method('getByusuario_id')->with('U123')->willReturn(['usuario_id' => 'U123', 'rol' => 'Administrador']);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        $this->expectException(TestExitException::class);
        $controller->detail();
    }

    public function testSearch()
    {
        $_GET['q'] = 'searchterm';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['searchByRol'])
            ->getMock();
        $userModelMock->method('searchByRol')->with('Paciente', 'searchterm')->willReturn([]);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        ob_start();
        try {
            $controller->search();
        } catch (TestExitException $e) {
            // expected exit
        }
        $output = ob_get_clean();
        $this->assertEquals('[]', $output);
    }

    public function testSearchWorkers()
    {
        $_GET['q'] = 'searchterm';

        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['searchByRol'])
            ->getMock();
        $userModelMock->method('searchByRol')->willReturn([]);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        ob_start();
        try {
            $controller->searchWorkers();
        } catch (TestExitException $e) {
            // expected exit
        }
        $output = ob_get_clean();
        $this->assertEquals('[]', $output);
    }

    public function testCreatePDF()
    {
        $userModelMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByRol'])
            ->getMock();
        
        $userModelMock->method('getByRol')->with('Paciente')->willReturn([
            [
                'usuario_id' => '12345678A',
                'nombre' => 'John',
                'apellidos' => 'Doe',
                'email' => 'john@example.com',
                'telefono' => '123456789',
                'genero' => 'Masculino',
                'fecha_nacimiento' => '1990-01-01'
            ]
        ]);

        $controller = $this->getControllerMock(PatientController::class);
        $controller->method('model')->with('User')->willReturn($userModelMock);

        ob_start();
        $controller->createPDF();
        $pdfOutput = ob_get_clean();

        $this->assertNotEmpty($pdfOutput);
        $this->assertStringContainsString('%PDF-', $pdfOutput);
    }
}
