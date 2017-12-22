<?php
class NamespaceCoverageClassExtendedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Foo\CoveredClass<extended>
     */
    public function testSomething()
    {
        $o = new Foo\CoveredClass;
        $o->publicMethod();
    }
}
