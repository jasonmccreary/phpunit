<?php
namespace PHPUnit\Extensions\TicketListener

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Base class for test listeners that interact with an issue tracker.
 */
abstract class TicketListener implements \PHPUnit\Framework\TestListener
{
    /**
     * @var array
     */
    protected $ticketCounts = [];

    /**
     * @var bool
     */
    protected $ran = false;

    /**
     * An error occurred.
     *
     * @param \PHPUnit\Framework\Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addError(\PHPUnit\Framework\Test $test, Exception $e, $time)
    {
    }

    /**
     * A failure occurred.
     *
     * @param \PHPUnit\Framework\Test                 $test
     * @param \PHPUnit\Framework\AssertionFailedError $e
     * @param float                                  $time
     */
    public function addFailure(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\AssertionFailedError $e, $time)
    {
    }

    /**
     * Incomplete test.
     *
     * @param \PHPUnit\Framework\Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addIncompleteTest(\PHPUnit\Framework\Test $test, Exception $e, $time)
    {
    }

    /**
     * Risky test.
     *
     * @param \PHPUnit\Framework\Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addRiskyTest(\PHPUnit\Framework\Test $test, Exception $e, $time)
    {
    }

    /**
     * Skipped test.
     *
     * @param \PHPUnit\Framework\Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addSkippedTest(\PHPUnit\Framework\Test $test, Exception $e, $time)
    {
    }

    /**
     * A test suite started.
     *
     * @param \PHPUnit\Framework\TestSuite $suite
     */
    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
    }

    /**
     * A test suite ended.
     *
     * @param \PHPUnit\Framework\TestSuite $suite
     */
    public function endTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
    }

    /**
     * A test started.
     *
     * @param \PHPUnit\Framework\Test $test
     */
    public function startTest(\PHPUnit\Framework\Test $test)
    {
        if (!$test instanceof \PHPUnit\Framework\WarningTestCase) {
            if ($this->ran) {
                return;
            }

            $name    = $test->getName(false);
            $tickets = \PHPUnit\Util\Test::getTickets(get_class($test), $name);

            foreach ($tickets as $ticket) {
                $this->ticketCounts[$ticket][$name] = 1;
            }

            $this->ran = true;
        }
    }

    /**
     * A test ended.
     *
     * @param \PHPUnit\Framework\Test $test
     * @param float                  $time
     */
    public function endTest(\PHPUnit\Framework\Test $test, $time)
    {
        if (!$test instanceof \PHPUnit\Framework\WarningTestCase) {
            if ($test->getStatus() == \PHPUnit\Runner\BaseTestRunner::STATUS_PASSED) {
                $ifStatus   = ['assigned', 'new', 'reopened'];
                $newStatus  = 'closed';
                $message    = 'Automatically closed by PHPUnit (test passed).';
                $resolution = 'fixed';
                $cumulative = true;
            } elseif ($test->getStatus() == \PHPUnit\Runner\BaseTestRunner::STATUS_FAILURE) {
                $ifStatus   = ['closed'];
                $newStatus  = 'reopened';
                $message    = 'Automatically reopened by PHPUnit (test failed).';
                $resolution = '';
                $cumulative = false;
            } else {
                return;
            }

            $name    = $test->getName(false);
            $tickets = \PHPUnit\Util\Test::getTickets(get_class($test), $name);

            foreach ($tickets as $ticket) {
                // Remove this test from the totals (if it passed).
                if ($test->getStatus() == \PHPUnit\Runner\BaseTestRunner::STATUS_PASSED) {
                    unset($this->ticketCounts[$ticket][$name]);
                }

                // Only close tickets if ALL referenced cases pass
                // but reopen tickets if a single test fails.
                if ($cumulative) {
                    // Determine number of to-pass tests:
                    if (count($this->ticketCounts[$ticket]) > 0) {
                        // There exist remaining test cases with this reference.
                        $adjustTicket = false;
                    } else {
                        // No remaining tickets, go ahead and adjust.
                        $adjustTicket = true;
                    }
                } else {
                    $adjustTicket = true;
                }

                $ticketInfo = $this->getTicketInfo($ticket);

                if ($adjustTicket && in_array($ticketInfo['status'], $ifStatus)) {
                    $this->updateTicket($ticket, $newStatus, $message, $resolution);
                }
            }
        }
    }

    /**
     * @param mixed $ticketId
     *
     * @return mixed
     */
    abstract protected function getTicketInfo($ticketId = null);

    /**
     * @param string $ticketId
     * @param string $newStatus
     * @param string $message
     * @param string $resolution
     */
    abstract protected function updateTicket($ticketId, $newStatus, $message, $resolution);
}
