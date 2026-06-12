<?php

namespace Tests\Unit;

use App\Controllers\EmailController;

class EmailControllerTest extends ControllerTestCase
{
    public function testSendEmailReturnsBoolean()
    {
        $controller = new EmailController();
        // We can call sendEmail with invalid details, it should catch the exception and return false
        // or if mailpit is available, we try to send. Let's capture output or handle it.
        $result = $controller->sendEmail('invalid-email-format', 'Test', 'Body');
        $this->assertIsBool($result);
    }
}
