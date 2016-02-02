<?php
namespace Wazly;

class D2O extends PDO
{
    public $message;

    public function say()
    {
        $this->message = 'Hello';
        return $this->message;
    }
}
