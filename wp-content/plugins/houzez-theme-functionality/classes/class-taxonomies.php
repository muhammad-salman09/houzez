<?php
/**
 * Class Houzez_Post_Type_Agency
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 28/09/16
 * Time: 10:16 PM
 */
class Houzez_Taxonomies {


	/**
	 * Sets up init
	 *
	 */
	public static function init() {
        add_action( 'admin_init', array( __CLASS__, 'houzez_register_settings' ) );
    }


	public static function render() {
      
        // Flush the rewrite rules if the settings were updated.
        if ( isset( $_GET['settings-updated'] ) )
            flush_rewrite_rules(); ?>

        <div class="wrap">

            <?php settings_errors(); ?>

            
            <?php
            $template = HOUZEZ_TEMPLATES.'tabs.php';

            if ( file_exists( $template ) ) {
                load_template( $template );
            }
            ?>

            <form method="post" action="options.php">
                <?php settings_fields( 'houzez_tax_settings' ); ?>
                <?php do_settings_sections( 'houzez_taxonomies' ); ?>
                <?php submit_button( esc_attr__( 'Update Settings', 'houzez-theme-functionality' ), 'primary' ); ?>
            </form>

        </div><!-- wrap -->
    <?php
    }

    public static function houzez_register_settings() {

        // Register the setting.
        register_setting( 'houzez_tax_settings', 'houzez_tax_settings', array( __CLASS__, 'houzez_validate_settings' ) );

        /* === Settings Sections === */
        add_settings_section( 'houzez_taxonomies_section', esc_html__( 'Taxonomies', 'houzez-theme-functionality' ), array( __CLASS__, 'houzez_section_taxonomies' ), 'houzez_taxonomies' );

        /* === Settings Fields === */
        add_settings_field( 'property_type',   esc_html__( 'Type',   'houzez-theme-functionality' ), array( __CLASS__, 'houzez_tax_type_field'   ), 'houzez_taxonomies', 'houzez_taxonomies_section' );

        add_settings_field( 'property_status',   esc_html__( 'status',   'houzez-theme-functionality' ), array( __CLASS__, 'houzez_tax_status_field'   ), 'houzez_taxonomies', 'houzez_taxonomies_section' );

        add_settings_field( 'property_feature',   esc_html__( 'features',   'houzez-theme-functionality' ), array( __CLASS__, 'houzez_tax_features_field'   ), 'houzez_taxonomies', 'houzez_taxonomies_section' );

        add_settings_field( 'property_label',   esc_html__( 'label',   'houzez-theme-functionality' ), array( __CLASS__, 'houzez_tax_label_field'   ), 'houzez_taxonomies', 'houzez_taxonomies_section' );

        add_settings_field( 'property_city',   esc_html__( 'City',   'houzez-theme-functionality' ), array( __CLASS__, 'houzez_tax_city_field'   ), 'houzez_taxonomies', 'houzez_taxonomies_section' );

        add_settings_field( 'property_area',   esc_html__( 'Neighborhood',   'houzez-theme-functionality' ), array( __CLASS__, 'houzez_tax_neighborhood_field'   ), 'houzez_taxonomies', 'houzez_taxonomies_section' );

        add_settings_field( 'property_state',   esc_html__( 'County / State',   'houzez-theme-functionality' ), array( __CLASS__, 'houzez_tax_state_field'   ), 'houzez_taxonomies', 'houzez_taxonomies_section' );

    }

    /**
     * Validates the plugin settings.
     *
     * @since  1.0.8
     * @access public
     * @param  array  $input
     * @return array
     */
    public static function houzez_validate_settings( $settings ) {

        // Text boxes.
        $settings['property_type'] = $settings['property_type'] ? trim( strip_tags( $settings['property_type']   ), '/' ) : '';
        $settings['property_status'] = $settings['property_status'] ? trim( strip_tags( $settings['property_status']   ), '/' ) : '';
        $settings['property_feature'] = $settings['property_feature'] ? trim( strip_tags( $settings['property_feature']   ), '/' ) : '';
        $settings['property_label'] = $settings['property_label'] ? trim( strip_tags( $settings['property_label']   ), '/' ) : '';
        $settings['property_city'] = $settings['property_city'] ? trim( strip_tags( $settings['property_city']   ), '/' ) : '';
        $settings['property_area'] = $settings['property_area'] ? trim( strip_tags( $settings['property_area']   ), '/' ) : '';
        $settings['property_state'] = $settings['property_state'] ? trim( strip_tags( $settings['property_state']   ), '/' ) : '';

        // Return the validated/sanitized settings.
        return $settings;
    }

