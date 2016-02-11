<?php
namespace Wazly;

use PDO;

class D2O extends PDO
{
    protected $stmt; // PDOStatement
    protected $types = [
        'str' => PDO::PARAM_STR,
        'bool' => PDO::PARAM_BOOL,
        'null' => PDO::PARAM_NULL,
        'int' => PDO::PARAM_INT,
        'lob' => PDO::PARAM_LOB,
        'stmt' => PDO::PARAM_STMT,
        'input_output' => PDO::PARAM_INPUT_OUTPUT,
    ];
    protected $styles = [
        'a' => PDO::FETCH_ASSOC,
        'arr' => PDO::FETCH_ASSOC,
        'ary' => PDO::FETCH_ASSOC,
        'array' => PDO::FETCH_ASSOC,
        'assoc' => PDO::FETCH_ASSOC,
        'association' => PDO::FETCH_ASSOC,
        'n' => PDO::FETCH_NUM,
        'num' => PDO::FETCH_NUM,
        'number' => PDO::FETCH_NUM,
        'o' => PDO::FETCH_OBJ,
        'obj' => PDO::FETCH_OBJ,
        'object' => PDO::FETCH_OBJ,
    ];

    public function state($statement, $driver_options = [])
    {
        $this->stmt = $this->prepare($statement, $driver_options);
        return $this;
    }

    public function bind($input_parameters, $type = 'value')
    {
        foreach ($input_parameters as $key => $value) {
            $value = (array) $value;
            if (!isset($value[1])) {
                $value[1] = 'str';
            }
            if ($type === 'param') {
                $this->stmt->bindParam($key, $value[0], $this->types[$value[1]]);
            } else {
                $this->stmt->bindValue($key, $value[0], $this->types[$value[1]]);
            }
        }
        return $this;
    }

    public function run($input_parameters = [], $type = 'value')
    {
        $this->bind($input_parameters, $type);
        $this->stmt->execute();
        return $this;
    }

    public function pick($style = 'object', $options = [])
    {
        return $this->stmt->fetch($this->styles[$style]);
    }

    public function format($style = 'object', $options = [
        'key' => false,
    ])
    {
        if ($style === 'group' || $style === 'g') {
            $rows = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            if (!empty($rows)) {
                $key = $options['key'] ? $options['key'] : key(array_slice($rows[0], 0, 1));
                foreach ($rows as $row) {
                    $result[$row[$key]] = (object)$row;
                    unset($result[$row[$key]]->$key);
                }
            }
            return $result;
        }
        return $this->stmt->fetchAll($this->styles[$style]);
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
