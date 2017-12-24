<?php
class CoverageNamespacedFunctionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers foo\func()
     */
    public function testFunc()
    {
        foo\func();
    }
}
