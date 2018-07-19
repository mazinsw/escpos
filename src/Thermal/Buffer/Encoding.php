<?php

namespace Thermal\Buffer;

class Encoding
{
    private $codepage;

    public function __construct($codepage)
    {
        $this->setCodePage($codepage);
    }

    public static function getSupportedCodePages()
    {
        $list = array_flip(mb_list_encodings());
        if (isset($list['Windows-1252'])) {
            $list['Windows-1252'] = 'CP1252';
        }
        return $list;
    }

    public function setCodePage($codepage)
    {
        $codes = self::getSupportedCodePages();
        if (!isset($codes[$codepage])) {
            $codes = array_flip($codes);
            if (!isset($codes[$codepage])) {
                throw new \Exception(
                    sprintf('Codepage "%s" not supported by mb_string', $codepage),
                    404
                );
            } else {
                $this->codepage = $codes[$codepage];
            }
        } else {
            $this->codepage = $codepage;
        }
        return $this;
    }

    public function getCodePage($printer_code = false)
    {
        if ($printer_code) {
            $codes = self::getSupportedCodePages();
            if (\is_string($codes[$this->codepage])) {
                return $codes[$this->codepage];
            }
        }
        return $this->codepage;
    }

    public function encode($text, $encoding = null)
    {
        return mb_convert_encoding($text, $this->getCodePage(), $encoding);
    }
}
