<?php
class Failure extends \PHPUnit\Framework\TestCase
{
    protected function runTest()
    {
        $this->fail();
    }
}
