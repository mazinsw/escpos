<?php

namespace Thermal\Profile;

use Thermal\Printer;

class Daruma extends EscPOS
{
    /**
     * @param int $number drawer id
     * @param int $on_time time in milliseconds that activate the drawer
     * @param int $off_time time in milliseconds that deactivate the drawer
     */
    public function drawer($number, $on_time, $off_time)
    {
        $index = [
            Printer::DRAWER_1 => "p"
        ];
        if (!isset($index[$number])) {
            throw new \Exception(
                sprintf('Drawer %d not available for printer "%s"', $this->getName(), intval($number)),
                404
            );
        }
        $this->getConnection()->write("\e" . $index[$number]);
        return $this;
    }

    protected function setAlignment($align)
    {
        $cmd = [
            Printer::ALIGN_LEFT => "\ej0",
            Printer::ALIGN_CENTER => "\ej1",
            Printer::ALIGN_RIGHT => "\ej2"
        ];
        $this->getConnection()->write($cmd[$align]);
    }

    protected function setStyle($style, $enable)
    {
        if ($enable) {
            // enable styles
            if (Printer::STYLE_BOLD == $style) {
                $this->getConnection()->write("\eE");
                return $this;
            }
        } else {
            // disable styles
            if (Printer::STYLE_BOLD == $style) {
                $this->getConnection()->write("\eF");
                return $this;
            }
        }
        return parent::setStyle($style, $enable);
    }

    protected function setMode($mode, $enable)
    {
        if ($enable) {
            // enable styles
            if (Printer::STYLE_DOUBLE_WIDTH & $mode) {
                $this->getConnection()->write("\x0E");
            }
            if (Printer::STYLE_DOUBLE_HEIGHT & $mode) {
                $this->getConnection()->write("\ew1");
            }
        } else {
            // disable styles
            if (Printer::STYLE_DOUBLE_HEIGHT & $mode) {
                $this->getConnection()->write("\ew0");
            }
            if (Printer::STYLE_DOUBLE_WIDTH & $mode) {
                $this->getConnection()->write("\x14");
            }
        }
        return $this;
    }

    protected function fontChanged($new_font, $old_font)
    {
        if ($new_font['name'] == 'Font A') {
            $this->getConnection()->write("\e\xC6XXXXXXXXXX0XXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
        } elseif ($new_font['name'] == 'Font B') {
            $this->getConnection()->write("\e\xC6XXXXXXXXXX1XXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
        }
        return $this;
    }

    public function feed($lines)
    {
        if ($lines > 1) {
            $this->getConnection()->write(\str_repeat("\r\n", $lines));
        } else {
            $this->getConnection()->write("\r\n");
        }
        return $this;
    }

    protected function getBitmapCmd()
    {
        return "\e*m";
    }
}
