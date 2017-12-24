<?php
class ChangeCurrentWorkingDirectoryTest extends \PHPUnit\Framework\TestCase
{
    public function testSomethingThatChangesTheCwd()
    {
        chdir('../');
        $this->assertTrue(true);
    }
}
