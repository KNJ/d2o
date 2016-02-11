<?php
use Wazly\D2O;

class FormatTest extends D2OReady
{
    public function testObjectFormat()
    {
        $this->d2o = new D2O("mysql:dbname=test;host=localhost", 'root', 'password');
        $sql = 'SELECT number, symbol, name FROM elements WHERE number <= 3';
        $rows = $this->d2o->state($sql)
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
