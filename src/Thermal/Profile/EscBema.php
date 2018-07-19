<?php

namespace Thermal\Profile;

use Thermal\Printer;

class EscBema extends EscPOS
{
    protected function setStyle($style, $on)
    {
        if ($this->getFont()['name'] != 'Font C') {
            return parent::setStyle($style, $on);
        }
        if ($on) {
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
        return parent::setStyle($style, $on);
    }

    public function buzzer()
    {
        if ($this->getFont()['name'] != 'Font C') {
            // Bematech changed ESC/POS buzzer command
            $this->getConnection()->write("\e(A\x05\x00ad\x02\x02\x01");
            return $this;
        }
        // ESC/Bema does not have buzzer command working, remove call bellow?
        return parent::buzzer();
    }

    /**
     * @param int $number drawer id
     */
    public function drawer($number, $on, $off)
    {
        if ($this->getFont()['name'] != 'Font C') {
            return parent::drawer($number, $on, $off);
        }
        $index = [
            Printer::DRAWER_1 => "v",
            Printer::DRAWER_2 => "\x80"
        ];
        if (!isset($index[$number])) {
            throw new \Exception(
                sprintf('Drawer %d not available for printer "%s"', $this->getName(), intval($number)),
                404
            );
        }
        $on = max(min((int)($on / 2), 255), 50);
        $this->getConnection()->write("\e" . $index[$number] . chr($on));
        return $this;
    }

    protected function fontChanged($newFont, $oldFont)
    {
        // columns define the command set: ESC/Bema or ESC/POS
        if ($newFont['name'] == 'Font C') {
            $this->getConnection()->write("\x1d\xf950");
            return $this;
        }
        $this->getConnection()->write("\x1d\xf951");
        return parent::fontChanged($newFont, $oldFont);
    }
}
