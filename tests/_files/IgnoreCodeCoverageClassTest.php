<?php
class IgnoreCodeCoverageClassTest extends \PHPUnit\Framework\TestCase
{
    public function testReturnTrue()
    {
        $sut = new IgnoreCodeCoverageClass();
        $this->assertTrue($sut->returnTrue());
    }

    public function testReturnFalse()
    {
        $sut = new IgnoreCodeCoverageClass();
        $this->assertFalse($sut->returnFalse());
    }
}
