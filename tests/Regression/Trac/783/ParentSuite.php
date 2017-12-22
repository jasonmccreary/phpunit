<?php
require_once 'ChildSuite.php';

class ParentSuite
{
    public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite('Parent');
        $suite->addTest(ChildSuite::suite());

        return $suite;
    }
}
