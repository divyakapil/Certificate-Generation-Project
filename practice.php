<?php
set_time_limit(0);
ini_set('memory_limit', '-1');
// Include the main TCPDF library and TCPDI.
require_once('vendor\setasign\tcpdf\tcpdf.php');
require_once('vendor\setasign\tcpdf\tcpdi.php');

// Create new PDF document.

$pdf = new TCPDI();

$files = [
        '1.pdf',
		'2.pdf',
		'3.pdf',
];

foreach ($files as $file) {
    $pageCount = $pdf->setSourceFile($file);

    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        $tplidx = $pdf->importPage($pageNo, '/BleedBox');
		$size = $pdf->getTemplatesize($tplidx);
		$orientation = ($size['w'] > $size['h']) ? 'L' : 'P';
        $pdf->AddPage($orientation);
        $s = $pdf->useTemplate($tplidx, 10, 10, 200);
    }
}

$file = uniqid().'.pdf';
//$pdf->Output('I', $file);
$pdf->Output('output/'.$file, 'I');
?>
