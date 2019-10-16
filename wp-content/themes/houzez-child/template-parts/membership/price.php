<h3 class="side-block-title"> <?php esc_html_e( 'Membership Package', 'houzez' ); ?> </h3>

<?php
$currency_symbol = '€';
$where_currency = houzez_option( 'currency_position' );
$select_packages_link = houzez_get_template_link('template-advanced-package.php');

if( isset( $_GET['selected_package'] ) || isset( $_GET['state'] ) ) {
    $selected_package_id     = isset( $_GET['selected_package'] ) ? $_GET['selected_package'] : '';

    $payment1 = get_post_meta( $selected_package_id, 'fave_payment_option1', true );
    $payment2 = get_post_meta( $selected_package_id, 'fave_payment_option2', true );
    $payment3 = get_post_meta( $selected_package_id, 'fave_payment_option3', true );
    $payment4 = get_post_meta( $selected_package_id, 'fave_payment_option4', true );
    $payment5 = get_post_meta( $selected_package_id, 'fave_payment_option5', true );
    $payment6 = get_post_meta( $selected_package_id, 'fave_payment_option6', true );
    $payment7 = get_post_meta( $selected_package_id, 'fave_payment_option7', true );

    $pack_title = get_the_title( $selected_package_id ) . ' Package';

    if (isset($_GET['option'])) {
        $option = $_GET['option'];
    } else {
        if ($payment7 != '' && $payment7 > 0)
            $option = 'option7';
        if ($payment6 != '' && $payment6 > 0)
            $option = 'option6';
        if ($payment5 != '' && $payment5 > 0)
            $option = 'option5';
        if ($payment4 != '' && $payment4 > 0)
            $option = 'option4';
        if ($payment3 != '' && $payment3 > 0)
            $option = 'option3';
        if ($payment2 != '' && $payment2 > 0)
            $option = 'option2';
        if ($payment1 != '' && $payment1 > 0)
            $option = 'option1';
    }
    
    $pack_price = get_post_meta( $selected_package_id, 'fave_payment_' . $option, true );

    $tax = get_post_meta( $selected_package_id, 'fave_package_tax', true );

    $total_price = floor(($pack_price * (1 + (int)$tax / 100)) * 100) / 100;

    if (isset($_GET['state'])) {
        $value = explode(',', urldecode($_GET['state']));

        $pack_price = $value[0];
        $selected_package_id = $value[1];
        $option = $value[2];
    }


    $pack_listings           = get_post_meta( $selected_package_id, 'fave_package_listings', true );
    $pack_featured_listings  = get_post_meta( $selected_package_id, 'fave_package_featured_listings', true );
    $pack_unlimited_listings = get_post_meta( $selected_package_id, 'fave_unlimited_listings', true );
    $pack_billing_period     = get_post_meta( $selected_package_id, 'fave_billing_time_unit', true );
    $pack_billing_frquency   = get_post_meta( $selected_package_id, 'fave_billing_unit', true );
    $fave_package_popular    = get_post_meta( $selected_package_id, 'fave_package_popular', true );

    if( $pack_billing_frquency > 1 ) {
        $pack_billing_period .='s';
    }
    
    ?>
    <ul class="pkg-total-list">
        <?php if ( is_user_logged_in() ) { ?>
        <li>
            <span id="houzez_package_name" class="pull-left"><?php echo get_the_title( $selected_package_id ); ?></span>
            <span class="pull-right"><a href="<?php echo esc_url( $select_packages_link ); ?>"><?php esc_html_e( 'Change Package', 'houzez' ); ?></a></span>
        </li>
        <?php } else { ?>
            <li>
                <span id="houzez_package_name" class="pull-left"><?php esc_html_e( 'Package Name', 'houzez' ); ?></span>
                <span class="pull-right"><a><?php echo get_the_title( $selected_package_id ); ?></a></span>
            </li>
        <?php } ?>
        <li>
            <span class="pull-left"><?php esc_html_e( 'Package Time:', 'houzez' ); ?></span>
            <span class="pull-right"><strong><?php echo esc_attr( $pack_billing_frquency ).' '.HOUZEZ_billing_period( $pack_billing_period ); ?></strong></span>
        </li>
        <li>
            <span class="pull-left"><?php esc_html_e( 'Listing Included:', 'houzez' ); ?></span>
            <span class="pull-right">
                <?php if( $pack_unlimited_listings == 1 ) { ?>
                    <strong><?php esc_html_e( 'Unlimited Listings', 'houzez' ); ?></strong>
                <?php } else { ?>
                    <strong><?php echo esc_attr( $pack_listings ); ?></strong>
                <?php } ?>
            </span>
        </li>
        <li>
            <span class="pull-left"><?php esc_html_e( 'Featured Listing Included:', 'houzez' ); ?></span>
            <span class="pull-right"><strong><?php echo esc_attr( $pack_featured_listings ); ?></strong></span>
        </li>
        <li>
            <span class="pull-left"><?php esc_html_e( 'Package Price:', 'houzez' ); ?></span>
            <span class="pull-right"><?php echo $currency_symbol . ' ' . esc_attr( $pack_price ); ?></span>
        </li>
        <?php if ($tax != '') { ?>
        <li>
            <span class="pull-left"><?php echo esc_attr( $tax ); ?>%<?php esc_html_e( ' Tax:', 'houzez' ); ?></span>
            <span class="pull-right">
                <?php echo $currency_symbol . ' '; ?>
                <?php echo floor(($pack_price * (int)$tax / 100) * 100) / 100; ?>
            </span>
        </li>
        <?php } ?>
        <li>
            <span class="pull-left"><?php esc_html_e( 'Total Price:', 'houzez' ); ?></span>
            <span class="pull-right"><?php echo $currency_symbol . ' ' . esc_attr( $total_price ); ?></span>
        </li>
    </ul>
<?php } ?>
