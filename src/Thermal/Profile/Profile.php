<?php

namespace Thermal\Profile;

use Thermal\Printer;
use Thermal\Buffer\Encoding;

abstract class Profile
{
    private $columns;
    protected $capabilities;
    private $connection;
    private $font;
    private $default_font;
    private $encoding;

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
        return $this->capabilities['name'];
    }

    public function getDefaultColumns()
    {
        return $this->capabilities['columns'];
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setColumns($columns)
    {
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
        return array_keys($this->capabilities['profile']['codepages']);
    }

    public function getDefaultCodePage()
    {
        return $this->capabilities['codepage'];
    }

    protected function checkCodePage($codepage)
    {
        if (!isset($this->capabilities['profile']['codepages'][$codepage])) {
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
        $this->encoding->setCodePage($codepage);
        $this->checkCodePage($codepage);
        $this->getConnection()->write($this->capabilities['profile']['codepages'][$codepage]);
    }

    public function getFont()
    {
        return $this->font;
    }

    public function setFont($font)
    {
        $found = false;
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
        }
        $this->font = $_font;
        return $this;
    }

    protected function fontChanged($newFont, $oldFont)
    {
        // ensure current codepage
        $this->setCodePage($this->encoding->getCodePage(true));
        return $this;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
        return $this;
    }

    public function initialize()
    {
        if (isset($this->capabilities['profile']['initialize'])) {
            // ensure defaults
            $this->getConnection()->write($this->capabilities['profile']['initialize']);
        }
        // ensure default codepage
        $this->setCodePage($this->getDefaultCodePage());
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
        $this->getConnection()->write($this->encoding->encode($text, 'UTF-8'));
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

    abstract public function feed($lines);
    abstract public function cutter($mode);
    abstract public function drawer($number, $on, $off);

    abstract protected function setAlignment($align);
    abstract protected function setMode($mode, $on);
    abstract protected function setStyle($style, $on);
}
