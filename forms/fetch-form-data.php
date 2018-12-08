<?php
function getFormDataFromDatabase($form_id, $year) {

    $data = array();
    
    global $wpdb;

    $table_form_x_fields = $wpdb->prefix .'assoforms_forms_x_fields';
    $table_name = $wpdb->prefix .'assoforms_form_field';
    $table_field_x_options = $wpdb->prefix .'assoforms_form_fields_x_options';
    $table_options = $wpdb->prefix .'assoforms_option';

    $results = $wpdb->get_results( 
        "SELECT $table_name.id, reference, field_type, title, placeholder_text, `required`, required_format 
            FROM $table_name 
            INNER JOIN $table_form_x_fields ON $table_name.id=$table_form_x_fields.form_field_id 
            WHERE $table_form_x_fields.form_id=$form_id
            ORDER BY position ASC");
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