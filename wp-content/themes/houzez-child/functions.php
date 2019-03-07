<?php
/**
 * Custom taxonomy for custom post type 'Property'
 */
add_action('admin_head', 'custom_styles');
function custom_styles() {
  echo '<style>
    .form-field input, .form-field textarea {
        width: auto !important;
    }
  </style>';
}

add_action('admin_enqueue_scripts', 'custom_scripts');
if (is_admin() ){
    function custom_scripts(){
        global $pagenow, $typenow;

        wp_enqueue_script('ftmetajs', get_template_directory_uri() .'/js/admin/init.js', array('jquery','media-upload','thickbox'));
        wp_enqueue_style( 'houzez-admin.css', get_template_directory_uri(). '/css/admin/admin.css', array(), HOUZEZ_THEME_VERSION, 'all' );

        wp_enqueue_script('houzez-admin-ajax', get_template_directory_uri() .'/js/admin/houzez-admin-ajax.js', array('jquery'));
        wp_localize_script('houzez-admin-ajax', 'houzez_admin_vars',
            array( 'ajaxurl'            => admin_url('admin-ajax.php'),
                'paid_status'        =>  __('Paid','houzez')

            )
        );

        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }

        if ( isset( $_GET['taxonomy'] ) && ( $_GET['taxonomy'] == 'property_lifestyle' || $_GET['taxonomy'] == 'property_region' ) ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'houzez_taxonomies', get_template_directory_uri().'/js/admin/metaboxes-taxonomies.js', array( 'jquery', 'wp-color-picker' ), 'houzez' );
        }

    }
}

add_action('init', 'overwrite_theme_post_types', 1000);
function overwrite_theme_post_types() {
	//unregister_taxonomy( 'property_label' );

    $labels_lifestyle = array(
        'name' => __( 'Lifestyles', 'read' ),
        'singular_name' => __( 'Lifestyle', 'read' ),
        'search_items' =>  __( 'Search', 'read' ),
        'all_items' => __( 'All', 'read' ),
        'parent_item' => __( 'Parent Lifestyle', 'read' ),
        'parent_item_colon' => __( 'Parent Lifestyle:', 'read' ),
        'edit_item' => __( 'Edit', 'read' ),
        'update_item' => __( 'Update', 'read' ),
        'add_new_item' => __( 'Add New Lifestyle', 'read' ),
        'new_item_name' => __( 'New Lifestyle Name', 'read' ),
        'menu_name' => __( 'Lifestyles', 'read' )
    );

    register_taxonomy(
        'property_lifestyle',
        array( 'property' ),
        array(
            'hierarchical' => true,
            'labels' => $labels_lifestyle,
            'show_ui' => true,
            'public' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'property_lifestyle'
            )
        )
    );

    $labels_region = array(
        'name' => __( 'Regions', 'read' ),
        'singular_name' => __( 'Region', 'read' ),
        'search_items' =>  __( 'Search', 'read' ),
        'all_items' => __( 'All', 'read' ),
        'parent_item' => __( 'Parent Region', 'read' ),
        'parent_item_colon' => __( 'Parent Region:', 'read' ),
        'edit_item' => __( 'Edit', 'read' ),
        'update_item' => __( 'Update', 'read' ),
        'add_new_item' => __( 'Add New Region', 'read' ),
        'new_item_name' => __( 'New Region Name', 'read' ),
        'menu_name' => __( 'Regions', 'read' )
    );

    register_taxonomy(
        'property_region',
        array( 'property' ),
        array(
            'hierarchical' => true,
            'labels' => $labels_region,
            'show_ui' => true,
            'public' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'property_region'
            )
        )
    );
}

if ( !function_exists( 'houzez_get_property_lifestyle_meta' ) ):
    function houzez_get_property_lifestyle_meta( $term_id = false, $field = false ) {
        $defaults = array(
            'color_type' => 'inherit',
            'color' => '#bcbcbc',
            'ppp' => ''
        );

        if ( $term_id ) {
            $meta = get_option( '_houzez_property_lifestyle_'.$term_id );
            $meta = wp_parse_args( (array) $meta, $defaults );
        } else {
            $meta = $defaults;
        }

        if ( $field ) {
            if ( isset( $meta[$field] ) ) {
                return $meta[$field];
            } else {
                return false;
            }
        }
        return $meta;
    }
endif;

