<?php
class CoverageMethodParenthesesWhitespaceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers CoveredClass::publicMethod ( )
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
