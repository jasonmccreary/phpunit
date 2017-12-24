<?php
class IniTest extends \PHPUnit\Framework\TestCase
{
    public function testIni()
    {
        $this->assertEquals('application/x-test', ini_get('default_mimetype'));
    }
}
