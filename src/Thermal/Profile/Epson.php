<?php

namespace Thermal\Profile;

use Thermal\Printer;

class Epson extends Profile
{
    public function setAlignment($align)
    {
        $cmd = [
            Printer::ALIGN_LEFT => "\ea0",
            Printer::ALIGN_CENTER => "\ea1",
            Printer::ALIGN_RIGHT => "\ea2"
        ];
        $this->getConnection()->write($cmd[$align]);
    }

    protected function setStyle($style, $enable)
    {
        if ($enable) {
            // enable styles
            if (Printer::STYLE_CONDENSED == $style) {
                $this->getConnection()->write("\e\x0f");
            } elseif (Printer::STYLE_BOLD == $style) {
                $this->getConnection()->write("\eE1");
            } elseif (Printer::STYLE_ITALIC == $style) {
                $this->getConnection()->write("\e4");
            } elseif (Printer::STYLE_UNDERLINE == $style) {
                $this->getConnection()->write("\e-1");
            }
        } else {
            // disable styles
            if (Printer::STYLE_UNDERLINE == $style) {
                $this->getConnection()->write("\e-0");
            } elseif (Printer::STYLE_ITALIC == $style) {
                $this->getConnection()->write("\e5");
            } elseif (Printer::STYLE_BOLD == $style) {
                $this->getConnection()->write("\eE0");
            } elseif (Printer::STYLE_CONDENSED == $style) {
                $this->getConnection()->write("\x12");
            }
        }
    }

    protected function setMode($mode, $enable)
    {
        $byte = 0b00000000; // keep Font A selected
        if ($this->getFont()['name'] == 'Font B') {
            $byte |= 0b00000001; // keep Font B selected
        }
        $before = $byte;
        if (Printer::STYLE_DOUBLE_HEIGHT & $mode) {
            $byte |= 0b00010000;
        }
        if (Printer::STYLE_DOUBLE_WIDTH & $mode) {
            $byte |= 0b00100000;
        }
        if ($enable) {
            $mask = 0b00110001;
        } else {
            $mask = 0b00000001;
        }
        if ($before != $byte) {
            $this->getConnection()->write("\e!" . chr($byte & $mask));
        }
    }

    public function feed($lines)
    {
        if ($lines > 1) {
            $count = (int)($lines / 255);
            $cmd = \str_repeat("\ed" . chr(min($lines, 255)), $count);
            $remaining = $lines - $count * 255;
            if ($remaining > 0) {
                $cmd .= "\ed" . chr($remaining);
            }
            $this->getConnection()->write($cmd);
        } else {
            $this->getConnection()->write("\r\n");
        }
        return $this;
    }

    public function buzzer()
    {
        $this->getConnection()->write("\x07");
        return $this;
    }

    public function cutter($mode)
    {
        // only partial cut
        $this->getConnection()->write("\em");
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
            Printer::DRAWER_1 => 0,
            Printer::DRAWER_2 => 1
        ];
        if (!isset($index[$number])) {
            throw new \Exception(
                sprintf('Drawer %d not available for printer "%s"', $this->getName(), intval($number)),
                404
            );
        }
        $on_time = min((int)($on_time / 2), 255);
        $off_time = min((int)($off_time / 2), 255);
        $this->getConnection()->write("\ep" . chr($index[$number]) . chr($on_time) . chr($off_time));
        return $this;
    }

    public function qrcode($data, $size)
    {
        $tipo = '2';
        $size = $size ?: 4;
        $error = '0';
        $len = strlen($data) + 3;
        $pL = $len & 0xFF;
        $pH = ($len >> 8) & 0xFF;
        $this->getConnection()->write("\x1d(k\x04\x001A" . $tipo . "\x00");
        $this->getConnection()->write("\x1d(k\x03\x001C" . chr($size));
        $this->getConnection()->write("\x1d(k\x03\x001E" . $error);
        $this->getConnection()->write("\x1d(k" . chr($pL) . chr($pH) . "1P0");
        $this->getConnection()->write($data);
        $this->getConnection()->write("\x1d(k\x03\x001Q0");
    }

    protected function fontChanged($new_font, $old_font)
    {
        if ($new_font['name'] == 'Font A') {
            $this->getConnection()->write("\eM\x00");
        } elseif ($new_font['name'] == 'Font B') {
            $this->getConnection()->write("\eM\x01");
        }
        parent::fontChanged($new_font, $old_font);
    }
}
