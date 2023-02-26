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
}
