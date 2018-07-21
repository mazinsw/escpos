<?php

namespace Thermal\Graphics\Filter;

use Thermal\Graphics\Filter;

class BayerOrdered implements Filter
{
    /**
     * Convert an image resource to black and white aplying dither.
     * The original image resource will not be changed, a new image resource will be created.
     *
     * @param ImageResource $image The source image resource
     * @return ImageResource The black and white image resource
     */
    public function process($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $new_image = imagecreatetruecolor($width, $height);
        // sets background to black
        $black = imagecolorallocate($new_image, 0, 0, 0);
        $white = imagecolorallocate($new_image, 255, 255, 255);
        $pattern = [
            [ 15, 195,  60, 240],
            [135,  75, 180, 120],
            [ 45, 225,  30, 210],
            [165, 105, 150,  90]
        ];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);
                $red = ($color >> 16) & 0xFF;
                $green = ($color >> 8) & 0xFF;
                $blue = $color & 0xFF;
                $gray = (int)($red * 0.3 + $green * 0.59 + $blue * 0.11);
                $threshold = $pattern[$y % 4][$x % 4];
                $gray = ($gray + $threshold) >> 1;
                if ($gray > 127) {
                    imagesetpixel($new_image, $x, $y, $white);
                }
            }
        }
        return $new_image;
    }
}
