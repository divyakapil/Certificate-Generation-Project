<?php
require_once('vendor/php-excel-reader/excel_reader2.php');
require_once('vendor/SpreadsheetReader.php');
require_once 'bootstrap.php';
use setasign\Fpdi;
use setasign\fpdf;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

if (isset($_POST["import"]))
{
    echo($_POST["event"]);
    $event = $_POST["event"];
    
 $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  echo($_FILES["file"]["type"]);
  $pathinfo = pathinfo($_FILES["file"]["name"]);

//   if(in_array($_FILES["file"]["type"],$allowedFileType))

if($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls' )  
  {
        $type = $_POST['Type'];
        $targetPath = 'uploads/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        
        $Reader = new SpreadsheetReader($targetPath);
        
        $sheetCount = count($Reader->sheets());
        for($i=0;$i<$sheetCount;$i++)
        {   $count = 1;
            
            $Reader->ChangeSheet($i);
            
            foreach ($Reader as $Row)
            {
                if($type == 'organiser'){
                    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('resources/Certi1-organizers.docx'); 
                    
                    // $templateProcessor->setValue('conduct', 'Co-ordinator');
                    echo('ORGANIZER');
                }else{
                    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('resources/Certi2- participants.docx');
                    echo('participants');
                    // $templateProcessor->setValue('conduct', '');
                    
                }  

                if($count > 1){
                    
                
                if (isset($Row[0])) {
                    # code...
                    $name = $Row[0];  
                    $templateProcessor->setValue('name', $name);
                    echo($name);
                    
                }


                
                
                $branch = "";
                if(isset($Row[1])) {
                    $branch = $Row[1];
                    $templateProcessor->setValue('branch', $branch);
                }

                $templateProcessor->setValue('event', $event);
                


                $templateProcessor->saveAs('results/'.$Row[0].'.docx');

                shell_exec('start /wait soffice --headless	--convert-to pdf --outdir "pdf/." "results/'.$Row[0].'.docx"');
              
                unlink('results/'.$Row[0].'.docx');
             }
             $count++;
            }
         }
         unlink('uploads/'.$_FILES['file']['name'] );
  }

  else
  { 
        $type = "error";
        $message = "Invalid File Type. Upload Excel File.";
  }
}

if (isset($_POST["download"])) {
    # code...
    
/**
 * Simply import all pages and different bounding boxes from different PDF documents.
 */

require_once 'vendor1/autoload.php';
require_once 'vendor1/setasign/fpdf/fpdf.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(2);
date_default_timezone_set('UTC');
$start = microtime(true);

$pdf = new Fpdi\Fpdi();
//$pdf = new Fpdi\TcpdfFpdi('L', 'mm', 'A3');

if ($pdf instanceof \TCPDF) {
    $pdf->SetProtection(['print'], '', 'owner');
    // $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
}

// $files = [
//         'result.pdf',
// 		'result1.pdf',
		
// ];
$files = array();

foreach (glob("*pdf/*") as $filename) {
    array_push($files,$filename);
    // echo $filename."<br />";
}

foreach ($files as $file) {
    $pageCount = $pdf->setSourceFile($file);

    for ($pageNo = 1; $pageNo <= 1; $pageNo++) {
        $pdf->AddPage();
        $pageId = $pdf->importPage($pageNo, '/MediaBox');
        //$pageId = $pdf->importPage($pageNo, Fpdi\PdfReader\PageBoundaries::ART_BOX);
        $s = $pdf->useTemplate($pageId, 10, 10, 200);
    }
}
$file = uniqid().'.pdf';
$pdf->Output('I', 'simple.pdf');
//$pdf->Output('output/'.$file, 'I');


}


?>

<!DOCTYPE html>
<html>    
<head>
<style>    
body {
	font-family: Arial;
	width: 550px;
}

.outer-container {
	background: #F0F0F0;
	border: #e0dfdf 1px solid;
	padding: 40px 20px;
	border-radius: 2px;
}

.btn-submit {
	background: #333;
	border: #1d1d1d 1px solid;
    border-radius: 2px;
	color: #f0f0f0;
	cursor: pointer;
    padding: 5px 20px;
    font-size:0.9em;
}

.tutorial-table {
    margin-top: 40px;
    font-size: 0.8em;
	border-collapse: collapse;
	width: 100%;
}

.tutorial-table th {
    background: #f0f0f0;
    border-bottom: 1px solid #dddddd;
	padding: 8px;
	text-align: left;
}

.tutorial-table td {
    background: #FFF;
	border-bottom: 1px solid #dddddd;
	padding: 8px;
	text-align: left;
}

#response {
    padding: 10px;
    margin-top: 10px;
    border-radius: 2px;
    display:none;
}

.success {
    background: #c7efd9;
    border: #bbe2cd 1px solid;
}

.error {
    background: #fbcfcf;
    border: #f3c6c7 1px solid;
}

div#response.display-block {
    display: block;
}
</style>
</head>

<body>
    <h2>Import Excel File into MySQL Database using PHP</h2>
    
    <div class="outer-container">
        <form action="" method="post"
            name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
            <div>
                <label>Choose Excel
                    File</label> <input type="file" name="file"
                    id="file" accept=".xls,.xlsx">
                <button type="submit" id="submit" name="import"
                    class="btn-submit">Import</button>
                    <select name="Type">
                     <option value="organiser">Organiser</option>
                         <option value="participants">Participants</option>
  
                        </select>   
                Event: <input type = "text" name = "event" value = "">
                <button type="submit"  name="download"
                    >Download</button>
            </div>
        
        </form>
        
    </div>
    <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
    


</body>
</html>