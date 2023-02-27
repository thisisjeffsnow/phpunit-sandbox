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

    public function testHomeControllerMainCallsViewMethod()
    {
        /*
         * Assert that the getMain method on HomeController
         * invokes the view class and renders a view.
         */
        $viewMock = $this->getMockBuilder(View::class)
            ->onlyMethods(['render'])
            ->getMock();

        $viewMock
            ->expects($this->once())
            ->method('render')
            ->willReturn(
                '<html><head><title>Test</title></head>' .
                    '<body><p>Hello, world!</p></body></html>'
            );

        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock->method('getMethod')->willReturn(Request::METHOD_GET);
        $requestMock->method('getPath')->willReturn('/home');

        $output = $this->homeController->getMain($requestMock);

        $this->assertStringContainsString('<p>Hello, world!</p>', $output);
        // or any other assertions based on the expected HTML content
        // getMain is not calling the render method though?
        // need to redesign so that the view object is being passed to
        // controller when it's instantiated so we can test better.
        // research needed: where does new View() come from? What class
        // calls this command and where is $view then passed to the controller?
        // in the router class?
    }
}
