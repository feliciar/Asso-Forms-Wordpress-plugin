<?php

function getSignUpDataHeader( $form_id, $year, $info_type=null) {
    $header_items = [];

    global $wpdb;
    $prefix = $wpdb->prefix .'assoforms_';

    $table_singup =                 $prefix . 'signup';
    $table_singup_x_responses =     $prefix . 'signup_x_responses';
    $table_response =               $prefix . 'response';
    $table_forms_x_fields =         $prefix . 'forms_x_fields';
    $table_form_field =             $prefix . 'form_field';
    $table_form_fields_x_options =  $prefix . 'form_fields_x_options';
    $table_option =  $prefix . 'option';

    $info_type_condition = $info_type ? " AND info_type='$info_type'" : "";
    $form_fields = $wpdb->get_results( "SELECT DISTINCT title, form_field_id FROM $table_singup 
    INNER JOIN $table_forms_x_fields ON $table_singup.form_id=$table_forms_x_fields.form_id
    INNER JOIN $table_form_field ON $table_forms_x_fields.form_field_id=$table_form_field.id
    WHERE $table_singup.form_id=$form_id AND year=$year $info_type_condition
    ORDER BY form_field_id" );

    foreach($form_fields as $row){
        $options = $wpdb->get_results( 
            "SELECT * FROM $table_option WHERE id IN 
                (SELECT option_id FROM $table_form_fields_x_options 
                    WHERE form_field_id = $row->form_field_id)");
        if ($info_type === 'allergy' && count($options) > 0 ) {
            foreach($options as $option) {
                $header_items[] = $option->display_name;
            }
        } else {
            $header_items[] = $row->title;
        }
    }
    return $header_items;
}

function getSignUpDataFromDatabase($form_id, $year, $print_out_type=null) {
    $return_string = '';

    global $wpdb;
    $prefix = $wpdb->prefix .'assoforms_';

    $table_singup =                 $prefix . 'signup';
    $table_singup_x_responses =     $prefix . 'signup_x_responses';
    $table_response =               $prefix . 'response';
    $table_forms_x_fields =         $prefix . 'forms_x_fields';
    $table_form_field =             $prefix . 'form_field';
    $table_form_fields_x_options =  $prefix . 'form_fields_x_options';
    $table_option =  $prefix . 'option';

    if ($print_out_type === 'allergy') {
        $basic_header_items = getSignUpDataHeader( $form_id, $year, 'basic');
        $allergy_header_items = getSignUpDataHeader( $form_id, $year, 'allergy');
        $header_items = array_merge($basic_header_items, $allergy_header_items);
    } else {
        $header_items = getSignUpDataHeader( $form_id, $year);
    }
    $return_string .= implode( ';', $header_items); 
    $return_string .= "\n";

    $info_type_condition = $print_out_type ? " AND (info_type='$print_out_type' OR info_type='basic')" : "";
    $results = $wpdb->get_results( "SELECT DISTINCT title, response, info_type, signup_id, field_id FROM $table_singup 
        INNER JOIN $table_singup_x_responses ON $table_singup.id=$table_singup_x_responses.signup_id
        INNER JOIN $table_response ON $table_singup_x_responses.response_id=$table_response.id
        INNER JOIN $table_form_field ON $table_response.field_id = $table_form_field.id
        WHERE form_id=$form_id AND year=$year $info_type_condition
        ORDER BY signup_id, field_id" );

    $signup_id = $results[0]->signup_id;
    $column_index = 0;
    $num_columns = count($header_items);
    $num_columns = count($form_fields);
    foreach($results as $row){
        $options = $wpdb->get_results( "SELECT * FROM $table_option
                                            WHERE id IN 
                                            (SELECT option_id FROM $table_form_fields_x_options WHERE form_field_id = $row->field_id)");
            

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

        if ($print_out_type === 'allergy' && $row->info_type === 'allergy' && count($options) > 0 ) {
            foreach ( $options as $option ) {
                $return_string .= ( strpos($row->response, $option->display_name) !== false ) ? $option->display_name : '';
                $return_string .= ';';
            }
        } elseif (strpos($row->response, ';') !== false) {
            $return_string .= str_replace ( ';' , ', ' , $row->response );
            $return_string .= ';';
        } else {
            $return_string .= $row->response;
            $return_string .= ';';
        }
        
        $column_index++;
        $signup_id = $row->signup_id;
    }
    while ($column_index < $num_columns) {
        $return_string .= ';';
        $column_index++;
    }

    return $return_string;
}