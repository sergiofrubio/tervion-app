<?php

namespace Tests\Unit;

use App\Controllers\TimeRecordController;
use App\Models\TimeRecord;
use App\Models\User;

class TimeRecordControllerTest extends ControllerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION['usuario_id'] = 'ADM001';
        $_SESSION['rol'] = 'Administrador';
    }

    public function testIndex()
    {
        $timeRecordMock = $this->getMockBuilder(TimeRecord::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getActiveRecord', 'getHistoryByUsuario'])
            ->getMock();

        $timeRecordMock->method('getActiveRecord')->willReturn(null);
        $timeRecordMock->method('getHistoryByUsuario')->willReturn([]);

        $controller = $this->getControllerMock(TimeRecordController::class);
        $controller->method('model')->with('TimeRecord')->willReturn($timeRecordMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('time-record/index', $this->callback(function ($data) {
                return array_key_exists('activeRecord', $data) && array_key_exists('history', $data);
            }));

        $controller->index();
    }

    public function testAdminIndex()
    {
        $timeRecordMock = $this->getMockBuilder(TimeRecord::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllRecords'])
            ->getMock();

        $userMock = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWorkers'])
            ->getMock();

        $timeRecordMock->method('getAllRecords')->willReturn([]);
        $userMock->method('getWorkers')->willReturn([]);

        $controller = $this->getControllerMock(TimeRecordController::class);
        $controller->method('model')->willReturnMap([
            ['TimeRecord', $timeRecordMock],
            ['User', $userMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('time-record/admin', $this->callback(function ($data) {
                return isset($data['workers']) && isset($data['records']) && isset($data['filters']);
            }));

        $controller->adminIndex();
    }

    public function testExportInspeccion()
    {
        $_GET['usuario_id'] = 'EMP001';
        $_GET['fecha_inicio'] = '2026-08-01';
        $_GET['fecha_fin'] = '2026-08-24';

        $timeRecordMock = $this->getMockBuilder(TimeRecord::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAllRecords'])
            ->getMock();

        $timeRecordMock->expects($this->once())
            ->method('getAllRecords')
            ->with([
                'usuario_id' => 'EMP001',
                'fecha_inicio' => '2026-08-01',
                'fecha_fin' => '2026-08-24'
            ])
            ->willReturn([
                [
                    'registro_id' => 1,
                    'usuario_id' => '12345678Z',
                    'nombre' => 'Juan',
                    'apellidos' => 'Pérez',
                    'nss' => '281234567890',
                    'rol' => 'Fisioterapeuta',
                    'fecha' => '2026-08-24',
                    'entrada' => '2026-08-24 09:00:00',
                    'salida' => '2026-08-24 17:30:00',
                    'notas' => 'Jornada continua'
                ]
            ]);

        $controller = $this->getControllerMock(TimeRecordController::class);
        $controller->method('model')->with('TimeRecord')->willReturn($timeRecordMock);

        $this->expectException(TestExitException::class);
        ob_start();
        try {
            $controller->exportInspeccion();
        } finally {
            $csvOutput = ob_get_clean();
            $this->assertStringContainsString('DNI / NIF Trabajador', $csvOutput);
            $this->assertStringContainsString('12345678Z', $csvOutput);
            $this->assertStringContainsString('281234567890', $csvOutput);
            $this->assertStringContainsString('Juan Pérez', $csvOutput);
            $this->assertStringContainsString('08h 30m', $csvOutput);
            $this->assertStringContainsString('Finalizada', $csvOutput);
        }
    }
}
