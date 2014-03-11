<?php
namespace Mandala\BookModule;

use Mandala\DesignModule\DesignFileService;

class BookFileService
{
    private $outputPath;
    private $designFileService;

    function __construct($outputPath, DesignFileService $designFileService)
    {
        $this->outputPath = $outputPath;
        $this->designFileService = $designFileService;
    }

    public function generatePdf(Book $book)
    {
        $designPdfPaths = array();
        foreach ($book->bookDesigns as $bookDesign) {
            $design = $bookDesign->design;
            $designPdfPaths[] = $this->designFileService->getPdfPath($design);
        }

        $outputPdfPath = $this->getPdfPath($book);
        $sourcePdfPaths = join(' ', $designPdfPaths);
        exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile=' . $outputPdfPath . ' ' . $sourcePdfPaths);
    }

    public function getPdfPath(Book $book)
    {
        return $this->outputPath . '/' . $book->id . '.pdf';
    }
} 