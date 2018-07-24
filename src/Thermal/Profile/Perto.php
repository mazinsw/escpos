<?php

namespace Thermal\Profile;

use Thermal\Printer;

class Perto extends Elgin
{
    public function cutter($mode)
    {
        $this->getConnection()->write("\eJ\x18\x1dVB(");
        return $this;
    }
}
