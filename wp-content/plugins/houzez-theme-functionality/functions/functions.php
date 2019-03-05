<?php
if(!function_exists('houzez_check_for_taxonomy_plugin')) {
    function houzez_check_for_taxonomy_plugin($tax_setting_name) {

        if(class_exists('Houzez_Taxonomies')) {
            if(Houzez_Taxonomies::get_setting($tax_setting_name) != 'disabled') {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }
}

if(!function_exists('houzez_check_post_types_plugin')) {
    function houzez_check_post_types_plugin($post_type) {

        if(class_exists('Houzez_Post_Type')) {
            if(Houzez_Post_Type::get_setting($post_type) != 'disabled') {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }
}
if (!function_exists('houzez_theme_activation')) {
    function houzez_theme_activation() {
        $status = get_option( 'houzez_activation' );
        if(empty($status) && $status != 'none'){
            update_option( 'houzez_activation', 'none' );
        }
        ?>
        <div class="notice">
        <form action="" method="post">
            <h2 class="activation_title">Activate Houzez</h2>
            <p>To unlock all Houzez features please enter your purchase code below. To get your purchase code, login to ThemeForest, and go to Downloads section and, click on the green Download button next to Houzez and select “License certificate & purchase code” in any format. </p>
            <div id="title-wrap" class="input-text-wrap">
                <label id="api_key_prompt_text" class="prompt" for="api_key"> Enter your purchase key </label>
                <input id="api_key" name="api_key" autocomplete="off" type="text">
            </div>
            <?php echo wp_nonce_field( 'envato_api_nonce', 'envato_api_nonce_field' ,true, false ); ?>
            <input type="submit" name="submit" class="button button-primary button-hero" value="Activate"/>
        </form>
        <?php

        if( isset( $_POST['envato_api_nonce_field'] ) &&  wp_verify_nonce( $_POST['envato_api_nonce_field'], 'envato_api_nonce' ) && !empty($_POST['api_key'])){

            $purchase_key = $_POST['api_key'];
            $item_id = 15752549;
            $purchase_data = houzez_verify_envato_purchase_key( $purchase_key );

            if( isset($purchase_data['verify-purchase']['buyer']) && $purchase_data['verify-purchase']['item_id'] == $item_id) {
                update_option( 'houzez_activation', 'activated' );
                echo '<p class="successful"> '.__( 'Activated Successfully, reload page!', 'houzez' ).' </p>';
            } else{
                echo '<p class="error"> '.__( 'Invalid license key', 'houzez' ).' </p>';
            }



        }
        echo '</div>';
    }
    $status = get_option( 'houzez_activation' );
    if(empty($status) || $status != 'activated'){
        add_action( 'admin_notices', 'houzez_theme_activation' );
    }
}
function houzez_verify_envato_purchase_key($code_to_verify) {

    $username = 'favethemes';

    $api_key = '2ftjwxihndy1yojj9ato4y8yjl3p7qcx';

    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, "http://marketplace.envato.com/api/edge/". $username ."/". $api_key ."/verify-purchase:". $code_to_verify .".json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    $output = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $output;
}