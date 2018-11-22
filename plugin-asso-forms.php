<?php
/*
Plugin Name: Assö Anmälan
*/

include( plugin_dir_path( __FILE__ ) . 'database/get-form-data.php');
include( plugin_dir_path( __FILE__ ) . 'database/send-form-data.php');
include( plugin_dir_path( __FILE__ ) . 'forms/build-form.php');
include( plugin_dir_path( __FILE__ ) . 'print-form-data.php');

function getDataOld() {
    $data = array();

    $data[] = array(
        'reference'=> 'full_name',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'Elevens namn',
        'placeholder' => 'Förnamn Efternamn',
    );
    $data[] = array(
        'reference'=> 'social_security_number',
        'required' => true,
        'field_type' => 'text_input',
        'required_format' => '/[0-9]{6}-[0-9]{4}/',
        'title' => 'Personnummer',
        'placeholder' => 'ÅÅMMDD-NNNN',
    );
    $data[] = array(
        'reference'=> 'address',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'Adress',
    );
    $data[] = array(
        'reference'=> 'postal_code',
        'required' => true,
        'field_type' => 'text_input',
        'required_format' => '/([0-9]{5})|([0-9]{3} [0-9]{2})/',
        'title' => 'Postnummer',
        'placeholder' => 'NNN NN',
    );
    $data[] = array(
        'reference'=> 'postal_area',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'Postort',
    );
    $data[] = array(
        'reference'=> 'home_phonenumber',
        'required' => false,
        'field_type' => 'text_input',
        'title' => 'Hemtelefon (inkl. riktnummer)',
    );
    $data[] = array(
        'reference'=> 'previous_years_at_asso',
        'required' => true,
        'field_type' => 'dropdown',
        'title' => 'Elevens antal tidigare år på Assö',
        'options' => [
            array('value'=>'0', 'display_name'=>'0'),
            array('value'=>'1', 'display_name'=>'1'),
            array('value'=>'2', 'display_name'=>'2'),
            array('value'=>'3', 'display_name'=>'3'),
            array('value'=>'4', 'display_name'=>'4'),
            array('value'=>'5', 'display_name'=>'5'),
            array('value'=>'6', 'display_name'=>'6'),
            array('value'=>'7', 'display_name'=>'7'),
            array('value'=>'8', 'display_name'=>'8'),
            array('value'=>'9', 'display_name'=>'9'),
            array('value'=>'10', 'display_name'=>'10'),
        ],
    );
    $data[] = array(
        'reference'=> 'member_in_allergy_association',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'Medlem i följande Astma- och Allergiförening (om ej medlem skriv "ej medlem")',
    );
    $data[] = array(
        'reference'=> 'personal_contact_1',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'Närmast anhörig under lägertiden',
    );
    $data[] = array(
        'reference'=> 'personal_contact_1_phonenumber',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'Telefon till närmast anhörig',
    );
    $data[] = array(
        'reference'=> 'personal_contact_1_email',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'E-post till närmast anhörig',
    );
    $data[] = array(
        'reference'=> 'doctor',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'Behandlande läkare för eleven',
        'placeholder' => 'Namn på läkaren',
    );
    $data[] = array(
        'reference'=> 'medical_clinic',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'Klinik vid vilken eleven har sin kontakt',
        'placeholder' => 'Namn på kliniken',
    );
    $data[] = array(
        'reference'=> 'medical_contact_phonenumber',
        'required' => true,
        'field_type' => 'text_input',
        'title' => 'Kontaktuppgifter till kliniken och/eller behandlande läkare',
        'placeholder' => 'Telefonnummer',
    );
    $data[] = array(
        'reference'=> 'non_critical_allergies',
        'field_type' => 'allergy_selector',
        'title' => 'Inget av följande sju livsmedel förekommer i kökshanteringen på lägret. Kryssa ändå för om barnet inte tål några av dessa.',
        'options' => [
            array('value'=>'fish', 'display_name'=>'Fisk'),
            array('value'=>'peanuts', 'display_name'=>'Jordnötter'),
            array('value'=>'almond', 'display_name'=>'Mandel'),
            array('value'=>'nuts', 'display_name'=>'Nötter'),
            array('value'=>'shellfish', 'display_name'=>'Skalddjur'),
            array('value'=>'soy', 'display_name'=>'Soja'),
            array('value'=>'egg', 'display_name'=>'Ägg'),
        ],
    );
    $data[] = array(
        'reference'=> 'allergies',
        'field_type' => 'allergy_selector',
        'title' => 'Kryssa för de livsmedel som eleven inte tål.',
        'options' => [
            array('value'=>'pineapple', 'display_name'=>'Ananas'),
            array('value'=>'apricote', 'display_name'=>'Aprikos'),
            array('value'=>'avocado', 'display_name'=>'Avokado'),
            array('value'=>'banana', 'display_name'=>'Banan'),
            array('value'=>'buckwheat', 'display_name'=>'Bovete'),
            array('value'=>'broccoli', 'display_name'=>'Broccoli'),
            array('value'=>'bulgur', 'display_name'=>'Bulgur'),
            array('value'=>'beans', 'display_name'=>'Bönor'),
            array('value'=>'citrus', 'display_name'=>'Citrus'),
            array('value'=>'dill', 'display_name'=>'Dill'),
            array('value'=>'cuecumber', 'display_name'=>'Gurka'),
            array('value'=>'rasberry', 'display_name'=>'Hallon'),
            array('value'=>'oat', 'display_name'=>'Havre'),
            array('value'=>'millet', 'display_name'=>'Hirs'),
            array('value'=>'honey', 'display_name'=>'Honung'),
            array('value'=>'strawberries', 'display_name'=>'Jordgubbar'),
            array('value'=>'cocoa', 'display_name'=>'Kakao'),
            array('value'=>'kiwi', 'display_name'=>'Kiwi'),
            array('value'=>'coconut', 'display_name'=>'Kokos'),
            array('value'=>'gooseberry', 'display_name'=>'Krusbär'),
            array('value'=>'cauliflower', 'display_name'=>'​​Kål (blomkål)'),
            array('value'=>'brussels_sprouts', 'display_name'=>'​​Kål (brysselkål)'),
            array('value'=>'kale', 'display_name'=>'​​Kål (grönkål)'),
            array('value'=>'red_cabbage', 'display_name'=>'​​Kål (rödkål)'),
            array('value'=>'cabbage', 'display_name'=>'​​Kål (vitkål)'),
            array('value'=>'swede', 'display_name'=>'Kålrot'),
            array('value'=>'cherry', 'display_name'=>'Körsbär'),
            array('value'=>'lactose_lecithin', 'display_name'=>'Laktos​​​​​​​​​​Lecitin'),
            array('value'=>'yellow_onion', 'display_name'=>'​​​​​​​​​​​​Lök (gul)'),
            array('value'=>'red_onion', 'display_name'=>'Lök (röd)'),
            array('value'=>'corn', 'display_name'=>'Majs'),
            array('value'=>'melon', 'display_name'=>'Melon'),
            array('value'=>'milk_protein_cow', 'display_name'=>'Mjölkprotein (komjölk)'),
            array('value'=>'carrot_cooked', 'display_name'=>'Morot (kokt)'),
            array('value'=>'carrow_raw', 'display_name'=>'​​​​Morot​ (rå)'),
            array('value'=>'rose_hip_soup', 'display_name'=>'Nypon(soppa)'),
            array('value'=>'oil_olive', 'display_name'=>'​​​​​​​​​​Olja (olivolja)'),
            array('value'=>'oil_palm', 'display_name'=>'​​​​​​​​​​Olja (palmolja)'),
            array('value'=>'oil_rapeseed', 'display_name'=>'​​​​​​​​​​Olja (rapsolja)'),
            array('value'=>'oil_sunflower', 'display_name'=>'​​​​​​​​​​Olja (solrosolja)'),
            array('value'=>'pepper_cooked', 'display_name'=>'​​​​​​Paprika​​ (kokt)'),
            array('value'=>'pepper_raw', 'display_name'=>'​​​​Paprika​​ (rå)'),
            array('value'=>'peach', 'display_name'=>'Persika'),
            array('value'=>'parsley', 'display_name'=>'Persilja'),
            array('value'=>'plum_cooked', 'display_name'=> '​​​​​​Plommon​​ (kokt)'),
            array('value'=>'plum_raw', 'display_name'=> '​​​​​​Plommon​​ (rått)'),
            array('value'=>'leek', 'display_name'=>'Purjolök'),
            array('value'=>'pear_cooked', 'display_name'=>'Päron​​ (kokt)'),
            array('value'=>'pear_raw', 'display_name'=>'​​​​Päron (rått)'),
            array('value'=>'quinoa', 'display_name'=>'Quinoa'),
            array('value'=>'rice', 'display_name'=>'Ris'),
            array('value'=>'rye', 'display_name'=>'Råg'),
            array('value'=>'celery', 'display_name'=>'Selleri'),
            array('value'=>'tomato_cooked', 'display_name'=>'​​​​​​​​​​​​​​​​Tomat​​ (kokt)'),
            array('value'=>'tomato_raw', 'display_name'=>'Tomat (rå)'),
            array('value'=>'wheat', 'display_name'=>'Vete'),
            array('value'=>'wheat_germ', 'display_name'=>'Vetegroddar'),
            array('value'=>'grapes', 'display_name'=>'Vindruvor'),
            array('value'=>'garlic', 'display_name'=>'Vitlök'),
            array('value'=>'apple_cooked', 'display_name'=>'Äpple (kokt)'),
            array('value'=>'apple_raw', 'display_name'=>'Äpple (kokt)'),
            array('value'=>'peas', 'display_name'=>'Ärtor​​'),
        ],
    );
    $data[] = array(
        'reference'=> 'e_number_allergies',
        'field_type' => 'text_input',
        'title' => 'E-nummer eleven inte tål (lista samtliga)',
    );
    $data[] = array(
        'reference'=> 'seed_allergies',
        'field_type' => 'text_input',
        'title' => 'Fröer eleven inte tål (lista samtliga)',
    );
    $data[] = array(
        'reference'=> 'other_allergies',
        'field_type' => 'allergy_selector',
        'title' => 'Övriga allergier',
        'options' => [
            array('value'=>'astma', 'display_name'=>'Astma'),
            array('value'=>'scents', 'display_name'=>'Dofter'),
            array('value'=>'eczema', 'display_name'=>'Eksem'),
            array('value'=>'hay_fever', 'display_name'=>'Hösnuva'),
            array('value'=>'chemicals', 'display_name'=>'Kemiska ämnen​​'),
            array('value'=>'contact_allergy', 'display_name'=>'Kontaktallergi'),
            array('value'=>'pollen', 'display_name'=>'Pollen'),
            array('value'=>'fur_animals', 'display_name'=>'Pälsdjur'),
        ],
    );

    $data[] = array(
        'reference'=> 'other_important_information',
        'field_type' => 'text_area',
        'title' =>  'Övriga allergier eller annan information för att eleven skall få bästa möjliga lägervistelse. ' .
                    'Vi behöver veta om eleven har psykiatriska diagnoser eller utreds för sådan. Vi behöver även veta om eleven har samarbetssvårigheter, ' .
                    'är mörkrädd, sängvätare eller annat motsvarande. Återigen vill vi poängtera att samtliga uppgifter hanteras konfidentiellt.',
    );
    $data[] = array(
        'reference'=> 'medicines_and_dosage',
        'field_type' => 'text_area',
        'title' =>  'Elevens samtliga mediciner samt dosering för respektive medicin',
    );
    $data[] = array(
        'reference'=> 'medicine_allergies',
        'required' => false,
        'field_type' => 'text_input',
        'title' => 'Eleven tål inte följande mediciner eller preparat:',
    );
    $data[] = array(
        'reference'=> 'vaccinations',
        'required' => false,
        'field_type' => 'text_input',
        'title' => 'Eleven är vaccinerad mot följande:',
    );
    $data[] = array(
        'reference'=> 'swim_ability',
        'required' => true,
        'field_type' => 'radio_buttons',
        'title' => 'Eleven är simkunnig:',
        'options' => [
            array('value'=>'no', 'display_name'=>'Ej simkunnig'),
            array('value'=>'at_least_100_meters', 'display_name'=>'Minst 100 meter'),
            array('value'=>'at_least_200_meters', 'display_name'=>'Minst 200 meter'),
        ],
    );
    $data[] = array(
        'reference'=> 'has_pets',
        'required' => false,
        'field_type' => 'radio_buttons',
        'title' => 'Har ni pälsdjur hemma?',
        'options' => [
            array('value'=>'yes', 'display_name'=>'Ja'),
            array('value'=>'no', 'display_name'=>'Nej'),
        ],
    );
    $data[] = array(
        'reference'=> 'how_did_you_find_asso',
        'required' => true,
        'field_type' => 'dropdown',
        'title' => 'För nya elever: hur fick ni kännedom om lägret?',
        'options' => [
            array('value'=>'choose', 'display_name'=>'Välj en av följande'),
            array('value'=>'friend', 'display_name'=>'Kompis'),
            array('value'=>'astma_och_allergiforbundet', 'display_name'=>'Astma- och Allergiförbundet'),
            array('value'=>'allergia', 'display_name'=>'Allergia'),
            array('value'=>'lanssvalan', 'display_name'=>'Länssvalan'),
            array('value'=>'hospital_reception', 'display_name'=>'Läkarmottagning'),
            array('value'=>'hospital', 'display_name'=>'Sjukhus'),
            array('value'=>'internet', 'display_name'=>'Internet'),
            array('value'=>'facebook', 'display_name'=>'Facebook'),
            array('value'=>'instagram', 'display_name'=>'Instagram'),
            array('value'=>'acquaintants', 'display_name'=>'Bekanta'),
        ],
    );
    $data[] = array(
        'reference'=> 'accepted_becoming_member_usf_r',
        'required' => true,
        'field_type' => 'checkbox',
        'title' =>  'Eleven vill bli medlem i Ungdomsförbundet Sveriges Flotta, ' .
                    'Roslagsdistriktet vilket krävs för deltagande på Assö Seglarskola då det är en aktivitet för medlemmar.',
    );
    $data[] = array(
        'reference'=> 'approved_publishing_photos',
        'required' => true,
        'field_type' => 'dropdown',
        'title' => 'Tillåter du/ni att bilder på ert/era barn publiceras i media (hemsida Facebook Instagram m.fl.). Vi publicerar aldrig efternamn.',
        'options' => [
            array('value'=>'yes', 'display_name'=>'Ja'),
            array('value'=>'no', 'display_name'=>'Nej'),
        ],
    );
    $data[] = array(
        'reference'=> 'attest_correctness_in_answers_and_is_aware_of_participation_fee',
        'required' => true,
        'field_type' => 'checkbox',
        'title' =>  'Jag intygar härmed att ovanstående är sant och är införstådd med att deltagaravgiften som angetts under sidan "Anmälan" är beräknad på att Assö Seglarskola får bidrag från Stockholms Läns Landsting som är beroende av att eleven har astma/allergi samt en etablerad läkarkontakt. Saknas läkarkontakten kan en högre deltagaravgift komma att tas ut.',
    );
    $data[] = array(
        'reference'=> 'consent_personal_data_policy',
        'required' => true,
        'field_type' => 'checkbox',
        'title' =>  ' Jag ger medgivande att datan ovan hanteras i enighet med Assö Seglarskolas personuppgiftspolicy (se www.assoseglarskola.se/personuppgiftspolicy/ för denna)',
    );

   
    
    return $data;
}

function insertFormDataIntoDatabase() {
    global $wpdb;
    $table_prefix = $wpdb->prefix .'assoforms_';
    $table_form = $table_prefix . 'form';
    $table_forms_x_fields = $table_prefix . 'forms_x_fields';
    $table_form_field = $table_prefix . 'form_field';
    $table_fields_x_options = $table_prefix . 'form_fields_x_options';
    $table_option = $table_prefix . 'option';

    $form_id = 1;

    $data = getDataOld();

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

    ?>
    <form action="" method="post">
        <input type="text" name="filename" value="läger" style="display: none">
        <input type="submit" name="get_csv" value="Ladda ner anmälningsdata">
    </form>
    <?php

    echo '<h1>Anmälan</h1>';

    if (function_exists('formDataValidation') && formDataValidation()) {
        sendDataToDatabase($atts['form-id'], $atts['year']);
        echo 'Tack för din anmälan!';
        getSignUpDataFromDatabase($atts['form-id'], $atts['year']);
    } else {
        createForm($atts['form-id'], $atts['year']);
        getSignUpDataFromDatabase($atts['form-id'], $atts['year']);
    }
});
