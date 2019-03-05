<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 10/10/16
 * Time: 4:17 AM
 * Since v1.4.0
 */
global $post;

$post_id = $post->ID;
$hide_yelp = houzez_option('houzez_yelp');
$houzez_yelp_api_key = houzez_option('houzez_yelp_api_key');

$allowed_html_array = array(
    'i' => array(
        'class' => array()
    ),
    'span' => array(
        'class' => array()
    ),
    'a' => array(
        'href' => array(),
        'title' => array(),
        'target' => array()
    )
);

$yelp_categories = array (
    'active' => array( 'name' => esc_html__( 'Active Life', 'houzez' ), 'icon' => 'fa fa-bicycle' ),
    'arts' => array( 'name' => esc_html__( 'Arts & Entertainment', 'houzez' ), 'icon' => 'fa fa-picture-o' ),
    'auto' => array( 'name' => esc_html__( 'Automotive', 'houzez' ), 'icon' => 'fa fa-car' ),
    'beautysvc' => array( 'name' => esc_html__( 'Beauty & Spas', 'houzez' ), 'icon' => 'fa fa-cutlery' ),
    'education' => array( 'name' => esc_html__( 'Education', 'houzez' ), 'icon' => 'fa fa-graduation-cap' ),
    'eventservices' => array( 'name' => esc_html__( 'Event Planning & Services', 'houzez' ), 'icon' => 'fa fa-birthday-cake' ),
    'financialservices' => array( 'name' => esc_html__( 'Financial Services', 'houzez' ), 'icon' => 'fa fa-money' ),
    'food' => array( 'name' => esc_html__( 'Food', 'houzez' ), 'icon' => 'fa fa-shopping-basket' ),
    'health' => array( 'name' => esc_html__( 'Health & Medical', 'houzez' ), 'icon' => 'fa fa-medkit' ),
    'homeservices' => array( 'name' => esc_html__( 'Home Services ', 'houzez' ), 'icon' => 'fa fa-wrench' ),
    'hotelstravel' => array( 'name' => esc_html__( 'Hotels & Travel', 'houzez' ), 'icon' => 'fa fa-bed' ),
    'localflavor' => array( 'name' => esc_html__( 'Local Flavor', 'houzez' ), 'icon' => 'fa fa-coffee' ),
    'localservices' => array( 'name' => esc_html__( 'Local Services', 'houzez' ), 'icon' => 'fa fa-dot-circle-o' ),
    'massmedia' => array( 'name' => esc_html__( 'Mass Media', 'houzez' ), 'icon' => 'fa fa-television' ),
    'nightlife' => array( 'name' => esc_html__( 'Nightlife', 'houzez' ), 'icon' => 'fa fa-glass' ),
    'pets' => array( 'name' => esc_html__( 'Pets', 'houzez' ), 'icon' => 'fa fa-paw' ),
    'professional' => array( 'name' => esc_html__( 'Professional Services', 'houzez' ), 'icon' => 'fa fa-suitcase' ),
    'publicservicesgovt' => array( 'name' => esc_html__( 'Public Services & Government', 'houzez' ), 'icon' => 'fa fa-university' ),
    'realestate' => array( 'name' => esc_html__( 'Real Estate', 'houzez' ), 'icon' => 'fa fa-building-o' ),
    'religiousorgs' => array( 'name' => esc_html__( 'Religious Organizations', 'houzez' ), 'icon' => 'fa fa-universal-access' ),
    'restaurants' => array( 'name' => esc_html__( 'Restaurants', 'houzez' ), 'icon' => 'fa fa-cutlery' ),
    'shopping' => array( 'name' => esc_html__( 'Shopping', 'houzez' ), 'icon' => 'fa fa-shopping-bag' ),
    'transport' =>  array( 'name' => esc_html__( 'Transportation', 'houzez' ), 'icon' => 'fa fa-bus' )
);

