<?php
use Wazly\D2O;

class FormatTest extends D2OReady
{
    public function testObjectFormat()
    {
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

    public function testGroupFormat()
    {
        $sql = 'SELECT number, symbol, name FROM elements WHERE number <= 3';

        $rows = $this->d2o->state($sql)
            ->run()
            ->format('group');
        $this->assertArraySubset([
            '1' => (object)[
                'symbol' => 'H',
                'name' => 'hydrogen',
            ],
            '2' => (object)[
                'symbol' => 'He',
                'name' => 'helium',
            ],
            '3' => (object)[
                'symbol' => 'Li',
                'name' => 'lithium',
            ],
        ], $rows);

        $rows = $this->d2o
            ->run()
            ->format('group', ['key' => 'symbol']);
        $this->assertArraySubset([
            'H' => (object)[
                'number' => '1',
                'name' => 'hydrogen',
            ],
            'He' => (object)[
                'number' => '2',
                'name' => 'helium',
            ],
            'Li' => (object)[
                'number' => '3',
                'name' => 'lithium',
            ],
        ], $rows);
    }
}
