<?php

namespace Tests\Unit;

use App\Controllers\AppointmentController;
use App\Models\Appointment;
use App\Models\User;

class AppointmentControllerTest extends ControllerTestCase
{
    public function testListAsPatient()
    {
        $_SESSION['rol'] = 'Paciente';
        $_SESSION['usuario_id'] = 'U123';

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByPatient'])
            ->getMock();
        $appointmentMock->method('getByPatient')->with('U123')->willReturn([]);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->with('Appointment')->willReturn($appointmentMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient-portal/appointment/list', ['appointments' => []]);

        $controller->list();
    }

    public function testListAsAdmin()
    {
        $_SESSION['rol'] = 'Administrador';

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAll'])
            ->getMock();
        $appointmentMock->method('getAll')->willReturn([]);

        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByRol'])
            ->getMock();
        $userMock->method('getByRol')->with('Fisioterapeuta')->willReturn([]);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->willReturnMap([
            ['Appointment', $appointmentMock],
            ['User', $userMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('appointment/list', [
                'appointments' => [],
                'fisioterapeutas' => []
            ]);

        $controller->list();
    }

    public function testCreateShowFormAsPatient()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['rol'] = 'Paciente';

        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByRol'])
            ->getMock();
        $userMock->method('getByRol')->willReturn([]);

        $typeMock = $this->getMockBuilder(\App\Models\AppointmentType::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllActive'])
            ->getMock();
        $typeMock->method('getAllActive')->willReturn([]);

        $settingMock = $this->getMockBuilder(\App\Models\Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDespachos'])
            ->getMock();
        $settingMock->method('getDespachos')->willReturn([]);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->willReturnMap([
            ['User', $userMock],
            ['AppointmentType', $typeMock],
            ['Setting', $settingMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient-portal/appointment/create', [
                'fisioterapeutas' => [],
                'tiposCitas' => [],
                'despachos' => []
            ]);

        $controller->create();
    }

    public function testCreateShowFormAsAdmin()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['rol'] = 'Administrador';

        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByRol'])
            ->getMock();
        $userMock->method('getByRol')->willReturn([]);

        $typeMock = $this->getMockBuilder(\App\Models\AppointmentType::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllActive'])
            ->getMock();
        $typeMock->method('getAllActive')->willReturn([]);

        $settingMock = $this->getMockBuilder(\App\Models\Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDespachos'])
            ->getMock();
        $settingMock->method('getDespachos')->willReturn([]);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->willReturnMap([
            ['User', $userMock],
            ['AppointmentType', $typeMock],
            ['Setting', $settingMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('appointment/form', [
                'fisioterapeutas' => [],
                'tiposCitas' => [],
                'despachos' => []
            ]);

        $controller->create();
    }

    public function testCreatePostAsPatientSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['rol'] = 'Paciente';
        $_SESSION['usuario_id'] = 'U123';
        $_POST['terapeuta_id'] = 'F1';
        $_POST['fecha_hora'] = '2026-06-15 10:00:00';
        $_POST['especialidad_id'] = 'E1';
        $_POST['ubicacion_tipo'] = 'telematica';

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();
        $appointmentMock->method('save')->willReturn(true);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->with('Appointment')->willReturn($appointmentMock);

        $this->expectException(TestExitException::class);
        $controller->create();
    }

    public function testDeleteAsPatientSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['rol'] = 'Paciente';
        $_SESSION['usuario_id'] = 'U123';
        $_POST['id'] = 'C1';

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById', 'delete'])
            ->getMock();
        $appointmentMock->method('getById')->with('C1')->willReturn(['paciente_id' => 'U123']);
        $appointmentMock->method('delete')->with('C1')->willReturn(true);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->with('Appointment')->willReturn($appointmentMock);

        $this->expectException(TestExitException::class);
        $controller->delete();
    }

    public function testDeleteAsPatientForbidden()
    {
        $_SESSION['rol'] = 'Paciente';
        $_SESSION['usuario_id'] = 'U123';
        $_POST['id'] = 'C1';

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById', 'delete'])
            ->getMock();

        $appointmentMock->method('getById')->with('C1')->willReturn(['paciente_id' => 'OTHER_USER']);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->with('Appointment')->willReturn($appointmentMock);

        $this->expectException(TestExitException::class);
        $controller->delete();
    }

    public function testEditShowFormAsPatient()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['rol'] = 'Paciente';
        $_SESSION['usuario_id'] = 'U123';
        $_GET['id'] = 'C1';

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById'])
            ->getMock();
        $appointmentMock->method('getById')->with('C1')->willReturn(['paciente_id' => 'U123']);

        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByRol'])
            ->getMock();
        $userMock->method('getByRol')->willReturn([]);

        $typeMock = $this->getMockBuilder(\App\Models\AppointmentType::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllActive'])
            ->getMock();
        $typeMock->method('getAllActive')->willReturn([]);

        $settingMock = $this->getMockBuilder(\App\Models\Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDespachos'])
            ->getMock();
        $settingMock->method('getDespachos')->willReturn([]);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->willReturnMap([
            ['Appointment', $appointmentMock],
            ['User', $userMock],
            ['AppointmentType', $typeMock],
            ['Setting', $settingMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient-portal/appointment/edit', $this->callback(function ($data) {
                return $data['appointment']['paciente_id'] === 'U123';
            }));

        $controller->edit();
    }

    public function testEditShowFormAsPatientForbidden()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['rol'] = 'Paciente';
        $_SESSION['usuario_id'] = 'U123';
        $_GET['id'] = 'C1';

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById'])
            ->getMock();
        $appointmentMock->method('getById')->with('C1')->willReturn(['paciente_id' => 'OTHER_USER']);

        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByRol'])
            ->getMock();
        $userMock->method('getByRol')->willReturn([]);

        $typeMock = $this->getMockBuilder(\App\Models\AppointmentType::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllActive'])
            ->getMock();
        $typeMock->method('getAllActive')->willReturn([]);

        $settingMock = $this->getMockBuilder(\App\Models\Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDespachos'])
            ->getMock();
        $settingMock->method('getDespachos')->willReturn([]);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->willReturnMap([
            ['Appointment', $appointmentMock],
            ['User', $userMock],
            ['AppointmentType', $typeMock],
            ['Setting', $settingMock]
        ]);

        $this->expectException(TestExitException::class);
        $controller->edit();
    }

    public function testEditPostAsPatientSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['rol'] = 'Paciente';
        $_SESSION['usuario_id'] = 'U123';
        $_POST['cita_id'] = 'C1';
        $_POST['terapeuta_id'] = 'F1';
        $_POST['fecha_hora'] = '2026-06-15 11:00:00';
        $_POST['estado'] = 'Programada';
        $_POST['especialidad_id'] = 'E1';
        $_POST['ubicacion_tipo'] = 'telematica';

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById', 'update'])
            ->getMock();
        $appointmentMock->method('getById')->with('C1')->willReturn(['paciente_id' => 'U123']);
        $appointmentMock->method('update')->willReturn(true);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->with('Appointment')->willReturn($appointmentMock);

        $this->expectException(TestExitException::class);
        $controller->edit();
    }

    public function testUpdateStatusSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['cita_id'] = 1;
        $_POST['estado'] = 'Realizada';

        $appointmentMock = $this->getMockBuilder(Appointment::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['updateStatus'])
            ->getMock();
        $appointmentMock->expects($this->once())
            ->method('updateStatus')
            ->with(1, 'Realizada')
            ->willReturn(true);

        $controller = $this->getControllerMock(AppointmentController::class);
        $controller->method('model')->with('Appointment')->willReturn($appointmentMock);

        $this->expectException(TestExitException::class);
        $controller->updateStatus();
    }

    public function testGetSlotsEmptyParams()
    {
        $_GET['fisio_id'] = '';
        $_GET['fecha'] = '';

        $controller = $this->getControllerMock(AppointmentController::class);

        $this->expectException(TestExitException::class);
        ob_start();
        try {
            $controller->getSlots();
        } finally {
            ob_end_clean();
        }
    }
}
