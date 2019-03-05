<?php
get_header();
global $post, $houzez_local;
$post_meta_data  = get_post_custom($post->ID);
$agent_position = get_post_meta( get_the_ID(), 'fave_agent_position', true );
$agent_company = get_post_meta( get_the_ID(), 'fave_agent_company', true );
$agent_mobile = get_post_meta( get_the_ID(), 'fave_agent_mobile', true );
$agent_office_num = get_post_meta( get_the_ID(), 'fave_agent_office_num', true );
$agent_fax = get_post_meta( get_the_ID(), 'fave_agent_fax', true );
$agent_email = get_post_meta( get_the_ID(), 'fave_agent_email', true );
$agent_licenses = get_post_meta( get_the_ID(), 'fave_agent_license', true );
$agent_tax_no = get_post_meta( get_the_ID(), 'fave_agent_tax_no', true );
$agent_language = get_post_meta( get_the_ID(), 'fave_agent_language', true );
$agent_address = get_post_meta( get_the_ID(), 'fave_agent_address', true );

$agent_mobile_call = str_replace(array('(',')',' ','-'),'', $agent_mobile);
$agent_office_call = str_replace(array('(',')',' ','-'),'', $agent_office_num);

$agent_facebook = get_post_meta( get_the_ID(), 'fave_agent_facebook', true );
$agent_twitter = get_post_meta( get_the_ID(), 'fave_agent_twitter', true );
$agent_linkedin = get_post_meta( get_the_ID(), 'fave_agent_linkedin', true );
$agent_googleplus = get_post_meta( get_the_ID(), 'fave_agent_googleplus', true );
$agent_pinterest = get_post_meta( get_the_ID(), 'fave_agent_pinterest', true );
$agent_instagram = get_post_meta( get_the_ID(), 'fave_agent_instagram', true );
$agent_vimeo = get_post_meta( get_the_ID(), 'fave_agent_vimeo', true );
$agent_youtube = get_post_meta( get_the_ID(), 'fave_agent_youtube', true );
$agent_company_logo = get_post_meta( get_the_ID(), 'fave_agent_logo', true );
$fave_agent_website = get_post_meta( get_the_ID(), 'fave_agent_website', true );
$sticky_sidebar = houzez_option('sticky_sidebar');
$agent_tabs = houzez_option('agent_tabs');
$sortby = houzez_option('agent_listings_order');
$agent_phone_full = houzez_option('agent_phone_full');
if( isset( $_GET['sortby'] ) ) {
    $sortby = $_GET['sortby'];
}

$clickToShowPhone = $clickToShow = '';
if($agent_phone_full != 1 ) {
    $clickToShowPhone = 'clickToShowPhone';
    $clickToShow = 'clickToShow';
}
?>

<?php get_template_part('template-parts/page', 'title' ); ?>

