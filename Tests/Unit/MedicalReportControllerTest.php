<?php

namespace Tests\Unit;

use App\Controllers\MedicalReportController;
use App\Models\MedicalReport;
use App\Models\User;

class MedicalReportControllerTest extends ControllerTestCase
{
    public function testCreateShowForm()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['paciente_id'] = 'U123';

        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByusuario_id'])
            ->getMock();
        $userMock->method('getByusuario_id')->with('U123')->willReturn(['usuario_id' => 'U123']);

        $controller = $this->getControllerMock(MedicalReportController::class);
        $controller->method('model')->with('User')->willReturn($userMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('medical-report/create', ['paciente' => ['usuario_id' => 'U123']]);

        $controller->create();
    }

    public function testCreatePostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['usuario_id'] = 'F123';
        $_POST['paciente_id'] = 'U123';
        $_POST['motivo_consulta'] = 'Back pain';
        $_POST['diagnostico'] = 'Sprain';
        $_POST['tratamiento'] = 'Rest';
        $_POST['observaciones'] = 'None';

        $reportMock = $this->getMockBuilder(MedicalReport::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();
        $reportMock->method('save')->willReturn(true);

        $controller = $this->getControllerMock(MedicalReportController::class);
        $controller->method('model')->with('MedicalReport')->willReturn($reportMock);

        $this->expectException(TestExitException::class);
        $controller->create();
    }

    public function testDetailReportNotFound()
    {
        $_GET['id'] = 999;

        $reportMock = $this->getMockBuilder(MedicalReport::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById'])
            ->getMock();
        $reportMock->method('getById')->with(999)->willReturn(null);

        $controller = $this->getControllerMock(MedicalReportController::class);
        $controller->method('model')->with('MedicalReport')->willReturn($reportMock);

        $this->expectException(TestExitException::class);
        $controller->detail();
    }

    public function testDetailSuccess()
    {
        $_GET['id'] = 1;
        $report = ['id' => 1, 'paciente_id' => 'P123', 'diagnostico' => 'Ok'];
        $paciente = ['usuario_id' => 'P123', 'nombre' => 'John'];

        $reportMock = $this->getMockBuilder(MedicalReport::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById'])
            ->getMock();
        $reportMock->method('getById')->with(1)->willReturn($report);

        $userMock = $this->getMockBuilder(\App\Models\User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByusuario_id'])
            ->getMock();
        $userMock->method('getByusuario_id')->with('P123')->willReturn($paciente);

        $controller = $this->getControllerMock(MedicalReportController::class);
        $controller->method('model')->willReturnMap([
            ['MedicalReport', $reportMock],
            ['User', $userMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('medical-report/detail', [
                'report' => $report,
                'paciente' => $paciente
            ]);

        $controller->detail();
    }

    public function testPdfSuccess()
    {
        $_GET['id'] = 1;
        $report = [
            'id' => 1,
            'paciente_nombre' => 'John',
            'paciente_apellidos' => 'Doe',
            'paciente_id' => 'U123',
            'paciente_fecha_nacimiento' => '1990-01-01',
            'paciente_genero' => 'Masculino',
            'diagnostico' => 'Sprain',
            'motivo_consulta' => 'Pain',
            'tratamiento' => 'Ice',
            'observaciones' => 'None',
            'fisioterapeuta_nombre' => 'Fisio',
            'fisioterapeuta_apellidos' => 'Fisioson',
            'especialidad' => 'Trauma',
            'fecha_consulta' => '2026-06-01'
        ];

        $reportMock = $this->getMockBuilder(MedicalReport::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById'])
            ->getMock();
        $reportMock->method('getById')->with(1)->willReturn($report);

        $controller = $this->getControllerMock(MedicalReportController::class);
        $controller->method('model')->with('MedicalReport')->willReturn($reportMock);

        ob_start();
        $controller->pdf();
        $pdfOutput = ob_get_clean();

        $this->assertNotEmpty($pdfOutput);
        $this->assertStringContainsString('%PDF-', $pdfOutput);
    }

    public function testPdfNotFound()
    {
        $_GET['id'] = 999;

        $reportMock = $this->getMockBuilder(MedicalReport::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getById'])
            ->getMock();
        $reportMock->method('getById')->with(999)->willReturn(null);

        $controller = $this->getControllerMock(MedicalReportController::class);
        $controller->method('model')->with('MedicalReport')->willReturn($reportMock);

        ob_start();
        $controller->pdf();
        $output = ob_get_clean();

        $this->assertStringContainsString('Informe no encontrado.', $output);
    }
}
