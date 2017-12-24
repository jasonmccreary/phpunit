<?php
class Issue1471Test extends \PHPUnit\Framework\TestCase
{
    public function testFailure()
    {
        $this->expectOutputString('*');

        print '*';

        $this->assertTrue(false);
    }
}
