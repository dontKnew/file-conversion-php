<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Spatie\PdfToImage\Pdf as PDFImage;

$pdf_path = __DIR__.'/pdf-list/';
$image_path = __DIR__.'/image-list/'; 

$pdf_files = getPDFFiles($pdf_path);

foreach($pdf_files as $pdf_file){
    $pdf_image_path = $image_path.$pdf_file;
    $pdf_image_path = str_replace('.pdf', '', $pdf_image_path);
    createFolder($pdf_image_path);

    $pdf_file = $pdf_path.$pdf_file;
    $pdf = new PDFImage($pdf_file);
    $pdf->quality(70); 
    $return = $pdf->saveAllPages($pdf_image_path);    
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
