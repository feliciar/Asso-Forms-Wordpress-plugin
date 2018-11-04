<?php
function getData() {

    $data = array();
    
    global $wpdb;
    $table_name = $wpdb->prefix .'assoforms_form_field';
    $table_field_x_options = $wpdb->prefix .'assoforms_form_fields_x_options';
    $table_options = $wpdb->prefix .'assoforms_option';
    
    $results = $wpdb->get_results( "SELECT * FROM $table_name");
    if(!empty($results)) {
        foreach($results as $row){
            $options = [];
            $result_options = $wpdb->get_results( "SELECT * FROM $table_options
                                                WHERE id IN 
                                                    (SELECT option_id FROM $table_field_x_options WHERE form_field_id = $row->id)");
            if (count( $result_options ) ) {
                foreach($result_options as $option){
                    $options[] = array(
                        'value' => $option->reference,
                        'display_name' => $option->display_name,
                    );
                }
            }
            $data[] = array( 
                'reference' => $row->reference,
                'field_type' => $row->field_type,
                'title' => $row->title,
                'placeholder' => $row->placeholder_text,
                'required' => $row->required,
                'required_format' => $row->required_format,
                'options' => $options,
            );
        }
    }
    
    return $data;
}