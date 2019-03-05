<?php
/**
 * Testimonials
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 07/01/16
 * Time: 4:00 PM
 */
if( !function_exists('houzez_testimonials') ) {
    function houzez_testimonials($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'testimonials_type' => '',
            'posts_limit' => '',
            'offset' => '',
            'orderby' => '',
            'order' => ''
        ), $atts));

        ob_start();
        $houzez_local = houzez_get_localization();

        $args = array(
            'post_type' => 'houzez_testimonials',
            'posts_per_page' => $posts_limit,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset
        );
        $testi_qry = new WP_Query($args);
        ?>

        <!--start testimonials module-->
        <?php if ($testimonials_type == 'grid') { ?>
        <div id="testimonial-module" class="houzez-module testimonial-module">

            <div class="row">
                <?php
                if ($testi_qry->have_posts()): while ($testi_qry->have_posts()): $testi_qry->the_post();
                    $text = get_post_meta(get_the_ID(), 'fave_testi_text', true);
                    $name = get_post_meta(get_the_ID(), 'fave_testi_name', true);
                    $position = get_post_meta(get_the_ID(), 'fave_testi_position', true);
                    $company = get_post_meta(get_the_ID(), 'fave_testi_company', true);
                    $photo_id = get_post_meta(get_the_ID(), 'fave_testi_photo', true);
                    $logo_id = get_post_meta(get_the_ID(), 'fave_testi_logo', true);
                    ?>


                    <div class="col-sm-3 col-xs-12 block-col">
                        <div class="testimonial-item text-center">
                            <?php if (!empty($photo_id)) { ?>
                                <figure class="auther-thumb">
                                    <?php echo wp_get_attachment_image($photo_id, 'thumbnail', false, array('class' => 'img-circle')); ?>
                                </figure>
                            <?php } ?>
                            <?php if (!empty($logo_id)) { ?>
                                <div class="web-logo text-center">
                                    <?php echo wp_get_attachment_image($logo_id, 'thumbnail'); ?>
                                </div>
                            <?php } ?>
                            <div class="block-body">
                                <p class="description"><?php echo wp_kses_post($text); ?></p>

                                <p class="auther-info">
                                    <span><?php echo $houzez_local['by_text']; ?> <span
                                            class="blue"><?php echo esc_attr($name); ?></span></span>
                                    <span><?php echo esc_attr($position); ?>
                                        <?php if(!empty($company)){ ?>
                                        , <?php echo esc_attr($company); ?></span>
                                        <?php } ?>
                                </p>
                            </div>
                        </div>
                    </div>


                <?php endwhile; endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>

        </div>
    <?php } elseif ($testimonials_type == 'slides') { ?>

        <div id="testimonial-carousel-module" class="houzez-module testimonial-carousel-module">

            <?php
            $token = wp_generate_password(5, false, false);
            if (is_rtl()) {
                $houzez_rtl = "true";
            } else {
                $houzez_rtl = "false";
            }
            ?>
            <script>
                jQuery(document).ready(function ($) {

                    $('#testimonial-carousel-<?php echo esc_attr( $token ); ?>').owlCarousel({
                        rtl: <?php echo esc_attr( $houzez_rtl ); ?>,
                        items: 1,
                        loop: true,
                        autoplay: true,
                        autoplaySpeed: 700,
                        autoplayHoverPause:true,
                        dots: true,
                        smartSpeed: 700,
                        slideBy: 1,
                        nav: true,
                        navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                    });
                });
            </script>

            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div id="testimonial-carousel-<?php echo esc_attr( $token ); ?>" class="testimonial-carousel slide-animated owl-carousel owl-theme">
                        <?php
                        if ($testi_qry->have_posts()): while ($testi_qry->have_posts()): $testi_qry->the_post();
                            $text = get_post_meta(get_the_ID(), 'fave_testi_text', true);
                            $name = get_post_meta(get_the_ID(), 'fave_testi_name', true);
                            $position = get_post_meta(get_the_ID(), 'fave_testi_position', true);
                            $company = get_post_meta(get_the_ID(), 'fave_testi_company', true);
                            $photo_id = get_post_meta(get_the_ID(), 'fave_testi_photo', true);
                            ?>
                            <div class="item">
                                <div class="testimonial-item text-center">
                                    <?php if (!empty($photo_id)) { ?>
                                        <figure class="auther-thumb">
                                            <?php echo wp_get_attachment_image($photo_id, 'thumbnail', false, array('class' => 'img-circle')); ?>
                                        </figure>
                                    <?php } ?>
                                    <div class="block-body">
                                        <h3 class="description"><?php echo wp_kses_post($text); ?></h3>

                                        <p class="auther-info">
                                            <span><?php echo $houzez_local['by_text']; ?> <span
                                                    class="blue"><?php echo esc_attr($name); ?></span></span>
                                            <span><?php echo esc_attr($position); ?>
                                                <?php if(!empty($company)){ ?>
                                                , <?php echo esc_attr($company); ?></span>
                                                <?php } ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; endif; ?>
                        <?php wp_reset_postdata(); ?>

                    </div>
                </div>
            </div>

        </div>

    <?php } ?>
        <!--end post testimonials module-->


        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-testimonials', 'houzez_testimonials');
}
?>