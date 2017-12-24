<?php
class TestIncomplete extends \PHPUnit\Framework\TestCase
{
    protected function runTest()
    {
        $this->markTestIncomplete('Incomplete test');
    }
}
