<?php
/**
 * Houzez functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Houzez
 * @since Houzez 1.0
 * @author Waqas Riaz
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
global $wp_version;
/**
*	----------------------------------------------------------------------------------------------------------------------------------------------------
*	Define constants
*	----------------------------------------------------------------------------------------------------------------------------------------------------
*/
define( 'HOUZEZ_THEME_NAME', 'Houzez' );
define( 'HOUZEZ_THEME_SLUG', 'houzez' );
define( 'HOUZEZ_THEME_VERSION', '1.6.5.1' );
/**
*	----------------------------------------------------------------------------------------------------------------------------------------------------
*	Set up theme default and register various supported features.
*	----------------------------------------------------------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'houzez_setup' ) ) {
	function houzez_setup() {

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		//Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		//Add support for post thumbnails.
		add_theme_support( 'post-thumbnails' );

		set_post_thumbnail_size( 150, 150 );
		add_image_size( 'houzez-single-big-size', 1170, 600, true ); // toparea-v1.php , single-property.php
		add_image_size( 'houzez-property-thumb-image', 385, 258, true ); // List, grid view. Include VC modules grid view, content-grid-1.php
		add_image_size( 'houzez-property-thumb-image-v2', 380, 280, true ); // List, grid view. Include VC modules grid view, content-grid-1.php
		add_image_size( 'houzez-image570_340', 570, 340, true ); // for property carousels and property grids
		add_image_size( 'houzez-property-detail-gallery', 810, 430, true ); // Slideshow.php , blog-function.php
		add_image_size( 'houzez-imageSize1170_738', 1170, 738, true ); // lightbox.php , toparea-v2.php
		add_image_size( 'houzez-prop_image1440_610', 1440, 610, true ); // property-slider.php
		add_image_size( 'houzez-image350_350', 350, 350, true ); // author.php , content-grid-2.php , single-houzez_agent.php , template-agent.php
		add_image_size( 'houzez-widget-prop', 150, 110, true ); // slideshow.php , dashboard_property_unit.php
		add_image_size( 'houzez-image_masonry', 350, 9999, false ); // blog-masonry.php
		add_image_size( 'houzez-toparea-v5', 0, 480, false ); // toparea-v5.php
		

		/**
		*	Register nav menus. 
		*/
		register_nav_menus(
			array(
				'top-menu' => esc_html__( 'Top Menu', 'houzez' ),
				'main-menu' => esc_html__( 'Main Menu', 'houzez' ),
				'main-menu-left' => esc_html__( 'Menu Left', 'houzez' ),
				'main-menu-right' => esc_html__( 'Menu Right', 'houzez' ),
				'mobile-menu-hed6' => esc_html__( 'Mobile Menu Header 6', 'houzez' ),
				'footer-menu' => esc_html__( 'Footer Menu', 'houzez' )
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array(

		) );

		//remove gallery style css
		add_filter( 'use_default_gallery_style', '__return_false' );
		
	}

	add_action( 'after_setup_theme', 'houzez_setup' );
}

/**
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 *	Make the theme available for translation.
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 */
load_theme_textdomain( 'houzez', get_template_directory() . '/languages' );

/**
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 *	Set up the content width value based on the theme's design.
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 */
if( !function_exists('houzez_content_width') ) {
	function houzez_content_width()
	{
		$GLOBALS['content_width'] = apply_filters('houzez_content_width', 1170);
	}

	add_action('after_setup_theme', 'houzez_content_width', 0);
}


/**
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 *	Enqueue scripts and styles.
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 */
require_once( get_template_directory() . '/inc/register-scripts.php' );

/**
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 *	TMG plugin activation
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 */
	require_once( get_template_directory() . '/framework/class-tgm-plugin-activation.php' );
	require_once( get_template_directory() . '/framework/register-plugins.php' );

