<?php
class CoverageNoneTest extends \PHPUnit\Framework\TestCase
{
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
