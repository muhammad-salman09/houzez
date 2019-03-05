<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 27/09/16
 * Time: 4:49 PM
 * Since v1.4.0
 */
global $post,
       $prop_floor_plan,
       $enable_multi_units,
       $multi_units,
       $prop_video_img,
       $prop_video_url,
       $virtual_tour,
       $prop_features,
       $houzez_prop_detail,
       $prop_description;

$agent_display_option = get_post_meta( $post->ID, 'fave_agent_display_option', true );
$enableDisable_agent_forms = houzez_option('agent_forms');

$prop_detail_nav = houzez_option('prop-detail-nav');
$prop_content_layout = houzez_option('prop-content-layout');
$hide_yelp = houzez_option('houzez_yelp');

$layout = houzez_option('property_blocks');
$layout = $layout['enabled'];
if( isset( $_GET['prop_nav'] ) ) {
    $prop_detail_nav = $_GET['prop_nav'];
}
$prop_description = get_the_content();

$layout = houzez_option('property_blocks_luxuryhomes');
$layout = $layout['enabled'];

if ($layout): foreach ($layout as $key=>$value) {

       switch($key) {

              case 'unit':
                     get_template_part('property-details/v2/multi-units');
                     break;

              case 'description':
                     get_template_part('property-details/v2/property-description-and-details');
                     break;

              case 'energy_class':
            
                   get_template_part('property-details/v2/energy', 'efficiency');
                   
                   break;       

              case 'address':
                     get_template_part('property-details/v2/address');
                     break;

              case 'gallery':
                     get_template_part('property-details/v2/gallery-images');
                     break;

              case 'features':
                     get_template_part('property-details/v2/features-and-additional-features');
                     break;

              case 'floor_plans':
                     if( $prop_floor_plan != 'disable' && !empty( $prop_floor_plan ) ) {
                            get_template_part('property-details/v2/floor-plans');
                     };
                     break;

              case 'video':
                     get_template_part('property-details/v2/video');
                     break;

              case 'virtual_tour':
                     get_template_part('property-details/v2/virtual-tour');
                     break;

              case 'walkscore':
                     get_template_part('property-details/v2/walkscore');
                     break;

              case 'stats':
                     get_template_part('property-details/v2/stats');
                     break;

              case 'booking_calendar':
                    get_template_part( 'property-details/v2/booking' );
                    break;       

              case 'yelp_nearby':
                     if( $hide_yelp ) {
                            get_template_part('property-details/v2/yelp-nearby');
                     }
                     break;

              case 'agent_form':
                     get_template_part('property-details/v2/agent-form');
                     break;

              case 'schedule_tour':
                    get_template_part( 'property-details/v2/schedule', 'tour' );
                    break;

              case 'adsense_space_1':
                  get_template_part( 'property-details/v2/adsense-space-1' );
                  break;
              case 'adsense_space_2':
                  get_template_part( 'property-details/v2/adsense-space-2' );
                  break;
              case 'adsense_space_3':
                  get_template_part( 'property-details/v2/adsense-space-3' );
                  break;       

       }

}

endif;

?>
