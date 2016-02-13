<?php
namespace Wazly\D2O;

class TypeSpecificationTest extends D2OReady
{
    public function testLimitedSelection()
    {
        $limit = 4;
        $sql = 'SELECT symbol FROM elements LIMIT :lim';
        $count = $this->d2o->state($sql)
            ->run(['lim' => $limit])
            ->format();
        $this->assertEquals(sizeof($count), $limit);

        $count = $this->d2o->run(['lim' => $limit], false)->format();
        $this->assertNotEquals(sizeof($count), $limit);

        $count = $this->d2o->run(['lim' => [$limit, 'int']], false)->format();
        $this->assertEquals(sizeof($count), $limit);

        $count = $this->d2o->run(['lim' => (string) $limit])->format();
        $this->assertNotEquals(sizeof($count), $limit);

        $count = $this->d2o->run(['lim' => $limit])->format();
        $this->assertEquals(sizeof($count), $limit);
    }

    public function testInsertion()
    {
        $name = 'jnk';
        $sql = 'INSERT INTO users (name) VALUES (:name)';
        $this->d2o->state($sql)
            ->run(['name' => $name]);
        $sql = 'SELECT name FROM users WHERE id = :id';
        $row = $this->d2o->state($sql)
            ->run(['id' => [2, 'str']])
            ->pick();
        $this->assertEquals($row->name, $name);
    }

    public function testNullInsertion()
    {
        $sql = 'INSERT INTO nulls (value) VALUES (:value)';
        $this->d2o->state($sql)
            ->run(['value' => null])
            ->run(['value' => 'null'])
            ->run(['value' => 'NULL'])
            ->run(['value' => null], false)
            ->run(['value' => 'null'], false)
            ->run(['value' => 'NULL'], false);
        $sql = 'SELECT value FROM nulls';
        $rows = $this->d2o->state($sql)
            ->run()
            ->format();
        $this->assertArraySubset([
            0 => (object) [
                'value' => null,
            ],
            1 => (object) [
                'value' => 'null',
            ],
            2 => (object) [
                'value' => 'NULL',
            ],
            3 => (object) [
                'value' => null,
            ],
            4 => (object) [
                'value' => 'null',
            ],
            5 => (object) [
                'value' => 'NULL',
            ],
        ], $rows);
    }
}
