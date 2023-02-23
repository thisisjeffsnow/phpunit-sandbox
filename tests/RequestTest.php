<?php

namespace Sandbox\Test;

require dirname(dirname(__FILE__)) .
    DIRECTORY_SEPARATOR .
    "vendor" .
    DIRECTORY_SEPARATOR .
    "autoload.php";

use PHPUnit\Framework\TestCase;
use Sandbox\Request;

class RequestTest extends TestCase
{
    private Request $request;

    public function setUp(): void
    {
        $this->request = new Request();
    }

    public function tearDown(): void
    {
        unset($this->request);
        unset($_SERVER["REQUEST_URI"]);
    }

    /**
     * @dataProvider requestPathProvider
     */
    public function testRequestGetPath($givenPath, $expectedResult)
    {
        $_SERVER["REQUEST_URI"] = "$givenPath";

        $result = $this->request->getPath();

        $this->assertEquals(
            $expectedResult,
            $result,
            "When the Request class runs getPath on '$givenPath', it should return '$expectedResult'."
        );
    }

    public static function requestPathProvider()
    {
        return [
            "query variable" => ["/test/path.php?id=1", "/test/path.php"],
            "slash" => ["/", "/"],
            "empty" => ["", "/"], # not sure if this even ever happens?
            "simple file" => ["/test.php", "/test.php"],
        ];
    }
}
