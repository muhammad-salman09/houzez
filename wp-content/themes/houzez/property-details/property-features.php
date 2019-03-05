<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 08/01/16
 * Time: 4:25 PM
 */
global $prop_features;
if( !empty($prop_features) ) {
    $features_icons = houzez_option('features_icons');
?>
<div id="features" class="detail-features detail-block target-block">
    <div class="detail-title">
        <h2 class="title-left"><?php esc_html_e( 'Features', 'houzez' ); ?></h2>
    </div>
    <ul class="list-three-col list-features">
        <?php
        if (!empty($prop_features)):
            foreach ($prop_features as $term):
                $term_link = get_term_link($term, 'property_feature');
                if (is_wp_error($term_link))
                    continue;

                $term_icon = get_tax_meta($term->term_id, 'fave_prop_features_icon');

                if( $features_icons != 1 ) {
                    echo '<li><a href="' . esc_url($term_link) . '"><i class="fa fa-check"></i>' . esc_attr($term->name) . '</a></li>';
                } else {
                    if( !empty($term_icon) ) {
                        echo '<li><a href="' . esc_url($term_link) . '"><i class="fa fa-'.$term_icon.'"></i>' . esc_attr($term->name) . '</a></li>';
                    } else {
                        echo '<li><a href="' . esc_url($term_link) . '">' . esc_attr($term->name) . '</a></li>';
                    }
                }
            endforeach;
        endif;
        ?>
    </ul>
</div>
<?php } ?>