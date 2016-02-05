<?php
namespace Wazly;

use PDO;

require_once __DIR__ . '/D2OResult.php';

class D2O extends PDO
{
    protected $stmt; // PDOStatement
    protected $result; // D2OResult

    public function state($statement, $driver_options = [])
    {
        $this->stmt = $this->prepare($statement, $driver_options);
        return $this;
    }

    public function bind($input_parameters, $type = 'value')
    {
        foreach ($input_parameters as $key => $value) {
            $value = (array) $value;
            if (isset($value[1])) {
                if (strtoupper($value[1]) === 'BOOL') {
                    $value[1] = PDO::PARAM_BOOL;
                } else if (strtoupper($value[1]) === 'NULL') {
                    $value[1] = PDO::PARAM_NULL;
                } else if (strtoupper($value[1]) === 'INT') {
                    $value[1] = PDO::PARAM_INT;
                } else if (strtoupper($value[1]) === 'LOB') {
                    $value[1] = PDO::PARAM_LOB;
                } else if (strtoupper($value[1]) === 'STMT') {
                    $value[1] = PDO::PARAM_STMT;
                } else if (strtoupper($value[1]) === 'INPUT_OUTPUT') {
                    $value[1] = PDO::PARAM_INPUT_OUTPUT;
                }
            } else {
                $value[1] = PDO::PARAM_STR;
            }
            if ($type === 'param') {
                $this->stmt->bindParam($key, $value[0], $value[1]);
            } else {
                $this->stmt->bindValue($key, $value[0], $value[1]);
            }
        }
        return $this;
    }

    public function run()
    {
        $this->stmt->execute();
        return new D2OResult($this->stmt);
    }

    public function execute()
    {
        $this->stmt->execute();
        return $this->stmt;
    }

    public function find()
    {
        $result = $this->execute();
        return $result->fetchObject();
    }
}
