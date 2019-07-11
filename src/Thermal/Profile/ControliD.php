<?php

namespace Thermal\Profile;

use Thermal\Printer;

class ControliD extends Epson
{
    public function qrcode($data, $size)
    {
        $this->drawQrcode($data, $size);
    }

    protected function setStyle($style, $enable)
    {
        if ($enable) {
            // enable styles
            if (Printer::STYLE_BOLD == $style) {
                $this->getConnection()->write("\eE\x01");
                return $this;
            }
        } else {
            // disable styles
            if (Printer::STYLE_BOLD == $style) {
                $this->getConnection()->write("\eE\x00");
                return $this;
            }
        }
        return parent::setStyle($style, $enable);
    }
}
