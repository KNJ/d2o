<?php
namespace Wazly;

use PHPUnit_Framework_TestCase;

class ConnectionTest extends PHPUnit_Framework_TestCase
{
    public function testConnect()
    {
        $dbh = new D2O("mysql:dbname=test;host=localhost", 'root', 'password');
        $sql = 'SELECT * FROM users WHERE id = 1';
        $result = $dbh
            ->state($sql)
            ->run()
            ->pick()
            ->name;
        $this->assertEquals('knj', $result);
    }
}
