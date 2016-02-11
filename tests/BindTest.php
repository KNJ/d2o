<?php
use Wazly\D2O;

class BindTest extends D2OReady
{
    public function testBind()
    {
        $name = 'hydrogen';
        $sql = 'SELECT * FROM elements WHERE name = :name';
        $row = $this->d2o->state($sql)
            ->bind([':name' => $name])
            ->run()
            ->pick();
        $this->assertEquals($row->name, $name);

        $name = 'helium';
        $row = $this->d2o
            ->run()
            ->pick();
        $this->assertNotEquals($row->name, $name);

        $row = $this->d2o
            ->bind([':name' => $name])
            ->run()
            ->pick();
        $this->assertEquals($row->name, $name);

        $name = 'lithium';
        $this->d2o->bind([':name' => $name]);
        $name = 'berylium';
        $row = $this->d2o->run()->pick();
        $this->assertNotEquals($row->name, $name);

        unset($name);
        $this->assertEquals($row->name, 'lithium');

        $name = 'boron';
        $row = $this->d2o
            ->run([':name' => $name])
            ->pick();
        $this->assertEquals($row->name, $name);

        // last bindings are reserved
        $row = $this->d2o->run()->pick();
        $this->assertEquals($row->name, $name);

        // D2O::run() didn't call PDOStatement::bindParam() but PDOStatement::bindValue()
        unset($name);
        $row = $this->d2o->run()->pick();
        $this->assertEquals($row->name, 'boron');
    }
}
