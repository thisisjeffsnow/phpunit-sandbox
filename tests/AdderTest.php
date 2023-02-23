<?php

namespace Sandbox\Test;

require dirname(dirname(__FILE__)) .
    DIRECTORY_SEPARATOR .
    "vendor" .
    DIRECTORY_SEPARATOR .
    "autoload.php";

use PHPUnit\Framework\TestCase;
use Sandbox\Adder;

class AdderTest extends TestCase
{
    private Adder $adder;

    public function setUp(): void
    {
        $this->adder = new Adder();
    }

    public function tearDown(): void
    {
        unset($this->adder);
    }

    /**
     * @dataProvider additionProvider
     */
    public function testBinaryAddition($addend1, $addend2, $expected)
    {
        $result = $this->adder->BinaryAddition($addend1, $addend2);
        $this->AssertEquals(
            $expected,
            $result,
            "The sum of $addend1 and $addend2 should be $expected."
        );
    }

    public static function additionProvider()
    {
        return [
            "standard addition test" => [2, 4, 6],
            "all zeroes" => [0, 0, 0],
            "negatives" => [-1, -1, -2],
        ];
    }
}
