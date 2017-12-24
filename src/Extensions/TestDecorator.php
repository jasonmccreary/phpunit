<?php
namespace PHPUnit\Extensions\TestDecorator

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A Decorator for Tests.
 *
 * Use TestDecorator as the base class for defining new
 * test decorators. Test decorator subclasses can be introduced
 * to add behaviour before or after a test is run.
 */
class TestDecorator extends \PHPUnit\Framework\Assert implements \PHPUnit\Framework\Test, \PHPUnit\Framework\SelfDescribing
{
    /**
     * The Test to be decorated.
     *
     * @var object
     */
    protected $test = null;

    /**
     * Constructor.
     *
     * @param \PHPUnit\Framework\Test $test
     */
    public function __construct(\PHPUnit\Framework\Test $test)
    {
        $this->test = $test;
    }

    /**
     * Returns a string representation of the test.
     *
     * @return string
     */
    public function toString()
    {
        return $this->test->toString();
    }

    /**
     * Runs the test and collects the
     * result in a TestResult.
     *
     * @param \PHPUnit\Framework\TestResult $result
     */
    public function basicRun(\PHPUnit\Framework\TestResult $result)
    {
        $this->test->run($result);
    }

    /**
     * Counts the number of test cases that
     * will be run by this test.
     *
     * @return int
     */
    public function count()
    {
        return count($this->test);
    }

    /**
     * Creates a default TestResult object.
     *
     * @return \PHPUnit\Framework\TestResult
     */
    protected function createResult()
    {
        return new \PHPUnit\Framework\TestResult;
    }

    /**
     * Returns the test to be run.
     *
     * @return \PHPUnit\Framework\Test
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Runs the decorated test and collects the
     * result in a TestResult.
     *
     * @param \PHPUnit\Framework\TestResult $result
     *
     * @return \PHPUnit\Framework\TestResult
     */
    public function run(\PHPUnit\Framework\TestResult $result = null)
    {
        if ($result === null) {
            $result = $this->createResult();
        }

        $this->basicRun($result);

        return $result;
    }
}
