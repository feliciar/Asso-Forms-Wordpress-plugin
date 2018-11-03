<?php
/*
Plugin Name: Assö Anmälan
*/

/**
 * This should be called outside of page, when a get or post param is set.
 */
function downloadCSV() {
    $csv_output = "Felicia Rosell, Gårdvägen 9, André Strömsjåäö\nLuïc Karlsson,18245-12456,felcia@hej.hej";

    header("Expires: 0");
    header("Cache-Control: no-cache, no-store, must-revalidate"); 
    header('Cache-Control: pre-check=0, post-check=0, max-age=0', false); 
    header("Pragma: no-cache");	
    header("Content-type: text/csv");
    header("Content-type: text/csv");
    header("Content-Disposition:attachment; filename=test.csv");
    header("Content-Type: application/force-download");
    
    echo mb_convert_encoding($csv_output, 'UTF-16LE', 'UTF-8');
    exit();
}

function getTableData() {
    global $wpdb;
    //$table_name = $wpdb->prefix .'users';
    $table_name = 'test';

    $results = $wpdb->get_results( "SELECT text FROM $table_name");
    echo 'results: ' . $results;
    if(!empty($results)) {
        foreach($results as $row){
            echo $row->text . '<br>';
        }
    }
}

function insertTableData() {
    global $wpdb;
    $table_name = 'test';

    $wpdb->insert( 
        $table_name, 
        array( 
            'text' => $_GET['testget'], 
        ) 
    );
}

function createForm() {
    ?>
    * Obligatoriskt fält
    <form action="" method="post">
        <?php

        $data = getData();
        foreach($data as $field) {
            if ($field['field_type'] === 'text_input') {
                createTextInputElement( $field['title'], $field['reference'], $field['placeholder'], $field['required'] );
            }
            if ($field['field_type'] === 'integer_input') {
                createIntegerInputElement( $field['title'], $field['reference'], $field['placeholder'], $field['required'] );
            }
            if ($field['field_type'] === 'text_area') {
                createTextAreaElement( $field['title'], $field['reference'], $field['required'] );
            }
            if ($field['field_type'] === 'checkbox') {
                creatCheckboxElement( $field['title'], $field['reference'], $field['required'] );
            }
            if ($field['field_type'] === 'radio_buttons') {
                createRadioButtonsElement( $field['title'], $field['reference'], $field['options'], $field['required'] );
            }
            if ($field['field_type'] === 'dropdown') {
                createSelectElement( $field['title'], $field['reference'], $field['options'], $field['required'] );
            }
            if ($field['field_type'] === 'allergy_selector') {
                createAllergySelectorElement( $field['title'], $field['reference'], $field['options'], $field['required'] );
            }
        }
        ?>
        
        <br>
        
        <!-- TODO: captcha-->
        <input type="submit">
    </form>
    <?
}

function createSelectElement( $title, $name, $options, $required ) {
    createInputTitleElement( $title, $required );
    ?>
    <select name=<?php echo $name; ?>>
        <?php
        foreach( $options as $option ) {
            $selected = $_POST[$name] === $option['value'];
            echo '<option value=' . $option['value'] . ($selected ? ' selected' : '') . '>' . $option['display_name'] . '</option>';
        }
        ?>
    </select>
    <br>
    <?php
}

function createTextInputElement( $title, $name, $place_holder, $required ) {
    createInputTitleElement( $title, $required );
    $value = isset( $_POST[$name] ) ? htmlspecialchars($_POST[$name]) : '';
    echo '<input type="text" name=' . $name . ' placeholder="' . $place_holder . '" value="' . $value . '">';
    echo '<br>';
}

function createIntegerInputElement( $title, $name, $place_holder, $required ) {
    echo $title . ($required ? '*' : '' ) . '<br>';
    $value = isset( $_POST[$name] ) ? htmlspecialchars($_POST[$name]) : '';
    echo '<input type="number" name=' . $name . ' placeholder="' . $place_holder . '" value="' . $value . '">';
    echo '<br>';
}

function createRadioButtonsElement( $title, $name, $options, $required ) {
    createInputTitleElement( $title, $required );
    foreach( $options as $option ) {
        $checked = isset( $_POST[$name] ) && $_POST[$name] === $option['value'];
        createSingleRadioButtonElement( $name, $option['value'], $option['display_name'], $checked);
    }
    echo '<br>';
}

function createSingleRadioButtonElement( $name, $value, $display_name, $checked ) {
    echo '<input type="radio" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '> ' . $display_name . '<br>';
}

function createTextAreaElement( $title, $name, $required ) {
    createInputTitleElement( $title, $required );
    $value = isset( $_POST[$name] ) ? htmlspecialchars($_POST[$name]) : '';
    ?>
    <textarea style="text-align:left; margin:0; width:99%; max-width:250px; height:120px;" 
        id="" name=<?php echo $name ?> cols="30" rows="10"><?php echo $value; ?></textarea>
    <br>
    <?php
}

function creatCheckboxElement( $title, $name, $required ) {
    $checked = isset( $_POST[$name] );
    echo '<input type="checkbox" name=' . $name . ' value=' . 1 . ($checked ? ' checked' : '') . '> ';
    createInputTitleElement( $title, $required );
    echo '<br>';
}

function createAllergySelectorElement( $title, $name, $options, $required ) {
    createInputTitleElement( $title, $required );
    foreach( $options as $option ) {
        $checked = isset( $_POST[$name . '_' . $option['value']] );
        createSingleAllergySelectorElement( $name, $option['value'], $option['display_name'], $checked);
    }
}

function createSingleAllergySelectorElement( $name, $value, $display_name, $checked ) {
    echo '<input type="checkbox" name=' . $name . '_' . $value . ' value=' . 1 . ($checked ? ' checked' : '') . '> ';
    echo $display_name;
    echo '<br>';
}

function createInputTitleElement( $title, $field_reqired) {
    echo $title . ($field_reqired ? '*' : '' ) . '<br>';
}

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

function getData() {
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

add_shortcode('asso-form', function () {
    echo '<h1>Anmälan</h1>';

    if (formDataValidation()) {
        // TODO: send to database
        echo 'Tack för din anmälan!';
    } else {
        createForm();
    }
});