$yelp_data = houzez_option( 'houzez_yelp_term' );
$yelp_dist_unit = houzez_option( 'houzez_yelp_dist_unit' );
$prop_location = get_post_meta( get_the_ID(), 'fave_property_location', true );
$prop_location = explode( ',', $prop_location );
$prop_location = $prop_location[0].','.$prop_location[1];


$dist_unit = 1.1515;
$unit_text = 'mi';
if ( $yelp_dist_unit == 'kilometers' ) {
    $dist_unit = 1.609344;
    $unit_text = 'km';
}
if( $hide_yelp ) {

    ?>
    <div id="yelp_nearby" class="yelp-category detail-block target-block">
        <div class="detail-title">
            <h2 class="title-left"> <?php echo esc_html__("What's Nearby?", "houzez"); ?> </h2>
            <div class="title-right">
                <?php echo esc_html__("powered by", "houzez"); ?>  <img src="<?php echo get_template_directory_uri(); ?>/images/yelp-logo.png" alt="yelp" class="v-align-bottom">
            </div>
        </div>
        <?php
        $link = site_url('wp-admin/admin.php?page=houzez_options&tab=30');
        if( empty( $houzez_yelp_api_key ) ) {
            echo '<div class="yelp-cat-block">';
            echo esc_html__('Please supply your API key', 'houzez').' ';
            echo '<a target="_blank" href="'.$link.'">'.esc_html__('Click Here', 'houzez').'</a>';
            echo '</div>';
        } else {

            foreach ( $yelp_data as $value ) :

                $term_id = $value;
                $term_name = $yelp_categories[ $term_id ]['name'];
                $term_icon = $yelp_categories[ $term_id ]['icon'];
                $response = yelp_query_api( $term_id, $prop_location );

                if ( isset( $response->businesses ) ) {
                    $businesses = $response->businesses;
                } else {
                    $businesses = array( $response );
                }
                $distance = false;
                $current_lat = '';
                $current_lng = '';

                if ( isset( $response->region->center ) ) {

                    $current_lat = $response->region->center->latitude;
                    $current_lng = $response->region->center->longitude;
                    $distance = true;

                }

                if ( sizeof( $businesses ) != 0 ) {

                    ?>
                    <div class="yelp-cat-block">
                        <h4 class="cat-title"><span class="yelp-cat-icon"><i class="<?php echo $term_icon; ?>"></i></span> <?php echo $term_name; ?> </h4>
                        <ul class="yelp-cat-list">
                            <?php

                            foreach ( $businesses as $data ) :

                                $location_distance = '';

                                if ( $distance && isset( $data->coordinates ) ) {

                                    $location_lat = $data->coordinates->latitude;
                                    $location_lng = $data->coordinates->longitude;
                                    $theta = $current_lng - $location_lng;
                                    $dist = sin( deg2rad( $current_lat ) ) * sin( deg2rad( $location_lat ) ) +  cos( deg2rad( $current_lat ) ) * cos( deg2rad( $location_lat ) ) * cos( deg2rad( $theta ) );
                                    $dist = acos( $dist );
                                    $dist = rad2deg( $dist );
                                    $miles = $dist * 60 * $dist_unit;

                                    $location_distance = '<span class="time-review"> (' . round( $miles, 2 ) . ' ' . $unit_text . ') </span>';

                                }

                                ?>
                                <li>
                                    <div class="cat-list-left"> <?php echo $data->name; ?> <?php echo $location_distance; ?></div>
                                    <div class="cat-list-right"><span class="rating-wrap"><input class="rating-display-only" name="rating" value="<?php echo $data->rating; ?>" type="number" min="0" max="5" step=1 data-size="md" class="rating" ></span> <span class="time-review"> <?php echo $data->review_count; ?> <?php esc_html_e('reviews', 'houzez');?> </span></div>
                                </li>
                                <?php

                            endforeach;

                            ?>
                        </ul>
                    </div>
                    <?php

                } //sizeof( $businesses )

            endforeach;
        } //houzez_yelp_api_key
        ?>
    </div>
    <?php

} //hide_yelp