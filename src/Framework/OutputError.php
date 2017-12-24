<?php
namespace PHPUnit\Framework\OutputError.php

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Extension to \PHPUnit\Framework\AssertionFailedError to mark the special
 * case of a test that printed output.
 */
class OutputError extends \PHPUnit\Framework\AssertionFailedError
{
}
