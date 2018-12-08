<?php
function formDataValidation( $form_id, $year ) {

    $invalid_fields = [];

    $data = getFormDataFromDatabase($form_id, $year);
    foreach( $data as $field ) {
        if ( $field['required'] ) {
            if ( empty( $_POST[$field['reference']] ) ) {
                $invalid_fields[] = $field['reference'];
            }
        }
        if ( ! empty( $field['required_format'] ) ) {
            if ( ! preg_match( $field['required_format'], $_POST[$field['reference']] ) ) {
                $invalid_fields[] = $field['reference'];
            }

        }
    }
    return $invalid_fields;
}

/**
 * Inserts a row into a table, and returns the id of the inserted row. Is thread safe.
 * 
 * @return boolean The id of the inserted row, or false on failure.
 */
function insertIntoTableAndGetId( $table, $insertArray, $format = null ) {
    global $wpdb;
    $count = 0;
    while ( $count < 10 ) {
        $id = $wpdb->get_var( "SELECT id FROM `$table` ORDER BY id DESC" ) + 1;
        $insertArray['id'] = $id;
        $result = $wpdb->insert( $table, $insertArray, $format );

        if ( $result ) {
            return $id;
        }
        sleep(1);
        $count++;
    }
    unset($insertArray['id']);
    if ( $wpdb->insert( $table, $insertArray, $format )) {
        return $wpdb->get_var( "SELECT id FROM `$table` ORDER BY id DESC" );
    }
    return false;
}

function sendDataToDatabase($form_id, $year) {
    global $wpdb;
    $table_prefix = $wpdb->prefix .'assoforms_';

    $table_name_signup = $table_prefix . 'signup';

    // Create new signup in signup table
    $signup_id = insertIntoTableAndGetId( 
        $table_name_signup, 
        array( 
            'form_id' => $form_id, 
            'year' => $year,
        ) 
    );
    if ( ! $signup_id ) {
        echo 'Something went wrong when inserting into signup table!';
        return;
    }

    $table_name_response = $table_prefix . 'response';
    $table_name_form_fields = $table_prefix . 'form_field';
    $table_name_signup_x_responses = $table_prefix . 'signup_x_responses';

    $data = getFormDataFromDatabase($form_id, $year);
    foreach($data as $field) {
        // Create new response in response table
        // TODO use a unique id instead of a reference
        $field_reference = $field['reference'];
        $field_id = $wpdb->get_var( $wpdb->prepare("SELECT id FROM `$table_name_form_fields` WHERE `reference` = %s", $field_reference));
        $response = $_POST[$field_reference] ?: '';

        if ( is_array($response ) ) {
            $response = implode(";", $response);
        }

        $response_id = insertIntoTableAndGetId(
            $table_name_response, 
            array(
                'field_id' => $field_id, 
                'response' => $response,
            ),
            array('%d', '%s')
        );
        if ( ! $response_id ) {
            echo 'Something went wrong when inserting into response table!';
            return;
        }

        // Create new signup - response connection
        $wpdb->insert(
            $table_name_signup_x_responses, 
            array(
                'signup_id' => $signup_id, 
                'response_id' => $response_id,
            ),
            array('%d', '%s')
        );
    }
}