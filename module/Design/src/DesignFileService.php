<?php
namespace Mandala\DesignModule;

use Exception;
use Imagick;
use ImagickPixel;

/**
 * Takes a Design and generates SVG/PDF content on the server-side.
 */
class DesignFileService
{
    private $scriptsPath;
    private $outputPath;

    /**
     * @param string $scriptsPath path to render scripts
     * @param string $outputPath folder to write SVG file to
     */
    function __construct($scriptsPath, $outputPath)
    {
        $this->scriptsPath = $scriptsPath;
        $this->outputPath = $outputPath;
    }

    /**
     * @param Design $design
     * @return string design svg path
     * @throws Exception
     */
    public function createSvg(Design $design)
    {
        $svgPath = $this->getSvgPath($design);
        if (file_exists($svgPath)) {
            return $svgPath;
        }

        $command = 'phantomjs ' . $this->scriptsPath . '/design-render.js' . ' \'' . $design->toJson() . '\'';
        exec($command, $output, $success);
        if ($success !== 0) {
            throw new Exception('Failed to render design SVG for data: ' . $design->toJson());
        }
        $svgContent = join('', $output);
        file_put_contents($svgPath, '<?xml version="1.0" encoding="utf-8"?>' . $svgContent);

        return $svgPath;
    }

    /**
     * @param Design $design
     * @return string path to SVG file for the given Design
     */
    public function getSvgPath(Design $design)
    {
        return $this->outputPath . '/' . $design->id . '.svg';
    }

    /**
     * @param Design $design
     * @return string design pdf path
     */
    public function createPdf(Design $design)
    {
        $pdfPath = $this->getPdfPath($design);
        if (file_exists($pdfPath)) {
            return $pdfPath;
        }

        $svgPath = $this->getSvgPath($design);
        exec('inkscape ' . $svgPath . ' -w=2550 -h=2550 --export-pdf=' . $pdfPath);

        return $pdfPath;
    }

    /**
     * @param Design $design
     * @return string path to PDF file for the given Design
     */
    public function getPdfPath(Design $design)
    {
        return $this->outputPath . '/' . $design->id . '.pdf';
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
            $svgPath = $this->getSvgPath($design);
            if (! file_exists($svgPath)) {
                $this->createSvg($design);
            }
            $svgContent = file_get_contents($svgPath);

            $image = new Imagick();
            $image->setBackgroundColor(new ImagickPixel('transparent'));
            $image->readImageBlob($svgContent);
            $image->setImageFormat("png24");
            $image->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1);
            $image->writeimage($thumbnailPath);
        }
        return file_get_contents($thumbnailPath);
    }
} 