<?php
namespace Wazly\D2O;

class TypeSpecificationTest extends D2OReady
{
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
}