if ( !function_exists( 'houzez_property_lifestyle_add_meta_fields' ) ) :
    function houzez_property_lifestyle_add_meta_fields() {
        $houzez_meta = houzez_get_property_lifestyle_meta();
        ?>

        <div class="form-field">
            <label for="Color"><?php _e( 'Global Color', 'houzez'); ?></label><br/>
            <label><input type="radio" name="fave[color_type]" value="inherit" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'inherit' );?>> <?php _e( 'Inherit from default accent color', 'houzez' ); ?></label>
            <label><input type="radio" name="fave[color_type]" value="custom" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'custom' );?>> <?php _e( 'Custom', 'houzez' ); ?></label>
            <div id="fave_color_wrap">
                <p>
                    <input name="fave[color]" type="text" class="fave_colorpicker" value="<?php echo $houzez_meta['color']; ?>" data-default-color="<?php echo $houzez_meta['color']; ?>"/>
                </p>
                <?php if ( !empty( $colors ) ) { echo $colors; } ?>
            </div>
            <div class="clear"></div>
            <p class="howto"><?php _e( 'Choose color', 'houzez' ); ?></p>
        </div>



        <?php
    }
endif;

add_action( 'property_lifestyle_add_form_fields', 'houzez_property_lifestyle_add_meta_fields', 10, 2 );

if ( !function_exists( 'houzez_property_lifestyle_edit_meta_fields' ) ) :
    function houzez_property_lifestyle_edit_meta_fields( $term ) {
        $houzez_meta = houzez_get_property_lifestyle_meta( $term->term_id );
        ?>
        <?php

        $most_used = get_option( 'houzez_recent_colors' );

        $colors = '';

        if ( !empty( $most_used ) ) {
            $colors .= '<p>'.__( 'Recently used', 'houzez' ).': <br/>';
            foreach ( $most_used as $color ) {
                $colors .= '<a href="#" style="width: 20px; height: 20px; background: '.$color.'; float: left; margin-right:3px; border: 1px solid #aaa;" class="fave_colorpick" data-color="'.$color.'"></a>';
            }
            $colors .= '</p>';
        }

        ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Color', 'houzez' ); ?></label></th>
            <td>
                <label><input type="radio" name="fave[color_type]" value="inherit" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'inherit' );?>> <?php _e( 'Inherit from default accent color', 'houzez' ); ?></label> <br/>
                <label><input type="radio" name="fave[color_type]" value="custom" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'custom' );?>> <?php _e( 'Custom', 'houzez' ); ?></label>
                <div id="fave_color_wrap">
                    <p>
                        <input name="fave[color]" type="text" class="fave_colorpicker" value="<?php echo $houzez_meta['color']; ?>" data-default-color="<?php echo $houzez_meta['color']; ?>"/>
                    </p>
                    <?php if ( !empty( $colors ) ) { echo $colors; } ?>
                </div>
                <div class="clear"></div>
                <p class="howto"><?php _e( 'Choose color', 'houzez' ); ?></p>
            </td>
        </tr>

        <?php
    }
endif;

add_action( 'property_lifestyle_edit_form_fields', 'houzez_property_lifestyle_edit_meta_fields', 10, 2 );


if ( !function_exists( 'houzez_save_property_lifestyle_meta_fields' ) ) :
    function houzez_save_property_lifestyle_meta_fields( $term_id ) {

        if ( isset( $_POST['fave'] ) ) {

            $houzez_meta = array();

            $houzez_meta['color'] = isset( $_POST['fave']['color'] ) ? $_POST['fave']['color'] : 0;
            $houzez_meta['color_type'] = isset( $_POST['fave']['color_type'] ) ? $_POST['fave']['color_type'] : 0;

            update_option( '_houzez_property_lifestyle_'.$term_id, $houzez_meta );

            if ( $houzez_meta['color_type'] == 'custom' ) {
                houzez_update_recent_colors( $houzez_meta['color'] );
            }

            houzez_update_property_lifestyle_colors( $term_id, $houzez_meta['color'], $houzez_meta['color_type'] );
        }

    }
endif;

add_action( 'edited_property_lifestyle', 'houzez_save_property_lifestyle_meta_fields', 10, 2 );
add_action( 'create_property_lifestyle', 'houzez_save_property_lifestyle_meta_fields', 10, 2 );

