<?php
/**
 * Template Name: Packages
 */
global $houzez_local, $current_user;

wp_get_current_user();

get_header();

get_template_part( 'template-parts/dashboard', 'menu' ); ?>

<div class="user-dashboard-right dashboard-with-panel">

    <?php get_template_part( 'template-parts/dashboard-title' ); ?>

    <div class="dashboard-content-area">
        <div class="container">

            <?php get_template_part('template-parts/create-listing-top'); ?>

            <div class="houzez-module package-table-module">
            <?php
            if( have_posts() ):
                while( have_posts() ): the_post();
                    $content = get_the_content();
                endwhile;
            endif;
            
            wp_reset_postdata();

            if( !empty($content) ) {
                the_content();
            } else {
                $args = array(
                    'post_type'       => 'houzez_packages',
                    'orderby'         => 'menu_order',
                    'order'           => 'ASC',
                    'posts_per_page'  => -1,
                    'meta_query'      =>  array(
                        array(
                            'key' => 'fave_package_visible',
                            'value' => 'yes',
                            'compare' => '=',
                        )
                    )
                );

                $fave_qry = new WP_Query($args);

                $first_pkg_column = '';

                $total_packages = 0;

                while( $fave_qry->have_posts() ): $fave_qry->the_post();
                    $flag = houzez_payment_option(get_the_ID());

                    if ($flag)
                        $total_packages++;
                endwhile;

                wp_reset_postdata();

                if( $total_packages == 3 ) {
                    $pkg_classes = 'col-md-4 col-sm-4 col-xs-12';
                } else if( $total_packages == 4 ) {
                    $pkg_classes = 'col-md-3 col-sm-6 col-xs-12';
                } else if( $total_packages == 2 ) {
                    $pkg_classes = 'col-md-4 col-sm-6 col-xs-12';
                } else if( $total_packages == 1 ) {
                    $pkg_classes = 'col-md-4 col-sm-12 col-xs-12';
                } else {
                    $pkg_classes = 'col-md-3 col-sm-6 col-xs-12';
                }

                $i = 0;
                while( $fave_qry->have_posts() ): $fave_qry->the_post();

                    $pack_listings           = get_post_meta( get_the_ID(), 'fave_package_listings', true );
                    $pack_unlimited_listings = get_post_meta( get_the_ID(), 'fave_unlimited_listings', true );
                    $pack_encrypt_doc = get_post_meta( get_the_ID(), 'fave_encrypt_doc', true );

                    $option1 = get_post_meta( get_the_ID(), 'fave_payment_option1', true );
                    $option2 = get_post_meta( get_the_ID(), 'fave_payment_option2', true );
                    $option3 = get_post_meta( get_the_ID(), 'fave_payment_option3', true );
                    $option4 = get_post_meta( get_the_ID(), 'fave_payment_option4', true );
                    $option5 = get_post_meta( get_the_ID(), 'fave_payment_option5', true );
                    $option6 = get_post_meta( get_the_ID(), 'fave_payment_option6', true );
                    $option7 = get_post_meta( get_the_ID(), 'fave_payment_option7', true );

                    $flag = houzez_payment_option(get_the_ID());

                    if ($flag) {
                        $price = get_post_meta( get_the_ID(), 'fave_package_price', true );

                        $cValue  = get_post_meta( get_the_ID(), 'fave_billing_custom_value', true );
                        $cOption = get_post_meta( get_the_ID(), 'fave_billing_custom_option', true );

                        $i++;

                        $process_link = '';
                        $payment_page_link = houzez_get_template_link('template-advanced-payment.php');
                        $process_link = add_query_arg( 'selected_package', get_the_ID(), $payment_page_link );

                        if( $i == 1 && $total_packages == 2 ) {
                            $first_pkg_column = 'col-md-offset-2 col-sm-offset-0';
                        } else if (  $i == 1 && $total_packages == 1  ) {
                            $first_pkg_column = 'col-md-offset-4 col-sm-offset-0';
                        } else {
                            $first_pkg_column = '';
                        }
                    ?>

                    <div class="<?php echo esc_attr( $pkg_classes.' '.$first_pkg_column ); ?>">
                        <div class="package-block">
                            <div class="package-head row">
                                <div class="col-md-6">                                    
                                    <h3 class="package-title"><?php the_title(); ?></h3>
                                    <h3 class="package-title"><?php echo '€' . $price; ?></h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="package-link">
                                        <a href="<?php echo esc_url($process_link); ?>" class="btn btn-primary btn-lg">
                                            <?php echo esc_attr('Select'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="package-content">
                                <?php if( $pack_unlimited_listings == 1 ) { ?>
                                    <p>
                                        <?php echo $houzez_local['unlimited_listings']; ?>
                                    </p>
                                <?php } else { ?>
                                    <?php if ($pack_listings != '' && $pack_listings > 0) { ?>
                                    <p>
                                        <?php 
                                            if ($pack_listings == 1)
                                                echo esc_attr('One Listing');
                                            else if ($pack_listings > 1 && $pack_listings < 6)
                                                echo esc_attr('1-5 Listings');
                                            else if ($pack_listings > 5 && $pack_listings < 11)
                                                echo esc_attr('6-10 Listings');
                                            else
                                                echo esc_attr($pack_listings . ' Listings');
                                        ?>
                                    </p>                                                
                                    <?php }?>
                                <?php } ?>
                                <?php if ($pack_encrypt_doc == 1) { ?>
                                    <p>
                                        <?php echo esc_attr('Encryption and Document Storage');?>
                                    </p>
                                <?php } ?>

                                <?php if ($option1 != '' && $option1 > 0) { ?>
                                <p>
                                    <?php echo esc_attr('Daily €' . $option1); ?>
                                </p>
                                <?php } ?>

                                <?php if ($option2 != '' && $option2 > 0) { ?>
                                <p>
                                    <?php echo esc_attr('Weekly €' . $option2); ?>
                                </p>
                                <?php } ?>

                                <?php if ($option3 != '' && $option3 > 0) { ?>
                                <p>
                                    <?php echo esc_attr('Monthly €' . $option3); ?>
                                </p>
                                <?php } ?>

                                <?php if ($option4 != '' && $option4 > 0) { ?>
                                <p>
                                    <?php echo esc_attr('Every 3 months €' . $option4); ?>
                                </p>
                                <?php } ?>

                                <?php if ($option5 != '' && $option5 > 0) { ?>
                                <p>
                                    <?php echo esc_attr('Every 6 months €' . $option5); ?>
                                </p>
                                <?php } ?>

                                <?php if ($option6 != '' && $option6 > 0) { ?>
                                <p>
                                    <?php echo esc_attr('Yearly €' . $option6); ?>
                                </p>
                                <?php } ?>

                                <?php if ($option7 != '' && $option7 > 0) { ?>
                                <p>
                                    <?php
                                        $arr = array(
                                            'custom1' => 'days',
                                            'custom2' => 'weeks',
                                            'custom3' => 'months'
                                        );

                                        $str = $cValue . ' ' . $arr[$cOption];

                                        echo esc_attr($str . ' €' . $option7);
                                    ?>
                                </p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php } ?>
                <p style="font-size: 20px;">
                    Looking to list more than one property with Affordable Mallorca?&nbsp;
                    <a href="https://amstaging.unfstaging.com/contact">Contact Us</a> to learn about Enterprise Packages. 
                </p>

                <p style="font-size: 12px;">
                    Affordable Mallorca reserves the right to remove any listings deemed to be added without authorization of property owner.&nbsp;
                    Furthermore, we reserve the right to remove any duplicate listings regardless of intent.
                </p>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>