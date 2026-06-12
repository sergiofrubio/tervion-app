<?php

namespace Tests\Unit;

use App\Controllers\ShopController;
use App\Models\Setting;
use App\Models\PaymentMethod;

class ShopControllerTest extends ControllerTestCase
{
    public function testListFiltersActiveBonos()
    {
        $bonos = [
            ['id' => 1, 'nombre' => 'Bono 5', 'estado' => 'Activo'],
            ['id' => 2, 'nombre' => 'Bono 10', 'estado' => 'Inactivo'],
        ];

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getBonos'])
            ->getMock();
        $settingMock->method('getBonos')->willReturn($bonos);

        $controller = $this->getControllerMock(ShopController::class);
        $controller->method('model')->with('Setting')->willReturn($settingMock);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient-view/shop/list', $this->callback(function($data) {
                return count($data['bonosActivos']) === 1 && reset($data['bonosActivos'])['id'] === 1;
            }));

        $controller->list();
    }

    public function testPagoRedirectsIfBonoNotFound()
    {
        $_GET['id'] = 999;
        $_SESSION['usuario_id'] = 'U123';

        $pmMock = $this->getMockBuilder(PaymentMethod::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByUsuario'])
            ->getMock();
        $pmMock->method('getByUsuario')->willReturn([]);

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getBonoById'])
            ->getMock();
        $settingMock->method('getBonoById')->with(999)->willReturn(null);

        $controller = $this->getControllerMock(ShopController::class);
        $controller->method('model')->willReturnMap([
            ['PaymentMethod', $pmMock],
            ['Setting', $settingMock]
        ]);

        $this->expectException(TestExitException::class);
        $controller->pago();
    }

    public function testPagoLoadsPageIfBonoExists()
    {
        $_GET['id'] = 1;
        $_SESSION['usuario_id'] = 'U123';

        $bono = ['id' => 1, 'nombre' => 'Bono 5', 'estado' => 'Activo'];

        $pmMock = $this->getMockBuilder(PaymentMethod::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getByUsuario'])
            ->getMock();
        $pmMock->method('getByUsuario')->with('U123')->willReturn([]);

        $settingMock = $this->getMockBuilder(Setting::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getBonoById'])
            ->getMock();
        $settingMock->method('getBonoById')->with(1)->willReturn($bono);

        $controller = $this->getControllerMock(ShopController::class);
        $controller->method('model')->willReturnMap([
            ['PaymentMethod', $pmMock],
            ['Setting', $settingMock]
        ]);

        $controller->expects($this->once())
            ->method('view')
            ->with('patient-view/shop/pago', [
                'idBono' => 1,
                'bono' => $bono,
                'metodosPago' => []
            ]);

        $controller->pago();
    }

    public function testProcesarPagoPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SESSION['usuario_id'] = 'U123';
        $_POST['save_method'] = '1';
        $_POST['payment_source'] = 'new';
        $_POST['card_number'] = '1234567812345678';
        $_POST['expiry'] = '12/28';

        $pmMock = $this->getMockBuilder(PaymentMethod::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();
        $pmMock->expects($this->once())
            ->method('save')
            ->with($this->callback(function($data) {
                return $data['usuario_id'] === 'U123' && $data['last4'] === '5678';
            }));

        $controller = $this->getControllerMock(ShopController::class);
        $controller->method('model')->with('PaymentMethod')->willReturn($pmMock);

        $this->expectException(TestExitException::class);
        $controller->procesarPago();
    }
}
