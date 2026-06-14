<?php

namespace Tests\Unit;

use App\Controllers\ShopController;
use App\Models\Setting;

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
}
