<?php
if( !function_exists('houzez_property_by_id') ) {
    function houzez_property_by_id($atts, $content = null)
    {

        extract(shortcode_atts(array(
            'prop_grid_style' => '',
            'property_id' => ''
        ), $atts));

        ob_start();

        $args = array(
            'post_type' => 'property',
            'post__in' => array($property_id),
            'post_status' => 'publish'
        );
        //do the query
        $the_query = New WP_Query($args);
        ?>

        <div id="property-item-module" class="property-item-module">
            <div class="row grid-row">

                <?php
                if( $prop_grid_style == "v_2" ) {
                    if ($the_query->have_posts()) :
                        while ($the_query->have_posts()) : $the_query->the_post();

                            get_template_part('template-parts/property-for-listing-v2-vc');

                        endwhile;
                        wp_reset_postdata();
                    else:
                        get_template_part('template-parts/property', 'none');
                    endif;
                } else {
                    if ($the_query->have_posts()) :
                        while ($the_query->have_posts()) : $the_query->the_post();

                            get_template_part('template-parts/property-for-listing-vc');

                        endwhile;
                        wp_reset_postdata();
                    else:
                        get_template_part('template-parts/property', 'none');
                    endif;
                }
                ?>

            </div>
        </div>

        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-prop-by-id', 'houzez_property_by_id');
}
?>
