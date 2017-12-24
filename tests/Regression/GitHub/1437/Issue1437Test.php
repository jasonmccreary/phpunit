<?php
class Issue1437Test extends \PHPUnit\Framework\TestCase
{
    public function testFailure()
    {
        ob_start();
        $this->assertTrue(false);
    }
}
