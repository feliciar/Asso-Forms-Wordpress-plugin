<?php

if ($_POST['get_csv']) {
    // TODO: perform verification and security measures
    downloadCSV( $_POST['filename'], $_POST['form-id'], $_POST['year'], $_POST['info-type'] );
}

function downloadCSV( $filename, $form_id, $year, $info_type ) {
    outputDataToCSV( getSignUpDataFromDatabase($form_id, $year, $info_type), $filename );
}

function outputDataToCSV( $data, $filename ) {
    header("Expires: 0");
    header("Cache-Control: no-cache, no-store, must-revalidate"); 
    header('Cache-Control: pre-check=0, post-check=0, max-age=0', false); 
    header("Pragma: no-cache");	
    header("Content-type: text/csv charset=ISO-8859-1");
    header("Content-Disposition:attachment; filename=" . $filename . ".csv");
    header("Content-Type: application/force-download");
    header('Content-Encoding: ISO-8859-1');

    echo mb_convert_encoding($data, 'ISO-8859-1', 'UTF-8');

    exit();
}