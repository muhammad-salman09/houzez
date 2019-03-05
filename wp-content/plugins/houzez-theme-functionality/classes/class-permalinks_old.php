<?php
/**
 * Plugin settings screen.
 *
 * @package    Houzez
 * @subpackage Admin
 * @author     Waqas <waqas@favethemes.com>
 * @copyright  Copyright (c) 2016, Favethemes
 * @link       http://favethemes.com
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Sets up and handles the plugin settings screen.
 *
 * @since  1.0.8
 * @access public
 */
final class Houzez_Permalinks {

	/**
	 * Settings page name.
	 *
	 * @since  1.0.8
	 * @access public
	 * @var    string
	 */
	public $settings_page = '';

	/**
	 * Sets up the needed actions for adding and saving the meta boxes.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'property_admin_menu' ) );
	}

	/**
	 * Sets up custom admin menus.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function property_admin_menu() {

		// Create the settings page.
		$this->settings_page = add_submenu_page(
			'edit.php?post_type=property',
			esc_html__( 'Permalinks', 'houzez-theme-functionality' ),
			esc_html__( 'Permalinks',           'houzez-theme-functionality' ),
			apply_filters( 'houzez_settings_capability', 'manage_options' ),
			'settings',
			array( $this, 'settings_page' )
		);

		if ( $this->settings_page ) {

			// Register settings.
			add_action( 'admin_init', array( $this, 'houzez_register_settings' ) );

			// Add help tabs.
			//add_action( "load-{$this->settings_page}", array( $this, 'add_help_tabs' ) );
		}
	}

	/**
	 * Registers the plugin settings.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	function houzez_register_settings() {

		// Register the setting.
		register_setting( 'houzez_settings', 'houzez_settings', array( $this, 'houzez_validate_settings' ) );

		/* === Settings Sections === */
		add_settings_section( 'permalinks', esc_html__( 'Permalinks',       'houzez-theme-functionality' ), array( $this, 'houzez_section_permalinks' ), $this->settings_page );

		/* === Settings Fields === */
		add_settings_field( 'property_rewrite_base',   esc_html__( 'Property Slug',   'houzez-theme-functionality' ), array( $this, 'houzez_property_rewrite_base'   ), $this->settings_page, 'permalinks' );
		add_settings_field( 'property_type_rewrite_base',   esc_html__( 'Property Type Slug',   'houzez-theme-functionality' ), array( $this, 'houzez_property_type_rewrite_base'   ), $this->settings_page, 'permalinks' );
		add_settings_field( 'property_feature_rewrite_base',   esc_html__( 'Property Feature Slug',   'houzez-theme-functionality' ), array( $this, 'houzez_property_feature_rewrite_base'   ), $this->settings_page, 'permalinks' );
		add_settings_field( 'property_status_rewrite_base',   esc_html__( 'Property Status Slug',   'houzez-theme-functionality' ), array( $this, 'houzez_property_status_rewrite_base'   ), $this->settings_page, 'permalinks' );
		add_settings_field( 'property_area_rewrite_base',   esc_html__( 'Property Area Slug',   'houzez-theme-functionality' ), array( $this, 'houzez_property_area_rewrite_base'   ), $this->settings_page, 'permalinks' );
		add_settings_field( 'property_city_rewrite_base',   esc_html__( 'Property City Slug',   'houzez-theme-functionality' ), array( $this, 'houzez_property_city_rewrite_base'   ), $this->settings_page, 'permalinks' );
		add_settings_field( 'property_state_rewrite_base',   esc_html__( 'Property State Slug',   'houzez-theme-functionality' ), array( $this, 'houzez_property_state_rewrite_base'   ), $this->settings_page, 'permalinks' );

		add_settings_field( 'agent_rewrite_base',   esc_html__( 'Agent Slug',   'houzez-theme-functionality' ), array( $this, 'houzez_agent_rewrite_base'   ), $this->settings_page, 'permalinks' );

