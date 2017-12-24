<?php
/**
 * @coversDefaultClass \Foo\CoveredClass
 */
class NamespaceCoverageCoversClassTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers ::privateMethod
     * @covers ::protectedMethod
     * @covers ::publicMethod
     * @covers \Foo\CoveredParentClass::privateMethod
     * @covers \Foo\CoveredParentClass::protectedMethod
     * @covers \Foo\CoveredParentClass::publicMethod
     */
    public function testSomething()
    {
        $o = new Foo\CoveredClass;
        $o->publicMethod();
    }
}
