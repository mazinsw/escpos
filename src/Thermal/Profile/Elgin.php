<?php

namespace Thermal\Profile;

use Thermal\Printer;

class Elgin extends EscPOS
{
    public function cutter($mode)
    {
        if ($mode == Printer::CUT_FULL) {
            $this->getConnection()->write("\ew");
            return $this;
        }
        return parent::cutter($mode);
    }

    public function buzzer()
    {
        $this->getConnection()->write("\e(A\x04\x00\x01\xFF\x00\xFF");
        return $this;
    }

    /**
     * @param int $number drawer id
     * @param int $on_time time in milliseconds that activate the drawer
     * @param int $off_time time in milliseconds that deactivate the drawer
     */
    public function drawer($number, $on_time, $off_time)
    {
        $index = [
            Printer::DRAWER_1 => "v"
        ];
        if (!isset($index[$number])) {
            throw new \Exception(
                sprintf('Drawer %d not available for printer "%s"', $this->getName(), intval($number)),
                404
            );
        }
        $on_time = max(min($on_time, 200), 50);
        $this->getConnection()->write("\e" . $index[$number] . chr($on_time));
        return $this;
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
                $this->getConnection()->write("\eW\x01");
            }
            if (Printer::STYLE_DOUBLE_HEIGHT & $mode) {
                $this->getConnection()->write("\ed\x01");
            }
        } else {
            // disable styles
            if (Printer::STYLE_DOUBLE_HEIGHT & $mode) {
                $this->getConnection()->write("\ed\x00");
            }
            if (Printer::STYLE_DOUBLE_WIDTH & $mode) {
                $this->getConnection()->write("\eW\x00");
            }
        }
        return $this;
    }
}
