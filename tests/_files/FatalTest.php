<?php

class FatalTest extends \PHPUnit\Framework\TestCase
{
    public function testFatalError()
    {
        if (extension_loaded('xdebug')) {
            xdebug_disable();
        }

        eval('class FatalTest {}');
    }
}