		add_settings_field( 'agency_rewrite_base',   esc_html__( 'Agency Slug',   'houzez-theme-functionality' ), array( $this, 'houzez_agency_rewrite_base'   ), $this->settings_page, 'permalinks' );
	}

	/**
	 * Permalinks section callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function houzez_section_permalinks() { ?>

		<p class="description">
			<?php esc_html_e( 'Set up custom permalinks for the property section on your site.', 'houzez-theme-functionality' ); ?>
		</p>
	<?php }

	/**
	 * Property rewrite base field callback.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function houzez_property_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="houzez_settings[property_rewrite_base]" value="<?php echo esc_attr( houzez_get_property_rewrite_base() ); ?>" />
		</label>

	<?php }

	/**
	 * Agent rewrite base field callback.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function houzez_agent_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="houzez_settings[agent_rewrite_base]" value="<?php echo esc_attr( houzez_get_agent_rewrite_base() ); ?>" />
		</label>

	<?php }

	/**
	 * Agency rewrite base field callback.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function houzez_agency_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="houzez_settings[agency_rewrite_base]" value="<?php echo esc_attr( houzez_get_agency_rewrite_base() ); ?>" />
		</label>

	<?php }

	/**
	 * Property type rewrite base field callback.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function houzez_property_type_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="houzez_settings[property_type_rewrite_base]" value="<?php echo esc_attr( houzez_get_property_type_rewrite_base() ); ?>" />
		</label>

	<?php }

	/**
	 * Property status rewrite base field callback.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function houzez_property_status_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="houzez_settings[property_status_rewrite_base]" value="<?php echo esc_attr( houzez_get_property_status_rewrite_base() ); ?>" />
		</label>

	<?php }

	/**
	 * Property feature rewrite base field callback.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function houzez_property_feature_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="houzez_settings[property_feature_rewrite_base]" value="<?php echo esc_attr( houzez_get_property_feature_rewrite_base() ); ?>" />
		</label>

	<?php }

	/**
	 * Property area rewrite base field callback.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function houzez_property_area_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="houzez_settings[property_area_rewrite_base]" value="<?php echo esc_attr( houzez_get_property_area_rewrite_base() ); ?>" />
		</label>

	<?php }

	/**
	 * Property city rewrite base field callback.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function houzez_property_city_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="houzez_settings[property_city_rewrite_base]" value="<?php echo esc_attr( houzez_get_property_city_rewrite_base() ); ?>" />
		</label>

	<?php }

	/**
	 * Property state rewrite base field callback.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function houzez_property_state_rewrite_base() { ?>

		<label>
			<code><?php echo esc_url( home_url( '/' ) ); ?></code>
			<input type="text" class="regular-text code" name="houzez_settings[property_state_rewrite_base]" value="<?php echo esc_attr( houzez_get_property_state_rewrite_base() ); ?>" />
		</label>

	<?php }


	/**
	 * Renders the settings page.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return void
	 */
	public function settings_page() {

		// Flush the rewrite rules if the settings were updated.
		if ( isset( $_GET['settings-updated'] ) )
			flush_rewrite_rules(); ?>

		<div class="wrap">

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'houzez_settings' ); ?>
				<?php do_settings_sections( $this->settings_page ); ?>
				<?php submit_button( esc_attr__( 'Update Settings', 'houzez-theme-functionality' ), 'primary' ); ?>
			</form>

		</div><!-- wrap -->
	<?php }


	/**
	 * Validates the plugin settings.
	 *
	 * @since  1.0.8
	 * @access public
	 * @param  array  $input
	 * @return array
	 */
	function houzez_validate_settings( $settings ) {

		// Text boxes.
		$settings['property_rewrite_base'] = $settings['property_rewrite_base'] ? trim( strip_tags( $settings['property_rewrite_base']   ), '/' ) : '';
		$settings['property_type_rewrite_base'] = $settings['property_type_rewrite_base'] ? trim( strip_tags( $settings['property_type_rewrite_base']   ), '/' ) : '';
		$settings['property_feature_rewrite_base'] = $settings['property_feature_rewrite_base'] ? trim( strip_tags( $settings['property_feature_rewrite_base']   ), '/' ) : '';
		$settings['property_status_rewrite_base'] = $settings['property_status_rewrite_base'] ? trim( strip_tags( $settings['property_status_rewrite_base']   ), '/' ) : '';
		$settings['property_area_rewrite_base'] = $settings['property_area_rewrite_base'] ? trim( strip_tags( $settings['property_area_rewrite_base']   ), '/' ) : '';

		$settings['agent_rewrite_base'] = $settings['agent_rewrite_base'] ? trim( strip_tags( $settings['agent_rewrite_base']   ), '/' ) : '';

		$settings['agency_rewrite_base'] = $settings['agency_rewrite_base'] ? trim( strip_tags( $settings['agency_rewrite_base']   ), '/' ) : '';

		// Return the validated/sanitized settings.
		return $settings;
	}


	/**
	 * Returns the instance.
	 *
	 * @since  1.0.8
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) )
			$instance = new self;

		return $instance;
	}
}

Houzez_Permalinks::get_instance();