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

function getSignUpDataFromDatabase($form_id, $year) {
    $return_string = '';

    global $wpdb;
    $prefix = $wpdb->prefix .'assoforms_';

    $table_singup =             $prefix . 'signup';
    $table_singup_x_responses = $prefix . 'signup_x_responses';
    $table_response =           $prefix . 'response';
    $table_forms_x_fields =         $prefix . 'forms_x_fields';
    $table_form_field =         $prefix . 'form_field';

    $form_fields = $wpdb->get_results( "SELECT DISTINCT title, form_field_id FROM $table_singup 
    INNER JOIN $table_forms_x_fields ON $table_singup.form_id=$table_forms_x_fields.form_id
    INNER JOIN $table_form_field ON $table_forms_x_fields.form_field_id=$table_form_field.id
    WHERE $table_singup.form_id=$form_id AND year=$year ORDER BY form_field_id" );

    foreach($form_fields as $row){
        $return_string .= $row->title . ';';
    }
    $return_string .= "\n";

    $results = $wpdb->get_results( "SELECT title, response, info_type, signup_id, field_id FROM $table_singup 
        INNER JOIN $table_singup_x_responses ON $table_singup.id=$table_singup_x_responses.signup_id
        INNER JOIN $table_response ON $table_singup_x_responses.response_id=$table_response.id
        INNER JOIN $table_form_field ON $table_response.field_id = $table_form_field.id
        WHERE form_id=$form_id AND year=$year ORDER BY signup_id, field_id" );

    $signup_id = $results[0]->signup_id;
    $column_index = 0;
    $num_columns = count($form_fields);
    foreach($results as $row){
        if ( $signup_id !== $row->signup_id) {
            while ($column_index < $num_columns) {
                $return_string .= ';';
                $column_index++;
            }

            $return_string .= "\n";
            $column_index = 0;
        }
        
        while ( $form_fields[$column_index]->form_field_id !== $row->field_id && $column_index < $num_columns ) {
            $return_string .= ';';
            $column_index++;
        }
        
        $return_string .= $row->response;
        $return_string .= ';';
        
        $column_index++;
        $signup_id = $row->signup_id;
    }
    while ($column_index < $num_columns) {
        $return_string .= ';';
        $column_index++;
    }

    return $return_string;
}