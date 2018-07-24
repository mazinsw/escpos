<?php

namespace Thermal\Profile;

use Thermal\Printer;

class EscBema extends EscPOS
{
    protected function setStyle($style, $enable)
    {
        if ($this->getFont()['name'] != 'Font C') {
            return parent::setStyle($style, $enable);
        }
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
     * @param int $on_time time in milliseconds that activate the drawer
     * @param int $off_time time in milliseconds that deactivate the drawer
     */
    public function drawer($number, $on_time, $off_time)
    {
        if ($this->getFont()['name'] != 'Font C') {
            return parent::drawer($number, $on_time, $off_time);
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
        $on_time = max(min($on_time, 255), 50);
        $this->getConnection()->write("\e" . $index[$number] . chr($on_time));
        return $this;
    }

    protected function fontChanged($new_font, $old_font)
    {
        // columns define the command set: ESC/Bema or ESC/POS
        if ($new_font['name'] == 'Font C') {
            $this->getConnection()->write("\x1d\xf950");
            return $this;
        }
        $this->getConnection()->write("\x1d\xf951");
        return parent::fontChanged($new_font, $old_font);
    }
}
