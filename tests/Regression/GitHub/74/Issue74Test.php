<?php
class Issue74Test extends \PHPUnit\Framework\TestCase
{
    public function testCreateAndThrowNewExceptionInProcessIsolation()
    {
        require_once __DIR__ . '/NewException.php';
        throw new NewException('Testing GH-74');
    }
}
