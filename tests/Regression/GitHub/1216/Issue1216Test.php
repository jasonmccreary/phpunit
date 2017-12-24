<?php
class Issue1216Test extends \PHPUnit\Framework\TestCase
{
    public function testConfigAvailableInBootstrap()
    {
        $this->assertTrue($_ENV['configAvailableInBootstrap']);
    }
}
