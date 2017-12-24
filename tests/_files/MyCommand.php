<?php

class MyCommand extends \PHPUnit\TextUI\Command
{
    public function __construct()
    {
        $this->longOptions['my-option='] = 'myHandler';
        $this->longOptions['my-other-option'] = null;
    }

    public function myHandler($value)
    {
        echo __METHOD__ . " $value\n";
    }
}
