<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 18/01/16
 * Time: 5:46 PM
 */
global $is_multi_steps;
?>
<div class="account-block <?php echo esc_attr($is_multi_steps);?>">
    <div class="add-title-tab">
        <h3><?php esc_html_e( 'Property Features', 'houzez' ); ?></h3>
        <div class="add-expand"></div>
    </div>
    <div class="add-tab-content">
        <div class="add-tab-row push-padding-bottom">
            <div class="row">
            <?php
            $feature_terms = get_terms( 'property_feature', array( 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false ) );

            if (!empty($feature_terms)) {
                $count = 1;
                foreach ($feature_terms as $term) {
                    echo '<div class="col-sm-3">';
                        echo '<div class="checkbox">';
                            echo '<label>';
                                echo '<input type="checkbox" name="prop_features[]" id="feature-' . esc_attr( $count ). '" value="' . esc_attr( $term->term_id ). '" />';
                                echo esc_attr( $term->name );
                            echo '</label>';
                        echo '</div>';
                    echo '</div>';
                    $count++;
                }
            }
            ?>

            </div>
        </div>
    </div>
</div>
