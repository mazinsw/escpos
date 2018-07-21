<?php

namespace Thermal\Graphics;

use Thermal\Printer;
use Thermal\Buffer\Encoding;
use Thermal\Connection\Connection;
use Thermal\Graphics\Filter\FloydSteinberg;

class Image
{
    private $data;
    private $lines;
    private $width;
    private $bytes_per_row;

    public function __construct($filename, $filter = null)
    {
        $filter = $filter ?: new FloydSteinberg();
        if (is_array($filename)) {
            $this->loadImageData($filename, $filter);
        } else {
            $this->loadImage($filename, $filter);
        }
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getData()
    {
        return $this->data;
    }

    public function bytesPerRow()
    {
        return $this->bytes_per_row;
    }

    /**
     * Load an image from disk, into memory, using GD.
     *
     * @param string $filename The filename to load from
     * @param Filter $filter filter process
     * @throws Exception if the image format is not supported,
     *  or the file cannot be opened.
     */
    protected function loadImage($filename, $filter)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($ext) {
            case 'png':
                $image = @imagecreatefrompng($filename);
                break;
            case 'jpg':
                $image = @imagecreatefromjpeg($filename);
                break;
            case 'gif':
                $image = @imagecreatefromgif($filename);
                break;
            default:
                throw new \Exception(sprintf('Image format "%s" not supported in GD', $ext));
        }
        if (!is_resource($image)) {
            throw new \Exception(sprintf('Failed to load image "%s"', $filename));
        }
        $processed_image = $filter->process($image);
        imagedestroy($image);
        $this->readImage($processed_image);
        imagedestroy($processed_image);
    }

    /**
     * Load an image from disk, into memory, using GD.
     *
     * @param string $filename The filename to load from
     * @param Filter $filter filter process
     * @throws Exception if the image format is not supported,
     *  or the file cannot be opened.
     */
    protected function loadImageData($data, $filter)
    {
        $image = @imagecreatefromstring($data['data']);
        if (!is_resource($image)) {
            throw new \Exception(sprintf('Failed to load image "%s"', $data['name']));
        }
        $processed_image = $filter->process($image);
        imagedestroy($image);
        $this->readImage($processed_image);
        imagedestroy($processed_image);
    }

    /**
     * Load actual image pixels from GD resource.
     *
     * @param resource $image GD resource to use
     */
    private function readImage($image)
    {
        $width = imagesx($image);
        $img_height = imagesy($image);
        $bits = 24;
        $slices = (int)($bits / 8);
        $height = (int)(($img_height + $bits - 1) / $bits) * $bits;
        $this->width = $width;
        $this->bytes_per_row = $slices * $width;
        $this->lines = (int)($height / $bits);
        $pos = 0;
        $data = str_repeat("\x00", $width * $height / 8);
        for ($by = 0; $by < $img_height; $by += $bits) {
            for ($x = 0; $x < $width; $x++) {
                // loop slices
                for ($s = 0; $s < $slices; $s++) {
                    $slice = 0b00000000;
                    for ($bit = 0; $bit < 8; $bit++) {
                        $y = $by + $s * 8 + $bit;
                        if ($y >= $img_height) {
                            break;
                        }
                        $color = imagecolorat($image, $x, $y);
                        $alpha = ($color >> 24) & 0xFF;
                        $red = ($color >> 16) & 0xFF;
                        $green = ($color >> 8) & 0xFF;
                        $blue = $color & 0xFF;
                        $greyness = (int)($red * 0.3 + $green * 0.59 + $blue * 0.11) >> 7;
                        // 1 for black, 0 for white, taking into account transparency
                        $dot = (1 - $greyness) >> ($alpha >> 6);
                        // apply the dot
                        $slice |= $dot << (7 - $bit);
                    }
                    $data[$pos] = chr($slice);
                    $pos++;
                }
            }
        }
        $this->data = $data;
    }

    public function getLineData($index)
    {
        return substr($this->getData(), $index * $this->bytesPerRow(), $this->bytesPerRow());
    }
}
