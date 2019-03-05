<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 23/01/16
 * Time: 11:33 PM
 */
if( !function_exists('houzez_blog_posts_carousel') ) {
    function houzez_blog_posts_carousel($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'grid_style' => '',
            'category_id' => '',
            'posts_limit' => '',
            'offset' => '',
            'title' => '',
            'sub_title' => '',
            'all_btn' => '',
            'all_url' => '',
        ), $atts));

        ob_start();

        $houzez_local = houzez_get_localization();

        $wp_query_args = array(
            'ignore_sticky_posts' => 1,
            'post_type' => 'post'
        );
        if (!empty($category_id)) {
            $wp_query_args['cat'] = $category_id;
        }
        if (!empty($offset)) {
            $wp_query_args['offset'] = $offset;
        }
        $wp_query_args['post_status'] = 'publish';

        if (empty($posts_limit)) {
            $posts_limit = get_option('posts_per_page');
        }
        $wp_query_args['posts_per_page'] = $posts_limit;

        $the_query = New WP_Query($wp_query_args);

        $token = wp_generate_password(5, false, false);
        if (is_rtl()) {
            $houzez_rtl = "true";
        } else {
            $houzez_rtl = "false";
        }
        ?>
        <script>
            jQuery(document).ready(function ($) {

                var owl_post_card = $('#carousel-post-card-<?php echo esc_attr( $token ); ?>');

                owl_post_card.owlCarousel({
                    rtl: <?php echo esc_attr( $houzez_rtl ); ?>,
                    loop: true,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 700,
                    slideBy: 1,
                    nav: false,
                    responsive:{
                        0: {
                            items: 1
                        },
                        320: {
                            items: 1
                        },
                        480: {
                            items: 1
                        },
                        768: {
                            items: 2
                        },
                        1000: {
                            items: 3
                        },
                        1280: {
                            items: 4
                        }
                    }
                });

                $('.btn-prev-post-card').on('click',function() {
                    owl_post_card.trigger('prev.owl.carousel',[700])
                });
                $('.btn-next-post-card').on('click',function() {
                    owl_post_card.trigger('next.owl.carousel',[700])
                });
            });
        </script>

        <?php if ($grid_style == 'style_1') { ?>
        <div id="carousel-post-card-module" class="houzez-module carousel-module">
            <div class="module-title-nav clearfix">
                <?php if (!empty($title) || !empty($sub_title)): ?>
                    <div>
                        <h2><?php echo esc_attr($title); ?></h2>

                        <h3 class="sub-title"><?php echo esc_attr($sub_title); ?></h3>
                    </div>
                <?php endif; ?>
                <div class="module-nav">
                    <button
                        class="btn btn-carousel btn-sm btn-prev-post-card"><?php echo $houzez_local['prev_text']; ?></button>
                    <button
                        class="btn btn-carousel btn-sm btn-next-post-card"><?php echo $houzez_local['next_text']; ?></button>
                    <?php if (!empty($all_url)): ?>
                        <a href="<?php echo esc_url($all_url); ?>"
                           class="btn btn-carousel btn-sm"><?php echo esc_attr($all_btn); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row grid-row">
                <div id="carousel-post-card-<?php echo esc_attr( $token ); ?>" class="slide-animated owl-carousel owl-theme">
                    <?php if ($the_query->have_posts()): while ($the_query->have_posts()): $the_query->the_post(); ?>
                        <div class="item">
                            <?php get_template_part('content-grid-1'); ?>
                        </div>
                    <?php endwhile; endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    <?php } elseif ($grid_style == 'style_2') { ?>

        <div id="carousel-post-card-block-module" class="houzez-module carousel-post-card-block-module">

            <div class="row">
                <div class="col-sm-12">
                    <div class="module-title-nav clearfix">
                            <?php if (!empty($title) || !empty($sub_title)): ?>
                                <div>
                                    <h2><?php echo esc_attr($title); ?></h2>

                                    <h3 class="sub-title"><?php echo esc_attr($sub_title); ?></h3>
                                </div>
                            <?php endif; ?>
                            <div class="module-nav">
                                <button
                                    class="btn btn-carousel btn-sm btn-prev-card-block"><?php echo $houzez_local['prev_text']; ?></button>
                                <button
                                    class="btn btn-carousel btn-sm btn-next-card-block"><?php echo $houzez_local['next_text']; ?></button>
                                <?php if (!empty($all_url)): ?>
                                    <a href="<?php echo esc_url($all_url); ?>"
                                       class="btn btn-carousel btn-sm"><?php echo esc_attr($all_btn); ?></a>
                                <?php endif; ?>
                            </div>
                        
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="row row-post-card-carousel">
                        <div id="carousel-post-card-<?php echo esc_attr( $token ); ?>" class="slide-animated owl-carousel owl-theme">
                            <?php if ($the_query->have_posts()): while ($the_query->have_posts()): $the_query->the_post(); ?>
                                <div class="item block-col">
                                    <?php get_template_part('content-grid-2'); ?>
                                </div>
                            <?php endwhile; endif; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    <?php } ?>

        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-blog-posts-carousel', 'houzez_blog_posts_carousel');
}
?>