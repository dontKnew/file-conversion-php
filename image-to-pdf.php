<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$pdf_path = __DIR__.'/pdf-list/';
$image_path = __DIR__.'/image-list/'; 
$pdf_files = getPDFFiles($pdf_path);
$output_path = __DIR__.'/output/';

try {
    createFolder($output_path);
    foreach($pdf_files as $pdf_file){        
        $pdf_image_path = $image_path.$pdf_file."/";
        $pdf_image_path = str_replace('.pdf', '', $pdf_image_path);
        $image_files = getImageFiles($pdf_image_path);
        $output_file = $output_path.$pdf_file;
        $lower_pdf_file = str_replace('.pdf', '', strtolower($pdf_file)); 
        $new = [__DIR__.'/personal/annexure/'.$lower_pdf_file.".png", __DIR__.'/personal/ignou-id.png'];
        $questionFiles = getImageFiles(__DIR__.'/personal/question/');
        foreach($questionFiles as $k=>$questionFile){
            $index = $k+1;
            $question_file = __DIR__.'/personal/question/'.$index.$lower_pdf_file.'.jpg';
            if(file_exists($question_file)){
                $new[] = $question_file;
            }
        }
        
        foreach($image_files as $k=>$image_file){
            $new[] = $pdf_image_path.$image_file;
        }
        $imagick = new Imagick($new);
        $imagick->setImageFormat('pdf');
        $imagick->setResolution(300, 300);
        $imagick->writeImages($output_file, true);
        $imagick->clear();
        $imagick->destroy();
    }
}catch(Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


function getImageFiles($directory){
    $pdfFiles = [];
    if (is_dir($directory)) {
        $files = scandir($directory);
        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if(!empty($extension)){
                $pdfFiles[] = $file;
            }
        }
    }
    return $pdfFiles;
}

function getPDFFiles($directory){
    $pdfFiles = [];
    if (is_dir($directory)) {
        $files = scandir($directory);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                $pdfFiles[] = $file;
            }
        }
    }
    return $pdfFiles;
}
function createFolder($backup_path){
    if (!file_exists($backup_path)) {
        mkdir($backup_path, 0777, true);  
    }
}