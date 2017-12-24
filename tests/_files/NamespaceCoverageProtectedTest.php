<?php
class NamespaceCoverageProtectedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Foo\CoveredClass::<protected>
     */
    public function testSomething()
    {
        $o = new Foo\CoveredClass;
        $o->publicMethod();
    }
}
