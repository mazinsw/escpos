<?php

namespace Thermal\Buffer;

use Thermal\PrinterTest;

class EncodingTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSupportedCodePages()
    {
        $list = Encoding::getSupportedCodePages();
        $this->assertInternalType('array', $list);
    }

    public function testSetCodePage()
    {
        $encoding = new Encoding('UTF-8');
        $this->assertEquals('UTF-8', $encoding->getCodePage());
        $encoding->setCodePage('CP850');
        $this->assertEquals('CP850', $encoding->getCodePage());
        $this->setExpectedException('\Exception');
        $encoding->setCodePage('KATAKANA');
    }

    public function testGetCodePage()
    {
        $encoding = new Encoding('Windows-1252');
        $this->assertEquals('Windows-1252', $encoding->getCodePage());
    }

    public function testEncode()
    {
        $encoding = new Encoding('Windows-1252');
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('Windows-1252_text.txt', $encoding->encode('AçáéíU', 'UTF-8')),
            $encoding->encode('AçáéíU', 'UTF-8')
        );
        $encoding->setCodePage('CP850');
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('CP850_text.txt', $encoding->encode('AçáéíU', 'UTF-8')),
            $encoding->encode('AçáéíU', 'UTF-8')
        );
        $encoding->setCodePage('UTF-8');
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('UTF-8_text.txt', $encoding->encode('AçáéíU', 'UTF-8')),
            $encoding->encode('AçáéíU', 'UTF-8')
        );
    }
}
