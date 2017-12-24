<?php
class TestError extends \PHPUnit\Framework\TestCase
{
    protected function runTest()
    {
        throw new Exception;
    }
}
