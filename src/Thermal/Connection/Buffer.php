<?php

namespace Thermal\Connection;

class Buffer implements Connection
{
    private $buffer;

    
    public function open()
    {
        $this->clear();
    }

    public function write($data)
    {
        $this->buffer .= $data;
        return strlen($data);
    }

    public function close()
    {
    }

    public function clear()
    {
        $this->buffer = null;
    }

    public function getBuffer()
    {
        return $this->buffer;
    }
}
