<?php
namespace Wazly\D2O;

use PHPUnit_Framework_TestCase;

class D2OReady extends PHPUnit_Framework_TestCase
{
    protected $d2o;

    public function __construct()
    {
        $this->d2o = new \Wazly\D2O("mysql:dbname=test;host=localhost", 'root', 'password');
    }
}
