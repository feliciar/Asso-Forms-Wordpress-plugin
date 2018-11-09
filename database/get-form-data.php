<?php
function getFormDataFromDatabase() {

    $data = array();
    
    global $wpdb;

    $table_form_x_fields = $wpdb->prefix .'assoforms_forms_x_fields';
    $table_name = $wpdb->prefix .'assoforms_form_field';
    $table_field_x_options = $wpdb->prefix .'assoforms_form_fields_x_options';
    $table_options = $wpdb->prefix .'assoforms_option';

    $form_id = 1;

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

function getSignUpDataFromDatabase() {
    global $wpdb;
    $prefix = $wpdb->prefix .'assoforms_';

    $table_singup =             $prefix . 'signup';
    $table_singup_x_responses = $prefix . 'signup_x_responses';
    $table_response =           $prefix . 'response';
    $table_forms_x_fields =         $prefix . 'forms_x_fields';
    $table_form_field =         $prefix . 'form_field';


    $form_id = 1;
    $year = 2019;

    $titles = $wpdb->get_results( "SELECT DISTINCT title, field_id FROM $table_singup 
    INNER JOIN $table_forms_x_fields ON $table_singup.form_id=$table_forms_x_fields.form_id
    INNER JOIN $table_form_field ON $table_forms_x_fields.form_field_id=$table_form_field.id
    WHERE $table_singup.form_id=$form_id AND year=$year" );

    foreach($titles as $row){
        echo $row->title . '<br>';
    }

    $results = $wpdb->get_results( "SELECT title, response, info_type, signup_id, field_id FROM $table_singup 
        INNER JOIN $table_singup_x_responses ON $table_singup.id=$table_singup_x_responses.signup_id
        INNER JOIN $table_response ON $table_singup_x_responses.response_id=$table_response.id
        INNER JOIN $table_form_field ON $table_response.field_id = $table_form_field.id
        WHERE form_id=$form_id AND year=$year ORDER BY signup_id" );

    foreach($results as $row){
        $signup_id = $row->signup_id;
        echo $signup_id . '<br>';
        echo $row->field_id . '<br>';
        echo $row->title . '<br>';
        echo $row->response . '<br>';
    }


    
}