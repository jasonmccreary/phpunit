<?php
namespace PHPUnit\TextUI\ResultPrinter;

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use SebastianBergmann\Environment\Console;

/**
 * Prints the result of a TextUI TestRunner run.
 */
class ResultPrinter extends \PHPUnit\Util\Printer implements \PHPUnit\Framework\TestListener
{
    const EVENT_TEST_START      = 0;
    const EVENT_TEST_END        = 1;
    const EVENT_TESTSUITE_START = 2;
    const EVENT_TESTSUITE_END   = 3;

    const COLOR_NEVER   = 'never';
    const COLOR_AUTO    = 'auto';
    const COLOR_ALWAYS  = 'always';
    const COLOR_DEFAULT = self::COLOR_NEVER;

    /**
     * @var array
     */
    private static $ansiCodes = [
      'bold'       => 1,
      'fg-black'   => 30,
      'fg-red'     => 31,
      'fg-green'   => 32,
      'fg-yellow'  => 33,
      'fg-blue'    => 34,
      'fg-magenta' => 35,
      'fg-cyan'    => 36,
      'fg-white'   => 37,
      'bg-black'   => 40,
      'bg-red'     => 41,
      'bg-green'   => 42,
      'bg-yellow'  => 43,
      'bg-blue'    => 44,
      'bg-magenta' => 45,
      'bg-cyan'    => 46,
      'bg-white'   => 47
    ];

    /**
     * @var int
     */
    protected $column = 0;

    /**
     * @var int
     */
    protected $maxColumn;

    /**
     * @var bool
     */
    protected $lastTestFailed = false;

    /**
     * @var int
     */
    protected $numAssertions = 0;

    /**
     * @var int
     */
    protected $numTests = -1;

    /**
     * @var int
     */
    protected $numTestsRun = 0;

    /**
     * @var int
     */
    protected $numTestsWidth;

    /**
     * @var bool
     */
    protected $colors = false;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var bool
     */
    protected $verbose = false;

    /**
     * @var int
     */
    private $numberOfColumns;

    /**
     * @var bool
     */
    private $reverse = false;

    /**
     * @var bool
     */
    private $defectListPrinted = false;

    /**
     * Constructor.
     *
     * @param mixed      $out
     * @param bool       $verbose
     * @param string     $colors
     * @param bool       $debug
     * @param int|string $numberOfColumns
     * @param bool       $reverse
     *
     * @throws \PHPUnit\Framework\Exception
     */
    public function __construct($out = null, $verbose = false, $colors = self::COLOR_DEFAULT, $debug = false, $numberOfColumns = 80, $reverse = false)
    {
        parent::__construct($out);

        if (!is_bool($verbose)) {
            throw \PHPUnit\Util\InvalidArgumentHelper::factory(2, 'boolean');
        }

        $availableColors = [self::COLOR_NEVER, self::COLOR_AUTO, self::COLOR_ALWAYS];

        if (!in_array($colors, $availableColors)) {
            throw \PHPUnit\Util\InvalidArgumentHelper::factory(
                3,
                vsprintf('value from "%s", "%s" or "%s"', $availableColors)
            );
        }

        if (!is_bool($debug)) {
            throw \PHPUnit\Util\InvalidArgumentHelper::factory(4, 'boolean');
        }

        if (!is_int($numberOfColumns) && $numberOfColumns != 'max') {
            throw \PHPUnit\Util\InvalidArgumentHelper::factory(5, 'integer or "max"');
        }

        if (!is_bool($reverse)) {
            throw \PHPUnit\Util\InvalidArgumentHelper::factory(6, 'boolean');
        }

        $console            = new Console;
        $maxNumberOfColumns = $console->getNumberOfColumns();

        if ($numberOfColumns == 'max' || $numberOfColumns > $maxNumberOfColumns) {
            $numberOfColumns = $maxNumberOfColumns;
        }

        $this->numberOfColumns = $numberOfColumns;
        $this->verbose         = $verbose;
        $this->debug           = $debug;
        $this->reverse         = $reverse;

        if ($colors === self::COLOR_AUTO && $console->hasColorSupport()) {
            $this->colors = true;
        } else {
            $this->colors = (self::COLOR_ALWAYS === $colors);
        }
    }

    /**
     * @param \PHPUnit\Framework\TestResult $result
     */
    public function printResult(\PHPUnit\Framework\TestResult $result)
    {
        $this->printHeader();
        $this->printErrors($result);
        $this->printWarnings($result);
        $this->printFailures($result);

        if ($this->verbose) {
            $this->printRisky($result);
            $this->printIncompletes($result);
            $this->printSkipped($result);
        }

        $this->printFooter($result);
    }

    /**
     * @param array  $defects
     * @param string $type
     */
    protected function printDefects(array $defects, $type)
    {
        $count = count($defects);

        if ($count == 0) {
            return;
        }

        if ($this->defectListPrinted) {
            $this->write("\n--\n\n");
        }

        $this->write(
            sprintf(
                "There %s %d %s%s:\n",
                ($count == 1) ? 'was' : 'were',
                $count,
                $type,
                ($count == 1) ? '' : 's'
            )
        );

        $i = 1;

        if ($this->reverse) {
            $defects = array_reverse($defects);
        }

        foreach ($defects as $defect) {
            $this->printDefect($defect, $i++);
        }

        $this->defectListPrinted = true;
    }

