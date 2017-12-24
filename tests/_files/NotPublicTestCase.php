<?php
class NotPublicTestCase extends \PHPUnit\Framework\TestCase
{
    public function testPublic()
    {
    }

    protected function testNotPublic()
    {
    }
}
