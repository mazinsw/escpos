<?php

namespace Thermal\Graphics;

interface Filter
{
    /**
     * Process filter and return image resource.
     * The original image resource will not be changed, a new image resource will be created.
     *
     * @param \resource $image The source image resource
     * @return \resource The new image resource filtered
     */
    public function process($image);
}
