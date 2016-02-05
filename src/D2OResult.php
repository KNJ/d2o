<?php
namespace Wazly;

use PDO;

class D2OResult
{
    protected $result;

    public function __construct($statement)
    {
        $this->result = $statement;
    }

    public function format($style = 'object')
    {
        return $this->result->fetchAll(
            [
                'array' => PDO::FETCH_ASSOC,
                'number' => PDO::FETCH_NUM,
                'object' => PDO::FETCH_OBJ,
            ]
            [$style]
        );
    }
}
