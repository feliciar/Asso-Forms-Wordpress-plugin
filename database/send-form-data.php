<?php
function formDataValidation() {
    if ( empty( $_POST) ) {
        return false;
    }
    /*
    $data = getData();
    foreach( $data as $field ) {
        if ( $field['required'] ) {
            if ( empty( $_POST[$field['reference']] ) ) {
                return false;
            }
        }
        if ( ! empty( $field['required_format'] ) ) {
            if ( ! preg_match( $field['required_format'], $_POST[$field['reference']] ) ) {
                return false;
            }

        }
    }*/
    return true;
}

function sendDataToDatabase() {
    global $wpdb;
    $table_prefix = $wpdb->prefix .'assoforms_';

    $table_name_signup = $table_prefix . 'signup';

    $form_id = 1;
    $year = 2019;

    // Create new signup in signup table
    $wpdb->insert( 
        $table_name_signup, 
        array( 
            'form_id' => $form_id, 
            'year' => $year,
        )
    );
    $signup_id = $wpdb->get_var( $wpdb->prepare("SELECT id FROM `$table_name_signup` WHERE `form_id` = %d AND `year` = %s ORDER BY id DESC", $form_id, $year));

    $table_name_response = $table_prefix . 'response';
    $table_name_form_fields = $table_prefix . 'form_field';
    $table_name_signup_x_responses = $table_prefix . 'signup_x_responses';

    $data = getData();
    foreach($data as $field) {
        // Create new response in response table
        $field_reference = $field['reference'];
        $field_id = $wpdb->get_var( $wpdb->prepare("SELECT id FROM `$table_name_form_fields` WHERE `reference` = %s", $field_reference));
        $response = $_POST[$field_reference];
        $wpdb->insert(
            $table_name_response, 
            array(
                'field_id' => $field_id, 
                'response' => $response,
            ),
            array('%d', '%s')
        );

        // Create new signup - response connection
        $response_id = $wpdb->get_var( $wpdb->prepare("SELECT id FROM `$table_name_response` WHERE `field_id` = %d AND `response` = %s ORDER BY id DESC", $field_id, $response));
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