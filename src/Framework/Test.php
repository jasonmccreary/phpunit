<?php
namespace PHPUnit\Framework\Test;

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A Test can be run and collect its results.
 */
interface Test extends Countable
{
    /**
     * Runs a test and collects its result in a TestResult instance.
     *
     * @param \PHPUnit\Framework\TestResult $result
     *
     * @return \PHPUnit\Framework\TestResult
     */
    public function run(\PHPUnit\Framework\TestResult $result = null);
}