    /**
     * Taxonomies section callback.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public static function houzez_section_taxonomies() { ?>

        <p class="description">
            <?php esc_html_e( 'Disable Taxonomies which you do not want to show(if disabled then these will not show on back-end and front-end)', 'houzez-theme-functionality' ); ?>
        </p>
    <?php }


    /**
     * Type field callback.
     *
     * @since  1.0.8
     * @access public
     * @return void
     */
    public static function houzez_tax_type_field() { ?>

        <label>
            <select name="houzez_tax_settings[property_type]" class="regular-text">
                <option <?php selected(self::get_setting('property_type'), 'enabled'); ?> value="enabled"><?php esc_html_e('Enabled', 'houzez'); ?></option>
                <option <?php selected(self::get_setting('property_type'), 'disabled'); ?> value="disabled"><?php esc_html_e('Disabled', 'houzez'); ?></option>
            </select>
        </label>

    <?php }

    /**
     * Status field callback.
     *
     * @since  1.0.8
     * @access public
     * @return void
     */
    public static function houzez_tax_status_field() { ?>

        <label>
            <select name="houzez_tax_settings[property_status]" class="regular-text">
                <option <?php selected(self::get_setting('property_status'), 'enabled'); ?> value="enabled"><?php esc_html_e('Enabled', 'houzez'); ?></option>
                <option <?php selected(self::get_setting('property_status'), 'disabled'); ?> value="disabled"><?php esc_html_e('Disabled', 'houzez'); ?></option>
            </select>
        </label>

    <?php }


    /**
     * Features field callback.
     *
     * @since  1.0.8
     * @access public
     * @return void
     */
    public static function houzez_tax_features_field() { ?>

        <label>
            <select name="houzez_tax_settings[property_feature]" class="regular-text">
                <option <?php selected(self::get_setting('property_feature'), 'enabled'); ?> value="enabled"><?php esc_html_e('Enabled', 'houzez'); ?></option>
                <option <?php selected(self::get_setting('property_feature'), 'disabled'); ?> value="disabled"><?php esc_html_e('Disabled', 'houzez'); ?></option>
            </select>
        </label>

    <?php }

    /**
     * Label field callback.
     *
     * @since  1.0.8
     * @access public
     * @return void
     */
    public static function houzez_tax_label_field() { ?>

        <label>
            <select name="houzez_tax_settings[property_label]" class="regular-text">
                <option <?php selected(self::get_setting('property_label'), 'enabled'); ?> value="enabled"><?php esc_html_e('Enabled', 'houzez'); ?></option>
                <option <?php selected(self::get_setting('property_label'), 'disabled'); ?> value="disabled"><?php esc_html_e('Disabled', 'houzez'); ?></option>
            </select>
        </label>

    <?php }
    
    /**
     * City field callback.
     *
     * @since  1.0.8
     * @access public
     * @return void
     */
    public static function houzez_tax_city_field() { ?>

        <label>
            <select name="houzez_tax_settings[property_city]" class="regular-text">
                <option <?php selected(self::get_setting('property_city'), 'enabled'); ?> value="enabled"><?php esc_html_e('Enabled', 'houzez'); ?></option>
                <option <?php selected(self::get_setting('property_city'), 'disabled'); ?> value="disabled"><?php esc_html_e('Disabled', 'houzez'); ?></option>
            </select>
        </label>

    <?php }

    /**
     * Neighbourhood field callback.
     *
     * @since  1.0.8
     * @access public
     * @return void
     */
    public static function houzez_tax_neighborhood_field() { ?>

        <label>
            <select name="houzez_tax_settings[property_area]" class="regular-text">
                <option <?php selected(self::get_setting('property_area'), 'enabled'); ?> value="enabled"><?php esc_html_e('Enabled', 'houzez'); ?></option>
                <option <?php selected(self::get_setting('property_area'), 'disabled'); ?> value="disabled"><?php esc_html_e('Disabled', 'houzez'); ?></option>
            </select>
        </label>

    <?php }

    /**
     * State field callback.
     *
     * @since  1.0.8
     * @access public
     * @return void
     */
    public static function houzez_tax_state_field() { ?>

        <label>
            <select name="houzez_tax_settings[property_state]" class="regular-text">
                <option <?php selected(self::get_setting('property_state'), 'enabled'); ?> value="enabled"><?php esc_html_e('Enabled', 'houzez'); ?></option>
                <option <?php selected(self::get_setting('property_state'), 'disabled'); ?> value="disabled"><?php esc_html_e('Disabled', 'houzez'); ?></option>
            </select>
        </label>

    <?php }


    /**
     * Returns taxonomy settings.
     *
     * @since  1.0.8
     * @access public
     * @param  string  $setting
     * @return mixed
     */
    public static function get_setting( $setting ) {

        $defaults = self::get_default_settings();
        $settings = wp_parse_args( get_option('houzez_tax_settings', $defaults ), $defaults );

        return isset( $settings[ $setting ] ) ? $settings[ $setting ] : false;
    }

    /**
     * Returns the default settings for the plugin.
     *
     * @since  1.0.8
     * @access public
     * @return array
     */
    public static function get_default_settings() {

        $settings = array(
            'property_type' => 'enabled',
            'property_status' => 'enabled',
            'property_feature' => 'enabled',
            'property_label' => 'enabled',
            'property_city' => 'enabled',
            'property_area' => 'enabled',
            'property_state' => 'enabled',
        );

        return $settings;
    }
	
}