/**
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 *	Visual Composer
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 */
if (is_plugin_active('js_composer/js_composer.php') && is_plugin_active('houzez-theme-functionality/houzez-theme-functionality.php') ) {

	if( !function_exists('houzez_include_composer') ) {
		function houzez_include_composer()
		{
			require_once(get_template_directory() . '/framework/vc_extend.php');
		}

		add_action('init', 'houzez_include_composer', 9999);
	}

	// Filter to replace default css class names for vc_row shortcode and vc_column
	if( !function_exists('houzez_custom_css_classes_for_vc_row_and_vc_column') ) {
		//add_filter('vc_shortcodes_css_class', 'houzez_custom_css_classes_for_vc_row_and_vc_column', 10, 2);
		function houzez_custom_css_classes_for_vc_row_and_vc_column($class_string, $tag)
		{
			if ($tag == 'vc_row' || $tag == 'vc_row_inner') {
				$class_string = str_replace('vc_row-fluid', 'row-fluid', $class_string);
				$class_string = str_replace('vc_row', 'row', $class_string);
				$class_string = str_replace('wpb_row', '', $class_string);
			}
			if ($tag == 'vc_column' || $tag == 'vc_column_inner') {
				$class_string = preg_replace('/vc_col-sm-(\d{1,2})/', 'col-sm-$1', $class_string);
				$class_string = str_replace('wpb_column', '', $class_string);
				$class_string = str_replace('vc_column_container', '', $class_string);
			}
			return $class_string;
		}
	}

}
/**
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 *	Functions
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 */
require_once( get_template_directory() . '/framework/functions/price_functions.php' );
require_once( get_template_directory() . '/framework/functions/helper_functions.php' );
require_once( get_template_directory() . '/framework/functions/profile_functions.php' );
require_once( get_template_directory() . '/framework/functions/property_functions.php' );
require_once( get_template_directory() . '/framework/functions/emails-functions.php' );
require_once( get_template_directory() . '/framework/functions/blog-functions.php' );
require_once( get_template_directory() . '/framework/functions/membership-functions.php' );
require_once( get_template_directory() . '/framework/functions/cron-functions.php' );
require_once( get_template_directory() . '/framework/functions/property-expirator.php');
require_once( get_template_directory() . '/framework/functions/messages_functions.php');
require_once( get_template_directory() . '/framework/functions/property_rating.php');
require_once( get_template_directory() . '/framework/functions/db-update.php');

if( $wp_version < '4.7.0' ) {
	require_once( get_template_directory() . '/framework/functions/houzez-localization.php');
} else {
	require_once(get_theme_file_path('framework/functions/houzez-localization.php'));
}

require_once( get_template_directory() . '/framework/thumbnails/better-jpgs.php');
require_once( get_template_directory() . '/framework/thumbnails/honor-ssl-for-attachments.php');

require_once( get_template_directory() . '/framework/functions/db-update.php' );
require_once( get_template_directory() . '/inc/header/favicon-apple-icons.php' );
require_once( get_template_directory() . '/inc/yelpauth/yelpoauth.php' );

if ( class_exists( 'ReduxFramework' ) ) {
	require_once( get_template_directory() . '/inc/styling-options.php' );
	require_once( get_template_directory() . '/framework/functions/demo-importer.php' );
}

/**
 *	---------------------------------------------------------------------------------------
 *	Widgets
 *	---------------------------------------------------------------------------------------
 */
require_once ( get_template_directory() . '/inc/widgets/houzez-featured-properties.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-properties.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-property-taxonomies.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-advanced-search.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-about.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-contact.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-latest-posts.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-code-banner.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-facebook.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-flickr-photos.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-image-banner-300-250.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-mortgage-calculator.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-instagram.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-twitter.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-properties-viewed.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-agents-search.php' );
require_once ( get_template_directory() . '/inc/widgets/houzez-agency-search.php' );

if( class_exists('Houzez_login_register') ) {
	require_once( get_template_directory() . '/inc/widgets/houzez-login-widget.php');
}

