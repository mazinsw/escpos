<?php

namespace Thermal;

class Printer
{
    const ALIGN_LEFT = 0;
    const ALIGN_CENTER = 1;
    const ALIGN_RIGHT = 2;

    const STYLE_BOLD = 1;
    const STYLE_ITALIC = 2;
    const STYLE_UNDERLINE = 4;
    const STYLE_CONDENSED = 8;
    const STYLE_DOUBLE_WIDTH = 16;
    const STYLE_DOUBLE_HEIGHT = 32;

    const CUT_FULL = 0;
    const CUT_PARTIAL = 1;

    const DRAWER_1 = 0;
    const DRAWER_2 = 1;

    private $model;
    private $connection;

    public function __construct($model, $connection)
    {
        $this->connection = $connection;
        $this->connection->open();
        $this->model = $model;
        $this->model->getProfile()->setConnection($this->connection);
        $this->model->getProfile()->initialize();
    }

    public function setCodePage($codepage)
    {
        $this->model->getProfile()->setCodePage($codepage);
        return $this;
    }

    public function buzzer()
    {
        $this->model->getProfile()->buzzer();
        return $this;
    }

    public function cutter($mode = self::CUT_PARTIAL)
    {
        $this->model->getProfile()->cutter($mode);
        return $this;
    }

    /**
     * @param int $number drawer id
     * @param int $on_time time in milliseconds that activate the drawer
     * @param int $off_time time in milliseconds that deactivate the drawer
     */
    public function drawer($number = self::DRAWER_1, $on_time = 120, $off_time = 240)
    {
        $this->model->getProfile()->drawer($number, $on_time, $off_time);
        return $this;
    }

    public function write($text, $styles = 0, $align = self::ALIGN_LEFT)
    {
        $this->model->getProfile()->write($text, $styles, $align);
        return $this;
    }

    public function writeln($text, $styles = 0, $align = self::ALIGN_LEFT)
    {
        if (strlen($text) > 0) {
            $this->write($text, $styles, $align);
        }
        $this->feed();
        return $this;
    }

    public function feed($lines = 1)
    {
        $this->model->getProfile()->feed($lines);
        return $this;
    }

    public function getColumns()
    {
        return $this->model->getProfile()->getColumns();
    }

    /**
     * Set columns aproximated to informed
     * @param int $columns aproximated number of columns
     */
    public function setColumns($columns)
    {
        $this->model->getProfile()->setColumns($columns);
        return $this;
    }

    public function close()
    {
        $this->model->getProfile()->finalize();
        return $this;
    }
}
