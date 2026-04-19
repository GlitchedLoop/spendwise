<?php

require_once '../../includes/session_check.php';
require_once '../../classes/CSVParser.php';

if (!isset($_FILES['csv_file'])) {
    setFlashMessage('error', 'No file uploaded.');
    redirect('modules/csv_import/upload_csv.php');
}

$file = $_FILES['csv_file'];

if (!uploadFileValid($file)) {
    setFlashMessage('error', 'Invalid CSV file.');
    redirect('modules/csv_import/upload_csv.php');
}

$fileName = time() . "_" . basename($file['name']);
$targetPath = UPLOAD_PATH . $fileName;

move_uploaded_file($file['tmp_name'], $targetPath);

$parser = new CSVParser();
$parsedRows = $parser->parse($targetPath);

$_SESSION['csv_preview'] = $parsedRows;
$_SESSION['csv_file_name'] = $fileName;

redirect('modules/csv_import/preview_import.php');