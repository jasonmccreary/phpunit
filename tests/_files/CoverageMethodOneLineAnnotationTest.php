<?php

class CoverageMethodOneLineAnnotationTest extends \PHPUnit\Framework\TestCase
{
    /** @covers CoveredClass::publicMethod */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
