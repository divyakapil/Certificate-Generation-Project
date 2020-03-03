<?php
/**
 * Simply import all pages and different bounding boxes from different PDF documents.
 */
use setasign\Fpdi;
use setasign\tcpdf;
require_once 'vendor/autoload.php';
require_once 'vendor/setasign/tcpdf/tcpdf.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(2);
date_default_timezone_set('UTC');
$start = microtime(true);

//$pdf = new Fpdi\Fpdi();
$pdf = new Fpdi\TcpdfFpdi();

if ($pdf instanceof \TCPDF) {
    $pdf->SetProtection(['print'], '', 'owner');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
}

$files = [
        '1.pdf',
		'2.pdf',
		'shaw-icse03.pdf',
];

foreach ($files as $file) {
    $pageCount = $pdf->setSourceFile($file);

    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        $pdf->AddPage();
        $pageId = $pdf->importPage($pageNo, '/MediaBox');
        //$pageId = $pdf->importPage($pageNo, Fpdi\PdfReader\PageBoundaries::ART_BOX);
        $s = $pdf->useTemplate($pageId, 10, 10, 200);
    }
}
$file = uniqid().'.pdf';
//$pdf->Output('I', $file);
$pdf->Output('output/'.$file, 'I');
?>
