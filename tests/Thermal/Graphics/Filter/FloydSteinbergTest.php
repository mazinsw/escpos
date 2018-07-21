<?php

namespace Thermal\Graphics\Filter;

use Thermal\PrinterTest;

class FloydSteinbergTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $image_path = dirname(dirname(dirname(__DIR__))) . '/resources/sample.jpg';
        $image = imagecreatefromjpeg($image_path);
        $filter = new FloydSteinberg();
        $new_image = $filter->process($image);
        imagedestroy($image);
        ob_start();
        imagepng($new_image);
        $image_data = ob_get_contents();
        ob_end_clean();
        imagedestroy($new_image);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('sample_floyd_steinberg.png', $image_data),
            $image_data
        );
    }
}