/**
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 *	Twitter Oauth
 *	----------------------------------------------------------------------------------------------------------------------------------------------------
 */
if(!class_exists('TwitterOAuth',false)) {
	require_once( get_template_directory() . '/inc/twitteroauth/twitteroauth.php' );
}


/**
 *	---------------------------------------------------------------------------------------
 *	Classes
 *	---------------------------------------------------------------------------------------
 */
require_once( get_template_directory() . '/framework/classes/houzez_data_source.php' );
require_once( get_template_directory() . '/framework/classes/Houzez_Compare_Properties.php' );

/**
 *	---------------------------------------------------------------------------------------
 *	Meta Boxes
 *	---------------------------------------------------------------------------------------
 */
require_once( get_template_directory() . '/framework/metaboxes/metaboxes.php' );
require_once( get_template_directory() . '/framework/metaboxes/property-status-meta.php' );
require_once( get_template_directory() . '/framework/metaboxes/property-type-meta.php' );
require_once( get_template_directory() . '/framework/metaboxes/property-label-meta.php' );
require_once( get_template_directory() . '/framework/metaboxes/property-area-meta.php' );
require_once( get_template_directory() . '/framework/metaboxes/property-cities-meta.php' );
require_once( get_template_directory() . '/framework/metaboxes/property-state-meta.php' );

$activation_status = get_option( 'houzez_activation' );
if( $activation_status == 'activated') {
	require_once(get_template_directory() . '/framework/metaboxes/houzez-meta-boxes.php');
}

/**
 *	---------------------------------------------------------------------------------------
 *	Options Admin Panel
 *	---------------------------------------------------------------------------------------
 */
require_once( get_template_directory() . '/framework/options/remove-tracking-class.php' ); // Remove tracking
require_once( get_template_directory() . '/framework/options/fave-options.php' );
require_once( get_template_directory() . '/framework/options/fave-option.php' );


/*-----------------------------------------------------------------------------------*/
/*	Register blog sidebar, footer and custom sidebar
/*-----------------------------------------------------------------------------------*/
if( !function_exists('houzez_widgets_init') ) {
	add_action('widgets_init', 'houzez_widgets_init');
	function houzez_widgets_init()
	{
		register_sidebar(array(
			'name' => esc_html__('Default Sidebar', 'houzez'),
			'id' => 'default-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in the blog sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Property Listings', 'houzez'),
			'id' => 'property-listing',
			'description' => esc_html__('Widgets in this area will be shown in property listings sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Single Property', 'houzez'),
			'id' => 'single-property',
			'description' => esc_html__('Widgets in this area will be shown in single property sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Agency Sidebar', 'houzez'),
			'id' => 'agency-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in agencies template and agency detail page.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Agent Sidebar', 'houzez'),
			'id' => 'agent-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in agents template and angent detail page.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Search Sidebar', 'houzez'),
			'id' => 'search-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in search result page.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Page Sidebar', 'houzez'),
			'id' => 'page-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in page sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('IDX Sidebar', 'houzez'),
			'id' => 'idx-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in idx template sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Create Listing Sidebar', 'houzez'),
			'id' => 'create-listing-sidebar',
			'description' => esc_html__('Widgets in this area will be shown in create listing sidebar.', 'houzez'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Footer Area 1', 'houzez'),
			'id' => 'footer-sidebar-1',
			'description' => esc_html__('Widgets in this area will be show in footer column one', 'houzez'),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Footer Area 2', 'houzez'),
			'id' => 'footer-sidebar-2',
			'description' => esc_html__('Widgets in this area will be show in footer column two', 'houzez'),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Footer Area 3', 'houzez'),
			'id' => 'footer-sidebar-3',
			'description' => esc_html__('Widgets in this area will be show in footer column three', 'houzez'),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
		register_sidebar(array(
			'name' => esc_html__('Footer Area 4', 'houzez'),
			'id' => 'footer-sidebar-4',
			'description' => esc_html__('Widgets in this area will be show in footer column four', 'houzez'),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-top"><h3 class="widget-title">',
			'after_title' => '</h3></div>',
		));
	}
}

