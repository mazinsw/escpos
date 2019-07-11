<?php

namespace Thermal\Profile;

use Thermal\Printer;

class Diebold extends Elgin
{
    public function buzzer()
    {
        $this->getConnection()->write("\x07");
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
            Printer::DRAWER_1 => '0'
        ];
        if (!isset($index[$number])) {
            throw new \Exception(
                sprintf('Drawer %d not available for printer "%s"', $this->getName(), intval($number)),
                404
            );
        }
        $on_time = min((int)($on_time / 2), 65);
        $off_time = min((int)($off_time / 2), 65);
        $this->getConnection()->write("\e&" . $index[$number] . chr($on_time) . chr($off_time));
        return $this;
    }
}
