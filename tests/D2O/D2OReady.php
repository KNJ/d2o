<?php
namespace Wazly\D2O;

use Wazly\D2O;
use PHPUnit_Framework_TestCase;

class D2OReady extends PHPUnit_Framework_TestCase
{
    protected $d2o;

    public function __construct()
    {
        $db_conf_path = __DIR__ . '/../../db_config.php';
        if (is_file($db_conf_path)) {
            $db = include $db_conf_path;
        } else {
            $db = include __DIR__ . '/../../db_config.example.php';
        }

        $this->d2o = new D2O(
            'mysql:dbname=' . $db['name'] . ';host=' . $db['host'],
            $db['user'],
            $db['password']
        );
    }
}
