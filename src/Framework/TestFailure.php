<?php
namespace PHPUnit\Framework\TestFailure

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A TestFailure collects a failed test together with the caught exception.
 */
class TestFailure
{
    /**
     * @var string
     */
    private $testName;

    /**
     * @var \PHPUnit\Framework\Test|null
     */
    protected $failedTest;

    /**
     * @var Exception
     */
    protected $thrownException;

    /**
     * Constructs a TestFailure with the given test and exception.
     *
     * @param \PHPUnit\Framework\Test $failedTest
     * @param Throwable              $t
     */
    public function __construct(\PHPUnit\Framework\Test $failedTest, $t)
    {
        if ($failedTest instanceof \PHPUnit\Framework\SelfDescribing) {
            $this->testName = $failedTest->toString();
        } else {
            $this->testName = get_class($failedTest);
        }

        if (!$failedTest instanceof \PHPUnit\Framework\TestCase || !$failedTest->isInIsolation()) {
            $this->failedTest = $failedTest;
        }

        $this->thrownException = $t;
    }

    /**
     * Returns a short description of the failure.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf(
            '%s: %s',
            $this->testName,
            $this->thrownException->getMessage()
        );
    }

    /**
     * Returns a description for the thrown exception.
     *
     * @return string
     */
    public function getExceptionAsString()
    {
        return self::exceptionToString($this->thrownException);
    }

    /**
     * Returns a description for an exception.
     *
     * @param Exception $e
     *
     * @return string
     */
    public static function exceptionToString(Exception $e)
    {
        if ($e instanceof \PHPUnit\Framework\SelfDescribing) {
            $buffer = $e->toString();

            if ($e instanceof \PHPUnit\Framework\ExpectationFailedException && $e->getComparisonFailure()) {
                $buffer = $buffer . $e->getComparisonFailure()->getDiff();
            }

            if (!empty($buffer)) {
                $buffer = trim($buffer) . "\n";
            }
        } elseif ($e instanceof \PHPUnit\Framework\Error) {
            $buffer = $e->getMessage() . "\n";
        } elseif ($e instanceof \PHPUnit\Framework\ExceptionWrapper) {
            $buffer = $e->getClassName() . ': ' . $e->getMessage() . "\n";
        } else {
            $buffer = get_class($e) . ': ' . $e->getMessage() . "\n";
        }

        return $buffer;
    }

    /**
     * Returns the name of the failing test (including data set, if any).
     *
     * @return string
     */
    public function getTestName()
    {
        return $this->testName;
    }

    /**
     * Returns the failing test.
     *
     * Note: The test object is not set when the test is executed in process
     * isolation.
     *
     * @see \PHPUnit\Framework\Exception
     *
     * @return \PHPUnit\Framework\Test|null
     */
    public function failedTest()
    {
        return $this->failedTest;
    }

    /**
     * Gets the thrown exception.
     *
     * @return Exception
     */
    public function thrownException()
    {
        return $this->thrownException;
    }

    /**
     * Returns the exception's message.
     *
     * @return string
     */
    public function exceptionMessage()
    {
        return $this->thrownException()->getMessage();
    }

    /**
     * Returns true if the thrown exception
     * is of type AssertionFailedError.
     *
     * @return bool
     */
    public function isFailure()
    {
        return ($this->thrownException() instanceof \PHPUnit\Framework\AssertionFailedError);
    }
}
