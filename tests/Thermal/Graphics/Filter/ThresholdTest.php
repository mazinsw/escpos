<?php

namespace Thermal\Graphics\Filter;

use Thermal\PrinterTest;
use Thermal\Graphics\ImageTest;

class ThresholdTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $image_path = dirname(dirname(dirname(__DIR__))) . '/resources/sample.jpg';
        $image = imagecreatefromjpeg($image_path);
        $filter = new BayerOrdered();
        $result_image = $filter->process($image);
        imagedestroy($image);
        ob_start();
        imagepng($result_image);
        $image_data = ob_get_contents();
        ob_end_clean();
        $expected_data = PrinterTest::getExpectedBuffer('sample_threshold.png', $image_data);
        $expected_image = imagecreatefromstring($expected_data);
        $this->assertEquals(
            [
                'width' => imagesx($expected_image),
                'height' => imagesy($expected_image),
                'pixels' => ImageTest::extractColors($expected_image)
            ],
            [
                'width' => imagesx($result_image),
                'height' => imagesy($result_image),
                'pixels' => ImageTest::extractColors($result_image)
            ]
        );
        imagedestroy($expected_image);
        imagedestroy($result_image);
    }
}