    /**
     * @param \PHPUnit\Framework\TestFailure $defect
     * @param int                           $count
     */
    protected function printDefect(\PHPUnit\Framework\TestFailure $defect, $count)
    {
        $this->printDefectHeader($defect, $count);
        $this->printDefectTrace($defect);
    }

    /**
     * @param \PHPUnit\Framework\TestFailure $defect
     * @param int                           $count
     */
    protected function printDefectHeader(\PHPUnit\Framework\TestFailure $defect, $count)
    {
        $this->write(
            sprintf(
                "\n%d) %s\n",
                $count,
                $defect->getTestName()
            )
        );
    }

    /**
     * @param \PHPUnit\Framework\TestFailure $defect
     */
    protected function printDefectTrace(\PHPUnit\Framework\TestFailure $defect)
    {
        $e = $defect->thrownException();
        $this->write((string) $e);

        while ($e = $e->getPrevious()) {
            $this->write("\nCaused by\n" . $e);
        }
    }

    /**
     * @param \PHPUnit\Framework\TestResult $result
     */
    protected function printErrors(\PHPUnit\Framework\TestResult $result)
    {
        $this->printDefects($result->errors(), 'error');
    }

    /**
     * @param \PHPUnit\Framework\TestResult $result
     */
    protected function printFailures(\PHPUnit\Framework\TestResult $result)
    {
        $this->printDefects($result->failures(), 'failure');
    }

    /**
     * @param \PHPUnit\Framework\TestResult $result
     */
    protected function printWarnings(\PHPUnit\Framework\TestResult $result)
    {
        $this->printDefects($result->warnings(), 'warning');
    }

    /**
     * @param \PHPUnit\Framework\TestResult $result
     */
    protected function printIncompletes(\PHPUnit\Framework\TestResult $result)
    {
        $this->printDefects($result->notImplemented(), 'incomplete test');
    }

    /**
     * @param \PHPUnit\Framework\TestResult $result
     */
    protected function printRisky(\PHPUnit\Framework\TestResult $result)
    {
        $this->printDefects($result->risky(), 'risky test');
    }

    /**
     * @param \PHPUnit\Framework\TestResult $result
     */
    protected function printSkipped(\PHPUnit\Framework\TestResult $result)
    {
        $this->printDefects($result->skipped(), 'skipped test');
    }

    protected function printHeader()
    {
        $this->write("\n\n" . PHP_Timer::resourceUsage() . "\n\n");
    }

    /**
     * @param \PHPUnit\Framework\TestResult $result
     */
    protected function printFooter(\PHPUnit\Framework\TestResult $result)
    {
        if (count($result) === 0) {
            $this->writeWithColor(
                'fg-black, bg-yellow',
                'No tests executed!'
            );

            return;
        }

        if ($result->wasSuccessful() &&
            $result->allHarmless() &&
            $result->allCompletelyImplemented() &&
            $result->noneSkipped()) {
            $this->writeWithColor(
                'fg-black, bg-green',
                sprintf(
                    'OK (%d test%s, %d assertion%s)',
                    count($result),
                    (count($result) == 1) ? '' : 's',
                    $this->numAssertions,
                    ($this->numAssertions == 1) ? '' : 's'
                )
            );
        } else {
            if ($result->wasSuccessful()) {
                $color = 'fg-black, bg-yellow';

                if ($this->verbose) {
                    $this->write("\n");
                }

                $this->writeWithColor(
                    $color,
                    'OK, but incomplete, skipped, or risky tests!'
                );
            } else {
                $this->write("\n");

                if ($result->errorCount()) {
                    $color = 'fg-white, bg-red';

                    $this->writeWithColor(
                        $color,
                        'ERRORS!'
                    );
                } elseif ($result->failureCount()) {
                    $color = 'fg-white, bg-red';

                    $this->writeWithColor(
                        $color,
                        'FAILURES!'
                    );
                } elseif ($result->warningCount()) {
                    $color = 'fg-black, bg-yellow';

                    $this->writeWithColor(
                        $color,
                        'WARNINGS!'
                    );
                }
            }

            $this->writeCountString(count($result), 'Tests', $color, true);
            $this->writeCountString($this->numAssertions, 'Assertions', $color, true);
            $this->writeCountString($result->errorCount(), 'Errors', $color);
            $this->writeCountString($result->failureCount(), 'Failures', $color);
            $this->writeCountString($result->warningCount(), 'Warnings', $color);
            $this->writeCountString($result->skippedCount(), 'Skipped', $color);
            $this->writeCountString($result->notImplementedCount(), 'Incomplete', $color);
            $this->writeCountString($result->riskyCount(), 'Risky', $color);
            $this->writeWithColor($color, '.', true);
        }
    }

    public function printWaitPrompt()
    {
        $this->write("\n<RETURN> to continue\n");
    }

