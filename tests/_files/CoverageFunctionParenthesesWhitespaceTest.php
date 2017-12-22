<?php
class CoverageFunctionParenthesesWhitespaceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers ::globalFunction ( )
     */
    public function testSomething()
    {
        globalFunction();
    }
}
