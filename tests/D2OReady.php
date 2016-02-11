<?php
use Wazly\D2O;

class D2OReady extends PHPUnit_Framework_TestCase
{
    protected $d2o;

    public function __construct()
    {
        $this->d2o = new D2O("mysql:dbname=test;host=localhost", 'root', 'password');
    }
}
