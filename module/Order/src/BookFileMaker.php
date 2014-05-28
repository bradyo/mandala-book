<?php
namespace Mandala\OrderModule;

use Mandala\DesignModule\DesignFileService;

class BookFileMaker
{
    private $outputPath;
    private $designFileService;

    function __construct($outputPath, DesignFileService $designFileService)
    {
        $this->outputPath = $outputPath;
        $this->designFileService = $designFileService;
    }

    public function createPdf(Order $order)
    {
        $outputPdfPath = $this->getPdfPath($order);
        if (file_exists($outputPdfPath)) {
            return $outputPdfPath;
        }

        $designPdfPaths = array();
        $designs = $order->getDesigns() ;
        foreach ($designs as $design) {
            $designPdfPaths[] = $this->designFileService->createPdf($design);
        }

        $sourcePdfPaths = join(' ', $designPdfPaths);
        exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile=' . $outputPdfPath . ' ' . $sourcePdfPaths);

        return $outputPdfPath;
    }

    private function getPdfPath(Order $order)
    {
        return $this->outputPath . '/' . $order->confirmationCode . '.pdf';
    }
} 