<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 05/04/17
 * Time: 3:29 PM
 */
$label_id = '';
$term_label = wp_get_post_terms( get_the_ID(), 'property_label', array("fields" => "all"));

if( !empty($term_label) ) {
    foreach( $term_label as $label ) {
        $label_id = $label->term_id;
        $label_name = $label->name;
        echo '<span class="label label-default label-color-'.intval($label_id).'">'.esc_attr($label_name).'</span>';
    }
}
?>