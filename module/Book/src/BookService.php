<?php
namespace Mandala\DesignModule;

class BookService
{
    private $outputPath;

    function __construct($outputPath)
    {
        $this->outputPath = $outputPath;
    }

    public function generatePdf(Book $book)
    {
        $designPdfPaths = array();
        $designs = $book->designs;
        foreach ($designs as $design) {
            $svgPath = '/vagrant/data/design-svgs/' . $design->id . '.svg';
            file_put_contents($svgPath, $design->svg);

            $pdfPath = '/vagrant/data/design-pdfs/' . $design->id . '.pdf';
            $convertCommand = 'inkscape ' . $svgPath . ' -w=2550 -h=2550 --export-pdf=' . $pdfPath;
            exec($convertCommand);

            $designPdfPaths[] = $pdfPath;
        }

        $bookPdfPath = $this->pdfPath . '/' . $book->id . '.pdf';
        $mergeCommand = 'gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile=' . $bookPdfPath . ' ' . join(' ', $designPdfPaths);
        exec($mergeCommand);

        return $bookPdfPath;
    }
} 