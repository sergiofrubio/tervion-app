<?php

namespace Tests\Unit;

use App\Controllers\LandingController;

class LandingControllerTest extends ControllerTestCase
{
    public function testIndexShowsLandingView()
    {
        $controller = $this->getControllerMock(LandingController::class);
        $controller->expects($this->once())
            ->method('view')
            ->with('landing');

        $controller->index();
    }
}
