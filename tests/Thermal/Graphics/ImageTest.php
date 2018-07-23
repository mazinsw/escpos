<?php

namespace Thermal\Graphics;

use Thermal\PrinterTest;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public static function extractColors($image)
    {
        $colors = [];
        $width = imagesx($image);
        $height = imagesy($image);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorsforindex($image, imagecolorat($image, $x, $y));
                $colors[] = $color;
            }
        }
        return $colors;
    }

    public function testCreateFromData()
    {
        $image_path = dirname(dirname(__DIR__)) . '/resources/sample.jpg';
        $image = new Image([
            'name' => 'sample.jpg',
            'data' => \file_get_contents($image_path)
        ]);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('image_data', $image->getData()),
            $image->getData()
        );
    }

    public function testCreateFromInvalidData()
    {
        $this->setExpectedException('\Exception');
        $image = new Image([
            'name' => 'sample.jpg',
            'data' => ''
        ]);
    }

    public function testCreateFromInvalidPngFile()
    {
        $image_path = dirname(dirname(__DIR__)) . '/resources/invalid_sample.png';
        $this->setExpectedException('\Exception');
        $image = new Image($image_path);
    }

    public function testCreateFromInvalidGifFile()
    {
        $image_path = dirname(dirname(__DIR__)) . '/resources/invalid_sample.gif';
        $this->setExpectedException('\Exception');
        $image = new Image($image_path);
    }

    public function testCreateFromUnsupportedExtension()
    {
        $this->setExpectedException('\Exception');
        $image = new Image('test.xml');
    }
}
