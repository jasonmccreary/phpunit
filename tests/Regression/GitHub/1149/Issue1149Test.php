<?php
class Issue1149Test extends \PHPUnit\Framework\TestCase
{
    public function testOne()
    {
        $this->assertTrue(true);
        print '1';
    }

    /**
     * @runInSeparateProcess
     */
    public function testTwo()
    {
        $this->assertTrue(true);
        print '2';
    }
}
