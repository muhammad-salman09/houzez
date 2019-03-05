<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 05/04/17
 * Time: 3:27 PM
 */
$term_id = '';
$term_status = wp_get_post_terms( get_the_ID(), 'property_status', array("fields" => "all"));

if( !empty($term_status) ) {
    foreach( $term_status as $status ) {
        $status_id = $status->term_id;
        $status_name = $status->name;
        echo '<span class="label-status label-status-'.intval($status_id).' label label-default">'.esc_attr($status_name).'</span>';
    }
}