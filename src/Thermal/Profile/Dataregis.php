<?php

namespace Thermal\Profile;

class Dataregis extends Elgin
{
    public function buzzer()
    {
        $this->getConnection()->write("\x07");
        return $this;
    }
}
