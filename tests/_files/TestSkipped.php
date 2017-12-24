<?php
class TestSkipped extends \PHPUnit\Framework\TestCase
{
    protected function runTest()
    {
        $this->markTestSkipped('Skipped test');
    }
}
