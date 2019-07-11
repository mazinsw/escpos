<?php

namespace Thermal\Profile;

use Thermal\Printer;
use Thermal\Buffer\Encoding;
use Thermal\Connection\Connection;
use Endroid\QrCode\QrCode;
use Thermal\Graphics\Image;
use Thermal\Graphics\Filter\Threshold;

abstract class Profile
{
    /**
     * Column count
     *
     * @var int
     */
    private $columns;

    /**
     * Model capabilities
     *
     * @var array
     */
    protected $capabilities;

    /**
     * Connection or output buffer
     *
     * @var \Thermal\Connection\Connection
     */
    private $connection;

    /**
     * Font name A, C or C
     *
     * @var string
     */
    private $font;

    /**
     * Printer default font name
     *
     * @var string
     */
    private $default_font;

    /**
     * Encoding
     *
     * @var \Thermal\Buffer\Encoding
     */
    private $encoding;

    /**
     * Profile constructor
     *
     * @param array $capabilities
     */
    public function __construct($capabilities)
    {
        $this->capabilities = $capabilities;
        $this->columns = $this->getDefaultColumns();
        $this->default_font = $this->findDefaultFont();
        $this->font = $this->getDefaultFont();
        $this->connection = null;
        $this->encoding = new Encoding($this->getDefaultCodePage());
    }

    public function getName()
    {
        $name = isset($this->capabilities['name']) ? $this->capabilities['name'] : $this->capabilities['model'];
        return $this->capabilities['brand'] . ' ' . $name;
    }

    /**
     * Printer encoding
     *
     * @return \Thermal\Buffer\Encoding
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Default column count
     *
     * @return int
     */
    public function getDefaultColumns()
    {
        return $this->capabilities['columns'];
    }

    /**
     * Column count for printing
     *
     * @return int
     */
    public function getColumns()
    {
        return $this->columns;
    }

    public function setColumns($columns)
    {
        $font = ['name' => 'Unknow'];
        // search for font with more columns
        // font list must be sorted by their number of columns
        foreach ($this->capabilities['fonts'] as $font) {
            if ($columns <= $font['columns']) {
                break;
            }
        }
        $this->setFont($font);
        $this->columns = $columns;
        return $this;
    }

    public function getDefaultFont()
    {
        return $this->default_font;
    }

    private function findDefaultFont()
    {
        foreach ($this->capabilities['fonts'] as $font) {
            if ($this->getDefaultColumns() == $font['columns']) {
                return $font;
            }
        }
        throw new \Exception(
            sprintf(
                'Default font with %d columns not found for printer "%s"',
                $this->getDefaultColumns(),
                $this->getName()
            ),
            404
        );
    }

    public function getCodePages()
    {
        return array_keys($this->capabilities['codepages']);
    }

    public function getDefaultCodePage()
    {
        return $this->capabilities['codepage'];
    }

    protected function checkCodePage($codepage)
    {
        if (!isset($this->capabilities['codepages'][$codepage])) {
            throw new \Exception(
                sprintf(
                    'Codepage "%s" not supported for printer "%s"',
                    $codepage,
                    $this->getName()
                ),
                401
            );
        }
        return $this;
    }

    public function setCodePage($codepage)
    {
        $this->getEncoding()->setCodePage($codepage);
        $this->checkCodePage($codepage);
        $this->getConnection()->write($this->capabilities['codepages'][$codepage]);
    }

    public function getFont()
    {
        return $this->font;
    }

    public function setFont($font)
    {
        $found = false;
        $_font = ['name' => 'Unknow'];
        foreach ($this->capabilities['fonts'] as $_font) {
            if ($_font['name'] == $font['name']) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            throw new \Exception(
                sprintf(
                    'Font "%s" not found for printer "%s"',
                    $font['name'],
                    $this->getName()
                ),
                404
            );
        }
        if ($this->font['name'] != $_font['name']) {
            $this->fontChanged($_font, $this->font['name']);
            $this->refresh();
        }
        $this->font = $_font;
        return $this;
    }

    protected function refresh()
    {
        // ensure current codepage
        $this->setCodePage($this->getEncoding()->getCodePage());
        return $this;
    }

    protected function fontChanged($new_font, $old_font)
    {
    }

    /**
     * Current connection
     *
     * @return \Thermal\Connection\Connection
     */
    public function getConnection()
    {
        if ($this->connection instanceof Connection) {
            return $this->connection;
        }
        throw new \Exception('Connection must be set before priting', 500);
    }

    /**
     * Set current connection
     *
     * @param \Thermal\Connection\Connection $connection
     * @return self
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
        return $this;
    }

    public function initialize()
    {
        if (isset($this->capabilities['initialize'])) {
            // ensure defaults
            $this->getConnection()->write($this->capabilities['initialize']);
        }
        $this->refresh();
    }

    public function finalize()
    {
    }

    public function write($text, $styles, $align)
    {
        if ($align !== null) {
            $this->setAlignment($align);
        }
        $this->setMode($styles, true);
        $this->setStyle(Printer::STYLE_CONDENSED & $styles, true);
        $this->setStyle(Printer::STYLE_BOLD & $styles, true);
        $this->setStyle(Printer::STYLE_ITALIC & $styles, true);
        $this->setStyle(Printer::STYLE_UNDERLINE & $styles, true);
        $this->getConnection()->write($this->getEncoding()->encode($text, 'UTF-8'));
        $this->setStyle(Printer::STYLE_UNDERLINE & $styles, false);
        $this->setStyle(Printer::STYLE_ITALIC & $styles, false);
        $this->setStyle(Printer::STYLE_BOLD & $styles, false);
        $this->setStyle(Printer::STYLE_CONDENSED & $styles, false);
        $this->setMode($styles, false);
        // reset align to left
        if ($align !== null && $align != Printer::ALIGN_LEFT) {
            $this->setAlignment(Printer::ALIGN_LEFT);
        }
        return $this;
    }

    protected function getBitmapCmd()
    {
        return "\e*!";
    }

    public function draw($image)
    {
        $width = $image->getWidth();
        $low = $width & 0xFF;
        $high = ($width >> 8) & 0xFF;
        $this->getConnection()->write("\e3\x10");
        for ($i=0; $i < $image->getLines(); $i++) {
            $data = $image->getLineData($i);
            $this->getConnection()->write($this->getBitmapCmd() . chr($low) . chr($high) . $data . "\eJ\x00");
        }
        $this->getConnection()->write("\e2");
        return $this;
    }

    protected function drawQrcode($data, $size)
    {
        $qrCode = new QrCode($data);
        $qrCode->setSize( min(11, max(1, $size ?: 4)) * 50);
        $image = new Image(
            [
                'data' => $qrCode->writeString(),
                'name' => 'qrcode.png',
            ],
            new Threshold()
        );
        $this->draw($image);
    }

    abstract public function feed($lines);
    abstract public function cutter($mode);
    abstract public function buzzer();

    /**
     * @param int $number drawer id
     * @param int $on_time time in milliseconds that activate the drawer
     * @param int $off_time time in milliseconds that deactivate the drawer
     */
    abstract public function drawer($number, $on_time, $off_time);

    abstract public function setAlignment($align);
    abstract protected function setMode($mode, $enable);
    abstract protected function setStyle($style, $enable);
}
