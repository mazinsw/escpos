<?php

namespace Thermal\Profile;

class Perto extends Elgin
{
    public function buzzer()
    {
        $this->getConnection()->write("\x07");
        return $this;
    }

    public function cutter($mode)
    {
        $this->getConnection()->write("\eJ\x18\x1dVB(");
        return $this;
    }
}
