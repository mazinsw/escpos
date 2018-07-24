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
        $list = mb_list_encodings();
        return $list;
    }

    public function setCodePage($codepage)
    {
        $codes = self::getSupportedCodePages();
        if (\array_search($codepage, $codes) === false) {
            throw new \Exception(
                sprintf('Codepage "%s" not supported by mb_string', $codepage),
                404
            );
        }
        $this->codepage = $codepage;
        return $this;
    }

    public function getCodePage()
    {
        return $this->codepage;
    }

    public function encode($text, $encoding = null)
    {
        return mb_convert_encoding($text, $this->getCodePage(), $encoding);
    }
}
