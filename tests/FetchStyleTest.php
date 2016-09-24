<?php
namespace Wazly\D2O;

class FetchStyleTest extends D2OReady
{
    public function testFetchClass() {
        $row = $this->d2o
            ->state('SELECT * FROM elements')
            ->run()
            ->pick('class', Element::class);
        $this->assertTrue($row instanceof Element);
        $ref = new \ReflectionClass($row);
        $this->assertTrue($ref->getProperty('number')->isPrivate());
        $this->assertTrue($ref->getProperty('symbol')->isProtected());
        $this->assertTrue($ref->getProperty('name')->isPublic());
        $this->assertFalse($ref->hasProperty('is_metal'));
        $this->assertSame('0', $row->is_metal);
    }
}

class Element
{
    private $number;
    protected $symbol;
    public $name;
}
