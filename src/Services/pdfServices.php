<?php 

namespace App\Services;

use Dompdf\Dompdf;

use Dompdf\Options;

  class pdfServices
 {
    private $dompdf;

    public function __construct()
    {
        
        $this->dompdf = new Dompdf();
        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Courier');

        $this->dompdf->setOptions($pdfOptions);

    }

    public function showPdfFile($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->stream("agitel.pdf", [
            'Attachment'=> false
        ]);
    }

    public function generateBinaryPdf($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->output();

    }

 }