<div class="row">
    <div class="col-sm-12">
        <?php if( have_posts() ): while( have_posts() ): the_post(); ?>
            <div class="profile-detail-block">
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="profile-image">
                            <?php
                            if( has_post_thumbnail( $post->ID ) ) {
                                the_post_thumbnail( 'houzez-image350_350' );
                            }else{
                                houzez_image_placeholder( 'houzez-image350_350' );
                            }
                            ?>

                            <?php if( !empty( $agent_company_logo ) ) {
                                $logo_url = wp_get_attachment_url( $agent_company_logo );
                                ?>
                            <div class="company-logo">
                                <img src="<?php echo esc_url( $logo_url ); ?>" alt="" width="105" height="75">
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="col-md-4 col-sm-8 col-xs-12">
                        <div class="profile-description">
                            <p class="agent-title"><?php the_title(); ?></p>
                            <p class="position">
                                <?php
                                if( !empty( $agent_position) ) { echo esc_attr( $agent_position ).' '; }

                                if( !empty( $agent_company) ) {
                                    echo $houzez_local['at'];
                                    echo ' ' . esc_attr( $agent_company );
                                }
                                ?>
                            </p>

                            <?php the_content(); ?>

                            <ul class="profile-contact">
                                <?php if( !empty($agent_licenses) ) { ?>
                                    <li><span><?php echo $houzez_local['licenses']; ?></span> <?php echo esc_attr( $agent_licenses ); ?></li>
                                <?php } ?>

                                <?php if( !empty($agent_office_num) ) { ?>
                                    <li><span><?php echo $houzez_local['office']; ?></span> <a href="tel:<?php echo esc_attr( $agent_office_call ); ?>"><span class="<?php echo $clickToShowPhone; ?>"><?php echo esc_attr( $agent_office_num ); ?></span></a></li>
                                <?php } ?>

                                <?php if( !empty( $agent_mobile ) ) { ?>
                                    <li><span><?php echo $houzez_local['mobile']; ?></span> <a href="tel:<?php echo esc_attr( $agent_mobile_call ); ?>"><span class="<?php echo $clickToShow; ?>"><?php echo esc_attr( $agent_mobile ); ?></span></a></li>
                                <?php } ?>

                                <?php if( !empty( $agent_fax ) ) { ?>
                                    <li><span><?php echo $houzez_local['fax']; ?></span> <a><?php echo esc_attr( $agent_fax ); ?></a></li>
                                <?php } ?>

                                <?php if( !empty($agent_tax_no) ) { ?>
                                    <li><span><?php echo $houzez_local['tax_number']; ?> </span> <?php echo esc_attr( $agent_tax_no ); ?></li>
                                <?php } ?>

                                <?php if( !empty($agent_language) ) { ?>
                                    <li><span><?php echo $houzez_local['languages']; ?> </span> <?php echo esc_attr( $agent_language ); ?></li>
                                <?php } ?>

                                <?php if( !empty( $agent_email ) ) { ?>
                                    <li><span><?php echo $houzez_local['email']; ?></span> <a href="mailto:<?php echo esc_attr( $agent_email ); ?>"><?php echo esc_attr( $agent_email ); ?></a></li>
                                <?php } ?>

                                <?php if( !empty( $fave_agent_website ) ) { ?>
                                    <li><span><?php echo $houzez_local['website']; ?></span> <a target="_blank" href="<?php echo esc_url( $fave_agent_website ); ?>"><?php echo esc_url( $fave_agent_website ); ?></a></li>
                                <?php } ?>
                            </ul>
                            <ul class="profile-social">
                                <?php if( !empty( $agent_facebook ) ) { ?>
                                    <li><a class="btn-facebook" href="<?php echo esc_url( $agent_facebook ); ?>" target="_blank"><i class="fa fa-facebook-square"></i></a></li>
                                <?php } ?>

                                <?php if( !empty( $agent_twitter ) ) { ?>
                                    <li><a class="btn-twitter" href="<?php echo esc_url( $agent_twitter ); ?>" target="_blank"><i class="fa fa-twitter-square"></i></a></li>
                                <?php } ?>

                                <?php if( !empty( $agent_linkedin ) ) { ?>
                                    <li><a class="btn-linkedin" href="<?php echo esc_url( $agent_linkedin ); ?>" target="_blank"><i class="fa fa-linkedin-square"></i></a></li>
                                <?php } ?>

                                <?php if( !empty( $agent_googleplus ) ) { ?>
                                    <li><a class="btn-google-plus" href="<?php echo esc_url( $agent_googleplus ); ?>" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>
                                <?php } ?>

                                <?php if( !empty( $agent_youtube ) ) { ?>
                                    <li><a class="btn-youtube" href="<?php echo esc_url( $agent_youtube ); ?>" target="_blank"><i class="fa fa-youtube-square"></i></a></li>
                                <?php } ?>

                                <?php if( !empty( $agent_instagram ) ) { ?>
                                    <li><a class="btn-instagram" href="<?php echo esc_url( $agent_instagram ); ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                <?php } ?>

                                <?php if( !empty( $agent_pinterest ) ) { ?>
                                    <li><a class="btn-pinterest" href="<?php echo esc_url( $agent_pinterest ); ?>" target="_blank"><i class="fa fa-pinterest-square"></i></a></li>
                                <?php } ?>

                                <?php if( !empty( $agent_vimeo ) ) { ?>
                                    <li><a class="btn-vimeo" href="<?php echo esc_url( $agent_vimeo ); ?>" target="_blank"><i class="fa fa-vimeo-square"></i></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">

                        <?php get_template_part( 'template-parts/agent-detail-contact'); ?>

                    </div>
                </div>
            </div>
        <?php endwhile; endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 list-grid-area container-contentbar">
        <div id="content-area">

            <div class="list-tabs table-list full-width">
                
                <?php 
                if($agent_tabs == 1) { 
                    $agent_detail_tab_1 = houzez_option('agent_detail_tab_1');
                    $agent_detail_tab_2 = houzez_option('agent_detail_tab_2');
                    $tab_1 = houzez_get_term_by( 'term_id', $agent_detail_tab_1, 'property_status' );
                    $tab_2 = houzez_get_term_by( 'term_id', $agent_detail_tab_2, 'property_status' );

                    $tab1_link = add_query_arg( 'tab', $tab_1->slug, get_permalink($post->ID) );
                    $tab2_link = add_query_arg( 'tab', $tab_2->slug, get_permalink($post->ID) );

                    $tab_all = $tab1_active = $tab2_active = $sortby = '';
                    if( isset( $_GET['tab'] ) && $_GET['tab'] == $tab_1->slug ) {
                        $tab1_active = "class = active";
                    } elseif( isset( $_GET['tab'] ) && $_GET['tab']  == $tab_2->slug ) {
                        $tab2_active = "class = active";
                    } else {
                        $tab_all = "class = active";
                    }

                    ?>
                <div class="tabs table-cell">
                    <ul>
                        <li>
                            <a href="<?php echo get_permalink($post->ID);?>" <?php echo esc_attr( $tab_all ); ?>><?php esc_html_e('ALL', 'houzez');?></a>
                        </li>
                        <?php if(!empty($agent_detail_tab_1)) { ?>
                        <li>
                            <a href="<?php echo esc_url($tab1_link);?>" <?php echo esc_attr( $tab1_active ); ?>><?php echo esc_attr($tab_1->name);?></a>
                        </li>
                        <?php } ?>

                        <?php if(!empty($agent_detail_tab_2)) { ?>
                        <li>
                            <a href="<?php echo esc_url($tab2_link);?>" <?php echo esc_attr( $tab2_active ); ?>><?php echo esc_attr($tab_2->name);?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
                <div class="sort-tab table-cell text-right agent-sort-tab" <?php if($agent_tabs == 0){ echo ''; }?>>
                    <?php esc_html_e( 'Sort by:', 'houzez' ); ?>
                    <select id="sort_properties" class="selectpicker bs-select-hidden" title="" data-live-search-style="begins" data-live-search="false">
                        <option value=""><?php esc_html_e( 'Default Order', 'houzez' ); ?></option>
                        <option <?php if( $sortby == 'a_price' ) { echo "selected"; } ?> value="a_price"><?php esc_html_e( 'Price (Low to High)', 'houzez' ); ?></option>
                        <option <?php if( $sortby == 'd_price' ) { echo "selected"; } ?> value="d_price"><?php esc_html_e( 'Price (High to Low)', 'houzez' ); ?></option>
                        <option <?php if( $sortby == 'a_date' ) { echo "selected"; } ?> value="a_date"><?php esc_html_e( 'Date Old to New', 'houzez' ); ?></option>
                        <option <?php if( $sortby == 'd_date' ) { echo "selected"; } ?> value="d_date"><?php esc_html_e( 'Date New to Old', 'houzez' ); ?></option>
                        <option <?php if( $sortby == 'featured_first' ) { echo "selected"; } ?> value="featured_first"><?php esc_html_e( 'Featured First', 'houzez' ); ?></option>
                    </select>
                </div>
            </div>

            <!--start property items-->
            <div class="property-listing list-view">
                <div class="row">
                    <?php
                    global $wp_query, $paged;
                    if ( is_front_page()  ) {
                        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                    }

                    $agent_id = $post->ID;
                    $tax_query = array();

                    if ( isset( $_GET['tab'] ) ) {
                        $tax_query[] = array(
                            'taxonomy' => 'property_status',
                            'field' => 'slug',
                            'terms' => $_GET['tab']
                        );
                    }

                    $agent_listing_args = array(
                        'post_type' => 'property',
                        'posts_per_page' => 10,
                        'post_status' => 'publish',
                        'paged' => $paged,
                        'meta_query' => array(
                            array(
                                'key' => 'fave_agents',
                                'value' => $agent_id,
                                'compare' => '='
                            )
                        )
                    );

                    $count = count($tax_query);
                    if($count > 0 ) {
                        $agent_listing_args['tax_query'] = $tax_query;
                    }

                    $agent_listing_args = houzez_prop_sort($agent_listing_args);

                    $the_query = new WP_Query( $agent_listing_args );

                    if ( $the_query->have_posts() ) :
                        while ( $the_query->have_posts() ) : $the_query->the_post();

                            get_template_part('template-parts/property-for-listing');

                        endwhile;
                        wp_reset_postdata();
                    else:
                        get_template_part('template-parts/property', 'none');
                    endif;
                    ?>
                </div>
            </div>
            <!--end property items-->

            <hr>
            <?php houzez_pagination( $the_query->max_num_pages, $range = 2 ); wp_reset_query(); ?>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 col-md-offset-0 col-sm-offset-3 container-sidebar <?php if( $sticky_sidebar['agent_sidebar'] != 0 ){ echo 'houzez_sticky'; }?>">
        <?php get_sidebar('houzez_agents'); ?>
    </div>
</div>

<?php get_footer(); ?>
