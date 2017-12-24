<?php
class CoverageClassExtendedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers CoveredClass<extended>
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
