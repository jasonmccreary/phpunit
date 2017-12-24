<?php
class Issue2758TestListener extends \PHPUnit\Framework\BaseTestListener
{
    public function endTest(\PHPUnit\Framework\Test $test, $time)
    {
        if (!$test instanceof \PHPUnit\Framework\TestCase) {
            return;
        }

        $test->addToAssertionCount(1);
    }
}
