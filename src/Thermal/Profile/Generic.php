<?php

namespace Thermal\Profile;

use Thermal\Printer;

class Generic extends Epson
{
    protected function setMode($mode, $enable)
    {
        if ($enable) {
            // enable styles
            if (Printer::STYLE_DOUBLE_WIDTH & $mode) {
                $this->getConnection()->write("\x0E");
            }
            if (Printer::STYLE_DOUBLE_HEIGHT & $mode) {
                $this->getConnection()->write("\ed1");
            }
        } else {
            // disable styles
            if (Printer::STYLE_DOUBLE_HEIGHT & $mode) {
                $this->getConnection()->write("\ed0");
            }
            if (Printer::STYLE_DOUBLE_WIDTH & $mode) {
                $this->getConnection()->write("\x14");
            }
        }
        return $this;
    }

    public function qrcode($data, $size)
    {
        $this->drawQrcode($data, $size);
    }
}
