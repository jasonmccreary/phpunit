<?php
class DependencySuccessTest extends \PHPUnit\Framework\TestCase
{
    public function testOne()
    {
    }

    /**
     * @depends testOne
     */
    public function testTwo()
    {
    }

    /**
     * @depends DependencySuccessTest::testTwo
     */
    public function testThree()
    {
    }
}
