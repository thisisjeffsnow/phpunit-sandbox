<?php

namespace Sandbox\Test;

require dirname(dirname(__FILE__)) .
    DIRECTORY_SEPARATOR .
    'vendor' .
    DIRECTORY_SEPARATOR .
    'autoload.php';

use PHPUnit\Framework\TestCase;
use Sandbox\Controller\HomeController;
use Sandbox\Request;
use Sandbox\View\View;

class HomeControllerTest extends TestCase
{
    public HomeController $homeController;

    public function setUp(): void
    {
        $this->homeController = new HomeController();
    }

    public function tearDown(): void
    {
        unset($this->homeController);
    }

    public function testHomeControllerGetMainOutputHTML()
    {
        /*
         * The getMain method should display the default view
         * with the default content of the main homepage.
         * Test that getMain does echo something that contains HTML?
         */

        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock->method('getMethod')->willReturn(Request::METHOD_GET);
        $requestMock->method('getPath')->willReturn('/home');

        ob_start();
        $this->homeController->getMain($requestMock);
        $output = ob_get_clean();

        $this->assertStringContainsString('<html>', $output);
    }

    public function testHomeControllerMainCallsViewMethod()
    {
        /*
         * Assert that the getMain method on HomeController
         * invokes the view class and renders a view.
         */
        $view = $this->getMockBuilder(View::class)->getMock();

        $view->expects($this->once())->method('render');

        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock->method('getMethod')->willReturn(Request::METHOD_GET);
        $requestMock->method('getPath')->willReturn('/home');

        $this->homeController->getMain($requestMock);
    }
}
