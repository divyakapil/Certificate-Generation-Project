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

                shell_exec('start /wait soffice --headless  --convert-to pdf --outdir "pdf/." "results/'.$Row[0].'.docx"');
              
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
//    'result1.pdf',
    
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


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <title>Certi Generation</title>

    <style>
      .form-container {border: 1px solid; padding: 50px 60px; margin-top: 2vh;}
    </style>
    <style type="text/css">
      <style>    
body {
  font-family: Arial;
  width: 100%;
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
    

.topnav {
  overflow: hidden;
     background-color: #8c0428;
}

.topnav a {
  float: right;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: white;
  color: #8c0428;
}

.topnav a.active {
  background-color: #8c0428;
  color: white;
}
.footer {
    margin-left: -15px;
    margin-right: -15px;
    background-color: #8c0428;
    padding-top: 90px;
    padding-bottom: 90px;
    font-size: 20px;
    text-align: center;
    color: white;
    margin-top: 100px;
    bottom:0;
    width: 102%;
}



    </style>

  </head>
  <body>
    
    <div class="container-fluid bg">
      <div class="row">
          <div class="col-md-5">
            <img src="d-y-patil-logo.png" alt="dy-patil-logo" > 
          </div>
          <div class="col-md-6">
            <br><br><br>
            <h1 style="color:brown; font-family: Verdana;">Certificate Generation System</h1>
          </div>
      </div>

      <div class="topnav">
    <a href="#">Report</a>
          
  <?php
      session_start();
      if(isset($_SESSION['Username'])) {
        echo 'Welcome '.$_SESSION['Username'];
        echo '<a  href="logout.php">Logout</a>';
      } else {
        echo '<a  href="home.php">Login</a>';
      }
      ?>
        
      </div>
  






<!--!>****************************************************************************************************************<!-->

    
    
    <div class="outer-container">
        <form action="" method="post"
            name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
            <div>
                <label>Choose Excel
                    File</label> <input type="file" name="file"
                    id="file" accept=".xls,.xlsx">
                  
                <button type="submit" id="submit" name="import"
                    class="btn-submit">Import</button><br><br>
                    <p>Select type of Certi :</p>
                    <select name="Type">
                      <option value="" selected disabled hidden>Choose</option>
                     <option value="organiser">Organiser</option>
                         <option value="participants">Participants</option>
  
                        </select>   <br><br><br>
                Event: <input type = "text" name = "event" value = "">
                <button type="submit"  name="download"
                    >Download</button>
            </div>
        
        </form>
        
    </div>
    <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
<!--!>****************************************************************************************************************<!-->
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  <div class="footer">
  <p>© Copyrights MRS Pvt. Ltd | Rights Reserved | Email us @ abc@yahoo.com </p>
</div>





  </body>

</html>
