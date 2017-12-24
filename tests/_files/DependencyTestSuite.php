<?php
class DependencyTestSuite
{
    public static function suite()
    {
        $suite = new \PHPUnit\Framework\TestSuite('Test Dependencies');

        $suite->addTestSuite('DependencySuccessTest');
        $suite->addTestSuite('DependencyFailureTest');

        return $suite;
    }
}