if ( !function_exists( 'houzez_update_property_lifestyle_colors' ) ):
    function houzez_update_property_lifestyle_colors( $cat_id, $color, $type ) {

        $colors = (array)get_option( 'fave_lifestyle_colors' );

        if ( array_key_exists( $cat_id, $colors ) ) {

            if ( $type == 'inherit' ) {
                unset( $colors[$cat_id] );
            } elseif ( $colors[$cat_id] != $color ) {
                $colors[$cat_id] = $color;
            }

        } else {

            if ( $type != 'inherit' ) {
                $colors[$cat_id] = $color;
            }
        }

        update_option( 'houzez_property_lifestyle_colors', $colors );

    }
endif;

if ( !function_exists( 'houzez_get_property_region_meta' ) ):
    function houzez_get_property_region_meta( $term_id = false, $field = false ) {
        $defaults = array(
            'color_type' => 'inherit',
            'color' => '#bcbcbc',
            'ppp' => ''
        );

        if ( $term_id ) {
            $meta = get_option( '_houzez_property_region_'.$term_id );
            $meta = wp_parse_args( (array) $meta, $defaults );
        } else {
            $meta = $defaults;
        }

        if ( $field ) {
            if ( isset( $meta[$field] ) ) {
                return $meta[$field];
            } else {
                return false;
            }
        }
        return $meta;
    }
endif;

if ( !function_exists( 'houzez_property_region_add_meta_fields' ) ) :
    function houzez_property_region_add_meta_fields() {
        $houzez_meta = houzez_get_property_region_meta();
        ?>

        <div class="form-field">
            <label for="Color"><?php _e( 'Global Color', 'houzez'); ?></label><br/>
            <label><input type="radio" name="fave[color_type]" value="inherit" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'inherit' );?>> <?php _e( 'Inherit from default accent color', 'houzez' ); ?></label>
            <label><input type="radio" name="fave[color_type]" value="custom" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'custom' );?>> <?php _e( 'Custom', 'houzez' ); ?></label>
            <div id="fave_color_wrap">
                <p>
                    <input name="fave[color]" type="text" class="fave_colorpicker" value="<?php echo $houzez_meta['color']; ?>" data-default-color="<?php echo $houzez_meta['color']; ?>"/>
                </p>
                <?php if ( !empty( $colors ) ) { echo $colors; } ?>
            </div>
            <div class="clear"></div>
            <p class="howto"><?php _e( 'Choose color', 'houzez' ); ?></p>
        </div>



        <?php
    }
endif;

add_action( 'property_region_add_form_fields', 'houzez_property_region_add_meta_fields', 10, 2 );

if ( !function_exists( 'houzez_property_region_edit_meta_fields' ) ) :
    function houzez_property_region_edit_meta_fields( $term ) {
        $houzez_meta = houzez_get_property_region_meta( $term->term_id );
        ?>
        <?php

        $most_used = get_option( 'houzez_recent_colors' );

        $colors = '';

        if ( !empty( $most_used ) ) {
            $colors .= '<p>'.__( 'Recently used', 'houzez' ).': <br/>';
            foreach ( $most_used as $color ) {
                $colors .= '<a href="#" style="width: 20px; height: 20px; background: '.$color.'; float: left; margin-right:3px; border: 1px solid #aaa;" class="fave_colorpick" data-color="'.$color.'"></a>';
            }
            $colors .= '</p>';
        }

        ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Color', 'houzez' ); ?></label></th>
            <td>
                <label><input type="radio" name="fave[color_type]" value="inherit" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'inherit' );?>> <?php _e( 'Inherit from default accent color', 'houzez' ); ?></label> <br/>
                <label><input type="radio" name="fave[color_type]" value="custom" class="fave-radio color-type" <?php checked( $houzez_meta['color_type'], 'custom' );?>> <?php _e( 'Custom', 'houzez' ); ?></label>
                <div id="fave_color_wrap">
                    <p>
                        <input name="fave[color]" type="text" class="fave_colorpicker" value="<?php echo $houzez_meta['color']; ?>" data-default-color="<?php echo $houzez_meta['color']; ?>"/>
                    </p>
                    <?php if ( !empty( $colors ) ) { echo $colors; } ?>
                </div>
                <div class="clear"></div>
                <p class="howto"><?php _e( 'Choose color', 'houzez' ); ?></p>
            </td>
        </tr>

        <?php
    }
endif;

add_action( 'property_region_edit_form_fields', 'houzez_property_region_edit_meta_fields', 10, 2 );


