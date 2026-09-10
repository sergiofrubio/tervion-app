<?php

namespace Tests\Unit;

use App\Controllers\SettingController;
use App\Models\Setting;

class SettingControllerTest extends ControllerTestCase
{
    public function testIndexSuccess()
    {
        $_SESSION['usuario_id'] = 'ADM123';
        $_SESSION['email'] = 'admin@example.com';

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getHorariosFisios', 'getAusenciasFisios', 'getBonos', 'getClinica', 'getMetodoPagoByUsuario', 'getCuentaClienteByEmail'])
            ->getMock();

        $settingMock->method('getHorariosFisios')->willReturn([]);
        $settingMock->method('getAusenciasFisios')->willReturn([]);
        $settingMock->method('getBonos')->willReturn([]);
        $settingMock->method('getClinica')->willReturn([]);
        $settingMock->method('getMetodoPagoByUsuario')->willReturn(false);
        $settingMock->method('getCuentaClienteByEmail')->willReturn(false);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('setting/index', [
                'horarios' => [],
                'ausencias' => [],
                'bonos' => [],
                'clinica' => [],
                'tarjeta' => false,
                'cuenta' => false
            ]);

        $controller->index();
    }

    public function testCreateHorarioGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getFisios'])
            ->getMock();
        $settingMock->method('getFisios')->willReturn([]);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('setting/horarios_form', ['fisios' => []]);

        $controller->createHorario();
    }

    public function testCreateHorarioPostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['terapeuta_id'] = 'F123';
        $_POST['dia_semana'] = 1;
        $_POST['hora_inicio'] = '09:00';
        $_POST['hora_fin'] = '14:00';

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['saveHorario'])
            ->getMock();
        $settingMock->method('saveHorario')->willReturn(true);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $this->expectException(TestExitException::class);
        $controller->createHorario();
    }

    public function testCreateAusenciaGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getFisios'])
            ->getMock();
        $settingMock->method('getFisios')->willReturn([]);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('setting/ausencias_form', ['fisios' => []]);

        $controller->createAusencia();
    }

    public function testCreateAusenciaPostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['terapeuta_id'] = 'F123';
        $_POST['fecha_inicio'] = '2026-06-15';
        $_POST['fecha_fin'] = '2026-06-20';
        $_POST['motivo'] = 'Vacation';

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['saveAusencia'])
            ->getMock();
        $settingMock->method('saveAusencia')->willReturn(true);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $this->expectException(TestExitException::class);
        $controller->createAusencia();
    }

    public function testCreateBonoGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $controller = $this->getControllerMock(SettingController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('setting/bonos_form');

        $controller->createBono();
    }

    public function testCreateBonoPostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['nombre'] = 'Bono 5';
        $_POST['sesiones'] = 5;
        $_POST['precio'] = 150;
        $_POST['estado'] = 'Activo';

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['saveBono'])
            ->getMock();
        $settingMock->method('saveBono')->willReturn(true);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $this->expectException(TestExitException::class);
        $controller->createBono();
    }

    public function testUpdateClinicaSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['nombre_comercial'] = 'Clinica Test';
        $_POST['razon_social'] = 'Test S.L.';

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['saveClinica'])
            ->getMock();
        $settingMock->method('saveClinica')->willReturn(true);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $this->expectException(TestExitException::class);
        $controller->updateClinica();
    }

    public function testEditHorarioGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['id'] = 5;

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getHorarioById', 'getFisios'])
            ->getMock();
        $settingMock->method('getHorarioById')->with(5)->willReturn(['id' => 5]);
        $settingMock->method('getFisios')->willReturn([]);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('setting/horarios_form', ['horario' => ['id' => 5], 'fisios' => []]);

        $controller->editHorario();
    }

    public function testEditHorarioPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['horario_id'] = 5;
        $_POST['terapeuta_id'] = 'F1';
        $_POST['dia_semana'] = 2;
        $_POST['hora_inicio'] = '09:00';
        $_POST['hora_fin'] = '15:00';

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['updateHorario'])
            ->getMock();
        $settingMock->method('updateHorario')->willReturn(true);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $this->expectException(TestExitException::class);
        $controller->editHorario();
    }

    public function testEditAusenciaGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['id'] = 5;

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAusenciaById', 'getFisios'])
            ->getMock();
        $settingMock->method('getAusenciaById')->with(5)->willReturn(['id' => 5]);
        $settingMock->method('getFisios')->willReturn([]);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('setting/ausencias_form', ['ausencia' => ['id' => 5], 'fisios' => []]);

        $controller->editAusencia();
    }

    public function testEditAusenciaPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['ausencia_id'] = 5;
        $_POST['terapeuta_id'] = 'F1';
        $_POST['fecha_inicio'] = '2026-06-15';
        $_POST['fecha_fin'] = '2026-06-20';
        $_POST['motivo'] = 'Vacances';

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['updateAusencia'])
            ->getMock();
        $settingMock->method('updateAusencia')->willReturn(true);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $this->expectException(TestExitException::class);
        $controller->editAusencia();
    }

    public function testEditBonoGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['id'] = 3;

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getBonoById'])
            ->getMock();
        $settingMock->method('getBonoById')->with(3)->willReturn(['id' => 3]);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('setting/bonos_form', ['bono' => ['id' => 3]]);

        $controller->editBono();
    }

    public function testEditBonoPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['bono_id'] = 3;
        $_POST['nombre'] = 'Bono Premium';
        $_POST['numero_sesiones'] = 10;
        $_POST['precio'] = 250;

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['updateBono'])
            ->getMock();
        $settingMock->method('updateBono')->willReturn(true);

        $controller = $this->getControllerMock(SettingController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $this->expectException(TestExitException::class);
        $controller->editBono();
    }
}
