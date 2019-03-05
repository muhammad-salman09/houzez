<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Houzez_Post_Type_Property {
    /**
     * Initialize custom post type
     *
     * @access public
     * @return void
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'definition' ) );

        
        add_action( 'init', array( __CLASS__, 'property_type' ) );
    
        add_action( 'init', array( __CLASS__, 'property_status' ) );
    
        add_action( 'init', array( __CLASS__, 'property_features' ) );
    
        add_action( 'init', array( __CLASS__, 'property_label' ) );
    
        add_action( 'init', array( __CLASS__, 'property_city' ) );
    
        add_action( 'init', array( __CLASS__, 'property_area' ) );
    
        add_action( 'init', array( __CLASS__, 'property_state' ) );

        add_action('admin_init', array( __CLASS__, 'houzez_approve_listing' ));
        add_action('admin_init', array( __CLASS__, 'houzez_expire_listing' ));

        add_action('restrict_manage_posts', array( __CLASS__, 'houzez_admin_property_type_filter' ));
        add_filter('parse_query', array( __CLASS__, 'houzez_convert_property_type_to_term_in_query' ));

        add_action('restrict_manage_posts', array( __CLASS__, 'houzez_admin_property_status_filter' ));
        add_filter('parse_query', array( __CLASS__, 'houzez_convert_property_status_to_term_in_query' ));

        add_action('restrict_manage_posts', array( __CLASS__, 'houzez_admin_property_label_filter' ));
        add_filter('parse_query', array( __CLASS__, 'houzez_convert_property_label_to_term_in_query' ));

        add_action('restrict_manage_posts', array( __CLASS__, 'houzez_admin_property_city_filter' ));
        add_filter('parse_query', array( __CLASS__, 'houzez_convert_property_city_to_term_in_query' ));

        add_action( 'save_post', array( __CLASS__, 'save_property_post_type' ), 10, 3 );
        //add_action( 'publish_property', array( __CLASS__, 'publish_property_post_type' ), 10, 2 );
        add_filter( 'manage_edit-property_columns', array( __CLASS__, 'custom_columns' ) );
        add_action( 'manage_pages_custom_column', array( __CLASS__, 'custom_columns_manage' ) );

        add_filter('manage_edit-property_area_columns', array( __CLASS__, 'propertyArea_columns_head' ));
        add_filter('manage_property_area_custom_column',array( __CLASS__, 'propertyArea_columns_content_taxonomy' ), 10, 3);

        add_filter('manage_edit-property_city_columns', array( __CLASS__, 'propertyCity_columns_head' ));
        add_filter('manage_property_city_custom_column',array( __CLASS__, 'propertyCity_columns_content_taxonomy' ), 10, 3);

        add_filter('manage_edit-property_state_columns', array( __CLASS__, 'propertyState_columns_head' ));
        add_filter('manage_property_state_custom_column',array( __CLASS__, 'propertyState_columns_content_taxonomy' ), 10, 3);

        if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'property') {
            add_filter('pre_get_posts', array( __CLASS__, 'houzez_post_types_admin_order' ));

            add_filter( 'posts_join', array( __CLASS__, 'houzez_search_join' ) );
            add_filter( 'posts_where', array( __CLASS__, 'houzez_search_where' ) );
        }
    }

    /**
     * Custom post type definition
     *
     * @access public
     * @return void
     */
    public static function definition() {
        $labels = array(
            'name' => __( 'Properties','houzez-theme-functionality'),
            'singular_name' => __( 'Property','houzez-theme-functionality' ),
            'add_new' => __('Add New Property','houzez-theme-functionality'),
            'add_new_item' => __('Add New','houzez-theme-functionality'),
            'edit_item' => __('Edit Property','houzez-theme-functionality'),
            'new_item' => __('New Property','houzez-theme-functionality'),
            'view_item' => __('View Property','houzez-theme-functionality'),
            'search_items' => __('Search Property','houzez-theme-functionality'),
            'not_found' =>  __('No Property found','houzez-theme-functionality'),
            'not_found_in_trash' => __('No Property found in Trash','houzez-theme-functionality'),
            'parent_item_colon' => ''
          );

        $labels = apply_filters( 'houzez_post_type_labels_property', $labels );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'has_archive' => true,
            'capability_type' => 'post',
            'map_meta_cap'    => true,
            'capabilities'    => self::houzez_get_property_capabilities(),
            'hierarchical' => true,
            'menu_icon' => 'dashicons-location',
            'menu_position' => 13,
            'can_export' => true,
            'show_in_rest'       => true,
            'rest_base'          => 'properties',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports' => array('title','editor','thumbnail','revisions','author','page-attributes','excerpt'),
            //'rewrite' => array( 'slug' => 'property' ),

             // The rewrite handles the URL structure.
            'rewrite' => array(
                  'slug'       => houzez_get_property_rewrite_slug(),
                  'with_front' => false,
                  'pages'      => true,
                  'feeds'      => true,
                  'ep_mask'    => EP_PERMALINK,
            ),
        );

        $args = apply_filters( 'houzez_post_type_args_property', $args );

        register_post_type('property',$args);
    }


    public static function property_type() {

        $type_labels = array(
                    'name'              => __('Type','houzez-theme-functionality'),
                    'add_new_item'      => __('Add New Type','houzez-theme-functionality'),
                    'new_item_name'     => __('New Type','houzez-theme-functionality')
        );
        $type_labels = apply_filters( 'houzez_type_labels', $type_labels );

        register_taxonomy('property_type', 'property', array(
                'labels' => $type_labels,
                'hierarchical'  => true,
                'query_var'     => true,
                'show_in_rest'          => true,
                'rest_base'             => 'type',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
                'rewrite'       => array( 'slug' => houzez_get_property_type_rewrite_slug() )
            )
        );
    }

    public static function property_status() {

        $status_labels = array(
                    'name'              => __('Status','houzez-theme-functionality'),
                    'add_new_item'      => __('Add New Status','houzez-theme-functionality'),
                    'new_item_name'     => __('New Status','houzez-theme-functionality')
        );
        $status_labels = apply_filters( 'houzez_status_labels', $status_labels );

        register_taxonomy('property_status', 'property', array(
                'labels' => $status_labels,
                'hierarchical'  => true,
                'query_var'     => true,
                'show_in_rest'          => true,
                'rest_base'             => 'status',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
                'rewrite'       => array( 'slug' => houzez_get_property_status_rewrite_slug() )
            )
        );
        
    }

    public static function property_features() {

        $features_labels = array(
                    'name'              => __('Features','houzez-theme-functionality'),
                    'add_new_item'      => __('Add New Feature','houzez-theme-functionality'),
                    'new_item_name'     => __('New Feature','houzez-theme-functionality')
        );
        $features_labels = apply_filters( 'houzez_features_labels', $features_labels );

        register_taxonomy('property_feature', 'property', array(
                'labels' => $features_labels,
                'hierarchical'  => true,
                'query_var'     => true,
                'show_in_rest'          => true,
                'rest_base'             => 'feature',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
                'rewrite'       => array( 'slug' => houzez_get_property_feature_rewrite_slug() )
            )
        );
    }

    public static function property_label() {

        $label_labels = array(
                    'name'              => __('Labels', 'houzez-theme-functionality'),
                    'add_new_item'      => __('Add New Label','houzez-theme-functionality'),
                    'new_item_name'     => __('New Label','houzez-theme-functionality')
        );
        $label_labels = apply_filters( 'houzez_label_labels', $label_labels );

        register_taxonomy('property_label', 'property', array(
                'labels' => $label_labels,
                'hierarchical'  => true,
                'query_var'     => true,
                'show_in_rest'          => true,
                'rest_base'             => 'label',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
                'rewrite'       => array( 'slug' => 'label' )
            )
        );
        
    }

    public static function property_city() {

        $city_labels = array(
                    'name'              => __('City','houzez-theme-functionality'),
                    'add_new_item'      => __('Add New City','houzez-theme-functionality'),
                    'new_item_name'     => __('New City','houzez-theme-functionality')
        );
        $city_labels = apply_filters( 'houzez_city_labels', $city_labels );

        register_taxonomy('property_city', 'property', array(
                'labels' => $city_labels,
                'hierarchical'  => true,
                'query_var'     => true,
                'show_in_rest'          => true,
                'rest_base'             => 'city',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
                'rewrite'       => array( 'slug' => houzez_get_property_city_rewrite_slug() )
            )
        );
    }

    public static function property_area() {

        $area_labels = array(
                    'name'              => __('Neighborhood','houzez-theme-functionality'),
                    'add_new_item'      => __('Add Property Neighborhood','houzez-theme-functionality'),
                    'new_item_name'     => __('New Property Neighborhood','houzez-theme-functionality')
        );
        $area_labels = apply_filters( 'houzez_area_labels', $area_labels );

        register_taxonomy('property_area', 'property', array(
                'labels' => $area_labels,
                'hierarchical'  => true,
                'query_var'     => true,
                'show_in_rest'          => true,
                'rest_base'             => 'area',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
                'rewrite'       => array( 'slug' => houzez_get_property_area_rewrite_slug() )
            )
        );
    }

    public static function property_state() {

        $property_state_labels = array(
                    'name'              => __('County / State','houzez-theme-functionality'),
                    'add_new_item'      => __('Add Property County / State','houzez-theme-functionality'),
                    'new_item_name'     => __('New Property County / State','houzez-theme-functionality')
        );
        $property_state_labels = apply_filters( 'houzez_state_labels', $property_state_labels );

        register_taxonomy('property_state', 'property', array(
                'labels' => $property_state_labels,
                'hierarchical'  => true,
                'query_var'     => true,
                'show_in_rest'          => true,
                'rest_base'             => 'state',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
                'rewrite'       => array( 'slug' => houzez_get_property_state_rewrite_slug() )
            )
        );
    }

    public static function houzez_get_property_capabilities() {

        $caps = array(
            // meta caps (don't assign these to roles)
            'edit_post'              => 'edit_property',
            'read_post'              => 'read_property',
            'delete_post'            => 'delete_property',

            // primitive/meta caps
            'create_posts'           => 'create_properties',

            // primitive caps used outside of map_meta_cap()
            'edit_posts'             => 'edit_properties',
            'edit_others_posts'      => 'edit_others_properties',
            'publish_post'           => 'publish_properties',
            'read_private_posts'     => 'read_private_properties',

            // primitive caps used inside of map_meta_cap()
            'read'                   => 'read',
            'delete_posts'           => 'delete_properties',
            'delete_private_posts'   => 'delete_private_properties',
            'delete_published_posts' => 'delete_published_properties',
            'delete_others_posts'    => 'delete_others_properties',
            'edit_private_posts'     => 'edit_private_properties',
            'edit_published_posts'   => 'edit_published_properties'
        );

        return apply_filters( 'houzez_get_property_capabilities', $caps );
    }


    /**
     * Custom admin columns for post type
     *
     * @access public
     * @return array
     */
    public static function custom_columns() {

        $columns = array(
            "cb" => "<input type=\"checkbox\" />",
            "title" => __( 'Property Title','houzez-theme-functionality' ),
            "thumbnail" => __( 'Thumbnail','houzez-theme-functionality' ),
            'city' => __( 'City','houzez-theme-functionality' ),
            "type" => __('Type','houzez-theme-functionality'),
            "status" => __('Status','houzez-theme-functionality'),
            "price" => __('Price','houzez-theme-functionality'),
            "id" => __( 'Property ID','houzez-theme-functionality' ),
            "featured" => __( 'Featured','houzez-theme-functionality' ),
            "listing_posted" => __( 'Posted','houzez-theme-functionality' ),
            "listing_expiry" => __( 'Expires','houzez-theme-functionality' ),
            "houzez_actions" => __( 'Actions','houzez-theme-functionality' ),
            "prop_id" => __( 'ID','houzez-theme-functionality' ),
        );

        $columns = apply_filters( 'houzez_custom_post_property_columns', $columns );

        return $columns;
        
    }

    /**
     * Custom admin columns implementation
     *
     * @access public
     * @param string $column
     * @return array
     */
    public static function custom_columns_manage( $column ) {
        global $post;
        $houzez_prefix = 'fave_';
        switch ($column)
        {
            case 'thumbnail':
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( 'thumbnail', array(
                        'class'     => 'attachment-thumbnail attachment-thumbnail-small',
                    ) );
                } else {
                    echo '-';
                }
                break;
            case 'id':
                $Prop_id = get_post_meta($post->ID, $houzez_prefix.'property_id',true);
                if(!empty($Prop_id)){
                    echo esc_attr( $Prop_id );
                }
                else{
                    _e('NA','houzez-theme-functionality');
                }
                break;

            case 'prop_id':
                echo get_the_ID();
                break;
            case 'featured':
                $featured = get_post_meta($post->ID, $houzez_prefix.'featured',true);
                if($featured != 1 ) {
                    _e( 'No', 'houzez-theme-functionality' );
                } else {
                    _e( 'Yes', 'houzez-theme-functionality' );
                }
                break;
            case 'city':
                echo Houzez::admin_taxonomy_terms ( $post->ID, 'property_city', 'property' );
                break;
            case 'address':
                $address = get_post_meta($post->ID, $houzez_prefix.'property_address',true);
                if(!empty($address)){
                    echo esc_attr( $address );
                }
                else{
                    _e('No Address Provided!','houzez-theme-functionality');
                }
                break;
            case 'type':
                echo Houzez::admin_taxonomy_terms ( $post->ID, 'property_type', 'property' );
                break;
            case 'status':
                echo Houzez::admin_taxonomy_terms ( $post->ID, 'property_status', 'property' );
                break;
            case 'price':
                houzez_property_price_admin();
                break;
            case 'bed':
                $bed = get_post_meta($post->ID, $houzez_prefix.'property_bedrooms',true);
                if(!empty($bed)){
                    echo esc_attr( $bed );
                }
                else{
                    _e('NA','houzez-theme-functionality');
                }
                break;
            case 'bath':
                $bath = get_post_meta($post->ID, $houzez_prefix.'property_bathrooms',true);
                if(!empty($bath)){
                    echo esc_attr( $bath );
                }
                else{
                    _e('NA','houzez-theme-functionality');
                }
                break;
            case 'garage':
                $garage = get_post_meta($post->ID, $houzez_prefix.'property_garage',true);
                if(!empty($garage)){
                    echo esc_attr( $garage );
                }
                else{
                    _e('NA','houzez-theme-functionality');
                }
                break;
            case 'features':
                echo get_the_term_list($post->ID,'property-feature', '', ', ','');
                break;
            case 'houzez_actions':
                echo '<div class="actions">';
                $admin_actions = apply_filters( 'post_row_actions', array(), $post );

                $user = wp_get_current_user();

                if ( in_array( $post->post_status, array( 'pending' ) ) && in_array( 'administrator', (array) $user->roles ) ) {
                    $admin_actions['approve']   = array(
                        'action'  => 'approve',
                        'name'    => __( 'Approve', 'houzez-theme-functionality' ),
                        'url'     =>  wp_nonce_url( add_query_arg( 'approve_listing', $post->ID ), 'approve_listing' )
                    );
                }
                if ( in_array( $post->post_status, array( 'publish', 'pending' ) ) && in_array( 'administrator', (array) $user->roles ) ) {
                    $admin_actions['expire']   = array(
                        'action'  => 'expire',
                        'name'    => __( 'Expire', 'houzez-theme-functionality' ),
                        'url'     =>  wp_nonce_url( add_query_arg( 'expire_listing', $post->ID ), 'expire_listing' )
                    );
                }
                /*if ( $post->post_status !== 'trash' ) {
                    if ( current_user_can( 'read_post', $post->ID ) ) {
                        $admin_actions['view']   = array(
                            'action'  => 'view',
                            'name'    => __( 'View', 'houzez-theme-functionality' ),
                            'url'     => get_permalink( $post->ID )
                        );
                    }
                    if ( current_user_can( 'edit_post', $post->ID ) ) {
                        $admin_actions['edit']   = array(
                            'action'  => 'edit',
                            'name'    => __( 'Edit', 'houzez-theme-functionality' ),
                            'url'     => get_edit_post_link( $post->ID )
                        );
                    }
                    if ( current_user_can( 'delete_post', $post->ID ) ) {
                        $admin_actions['delete'] = array(
                            'action'  => 'delete',
                            'name'    => __( 'Delete', 'houzez-theme-functionality' ),
                            'url'     => get_delete_post_link( $post->ID )
                        );
                    }
                }*/

                $admin_actions = apply_filters( 'houzez_admin_actions', $admin_actions, $post );

                foreach ( $admin_actions as $action ) {
                    if ( is_array( $action ) ) {
                        printf( '<a class="button button-icon tips icon-%1$s" href="%2$s" data-tip="%3$s">%4$s</a>', $action['action'], esc_url( $action['url'] ), esc_attr( $action['name'] ), esc_html( $action['name'] ) );
                    } else {
                        //echo str_replace( 'class="', 'class="button ', $action );
                    }
                }

                echo '</div>';

                break;
                case "listing_posted" :
                    echo '<p>' . date_i18n( get_option('date_format').' '.get_option('time_format'), strtotime( $post->post_date ) ) . '</p>';
                    echo '<p>'.( empty( $post->post_author ) ? __( 'by a guest', 'houzez-theme-functionality' ) : sprintf( __( 'by %s', 'houzez-theme-functionality' ), '<a href="' . esc_url( add_query_arg( 'author', $post->post_author ) ) . '">' . get_the_author() . '</a>' ) ) . '</p>';
                    break;
            case "listing_expiry" :
                if( houzez_user_role_by_post_id($post->ID) != 'administrator' && get_post_status ( $post->ID ) == 'publish' ) {
                    houzez_listing_expire();

                }
                break;
        }
    }



    /**
     * Custom admin columns for area taxonomy
     *
     * @access public
     * @return array
     */
    
    public static function propertyArea_columns_head() {

        $new_columns = array(
            'cb'            => '<input type="checkbox" />',
            'name'          => __('Name','houzez-theme-functionality'),
            'city'          => __('City','houzez-theme-functionality'),
            'header_icon'   => '',
            'slug'          => __('Slug','houzez-theme-functionality'),
            'posts'         => __('Posts','houzez-theme-functionality')
        );


        return $new_columns;
    }


    public static function propertyArea_columns_content_taxonomy($out, $column_name, $term_id) {
        if ($column_name == 'city') {
            $term_meta= get_option( "_houzez_property_area_$term_id");
            $term = get_term_by('slug', $term_meta['parent_city'], 'property_city'); 
            if(!empty($term)) {
                print stripslashes( $term->name );
            }
            return;
        }
    }

    /**
     * Custom admin columns for city taxonomy
     *
     * @access public
     * @return array
     */
    public static function propertyCity_columns_head() {

        $new_columns = array(
            'cb'            => '<input type="checkbox" />',
            'name'          => __('Name','houzez-theme-functionality'),
            'county_state'          => __('County/State','houzez-theme-functionality'),
            'header_icon'   => '',
            'slug'          => __('Slug','houzez-theme-functionality'),
            'posts'         => __('Posts','houzez-theme-functionality')
        );


        return $new_columns;
    }


    public static function propertyCity_columns_content_taxonomy($out, $column_name, $term_id) {
        if ($column_name == 'county_state') {
            $term_meta= get_option( "_houzez_property_city_$term_id");
            $term = get_term_by('slug', $term_meta['parent_state'], 'property_state'); 
            if(!empty($term)) {
                print stripslashes( $term->name );
            }
            return;
        }
    }



    /**
     * Custom admin columns for state taxonomy
     *
     * @access public
     * @return array
     */
    public static function propertyState_columns_head() {

        $new_columns = array(
            'cb'            => '<input type="checkbox" />',
            'name'          => __('Name','houzez-theme-functionality'),
            'country'       => __('Country','houzez-theme-functionality'),
            'header_icon'   => '',
            'slug'          => __('Slug','houzez-theme-functionality'),
            'posts'         => __('Posts','houzez-theme-functionality')
        );


        return $new_columns;
    }


    public static function propertyState_columns_content_taxonomy($out, $column_name, $term_id) {
        if ($column_name == 'country') {
            $term_meta= get_option( "_houzez_property_state_$term_id");
            print stripslashes( houzez_country_code_to_country( $term_meta['parent_country'] ) );
        }
    }


    /**
     * Update post meta associated info when post updated
     *
     * @access public
     * @return
     */
    public static function save_property_post_type($post_id, $post, $update) {

        if(isset($_POST) && $_POST && !defined( 'DOING_AJAX' )) {
            // If this is just a revision, don't send the email.
            if ( wp_is_post_revision( $post_id ) )
            return;

            if (!is_object($post) || !isset($post->post_type)) {
                return;
            }

            $slug = 'property';
            // If this isn't a 'book' post, don't update it.
            if ($slug != $post->post_type) {
                return;
            }



            $post_author_id = $post->post_author;
            $houzez_user_role = houzez_user_role_by_user_id($post_author_id);

            if( $houzez_user_role == 'houzez_agency' ) {
                $user_agency_id = get_user_meta( $post_author_id, 'fave_author_agency_id', true );
                if( !empty($user_agency_id) ) {
                    update_post_meta($post_id, 'fave_agent_display_option', 'agency_info');
                    update_post_meta($post_id, 'fave_property_agency', $user_agency_id);
                }
            } else if( $houzez_user_role == 'houzez_agent' ) {
                $user_agent_id = get_user_meta( $post_author_id, 'fave_author_agent_id', true );
                if( !empty($user_agent_id) ) {
                    update_post_meta($post_id, 'fave_agent_display_option', 'agent_info');
                    update_post_meta($post_id, 'fave_agents', $user_agent_id);
                }
            }

            if(class_exists('Houzez_Currencies')) {
                $currency_code = get_post_meta($post_id, 'fave_currency', true);
                $currencies = Houzez_Currencies::get_property_currency_2($post_id, $currency_code);

                update_post_meta( $post_id, 'fave_currency_info', $currencies );
                
            }

            $auto_property_id = houzez_option('auto_property_id');
            $fave_property_id = get_post_meta( $post_id, 'fave_property_id', true );

            if( $auto_property_id != 0 && $fave_property_id === '') {
                update_post_meta($post_id, 'fave_property_id', $post_id);
            }

            $lat_long = get_post_meta( $post_id, 'fave_property_location', true );
            if( isset($lat_long) && !empty($lat_long)) {
                $lat_long = explode(',', $lat_long);
                $lat = $lat_long[0];
                $long = $lat_long[1];

                update_post_meta($post_id, 'houzez_geolocation_lat', $lat);
                update_post_meta($post_id, 'houzez_geolocation_long', $long);
            }
        }

    }

    public static function publish_property_post_type($post_id, $post) {

        //$auto_property_id = houzez_option('auto_property_id');
        //$fave_property_id = get_post_meta( $post_id, 'fave_property_id', true );

        //if( isset($fave_property_id) && !empty($fave_property_id) && $auto_property_id != 0 ) {
            update_post_meta($post_id, 'fave_property_id', $post_id);
        //}

            //echo $post_id; //wp_die();
    }


    public static function houzez_approve_listing()
    {
        if (!empty($_GET['approve_listing']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'approve_listing') && current_user_can('publish_post', $_GET['approve_listing'])) {
            $post_id = absint($_GET['approve_listing']);
            $listing_data = array(
                'ID' => $post_id,
                'post_status' => 'publish'
            );
            wp_update_post($listing_data);

            $author_id = get_post_field ('post_author', $post_id);
            $user           =   get_user_by('id', $author_id );
            $user_email     =   $user->user_email;

            $args = array(
                'listing_title' => get_the_title($post_id),
                'listing_url' => get_permalink($post_id)
            );
            houzez_email_type( $user_email,'listing_approved', $args );

            wp_redirect(remove_query_arg('approve_listing', add_query_arg('approve_listing', $post_id, admin_url('edit.php?post_type=property'))));
            exit;
        }
    }

    public static function houzez_expire_listing() {

        if (!empty($_GET['expire_listing']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'expire_listing') && current_user_can('publish_post', $_GET['expire_listing'])) {
            $post_id = absint($_GET['expire_listing']);
            $listing_data = array(
                'ID' => $post_id,
                'post_status' => 'expired'
            );
            wp_update_post($listing_data);

            $author_id = get_post_field ('post_author', $post_id);
            $user           =   get_user_by('id', $author_id );
            $user_email     =   $user->user_email;

            $args = array(
                'listing_title' => get_the_title($post_id),
                'listing_url' => get_permalink($post_id)
            );
            houzez_email_type( $user_email,'listing_expired', $args );

            wp_redirect(remove_query_arg('expire_listing', add_query_arg('expire_listing', $post_id, admin_url('edit.php?post_type=property'))));
            exit;
        }
    }


    /*------------------------------------------------
     * Types filter
     *----------------------------------------------- */
    public static function houzez_admin_property_type_filter() {
        global $typenow;
        $post_type = 'property';
        $taxonomy = 'property_type';
        if ($typenow == $post_type) {
            $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => esc_html__("All Types", 'houzez-theme-functionality'),
                'taxonomy' => $taxonomy,
                'name' => $taxonomy,
                'orderby' => 'name',
                'selected' => $selected,
                'show_count' => true,
                'hide_empty' => false,
            ));
        };
    }

    public static function houzez_convert_property_type_to_term_in_query($query) {
        global $pagenow;
        $post_type = 'property';
        $taxonomy = 'property_type';
        $q_vars = &$query->query_vars;
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        }
    }

    /*------------------------------------------------
     * Status filter
     *----------------------------------------------- */
    public static function houzez_admin_property_status_filter() {
        global $typenow;
        $post_type = 'property';
        $taxonomy = 'property_status';
        if ($typenow == $post_type) {
            $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => esc_html__("All Status", 'houzez-theme-functionality'),
                'taxonomy' => $taxonomy,
                'name' => $taxonomy,
                'orderby' => 'name',
                'selected' => $selected,
                'show_count' => true,
                'hide_empty' => false,
            ));
        };
    }

    public static function houzez_convert_property_status_to_term_in_query($query) {
        global $pagenow;
        $post_type = 'property';
        $taxonomy = 'property_status';
        $q_vars = &$query->query_vars;
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        }
    }

    /*------------------------------------------------
     * Labels filter
     *----------------------------------------------- */
    public static function houzez_admin_property_label_filter() {
        global $typenow;
        $post_type = 'property';
        $taxonomy = 'property_label';
        if ($typenow == $post_type) {
            $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => esc_html__("All Labels", 'houzez-theme-functionality'),
                'taxonomy' => $taxonomy,
                'name' => $taxonomy,
                'orderby' => 'name',
                'selected' => $selected,
                'show_count' => true,
                'hide_empty' => false,
            ));
        };
    }

    public static function houzez_convert_property_label_to_term_in_query($query) {
        global $pagenow;
        $post_type = 'property';
        $taxonomy = 'property_label';
        $q_vars = &$query->query_vars;
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        }
    }

    /*------------------------------------------------
     * Cities filter
     *----------------------------------------------- */
    public static function houzez_admin_property_city_filter() {
        global $typenow;
        $post_type = 'property';
        $taxonomy = 'property_city';
        if ($typenow == $post_type) {
            $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => esc_html__("All Cities", 'houzez-theme-functionality'),
                'taxonomy' => $taxonomy,
                'name' => $taxonomy,
                'orderby' => 'name',
                'selected' => $selected,
                'show_count' => true,
                'hide_empty' => false,
            ));
        };
    }

    public static function houzez_convert_property_city_to_term_in_query($query) {
        global $pagenow;
        $post_type = 'property';
        $taxonomy = 'property_city';
        $q_vars = &$query->query_vars;
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
            $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
            $q_vars[$taxonomy] = $term->slug;
        }
    }

    public static function houzez_post_types_admin_order($query) {
            
        if(is_admin() && $query->is_main_query()) {
            $post_type = $query->query['post_type'];

            if ($post_type == 'property') {

                $query->set('orderby', 'date');

                $query->set('order', 'DESC');
            }
        }
    }



    public static function houzez_search_join ( $join ) {
        global $pagenow, $wpdb;

        if ( is_admin() && 'edit.php' === $pagenow && 'property' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {    
            $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
        }
        return $join;
    }

    
    public static function houzez_search_where( $where ) {
        global $pagenow, $wpdb;

        if ( is_admin() && 'edit.php' === $pagenow && 'property' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
            $where = preg_replace(
                "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
                "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
        }
        return $where;
    }


}