    /**
     * An error occurred.
     *
     * @param \PHPUnit\Framework\Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addError(\PHPUnit\Framework\Test $test, Exception $e, $time)
    {
        $this->writeProgressWithColor('fg-red, bold', 'E');
        $this->lastTestFailed = true;
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
        $this->writeProgressWithColor('bg-red, fg-white', 'F');
        $this->lastTestFailed = true;
    }

    /**
     * A warning occurred.
     *
     * @param \PHPUnit\Framework\Test    $test
     * @param \PHPUnit\Framework\Warning $e
     * @param float                     $time
     */
    public function addWarning(\PHPUnit\Framework\Test $test, \PHPUnit\Framework\Warning $e, $time)
    {
        $this->writeProgressWithColor('fg-yellow, bold', 'W');
        $this->lastTestFailed = true;
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
        $this->writeProgressWithColor('fg-yellow, bold', 'I');
        $this->lastTestFailed = true;
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
        $this->writeProgressWithColor('fg-yellow, bold', 'R');
        $this->lastTestFailed = true;
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
        $this->writeProgressWithColor('fg-cyan, bold', 'S');
        $this->lastTestFailed = true;
    }

    /**
     * A testsuite started.
     *
     * @param \PHPUnit\Framework\TestSuite $suite
     */
    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
        if ($this->numTests == -1) {
            $this->numTests      = count($suite);
            $this->numTestsWidth = strlen((string) $this->numTests);
            $this->maxColumn     = $this->numberOfColumns - strlen('  /  (XXX%)') - (2 * $this->numTestsWidth);
        }
    }

    /**
     * A testsuite ended.
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
        if ($this->debug) {
            $this->write(
                sprintf(
                    "\nStarting test '%s'.\n",
                    \PHPUnit\Util\Test::describe($test)
                )
            );
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
        if (!$this->lastTestFailed) {
            $this->writeProgress('.');
        }

        if ($test instanceof \PHPUnit\Framework\TestCase) {
            $this->numAssertions += $test->getNumAssertions();
        } elseif ($test instanceof \PHPUnit\Extensions\PhptTestCase) {
            $this->numAssertions++;
        }

        $this->lastTestFailed = false;

        if ($test instanceof \PHPUnit\Framework\TestCase) {
            if (!$test->hasExpectationOnOutput()) {
                $this->write($test->getActualOutput());
            }
        }
    }

    /**
     * @param string $progress
     */
    protected function writeProgress($progress)
    {
        $this->write($progress);
        $this->column++;
        $this->numTestsRun++;

        if ($this->column == $this->maxColumn
            || $this->numTestsRun == $this->numTests
        ) {
            if ($this->numTestsRun == $this->numTests) {
                $this->write(str_repeat(' ', $this->maxColumn - $this->column));
            }

            $this->write(
                sprintf(
                    ' %' . $this->numTestsWidth . 'd / %' .
                    $this->numTestsWidth . 'd (%3s%%)',
                    $this->numTestsRun,
                    $this->numTests,
                    floor(($this->numTestsRun / $this->numTests) * 100)
                )
            );

            if ($this->column == $this->maxColumn) {
                $this->writeNewLine();
            }
        }
    }

    protected function writeNewLine()
    {
        $this->column = 0;
        $this->write("\n");
    }

    /**
     * Formats a buffer with a specified ANSI color sequence if colors are
     * enabled.
     *
     * @param string $color
     * @param string $buffer
     *
     * @return string
     */
    protected function formatWithColor($color, $buffer)
    {
        if (!$this->colors) {
            return $buffer;
        }

        $codes   = array_map('trim', explode(',', $color));
        $lines   = explode("\n", $buffer);
        $padding = max(array_map('strlen', $lines));
        $styles  = [];

        foreach ($codes as $code) {
            $styles[] = self::$ansiCodes[$code];
        }

        $style = sprintf("\x1b[%sm", implode(';', $styles));

        $styledLines = [];

        foreach ($lines as $line) {
            $styledLines[] = $style . str_pad($line, $padding) . "\x1b[0m";
        }

        return implode("\n", $styledLines);
    }

    /**
     * Writes a buffer out with a color sequence if colors are enabled.
     *
     * @param string $color
     * @param string $buffer
     * @param bool   $lf
     */
    protected function writeWithColor($color, $buffer, $lf = true)
    {
        $this->write($this->formatWithColor($color, $buffer));

        if ($lf) {
            $this->write("\n");
        }
    }

    /**
     * Writes progress with a color sequence if colors are enabled.
     *
     * @param string $color
     * @param string $buffer
     */
    protected function writeProgressWithColor($color, $buffer)
    {
        $buffer = $this->formatWithColor($color, $buffer);
        $this->writeProgress($buffer);
    }

    /**
     * @param int    $count
     * @param string $name
     * @param string $color
     * @param bool   $always
     */
    private function writeCountString($count, $name, $color, $always = false)
    {
        static $first = true;

        if ($always || $count > 0) {
            $this->writeWithColor(
                $color,
                sprintf(
                    '%s%s: %d',
                    !$first ? ', ' : '',
                    $name,
                    $count
                ),
                false
            );

            $first = false;
        }
    }
}
