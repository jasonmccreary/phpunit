<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Wrapper for PHP notices.
 * You can disable notice-to-exception conversion by setting
 *
 * <code>
 * \PHPUnit\Framework\Error\Notice::$enabled = false;
 * </code>
 */
class \PHPUnit\Framework\Error\Notice extends \PHPUnit\Framework\Error
{
    public static $enabled = true;
}
