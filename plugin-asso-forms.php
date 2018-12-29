<?php
/*
Plugin Name: Assö Anmälan
*/

include( plugin_dir_path( __FILE__ ) . 'admin/download-form-data.php');
include( plugin_dir_path( __FILE__ ) . 'forms/build-form.php');
include( plugin_dir_path( __FILE__ ) . 'forms/fetch-form-data.php');
include( plugin_dir_path( __FILE__ ) . 'signup/fetch-form-data.php');
include( plugin_dir_path( __FILE__ ) . 'signup/send-form-data.php');
include( plugin_dir_path( __FILE__ ) . 'forms/print-form-data.php');

function insertFormDataIntoDatabase( $data ) {
    global $wpdb;
    $table_prefix = $wpdb->prefix .'assoforms_';
    $table_form = $table_prefix . 'form';
    $table_forms_x_fields = $table_prefix . 'forms_x_fields';
    $table_form_field = $table_prefix . 'form_field';
    $table_fields_x_options = $table_prefix . 'form_fields_x_options';
    $table_option = $table_prefix . 'option';

    $form_id = 1;
    $position = 0;

    foreach($data as $field) {

        echo "SELECT id FROM $table_form_field WHERE `reference` = " . $field['reference'];
        $form_field_id = $wpdb->get_var( 
            $wpdb->prepare("SELECT id FROM $table_form_field WHERE `reference` = '%s'", $field['reference'])
        );
        echo 'id: ' . $form_field_id;
        if (! isset($form_field_id)) {
            $result = $wpdb->insert(
                $table_form_field, 
                array(
                    'reference' => $field['reference'] ?: '', 
                    'field_type' => $field['field_type'] ?: '',
                    'title' => $field['title'] ?: '',
                    'placeholder_text' => $field['placeholder'] ?: '',
                )
            );

            echo 'Insert! Success: ' . $result . '<br>';

            $form_field_id = $wpdb->get_var( 
                $wpdb->prepare("SELECT id FROM $table_form_field WHERE `reference` = '%s'", $field['reference'])
            );
        }
        echo 'form_field_id' . $form_field_id . '<br>';

        $result = $wpdb->get_var( 
            $wpdb->prepare("SELECT id FROM $table_forms_x_fields WHERE `form_id` = %d AND `form_field_id` = %d", $form_id, $form_field_id)
        );

        if (! isset($result)) {
            $success = $wpdb->insert(
                $table_forms_x_fields, 
                array(
                    'form_id' => $form_id, 
                    'form_field_id' => $form_field_id,
                    'position' => $position,
                ),
                array('%d', '%d', '%d')
            );
            echo 'Inserting form x field, success: ' . $success;
        }

        if ( isset( $field['options'] ) ) {
            foreach ( $field['options'] as $option ) {
                $option_id = $wpdb->get_var( 
                    $wpdb->prepare(
                        "SELECT id FROM $table_option WHERE `reference` = %s AND `display_name` = %s", 
                        $option['value'], $option['display_name']
                    )
                );

                if ( ! isset( $option_id ) ) {
                    $success = $wpdb->insert(
                        $table_option, 
                        array(
                            'reference' => $option['value'], 
                            'display_name' =>$option['display_name']
                        ),
                        array('%s', '%s')
                    );

                    echo 'Insrting option! scucess: ' . $success . '<br>';

                    $option_id = $wpdb->get_var( 
                        $wpdb->prepare(
                            "SELECT id FROM $table_option WHERE `reference` = %s AND `display_name` = %s", 
                            $option['value'], $option['display_name']
                        )
                    );
                }

                echo 'option_id: ' . $option_id . '<br>';

                $success = $wpdb->insert(
                    $table_fields_x_options, 
                    array(
                        'option_id' => $option_id, 
                        'form_field_id' => $form_field_id,
                    ),
                    array('%d', '%d')
                );
                var_dump( array(
                    'option_id' => $option_id, 
                    'form_field_id' => $form_field_id,
                ));

                echo 'Inserting fields_x_options scucess: ' . $success . '<br>';

            }
        }
        
        $position++;
    }
}

add_shortcode('asso-form', function ($atts, $content, $tag) {
    if ( ! isset($atts['form-id']) || ! isset($atts['year'])  ) {
        return;
    }

    $invalid_fields = [];

    if ( ! empty( $_POST )) {
        $invalid_fields = formDataValidation($atts['form-id'], $atts['year']);

        if ( empty( $invalid_fields )) {
            sendDataToDatabase($atts['form-id'], $atts['year']);
            echo 'Tack för din anmälan!';
            return;
        }
    }

    ob_start();
    createForm($atts['form-id'], $atts['year'], $invalid_fields);
    $output = ob_get_clean();
    return $output;
});
