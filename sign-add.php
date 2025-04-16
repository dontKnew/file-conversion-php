<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
try {
    
    $pdf_path = __DIR__.'/pdf-list/';
    $image_path = __DIR__.'/image-list/'; 
    $pdf_files = getPDFFiles($pdf_path);

    foreach($pdf_files as $pdf_file){
        $pdf_image_path = $image_path.$pdf_file."/";
        $pdf_image_path = str_replace('.pdf', '', $pdf_image_path);
        $image_files = getImageFiles($pdf_image_path);
        foreach($image_files as $k=>$image_file){
            $image_file = $pdf_image_path.$image_file;
            $random_left = mt_rand(160, 200);
            $random_top = mt_rand(10, 15);
            
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image_file);
            $image->place('signature.png', 'bottom-left', $random_left, $random_top);
            $index = $k+1;
            $image->text("Page $index", 500, 70, function($font) {
                $font->file('Handlee-Regular.ttf');
                $font->size(30);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });
            $image->save($image_file);
        }
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