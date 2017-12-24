<?php
namespace PHPUnit\Framework\Error\Deprecated.php

/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Wrapper for PHP deprecated errors.
 * You can disable deprecated-to-exception conversion by setting
 *
 * <code>
 * \PHPUnit\Framework\Error\Deprecated::$enabled = false;
 * </code>
 */
class Deprecated extends \PHPUnit\Framework\Error
{
    public static $enabled = true;
}