if ( !function_exists( 'houzez_save_property_region_meta_fields' ) ) :
    function houzez_save_property_region_meta_fields( $term_id ) {

        if ( isset( $_POST['fave'] ) ) {

            $houzez_meta = array();

            $houzez_meta['color'] = isset( $_POST['fave']['color'] ) ? $_POST['fave']['color'] : 0;
            $houzez_meta['color_type'] = isset( $_POST['fave']['color_type'] ) ? $_POST['fave']['color_type'] : 0;

            update_option( '_houzez_property_region_'.$term_id, $houzez_meta );

            if ( $houzez_meta['color_type'] == 'custom' ) {
                houzez_update_recent_colors( $houzez_meta['color'] );
            }

            houzez_update_property_region_colors( $term_id, $houzez_meta['color'], $houzez_meta['color_type'] );
        }

    }
endif;

add_action( 'edited_property_region', 'houzez_save_property_region_meta_fields', 10, 2 );
add_action( 'create_property_region', 'houzez_save_property_region_meta_fields', 10, 2 );

if ( !function_exists( 'houzez_update_property_region_colors' ) ):
    function houzez_update_property_region_colors( $cat_id, $color, $type ) {

        $colors = (array)get_option( 'fave_region_colors' );

        if ( array_key_exists( $cat_id, $colors ) ) {

            if ( $type == 'inherit' ) {
                unset( $colors[$cat_id] );
            } elseif ( $colors[$cat_id] != $color ) {
                $colors[$cat_id] = $color;
            }

        } else {

            if ( $type != 'inherit' ) {
                $colors[$cat_id] = $color;
            }
        }

        update_option( 'houzez_property_region_colors', $colors );

    }
endif;

/*-----------------------------------------------------------------------------------*/
/*  Property Addon Settings
/*-----------------------------------------------------------------------------------*/
if ( !function_exists('houzez_property_addon_settings') ) {
    function houzez_property_addon_settings($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'hz_limit_post_number' => '',
            'hz_select_addon' => ''
        ), $atts));

        ob_start();
        ?>

        <?php
        $result = ob_get_contents();
        ob_end_clean();
        return $result;

    }

    add_shortcode('houzez-property_addon_settings', 'houzez_property_addon_settings');
}

vc_map( array(
    "name"  =>  esc_html__( "Property Addon Settings", "houzez" ),
    "description"           => '',
    "base"                  => "houzez-property_addon_settings",
    'category'              => "By Favethemes",
    "class"                 => "",
    'admin_enqueue_js'      => "",
    'admin_enqueue_css'     => "",
    "icon"                  => "icon-addon-settings",
    "params"                => array(
        array(
            "param_name" => "hz_limit_post_number",
            "type" => "textfield",
            "value" => '',
            "heading" => esc_html__("Limit post number:", "houzez" ),
            "description" => esc_html__( "Enter limit post number", "houzez" ),
            "save_always" => true
        ),
        array(
            "param_name" => "hz_select_addon",
            "type" => "dropdown",
            "value" => array( 'Featured Listing' => 'featured', 'Property of the week' => 'week' ),
            "heading" => esc_html__("Select Property Add On", "houzez" ),
            "save_always" => true
        ),
    )
) );

/*
 * Widget Name: Property Add On: Property of the week
 */
 
class HOUZEZ_property_week extends WP_Widget {
    /**
     * Register widget
    **/
    public function __construct() {
        
        parent::__construct(
            'houzez_property_week', // Base ID
            esc_html__( 'HOUZEZ: Property Add On: Property of the Week', 'houzez' ), // Name
            array( 'description' => esc_html__( 'Show property of the week', 'houzez' ), ) // Args
        );
        
    }
    /**
     * Front-end display of widget
    **/
    public function widget( $args, $instance ) {
        
    }
    /**
     * Sanitize widget form values as they are saved
    **/
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        /* Strip tags to remove HTML. For text inputs and textarea. */
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['items_num'] = strip_tags( $new_instance['items_num'] );
        $instance['widget_type'] = strip_tags( $new_instance['widget_type'] );
        
