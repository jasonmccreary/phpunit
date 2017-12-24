<?php
namespace PHPUnit\Framework\BaseTestListener;

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * An empty Listener that can be extended to implement TestListener
 * with just a few lines of code.
 *
 * @see \PHPUnit\Framework\TestListener for documentation on the API methods.
 */
abstract class BaseTestListener implements \PHPUnit\Framework\TestListener
{
    public function addError(\PHPUnit\Framework\Test $test, Exception $e, $time)
    {
    }

    public function addWarning(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\Warning $e, $time)
    {
    }

    public function addFailure(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\AssertionFailedError $e, $time)
    {
    }

    public function addIncompleteTest(\PHPUnit\Framework\Test $test, Exception $e, $time)
    {
    }

    public function addRiskyTest(\PHPUnit\Framework\Test $test, Exception $e, $time)
    {
    }

    public function addSkippedTest(\PHPUnit\Framework\Test $test, Exception $e, $time)
    {
    }

    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
    }

    public function endTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
    }

    public function startTest(\PHPUnit\Framework\Test $test)
    {
    }

    public function endTest(\PHPUnit\Framework\Test $test, $time)
    {
    }
}
