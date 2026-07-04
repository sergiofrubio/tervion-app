<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ControllerTestCase extends TestCase
{
    protected $originalSession;
    protected $originalPost;
    protected $originalGet;
    protected $originalServer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->originalSession = $_SESSION ?? [];
        $this->originalPost = $_POST;
        $this->originalGet = $_GET;
        $this->originalServer = $_SERVER;

        $_SESSION = [];
        $_POST = [];
        $_GET = [];

        if (!defined('PROJECT_ROOT')) {
            define('PROJECT_ROOT', '');
        }
    }

    protected function tearDown(): void
    {
        $_SESSION = $this->originalSession;
        $_POST = $this->originalPost;
        $_GET = $this->originalGet;
        $_SERVER = $this->originalServer;

        parent::tearDown();
    }

    /**
     * Mocks a controller to bypass exitApp and view methods.
     */
    protected function getControllerMock(string $className, array $mockedMethods = [])
    {
        $methodsToMock = array_unique(array_merge(['exitApp', 'view', 'model'], $mockedMethods));
        
        $mock = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->onlyMethods($methodsToMock)
            ->getMock();

        $mock->method('exitApp')->willThrowException(new TestExitException("Application exited."));

        return $mock;
    }
}
