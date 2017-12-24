<?php
class CoverageProtectedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers CoveredClass::<protected>
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
