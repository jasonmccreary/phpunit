<?php
class WasRun extends \PHPUnit\Framework\TestCase
{
    public $wasRun = false;

    protected function runTest()
    {
        $this->wasRun = true;
    }
}
