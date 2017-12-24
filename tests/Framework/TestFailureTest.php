<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Framework_TestFailureTest extends \PHPUnit\Framework\TestCase
{
    public function testToString()
    {
        $test      = new self(__FUNCTION__);
        $exception = new \PHPUnit\Framework\Exception('message');
        $failure   = new \PHPUnit\Framework\TestFailure($test, $exception);

        $this->assertEquals(__METHOD__ . ': message', $failure->toString());
    }
}
