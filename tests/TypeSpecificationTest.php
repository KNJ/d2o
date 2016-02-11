<?php
use Wazly\D2O;

class TypeSpecificationTest extends PHPUnit_Framework_TestCase
{
    public function testInsertion()
    {
        $name = 'jnk';
        $d2o = new D2O("mysql:dbname=test;host=localhost", 'root', 'password');
        $sql = 'INSERT INTO users (name) VALUES (:name)';
        $d2o->state($sql)
            ->run([':name' => $name]);
        $sql = 'SELECT name FROM users WHERE id = :id';
        $row = $d2o->state($sql)
            ->run([':id' => [2, 'str']])
            ->pick();
        $this->assertEquals($row->name, $name);
    }
}
