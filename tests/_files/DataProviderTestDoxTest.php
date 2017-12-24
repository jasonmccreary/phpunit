<?php
class DataProviderTestDoxTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider provider
     * @testdox Does something with
     */
    public function testOne()
    {
    }

    /**
     * @dataProvider provider
     */
    public function testDoesSomethingElseWith()
    {
    }

    public function provider()
    {
        return [
            'one' => [1],
            'two' => [2]
        ];
    }
}
