<?php

if ($_POST['get_csv']) {
    // TODO: perform verification and security measures
    downloadCSV( $_POST['filename'], $_POST['form-id'], $_POST['year'] );
}

function downloadCSV( $filename, $form_id, $year ) {
    outputDataToCSV( getSignUpDataFromDatabase($form_id, $year), $filename );
}

function outputDataToCSV( $data, $filename ) {
    header("Expires: 0");
    header("Cache-Control: no-cache, no-store, must-revalidate"); 
    header('Cache-Control: pre-check=0, post-check=0, max-age=0', false); 
    header("Pragma: no-cache");	
    header("Content-type: text/csv");
    header("Content-Disposition:attachment; filename=" . $filename . ".csv");
    header("Content-Type: application/force-download");

    echo mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');
    exit();
}