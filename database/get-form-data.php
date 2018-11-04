<?php
function getData() {

    $data = array();
    
    global $wpdb;
    $table_name = $wpdb->prefix .'assoforms_form_field';
    
    $results = $wpdb->get_results( "SELECT * FROM $table_name");
    echo 'results: ' . $results;
    if(!empty($results)) {
        foreach($results as $row){
            $data[] = array( 
                'reference' => $row->reference,
                'field_type' => $row->field_type,
                'title' => $row->title,
                'placeholder' => $row->placeholder_text,
            );
        }
    }
    
    return $data;
}