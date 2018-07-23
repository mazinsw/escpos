<?php

namespace Thermal\Graphics\Filter;

use Thermal\PrinterTest;
use Lupka\PHPUnitCompareImages\CompareImagesTrait;

class BayerOrderedTest extends \PHPUnit_Framework_TestCase
{
    use CompareImagesTrait;

    public function testProcess()
    {
        $image_path = dirname(dirname(dirname(__DIR__))) . '/resources/sample.jpg';
        $image = imagecreatefromjpeg($image_path);
        $filter = new BayerOrdered();
        $new_image = $filter->process($image);
        imagedestroy($image);
        ob_start();
        imagepng($new_image);
        $image_data = ob_get_contents();
        ob_end_clean();
        imagedestroy($new_image);
        $result_image = new \Imagick();
        $result_image->readImageBlob($image_data);
        $expected_data = PrinterTest::getExpectedBuffer('sample_bayer_ordered.png', $image_data);
        $expected_image = new \Imagick();
        $expected_image->readImageBlob($expected_data);
        $this->assertImagesSame($expected_image, $result_image);
    }
}
