<?php
/*
Plugin Name: Assö Anmälan
*/

/**
 * This should be called outside of page, when a get or post param is set.
 */
function downloadCSV() {
    $csv_output = "Felicia Rosell, Gårdvägen 9, André Strömsjåäö\nLuïc Karlsson,18245-12456,felcia@hej.hej";

    header("Expires: 0");
    header("Cache-Control: no-cache, no-store, must-revalidate"); 
    header('Cache-Control: pre-check=0, post-check=0, max-age=0', false); 
    header("Pragma: no-cache");	
    header("Content-type: text/csv");
    header("Content-type: text/csv");
    header("Content-Disposition:attachment; filename=test.csv");
    header("Content-Type: application/force-download");
    
    echo mb_convert_encoding($csv_output, 'UTF-16LE', 'UTF-8');
    exit();
}

function getTableData() {
    global $wpdb;
    //$table_name = $wpdb->prefix .'users';
    $table_name = 'test';

    $results = $wpdb->get_results( "SELECT text FROM $table_name");
    echo 'results: ' . $results;
    if(!empty($results)) {
        foreach($results as $row){
            echo $row->text . '<br>';
        }
    }
}

function insertTableData() {
    global $wpdb;
    $table_name = 'test';

    $wpdb->insert( 
        $table_name, 
        array( 
            'text' => $_GET['testget'], 
        ) 
    );
}

function createForm() {
    ?>
    <form action="../test-har-tagit-emot-din-anmalan" method="get">
        <input type="text" name="testget">
        <input type="submit">
    </form>
    <?
}

add_shortcode('asso-form', function () {
    echo '<h1>Anmälan</h1>';

    if ( isset ($_GET['testget']) ) {
        echo 'Tack för din anmälan!';
    } else {
        createForm();
    }
});
