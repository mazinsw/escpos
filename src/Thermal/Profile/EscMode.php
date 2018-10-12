<?php

namespace Thermal\Profile;

use Thermal\Printer;

class EscMode extends EscPOS
{
    protected function fontChanged($new_font, $old_font)
    {
        return parent::fontChanged($new_font, $old_font);
    }
}
