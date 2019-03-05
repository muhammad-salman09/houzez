<?php
/**
 * Price Table shortcode
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 30/09/16
 * Time: 12:20 AM
 * Since V1.4.0
 */
if( !function_exists('houzez_price_table') ) {
    function houzez_price_table($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'package_id' => '',
            'package_data' => '',
            'package_popular' => '',
            'package_name' => '',
            'package_price' => '',
            'package_decimal' => '',
            'package_currency' => '',
            'price_table_content' => '',
            'package_btn_text' => '',
        ), $atts));

        ob_start();

        $houzez_local = houzez_get_localization();
        $payment_page_link = houzez_get_template_link('template/template-payment.php');
        $payment_process_link = add_query_arg( 'selected_package', $package_id, $payment_page_link );
        $package_custom_link    = get_post_meta( $package_id, 'fave_package_custom_link', true );

        if( $package_popular == "yes" ) {
            $is_popular = 'active';
        } else {
            $is_popular = '';
        }

        if(!empty($package_custom_link)) {
            $payment_process_link = $package_custom_link;
        }

        if( $package_data == 'dynamic' ) {

            $currency_symbol = houzez_option( 'currency_symbol' );
            $where_currency = houzez_option( 'currency_position' );

            $pack_price              = get_post_meta( $package_id, 'fave_package_price', true );
            $pack_listings           = get_post_meta( $package_id, 'fave_package_listings', true );
            $pack_featured_listings  = get_post_meta( $package_id, 'fave_package_featured_listings', true );
            $pack_unlimited_listings = get_post_meta( $package_id, 'fave_unlimited_listings', true );
            $pack_billing_period     = get_post_meta( $package_id, 'fave_billing_time_unit', true );
            $pack_billing_frquency   = get_post_meta( $package_id, 'fave_billing_unit', true );
            $pack_package_tax   = get_post_meta( $package_id, 'fave_package_tax', true );
            $fave_package_popular    = get_post_meta( $package_id, 'fave_package_popular', true );

            if(class_exists('Houzez_Currencies')) {
                $multi_currency = houzez_option('multi_currency');
                $default_currency = houzez_option('default_multi_currency');
                if(empty($default_currency)) {
                    $default_currency = 'USD';
                }

                if($multi_currency == 1) {
                    $currency = Houzez_Currencies::get_currency_by_code($default_currency);
                    $currency_symbol = $currency['currency_symbol'];
                }
            }

            if( $pack_billing_frquency > 1 ) {
                $pack_billing_period .='s';
            }
            if ( $where_currency == 'before' ) {
                $package_price = '<span class="price-before">'.$currency_symbol.'</span><span class="price-number">'.$pack_price.'</span>';
            } else {
                $package_price = '<span class="price-number">'.$pack_price.'</span><span class="price-before">'.$currency_symbol.'</span>';
            }

        ?>
            <div class="package-block <?php esc_attr_e( $is_popular ); ?>">
                <h3 class="package-title"><?php echo get_the_title( $package_id ); ?></h3>
                <h1 class="package-price">
                    <?php echo $package_price; ?>
                </h1>
                <ul class="package-list">
                    <li><i class="fa fa-check"></i> <?php echo $houzez_local['time_period'].':'; ?> <strong><?php echo esc_attr( $pack_billing_frquency ).' '.HOUZEZ_billing_period( $pack_billing_period ); ?></strong></li>
                    <li><i class="fa fa-check"></i> <?php echo $houzez_local['properties'].':'; ?>
                        <?php if( $pack_unlimited_listings == 1 ) { ?>
                            <strong><?php echo $houzez_local['unlimited_listings']; ?></strong>
                        <?php } else { ?>
                            <strong><?php echo esc_attr( $pack_listings ); ?></strong>
                        <?php } ?>
                    </li>
                    <li><i class="fa fa-check"></i> <?php echo $houzez_local['featured_listings'].':'; ?> <strong><?php echo esc_attr( $pack_featured_listings ); ?></strong></li>
                    <?php if( !empty($pack_package_tax)) { ?>
                        <li><i class="fa fa-check"></i> <?php echo $houzez_local['package_taxes'].':'; ?> <strong><?php echo esc_attr( $pack_package_tax ); ?></strong></li>
                    <?php } ?>
                </ul>
                <div class="package-link">
                    <a href="<?php echo esc_url($payment_process_link); ?>" class="btn btn-primary btn-lg"><?php echo $houzez_local['get_started']; ?></a>
                </div>
            </div>

        <?php
        } else { ?>
            <div class="package-block <?php esc_attr_e( $is_popular ); ?>">
                <h3 class="package-title"><?php echo esc_attr( $package_name ); ?></h3>
                <h1 class="package-price">
                    <span class="price-before"><?php echo esc_attr( $package_currency ); ?></span><span class="price-number"><?php echo esc_attr( $package_price ); ?></span><span class="price-before"><?php echo esc_attr( $package_decimal ); ?></span>
                </h1>
                <?php echo $content; ?>
                <div class="package-link">
                    <a href="<?php echo esc_url($payment_process_link); ?>" class="btn btn-primary btn-lg"><?php echo esc_attr( $package_btn_text ); ?></a>
                </div>
            </div>
        <?php
        }

        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }
    add_shortcode('houzez-price-table', 'houzez_price_table');
}