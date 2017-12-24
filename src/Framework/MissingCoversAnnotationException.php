<?php
namespace PHPUnit\Framework\MissingCoversAnnotationException.php

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Extension to \PHPUnit\Framework\AssertionFailedError to mark a test as risky
 * when it does not have a @covers annotation but is expected to have one.
 */
class MissingCoversAnnotationException extends \PHPUnit\Framework\RiskyTestError
{
}
