<?php

function my_admin_menu() {
    $page_title = 'Assö';
    $menu_title = 'Assö';
    $capability = 'manage_options';
    $menu_slug = 'asso-form-admin-page';
    $function = 'download_signup_data_button';
    $icon_url = '';
    $position = null;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position  );

    /*
    $page_title = 'Assö Anmälningsinfo';
    $menu_title = 'Assö Anmälningsinfo';
    $menu_slug_submenu = 'asso-form-download-data-admin-page';
    $function = 'download_signup_data_button';

    // ( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
    add_submenu_page( $menu_slug, $page_title, $menu_title, $capability, $menu_slug_submenu, $function ); 
    */
}
add_action( 'admin_menu', 'my_admin_menu' );

function download_signup_data_button() {
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    ?>
    <div class='wrap'>

        <table class="form-table">

            <tbody>
            <h1>Deltagarlistor</h1>
            <tr>
                <h2>Sjösport 2019</h2>
                <form action="" method="post">
                    <input type="text" name="filename" value="deltagarlista-sjosport" style="display: none">
                    <input type="hidden" name="form-id" value="2" />
                    <input type="hidden" name="year" value="2019" />
                    <input type="hidden" name="info-type" value="basic" />
                    <input type="submit" name="get_csv" value="Ladda ner deltagarlista">
                </form>
            </tr>

            <tr>
                <h2>Knatte 2019</h2>
                <form action="" method="post">
                    <input type="hidden" name="filename" value="deltagarlista-knatte">
                    <input type="hidden" name="form-id" value="1" />
                    <input type="hidden" name="year" value="2019" />
                    <input type="hidden" name="info-type" value="basic" />
                    <input type="submit" name="get_csv" value="Ladda ner deltagarlista">
                </form>
            </tr>
            
            </tbody>
        </table>
    </div>
    <?php
}