<?php

function createForm($form_id, $year) {
    ?>
    * Obligatoriskt fält
    <form action="" method="post">
        <?php

        $data = getFormDataFromDatabase($form_id, $year);
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
    echo '<input type="radio" name="' . $name . '" value="' . $display_name . '"' . ($checked ? ' checked' : '') . '> ' . $display_name . '<br>';
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
        $checked = isset( $_POST[$name][$option['value']] );
        createSingleAllergySelectorElement( $name, $option['value'], $option['display_name'], $checked);
    }
}

function createSingleAllergySelectorElement( $name, $value, $display_name, $checked ) {
    echo '<input type="checkbox" name=' . $name . '[]' . ' value=' . $display_name . ($checked ? ' checked' : '') . '> ';
    echo $display_name;
    echo '<br>';
}

function createInputTitleElement( $title, $field_reqired) {
    echo $title . ($field_reqired ? '*' : '' ) . '<br>';
}
