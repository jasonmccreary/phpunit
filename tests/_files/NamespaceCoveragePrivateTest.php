<?php
class NamespaceCoveragePrivateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Foo\CoveredClass::<private>
     */
    public function testSomething()
    {
        $o = new Foo\CoveredClass;
        $o->publicMethod();
    }
}
