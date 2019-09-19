<?php

use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

require __DIR__ . '/../Header.php';
$inputFileType = 'Xlsx';

$helper->log('Start');

$spreadsheet = new Spreadsheet();

$aSheet = $spreadsheet->getActiveSheet();

$gdImage = @imagecreatetruecolor(120, 20);
$textColor = imagecolorallocate($gdImage, 255, 255, 255);
imagestring($gdImage, 1, 5, 5, 'Created with PhpSpreadsheet', $textColor);

$baseUrl = 'https://phpspreadsheet.readthedocs.io';

$drawing = new MemoryDrawing();
$drawing->setName('In-Memory image 1');
$drawing->setDescription('In-Memory image 1');
$drawing->setCoordinates('A1');
$drawing->setImageResource($gdImage);
$drawing->setRenderingFunction(
    MemoryDrawing::RENDERING_JPEG
);
$drawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
$drawing->setHeight(36);
$helper->log('Write image');

$hyperLink = new Hyperlink($baseUrl, 'test image');
$drawing->setHyperlink($hyperLink);
$helper->log('Write link: ' . $baseUrl);

$drawing->setWorksheet($aSheet);

$filename = tempnam(File::sysGetTempDir(), 'phpspreadsheet-test');

$writer = IOFactory::createWriter($spreadsheet, $inputFileType);
$writer->save($filename);

$reader = IOFactory::createReader($inputFileType);

$reloadedSpreadsheet = $reader->load($filename);
unlink($filename);

$helper->log('reloaded Spreadsheet');

foreach ($reloadedSpreadsheet->getActiveSheet()->getDrawingCollection() as $pDrawing) {
    $helper->log('Read link: ' . $pDrawing->getHyperlink()->getUrl());
}

$helper->log('end');
