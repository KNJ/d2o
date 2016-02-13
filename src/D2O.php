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

    public function bind($input_parameters, $auto = true)
    {
        foreach ($input_parameters as $key => $value) {
            if (is_null($value)) {
                $value = [null];
            } else {
                $value = (array) $value;
            }
            if (is_int($key)) {
                $key = $key + 1;
            } else if (substr($key, 0, 1) !== ':') {
                $key = ':' . $key;
            }
            if (!isset($value[1])) {
                if ($auto) {
                    if (is_string($value[0])) {
                        $value[1] = 'str';
                    } else if (is_int($value[0])) {
                        $value[1] = 'int';
                    } else if (is_null($value[0])) {
                        $value[1] = 'null';
                    } else {
                        $value[1] = 'str';
                    }
                } else {
                    $value[1] = 'str';
                }
            }
            $this->stmt->bindValue($key, $value[0], $this->types[$value[1]]);
        }
        return $this;
    }

    public function run($input_parameters = false, $auto = true)
    {
        if ($input_parameters) {
            $this->bind($input_parameters, $auto);
        }
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
}
