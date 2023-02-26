<?php

namespace Sandbox\Test;

require dirname(dirname(__FILE__)) .
    DIRECTORY_SEPARATOR .
    'vendor' .
    DIRECTORY_SEPARATOR .
    'autoload.php';

use PHPUnit\Framework\TestCase;
use Sandbox\Router;
use Sandbox\Request;

class RouterTest extends TestCase
{
    private Router $router;

    public function setUp(): void
    {
        $this->router = new Router();
    }

    public function tearDown(): void
    {
        unset($this->router);
    }

    /**
     * @dataProvider addRouteProvider
     */
    public function testAddRoute(
        $givenRequestMethod,
        $givenRequestPath,
        $givenControllerClass,
        $givenControllerMethod,
        $expectedResult
    ) {
        $this->router->addRoute(
            $givenRequestMethod,
            $givenRequestPath,
            $givenControllerClass,
            $givenControllerMethod
        );
        $this->assertEquals($expectedResult, $this->router->routeMap);
    }

    public static function addRouteProvider()
    {
        return [
            [
                Request::METHOD_GET,
                '/',
                'Sandbox\HomeController',
                'getHome',
                [
                    Request::METHOD_GET => [
                        '/' => [
                            'class' => 'Sandbox\HomeController',
                            'method' => 'getHome',
                        ],
                    ],
                ],
            ],
            [
                Request::METHOD_POST,
                '/',
                'Sandbox\HomeController',
                'postHome',
                [
                    Request::METHOD_POST => [
                        '/' => [
                            'class' => 'Sandbox\HomeController',
                            'method' => 'postHome',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function testResolveRoute()
    {
        $testController = new class {
            public function testControllerMethod()
            {
                return 'Test Result';
            }
        };

        $this->router->routeMap[Request::METHOD_GET]['/test'] = [
            'class' => $testController,
            'method' => 'testControllerMethod',
        ];

        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Request::METHOD_GET);
        $requestMock
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/test');

        $actualResult = $this->router->resolveRoute($requestMock);

        $this->assertEquals('Test Result', $actualResult);
    }

    public function testResolveRouteArrayNotFoundException()
    {
        // test that an exception is thrown when resolveRoute can't
        // find an array entry for request method and request path

        $this->router->routeMap = [];

        $this->expectException(\Sandbox\RouteArrayNotFoundException::class);
        $this->expectExceptionMessageMatches(
            '/Requested Route for Request Method ' .
                'and Request Path not found in RouteMap\./'
        );

        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Request::METHOD_GET);
        $requestMock
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/test');

        $this->router->resolveRoute($requestMock);
    }

    public function testResolveRouteRouteClassNotFoundException()
    {
        // test that an exception is thrown when the requested class
        // in routeMap doesn't exist or can't be instantiated.

        $this->router->routeMap[Request::METHOD_GET]['/test'] = [
            'class' => 'classThatDoesntExist',
            'method' => 'someMethod',
        ];

        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Request::METHOD_GET);
        $requestMock
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/test');

        $this->expectException(\Sandbox\RouteClassNotFoundException::class);
        $this->expectExceptionMessageMatches(
            '/Requested Class for Requested Route not found ' .
                'or not instantiated\./'
        );

        $this->router->resolveRoute($requestMock);
    }
}