if ( !current_user_can('administrator') && !is_admin() ) {
	//show_admin_bar( false );
	add_filter('show_admin_bar', '__return_false');
}

if( !function_exists('houzez_vcSetAsTheme') ) {
	add_action('vc_before_init', 'houzez_vcSetAsTheme');
	function houzez_vcSetAsTheme()
	{
		vc_set_as_theme($disable_updater = false);
	}
}

if ( !function_exists( 'houzez_block_users' ) ) :

	add_action( 'init', 'houzez_block_users' );

	function houzez_block_users() {
		$users_admin_access = houzez_option('users_admin_access');

		if( is_user_logged_in() ) {
			if ($users_admin_access != 0) {
				if (is_admin() && !current_user_can('administrator') && isset( $_GET['action'] ) != 'delete' && !(defined('DOING_AJAX') && DOING_AJAX)) {
					wp_die(esc_html("You don't have permission to access this page.", "Houzez"));
					exit;
				}
			}
		}
	}

endif;

if( !function_exists('houzez_custom_ppp') ) {
	function houzez_custom_ppp($query)
	{
		if (!is_admin() && $query->is_tax(array('property_type', 'property_feature', 'property_status', 'property_city', 'property_area', 'property_state', 'property_label')) && $query->is_main_query()) {
			$taxonomy_num_posts = houzez_option('taxonomy_num_posts');
			$query->set('posts_per_page', $taxonomy_num_posts);
		}
	}

	add_action('pre_get_posts', 'houzez_custom_ppp');
}

if(!function_exists('update_ae_to_uae')) {
	function update_ae_to_uae() {
		if(isset($_GET['update_ae_to_uae']) && $_GET['update_ae_to_uae'] == 'yes') {

				global $wpdb;

				$sql_2 = $wpdb->prepare( "SELECT * from $wpdb->postmeta WHERE meta_key = '%s' and meta_value = '%s' GROUP BY meta_value ORDER BY meta_value ASC", 'fave_property_country', 'AE');

		        $countries = $wpdb->get_results( $sql_2, OBJECT_K );
		        
		        foreach ($countries as $con) {
		        	update_post_meta( $con->post_id, 'fave_property_country', 'UAE', 'AE' ); 
		        }
		}
	}
}
update_ae_to_uae();


//Delete property attachments when delete property
add_action( 'before_delete_post', 'houzez_delete_property_attachments' );
if( !function_exists('houzez_delete_property_attachments') ) {
	function houzez_delete_property_attachments($postid)
	{

		// We check if the global post type isn't ours and just return
		global $post_type;

		if ($post_type == 'property') {
			$media = get_children(array(
				'post_parent' => $postid,
				'post_type' => 'attachment'
			));
			if (!empty($media)) {
				foreach ($media as $file) {
					// pick what you want to do
					//unlink(get_attached_file($file->ID));
					wp_delete_attachment($file->ID);
				}
			}
			$attachment_ids = get_post_meta($postid, 'fave_property_images', false);
			if (!empty($attachment_ids)) {
				foreach ($attachment_ids as $id) {
					wp_delete_attachment($id);
				}
			}
		}
		return;
	}
}

/**
 *	---------------------------------------------------------------------------------------
 *	Gravity Forms Support
 *	---------------------------------------------------------------------------------------
 */
add_filter( 'gform_field_container', 'add_bootstrap_container_class', 10, 6 );
function add_bootstrap_container_class( $field_container, $field, $form, $css_class, $style, $field_content ) {
  $id = $field->id;
  $field_id = is_admin() || empty( $form ) ? "field_{$id}" : 'field_' . $form['id'] . "_$id";
  return '<li id="' . $field_id . '" class="' . $css_class . ' form-group">{FIELD_CONTENT}</li>';
}
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
add_filter( 'gform_confirmation_anchor', '__return_false' );

?>