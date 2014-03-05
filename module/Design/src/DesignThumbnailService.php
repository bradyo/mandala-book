<?php
namespace Mandala\DesignModule;

use Imagick;

/**
 * Generates png thumbnails for Designs
 */
class DesignThumbnailService
{
    private $outputPath;

    /**
     * @param string $outputPath path to png thumbnail output folder
     */
    public function __construct($outputPath)
    {
        $this->outputPath = $outputPath;
    }

    /**
     * @param Design $design
     * @param int $size thumbnail size in pixels
     * @return string path of generated thumbnail file
     */
    public function createThumbnail(Design $design, $size = 120)
    {
        $thumbnailPath = $this->outputPath . '/' . $design->id . '-' . $size . 'px.png';
        if (! file_exists($thumbnailPath)) {
            $image = new Imagick();
            $image->readImageBlob($design->svg);
            $image->setImageFormat("png24");
            $image->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1);
            $image->writeimage($thumbnailPath);
        }
        return $thumbnailPath;
    }
}