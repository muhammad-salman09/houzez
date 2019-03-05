<?php
/**
 * Partners
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 07/01/16
 * Time: 7:00 PM
 */
if( !function_exists('houzez_partners') ) {
    function houzez_partners($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'custom_title' => '',
            'custom_subtitle' => '',
            'posts_limit' => '',
            'offset' => '',
            'orderby' => '',
            'order' => ''
        ), $atts));

        ob_start();
        $houzez_local = houzez_get_localization();

        $args = array(
            'post_type' => 'houzez_partner',
            'posts_per_page' => $posts_limit,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset
        );
        $wp_qry = new WP_Query($args);
        ?>

        <!--start agents carousel module-->
        <div id="partner-carousel-module" class="houzez-module agents-carousel-module">

            <div class="row">
                <div class="col-sm-12">
                    <div class="module-title-nav clearfix">
                        <?php if (!empty($custom_title)) { ?>
                            <div>
                                <h2><?php echo esc_attr($custom_title); ?></h2>

                                <h3 class="sub-title"><?php echo esc_attr($custom_subtitle); ?></h3>
                            </div>
                        <?php } ?>
                        <div class="module-nav">
                            <button
                                class="btn btn-carousel btn-sm btn-prev-partners"><?php echo $houzez_local['prev_text']; ?></button>
                            <button
                                class="btn btn-carousel btn-sm btn-next-partners"><?php echo $houzez_local['next_text']; ?></button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div id="partner-carousel" class="partner-carousel slide-animated owl-carousel owl-theme">
                        <?php
                        if ($wp_qry->have_posts()): while ($wp_qry->have_posts()): $wp_qry->the_post();
                            $website = get_post_meta(get_the_ID(), 'fave_partner_website', true); ?>
                            <div class="item">
                                <div class="partner-block">
                                    <div class="partner-logo">
                                        <a href="<?php echo esc_url($website); ?>">
                                            <?php the_post_thumbnail('thumbnail'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; endif; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>

        </div>
        <!--end agents carousel module-->


        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-partners', 'houzez_partners');
}