        return $instance;
    }
    /**
     * Back-end widget form
    **/
    public function form( $instance ) {
        /* Default widget settings. */
        $defaults = array(
            'title' => 'Property of the Week',
            'items_num' => '1',
            'widget_type' => 'entries'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        
    ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'houzez'); ?></label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'items_num' ) ); ?>"><?php esc_html_e('Maximum posts to show:', 'houzez'); ?></label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'items_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'items_num' ) ); ?>" value="<?php echo esc_attr( $instance['items_num'] ); ?>" size="1" />
        </p>
        <p>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_type' ) ); ?>" <?php if ($instance["widget_type"] == 'slider')  echo 'checked="checked"'; ?> value="slider" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>"><?php esc_html_e( 'Display Properties as Slider', 'houzez' ); ?></label><br />

            <input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'entries' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_type' ) ); ?>" <?php if ($instance["widget_type"] == 'entries') echo 'checked="checked"'; ?> value="entries" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'entries' ) ); ?>"><?php esc_html_e( 'Display Properties as List', 'houzez' ); ?></label>
        </p>
        
    <?php
    }

}

if ( ! function_exists( 'HOUZEZ_property_week_loader' ) ) {
    function HOUZEZ_property_week_loader (){
     register_widget( 'HOUZEZ_property_week' );
    }
     add_action( 'widgets_init', 'HOUZEZ_property_week_loader' );
}

/*
 * Widget Name: Property Add On: Featured Listing
 */
 
class HOUZEZ_featured_listing extends WP_Widget {
    /**
     * Register widget
    **/
    public function __construct() {
        
        parent::__construct(
            'houzez_featured_listing', // Base ID
            esc_html__( 'HOUZEZ: Property Add On: Featured Listing', 'houzez' ), // Name
            array( 'description' => esc_html__( 'Show featured listing', 'houzez' ), ) // Args
        );
        
    }
    /**
     * Front-end display of widget
    **/
    public function widget( $args, $instance ) {

    }
    /**
     * Sanitize widget form values as they are saved
    **/
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        /* Strip tags to remove HTML. For text inputs and textarea. */
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['items_num'] = strip_tags( $new_instance['items_num'] );
        $instance['widget_type'] = strip_tags( $new_instance['widget_type'] );
        
        return $instance;
    }
    /**
     * Back-end widget form
    **/
    public function form( $instance ) {
        
        /* Default widget settings. */
        $defaults = array(
            'title' => 'Featured Listing',
            'items_num' => '5',
            'widget_type' => 'entries'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );    
    ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'houzez'); ?></label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'items_num' ) ); ?>"><?php esc_html_e('Maximum posts to show:', 'houzez'); ?></label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'items_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'items_num' ) ); ?>" value="<?php echo esc_attr( $instance['items_num'] ); ?>" size="1" />
        </p>
        <p>
            <input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_type' ) ); ?>" <?php if ($instance["widget_type"] == 'slider')  echo 'checked="checked"'; ?> value="slider" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>"><?php esc_html_e( 'Display Properties as Slider', 'houzez' ); ?></label><br />

            <input type="radio" id="<?php echo esc_attr( $this->get_field_id( 'entries' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_type' ) ); ?>" <?php if ($instance["widget_type"] == 'entries') echo 'checked="checked"'; ?> value="entries" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'entries' ) ); ?>"><?php esc_html_e( 'Display Properties as List', 'houzez' ); ?></label>
        </p>
        
    <?php
    }

}

if ( ! function_exists( 'HOUZEZ_featured_listing_loader' ) ) {
    function HOUZEZ_featured_listing_loader (){
     register_widget( 'HOUZEZ_featured_listing' );
    }
     add_action( 'widgets_init', 'HOUZEZ_featured_listing_loader' );
}

/**
 * Footer Mortage Sitemap
 */
/* Add 2 widgets for footer */
add_action('widgets_init', 'houzez_add_widget', 20);
if( !function_exists('houzez_add_widget') ) {
    function houzez_add_widget() {
        register_sidebar(array(
            'name' => esc_html__('Footer Area 5', 'houzez'),
            'id' => 'footer-sidebar-5',
            'description' => esc_html__('Widgets in this area will be show in footer column five', 'houzez'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<div class="widget-top"><h3 class="widget-title">',
            'after_title' => '</h3></div>',
        ));

        register_sidebar(array(
            'name' => esc_html__('Footer Area 6', 'houzez'),
            'id' => 'footer-sidebar-6',
            'description' => esc_html__('Widgets in this area will be show in footer column six', 'houzez'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<div class="widget-top"><h3 class="widget-title">',
            'after_title' => '</h3></div>',
        ));
    }
}

?>