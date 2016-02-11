<?php
use Wazly\D2O;

class FormatTest extends PHPUnit_Framework_TestCase
{
    public function testObjectFormat()
    {
        $d2o = new D2O("mysql:dbname=test;host=localhost", 'root', 'password');
        $sql = 'SELECT number, symbol, name FROM elements WHERE number <= 3';
        $rows = $d2o->state($sql)
            ->run()
            ->format();
        $this->assertArraySubset([
            0 => (object)[
                'number' => '1',
                'symbol' => 'H',
                'name' => 'hydrogen',
            ],
            1 => (object)[
                'number' => '2',
                'symbol' => 'He',
                'name' => 'helium',
            ],
            2 => (object)[
                'number' => '3',
                'symbol' => 'Li',
                'name' => 'lithium',
            ],
        ], $rows);
    }
}
