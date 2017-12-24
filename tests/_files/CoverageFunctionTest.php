<?php
class CoverageFunctionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers ::globalFunction
     */
    public function testSomething()
    {
        globalFunction();
    }
}
