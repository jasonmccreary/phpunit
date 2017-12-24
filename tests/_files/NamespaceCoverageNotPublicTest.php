<?php
class NamespaceCoverageNotPublicTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Foo\CoveredClass::<!public>
     */
    public function testSomething()
    {
        $o = new Foo\CoveredClass;
        $o->publicMethod();
    }
}
