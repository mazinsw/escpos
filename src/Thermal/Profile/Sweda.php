<?php

namespace Thermal\Profile;

class Sweda extends Elgin
{
    public function buzzer()
    {
        $this->getConnection()->write("\x07");
        return $this;
    }
}
