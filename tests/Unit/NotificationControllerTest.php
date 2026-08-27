<?php

namespace Tests\Unit;

use App\Controllers\NotificationController;

class NotificationControllerTest extends ControllerTestCase
{
    public function testSendEmailReturnsBoolean()
    {
        $controller = new NotificationController();
        // We can call sendEmail with invalid details, it should catch the exception and return false
        // or if mailpit is available, we try to send. Let's capture output or handle it.
        $result = $controller->sendEmail('invalid-email-format', 'Test', 'Body');
        $this->assertIsBool($result);
    }

    public function testSendWhatsAppMessageReturnsArray()
    {
        $controller = new NotificationController();
        $result = $controller->sendWhatsAppMessage('34600000000', 'Test message');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('response', $result);
        $this->assertArrayHasKey('error', $result);
        $this->assertArrayHasKey('http_code', $result);
    }
}
