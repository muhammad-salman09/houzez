<?php
/**
 * Properties Grids
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 07/01/16
 * Time: 1:08 PM
 */
if( !function_exists('houzez_prop_grids') ) {
    function houzez_prop_grids($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'prop_grid_type' => '',
            'property_type' => '',
            'property_status' => '',
            'property_state' => '',
            'property_city' => '',
            'property_area' => '',
            'property_label' => '',
            'featured_prop' => '',
            'posts_limit' => '',
            'offset' => '',
            'custom_title' => '',
            'all_btn' => 'All',
            'all_url' => '',
        ), $atts));

        ob_start();

        //do the query
        $the_query = houzez_data_source::get_wp_query($atts); //by ref  do the query
        ?>

        <!--start property grid module-->
        <div id="property-grid-module" class="houzez-module property-grid-module grid">
            <!-- <div class="container"> -->

            <?php if (!empty($custom_title) || !empty($all_url)) { ?>
                <div class="col-sm-12 col-xs-12">
                    <div class="module-title-nav clearfix">
                        <?php if (!empty($custom_title)) { ?>
                            <div>
                                <h2><?php echo esc_attr($custom_title); ?></h2>
                            </div>
                        <?php } ?>

                        <?php if (!empty($all_url)) { ?>
                            <div class="module-nav">
                                <a href="<?php echo esc_url($all_url); ?>"
                                   class="btn btn-carousel btn-sm"><?php echo esc_attr($all_btn); ?></a>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            <?php } ?>

            <div class="row no-margin">
                <?php
                $count = 0;
                if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post();

                    $count++;
                    if ($prop_grid_type == 'grid_1') {

                        if ($count == 1 || $count == 2) {
                            echo '<div class="col-sm-6 col-xs-12">';
                        } else {
                            echo '<div class="col-md-3 col-sm-6 col-xs-12 grid-four-col">';
                        }

                        get_template_part('template-parts/property-for-vc-grids');

                        echo '</div>';

                        if ($count == 6) {
                            $count = 0;
                        }
                    } // end grid 1

                    if ($prop_grid_type == 'grid_2') {

                        if ($count == 1 || $count == 6) {
                            echo '<div class="col-md-6 col-sm-12">';
                        } else {
                            echo '<div class="col-md-3 col-sm-6 col-xs-12 grid-four-col">';
                        }

                        get_template_part('template-parts/property-for-vc-grids');

                        echo '</div>';

                        if ($count == 6) {
                            $count = 0;
                        }
                    } // end grid 2


                    if ($prop_grid_type == 'grid_3') {

                        if ($count == 1 || $count == 2 || $count == 3) {
                            echo '<div class="col-sm-4 col-xs-12 grid-three-col">';
                        } else {
                            echo '<div class="col-md-3 col-sm-6 col-xs-12 grid-four-col">';
                        }

                        get_template_part('template-parts/property-for-vc-grids');

                        echo '</div>';

                        if ($count == 7) {
                            $count = 0;
                        }
                    } // end grid 3

                    if ($prop_grid_type == 'grid_4') {

                        if ($count == 1 || $count == 4) {
                            echo '<div class="col-sm-8 col-xs-12 col no-padding">';
                        } else {
                            echo '<div class="col-sm-4 col-xs-12 col no-padding grid-three-col">';
                        }

                        get_template_part('template-parts/property-for-vc-grids');

                        echo '</div>';

                        if ($count == 4) {
                            $count = 0;
                        }
                    } // end grid 4

                endwhile; endif; ?>
            </div>
            <!-- </div> -->
        </div>
        <!--end property grid module-->

        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-prop-grids', 'houzez_prop_grids');
}
?>