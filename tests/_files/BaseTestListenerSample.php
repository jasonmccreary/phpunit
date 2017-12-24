<?php

class BaseTestListenerSample extends \PHPUnit\Framework\BaseTestListener
{
    public $endCount = 0;

    public function endTest(\PHPUnit\Framework\Test $test, $time)
    {
        $this->endCount++;
    }
}
