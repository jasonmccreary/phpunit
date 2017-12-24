<?php
class ThrowExceptionTestCase extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        throw new RuntimeException('A runtime error occurred');
    }
}
