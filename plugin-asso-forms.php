<?php
/*
Plugin Name: Assö Anmälan
*/

add_shortcode('asso-form', function () {
    echo '<h1>Anmälan</h1>';

    if ( isset ($_GET['testget']) ) {
        echo 'Tack för din anmälan!';
    } else {
        ?>
        <form action="../test-har-tagit-emot-din-anmalan" method="get">
            <input type="text" name="testget">
            <input type="submit">
        </form>
        <?
    }
});
