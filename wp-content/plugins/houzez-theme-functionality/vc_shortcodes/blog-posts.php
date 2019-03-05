<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 23/01/16
 * Time: 11:33 PM
 */
if( !function_exists('houzez_blog_posts') ) {
    function houzez_blog_posts($atts, $content = null)
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
        ?>

        <?php if ($grid_style == 'style_1') { ?>
        <div id="post-card-grid-module" class="houzez-module post-card-module">
            <?php if (!empty($title) || !empty($sub_title)): ?>
                <div class="module-title-nav clearfix">

                    <?php if (!empty($title) || !empty($sub_title)): ?>
                        <div>
                            <h2><?php echo esc_attr($title); ?></h2>

                            <h3 class="sub-title"><?php echo esc_attr($sub_title); ?></h3>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($all_url)): ?>
                        <div class="module-nav">
                            <a href="<?php echo esc_url($all_url); ?>"
                               class="btn btn-carousel btn-sm"><?php echo esc_attr($all_btn); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="row grid-row">
                <?php if ($the_query->have_posts()): while ($the_query->have_posts()): $the_query->the_post(); ?>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <?php get_template_part('content-grid-1'); ?>
                    </div>
                <?php endwhile; endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    <?php } elseif ($grid_style == 'style_2') { ?>

        <div id="post-card-block-module" class="houzez-module post-card-module">
            <?php if (!empty($title) || !empty($sub_title)): ?>

                <div class="module-title-nav clearfix">
                    <?php if (!empty($title) || !empty($sub_title)): ?>
                        <div>
                            <h2><?php echo esc_attr($title); ?></h2>

                            <h3 class="sub-title"><?php echo esc_attr($sub_title); ?></h3>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($all_url)): ?>
                        <div class="module-nav">
                            <a href="<?php echo esc_url($all_url); ?>"
                               class="btn btn-carousel btn-sm"><?php echo esc_attr($all_btn); ?></a>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
            <div class="row grid-row">
                <?php if ($the_query->have_posts()): while ($the_query->have_posts()): $the_query->the_post(); ?>
                    <div class="col-md-3 col-sm-6  col-xs-12">
                        <?php get_template_part('content-grid-2'); ?>
                    </div>
                <?php endwhile; endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>

    <?php } ?>

        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-blog-posts', 'houzez_blog_posts');
}
?>