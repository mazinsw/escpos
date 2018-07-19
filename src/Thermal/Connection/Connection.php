<?php

namespace Thermal\Connection;

interface Connection
{
    public function open();
    public function write($data);
    public function close();
}
