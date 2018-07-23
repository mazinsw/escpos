<?php

namespace Thermal\Graphics\Filter;

use Thermal\Graphics\Filter;

class FloydSteinberg implements Filter
{
    /**
     * Convert an image resource to black and white aplying dither.
     * The original image resource will not be changed, a new image resource will be created.
     *
     * @param \resource $image The source image resource
     * @return \resource The black and white image resource
     */
    public function process($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $new_image = imagecreatetruecolor($width, $height);
        // sets background to black
        $black = imagecolorallocate($new_image, 0, 0, 0);
        $white = imagecolorallocate($new_image, 255, 255, 255);
        $pixel = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);
                $red = ($color >> 16) & 0xFF;
                $green = ($color >> 8) & 0xFF;
                $blue = $color & 0xFF;
                $gray = (int)($red * 0.3 + $green * 0.59 + $blue * 0.11);
                // Add errors to color if there are
                if (isset($pixel[$x][$y])) {
                    $gray += $pixel[$x][$y];
                }
                $new_color = $black;
                if ($gray > 127) {
                    $new_color = $white;
                }
                imagesetpixel($new_image, $x, $y, $new_color);
                $error = $gray - ($new_color & 0xFF);
                if ($x + 1 < $width) {
                    $pixel[$x + 1][$y] = (isset($pixel[$x + 1][$y]) ? $pixel[$x + 1][$y] : 0) + ($error * 7 >> 4);
                }
                // if we are in the last line
                if ($y == $height - 1) {
                    continue;
                }
                if ($x > 0) {
                    $prev = isset($pixel[$x - 1][$y + 1]) ? $pixel[$x - 1][$y + 1] : 0;
                    $pixel[$x - 1][$y + 1] = $prev + ($error * 3 >> 4);
                }
                $pixel[$x][$y + 1] = (isset($pixel[$x][$y + 1]) ? $pixel[$x][$y + 1] : 0) + ($error * 5 >> 4);
                if ($x < $width - 1) {
                    $prev = isset($pixel[$x + 1][$y + 1]) ? $pixel[$x + 1][$y + 1] : 0;
                    $pixel[$x + 1][$y + 1] = $prev + ($error * 1 >> 4);
                }
            }
        }
        return $new_image;
    }
}
