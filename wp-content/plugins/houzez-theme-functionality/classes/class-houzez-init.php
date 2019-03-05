<?php


class Houzez {

    /**
     * Plugin instance.
     *
     * @var Houzez
     */
    protected static $instance;


    /**
     * Plugin version.
     *
     * @var string
     */
    protected static $version = '1.5.4';


    /**
     * Constructor.
     */
    protected function __construct()
    {   
        $this->actions();
        $this->init();
        $this->houzez_inc_files();
        $this->filters();
    }

    /**
     * Return plugin version.
     *
     * @return string
     */
    public static function getVersion() {
        return static::$version;
    }

    /**
     * Return plugin instance.
     *
     * @return Houzez
     */
    protected static function getInstance() {
        return is_null( static::$instance ) ? new Houzez() : static::$instance;
    }

    /**
     * Initialize plugin.
     *
     * @return void
     */
    public static function run() {
        self::houzez_function_loader();
        self::houzez_class_loader();
        static::$instance = static::getInstance();
    }


    /**
     * include files
     *
     * @since 1.0
     *
    */
    function houzez_inc_files() {

        $fave_theme_name = (wp_get_theme()->Name);

        $activation_status = get_option( 'houzez_activation' );

        if( $fave_theme_name == 'Houzez' || $fave_theme_name == 'Houzez Child' ) {

            // VC Shourcodes
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/section-title.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/advance-search.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/grids.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/property-carousel.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/property-carousel-v2.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/properties-grids.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/testimonials.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/agents.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/partners.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/blog-posts.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/blog-posts-carousel.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/properties.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/property-by-id.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/property-by-ids.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/properties-map.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/team-member.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/price-table.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/vc_shortcodes/space.php');

            //Meta & Tax
            require_once(HOUZEZ_PLUGIN_PATH . '/extensions/Tax-meta-class/Tax-meta-class.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/extensions/Tax-meta-class/fave-class-config.php');

            if (!class_exists('RW_Meta_Box')) {
                require_once(HOUZEZ_PLUGIN_PATH . '/extensions/meta-box/meta-box.php');
            }
            if (!class_exists('RWMB_Tabs')) {
                require_once(HOUZEZ_PLUGIN_PATH . '/extensions/meta-box/addons/meta-box-tabs/meta-box-tabs.php');
            }
            if (!class_exists('RWMB_Columns')) {
                require_once(HOUZEZ_PLUGIN_PATH . '/extensions/meta-box/addons/meta-box-columns/meta-box-columns.php');
            }
            if (!class_exists('RWMB_Group')) {
                require_once(HOUZEZ_PLUGIN_PATH . '/extensions/meta-box/addons/meta-box-group/meta-box-group.php');
            }

            // Include the Redux theme options Framework
            if (!class_exists('ReduxFramework')) {
                require_once(HOUZEZ_PLUGIN_PATH . '/extensions/redux/ReduxCore/framework.php');
            }

            //paypal
            require_once(HOUZEZ_PLUGIN_PATH . '/third-party/3rdparty_functions.php');

            require_once(HOUZEZ_PLUGIN_PATH . '/statistics/houzez-statistics-functions.php');
            require_once(HOUZEZ_PLUGIN_PATH . '/statistics/houzez-statistics.php');

        } // End theme check
    }


    /**
     * Plugin actions.
     *
     * @return void
     */
    public function actions() {

        add_action( 'admin_menu', array( $this, 'houzez_register_admin_pages' ) );
        //add_action( 'activated_plugin', array( $this, 'redirect' ) );
    }

    /**
     * Add filters to the WordPress functionality.
     *
     * @return void
     */
    public function filters() {
        add_filter( 'houzez_theme_meta', array( $this, 'houzez_field_builder_meta' ), 9, 1 );
    }

    public function houzez_field_builder_meta($meta_boxes) {
        //print_r($meta_boxes); 

        if(class_exists('Houzez_Fields_Builder')) {
            $fields = array();
            $houzez_prefix = 'fave_';
            $fields_array = Houzez_Fields_Builder::get_form_fields();
            $i = 500; $j = 0;

            $columns = 6;
            
            if(!empty($fields_array)) {
                $numItems = count($fields_array);
                foreach ($fields_array as $value) {
                    $i++;

                    /*if((++$j === $numItems) && $numItems % 2 != 0 ) {
                        $columns = 12;
                      }*/


                    if($value->type == 'select') {
                        $options = unserialize($value->fvalues);

                        $options_array = array();
                        if(!empty($options)) {
                            foreach ($options as $key => $val) {
                                $options_array[$key] = $val;
                            }
                        }

                        $fields = array(
                            'id' => "{$houzez_prefix}".$value->field_id,
                            'name' => $value->label,
                            'type' => $value->type,
                            'placeholder' => $value->placeholder,
                            'std' => "",
                            'desc' => '',
                            'options' => $options_array,
                            'columns' => $columns,
                            'tab' => 'property_details',
                        );
                    } else {
                        $fields = array(
                            'id' => "{$houzez_prefix}".$value->field_id,
                            'name' => $value->label,
                            'type' => $value->type,
                            'placeholder' => $value->placeholder,
                            'std' => "",
                            'desc' => '',
                            'columns' => $columns,
                            'tab' => 'property_details',
                        );
                    }

                    $meta_boxes[0]['fields'][$i] = $fields;
                }
            }
        }

        return $meta_boxes;
    }


    /**
     * Initialize classes
     *
     * @return void
     */
    public function init() {

        Houzez_Post_Type_Property::init();

        if(houzez_check_post_types_plugin('houzez_agencies_post')) {
            Houzez_Post_Type_Agency::init();
        }

        if(houzez_check_post_types_plugin('houzez_agents_post')) {
            Houzez_Post_Type_Agent::init();
        }

        if(houzez_check_post_types_plugin('houzez_testimonials_post')) {
            Houzez_Post_Type_Testimonials::init();
        }

        if(houzez_check_post_types_plugin('houzez_partners_post')) {
            Houzez_Post_Type_Partner::init();
        }

        if(houzez_check_post_types_plugin('houzez_invoices_post')) {
            Houzez_Post_Type_Invoice::init();
        }

        if(houzez_check_post_types_plugin('houzez_packages_post')) {
            Houzez_Post_Type_Membership::init();
        }
        
        if( is_admin() ) {

            if(houzez_check_post_types_plugin('houzez_packages_info_post')) {
                Houzez_Post_Type_Packages::init();
            }
            Houzez_Fields_Builder::init();
            Houzez_Dashboard::init();
            Houzez_Currencies::init();
            Houzez_Changelog::init();
            Houzez_Post_Type::init();
            //Houzez_Taxonomies::init();
            Houzez_Permalinks::init();
        }

        add_action( 'admin_enqueue_scripts', array( __CLASS__ , 'enqueue_scripts' ) );

    }


    public static function enqueue_scripts() {
        $js_path = 'assets/admin/js/';
        $css_path = 'assets/admin/css/';

        wp_enqueue_style('houzez-admin-style', HOUZEZ_PLUGIN_URL . $css_path . 'style.css', array(), '1.0.0', 'all');
    }


    /**
     * Load plugin files.
     *
     * @return void
     */
    public static function houzez_class_loader()
    {
        $files = apply_filters( 'houzez_class_loader', array(
            HOUZEZ_PLUGIN_PATH . '/classes/class-property-post-type.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-agency-post-type.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-agent-post-type.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-membership-post-type.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-partners-post-type.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-testimonials-post-type.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-invoice-post-type.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-user-packages-post-type.php',
            HOUZEZ_PLUGIN_PATH . '/classes/Houzez_Query.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-dashboard.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-fields-builder.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-permalinks.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-changelog.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-currencies.php',
            HOUZEZ_PLUGIN_PATH . '/classes/class-post-types.php',
            //HOUZEZ_PLUGIN_PATH . '/classes/class-taxonomies.php',
        ) );

        foreach ( $files as $file ) {
            if ( file_exists( $file ) ) {
                include $file;
            }
        }
    }


    public static function houzez_function_loader() {
        $files = apply_filters( 'houzez_function_loader', array(
            HOUZEZ_PLUGIN_PATH . '/functions/functions-rewrite.php',
            HOUZEZ_PLUGIN_PATH . '/functions/functions-options.php',
            HOUZEZ_PLUGIN_PATH . '/functions/functions.php',
            
        ) );

        foreach ( $files as $file ) {
            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }
    }


    /**
     * Comma separated taxonomy terms with admin side links
     *
     * @return boolean | term
     */
    public static function admin_taxonomy_terms( $post_id, $taxonomy, $post_type ) {

        $terms = get_the_terms( $post_id, $taxonomy );

        if ( ! empty ( $terms ) ) {
            $out = array();
            /* Loop through each term, linking to the 'edit posts' page for the specific term. */
            foreach ( $terms as $term ) {
                $out[] = sprintf( '<a href="%s">%s</a>',
                    esc_url( add_query_arg( array( 'post_type' => $post_type, $taxonomy => $term->slug ), 'edit.php' ) ),
                    esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $taxonomy, 'display' ) )
                );
            }
            /* Join the terms, separating them with a comma. */
            return join( ', ', $out );
        }

        return false;
    }


    /*
    * Render Form fields
    */
    public static function render_form_field( $label, $field_name, $type, $options = array() )
    {
        $template = '<div class="">
                        <div class=""><label>%s</label></div>
                        <div class="">%s</div>
                    </div>';

        $template = apply_filters( 'houzez_form_fields_template', $template, $label, $options );

        $options_string = null;
        $options['name'] = $field_name;
        $options['value'] = ! empty( $options['value'] ) ? $options['value'] : false;

        foreach ( $options as $key => $value ) {
            if ( is_array( $value ) || ! $value ) continue;
            $options_string .= $key . '="' . $value . '" ';
        }

        switch ( $type ) {
            case 'checkbox':
                $field = "<input type='hidden' name='{$field_name}' value='0'/>
                          <input type='checkbox' {$options_string}>";
                break;

            case 'list':
            case 'select':
            case 'selectbox':
                $field = "<select {$options_string}>";

                if ( ! empty( $options['placeholder'] ) ) {
                    $field .= '<option value="">' . $options['placeholder'] . '</option>';
                }

                if ( ! empty( $options['values'] ) ) {
                    foreach ( $options['values'] as $pvalue => $plabel ) {
                        $field .= '<option value="' . $pvalue . '" '. selected( $pvalue, $options['value'], false ) .'>' .
                            ( is_string( $plabel ) ? $plabel : $plabel['label'] )
                            . '</option>';
                    }
                }

                $field .= '</select>';

                break;

            default:
                $field = "<input type='" . $type . "' {$options_string}>";
        }

        $template = sprintf( $template, $label, $field );

        return $template;
    }

    /**
    *
    * Register admin dashboard pages
    */

    public function houzez_register_admin_pages() {

        add_menu_page(
            esc_html__( 'Houzez', 'houzez-theme-functionality' ),
            esc_html__( 'Houzez', 'houzez-theme-functionality' ),
            'manage_options',
            'houzez_dashboard',
            array( 'Houzez_Dashboard', 'render' ),
            '',
            '4'
        );

        add_submenu_page(
            'houzez_dashboard',
            esc_html__( 'Dashboard', 'houzez-theme-functionality' ),
            esc_html__( 'Dashboard', 'houzez-theme-functionality' ),
            'manage_options',
            'houzez_dashboard',
            array( 'Houzez_Dashboard', 'render' )
        );

        add_submenu_page(
            'houzez_dashboard',
            esc_html__( 'Fields builder', 'houzez-theme-functionality' ),
            esc_html__( 'Fields builder', 'houzez-theme-functionality' ),
            'manage_options',
            'houzez_fbuilder',
            array( 'Houzez_Fields_Builder', 'render' )
        );

        add_submenu_page(
            'houzez_dashboard',
            esc_html__( 'Currencies', 'houzez-theme-functionality' ),
            esc_html__( 'Currencies', 'houzez-theme-functionality' ),
            'manage_options',
            'houzez_currencies',
            array( 'Houzez_Currencies', 'render' )
        );

        add_submenu_page(
            'houzez_dashboard',
            esc_html__( 'Post Types', 'houzez-theme-functionality' ),
            esc_html__( 'Post Types', 'houzez-theme-functionality' ),
            'manage_options',
            'houzez_post_types',
            array( 'Houzez_Post_Type', 'render' )
        );

        add_submenu_page(
            'houzez_dashboard',
            esc_html__( 'Permalinks', 'houzez-theme-functionality' ),
            esc_html__( 'Permalinks', 'houzez-theme-functionality' ),
            'manage_options',
            'houzez_permalinks',
            array( 'Houzez_Permalinks', 'render' )
        );
        /*add_submenu_page(
            'houzez_dashboard',
            esc_html__( 'Taxonomies', 'houzez-theme-functionality' ),
            esc_html__( 'Taxonomies', 'houzez-theme-functionality' ),
            'manage_options',
            'houzez_taxonomies',
            array( 'Houzez_Taxonomies', 'render' )
        );*/
        add_submenu_page(
            'houzez_dashboard',
            esc_html__( 'Changelog', 'houzez-theme-functionality' ),
            esc_html__( 'Changelog', 'houzez-theme-functionality' ),
            'manage_options',
            'houzez_changelog',
            array( 'Houzez_Changelog', 'render' )
        );

    }


    public static function houzez_plugin_activation()
    {

        global $wpdb;

        $table_name         = $wpdb->prefix . 'houzez_currencies';
        $charset_collate    = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id int(10) NOT NULL AUTO_INCREMENT,
          currency_name varchar(255) NOT NULL,
          currency_code varchar(55) NOT NULL,
          currency_symbol varchar(25) NOT NULL,
          currency_position varchar(25) NOT NULL DEFAULT 'before',
          currency_decimal int(10) NOT NULL,
          currency_decimal_separator varchar(10) NOT NULL DEFAULT '.',
          currency_thousand_separator varchar(10) NOT NULL DEFAULT ',',
          PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $sql );

        $table_name         = $wpdb->prefix . 'houzez_fields_builder';
        $charset_collate    = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
          id int(10) NOT NULL AUTO_INCREMENT,
          label varchar(255) NOT NULL,
          field_id varchar(255) NOT NULL,
          type varchar(25) NOT NULL,
          options text NULL,
          fvalues text NULL,
          is_search varchar(25) NULL,
          search_compare varchar(25) NULL,
          placeholder varchar(255) NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta( $sql );


        $table_name         = $wpdb->prefix . 'houzez_search';
        $charset_collate    = $wpdb->get_charset_collate();
        $sql                = "CREATE TABLE $table_name (
           id mediumint(9) NOT NULL AUTO_INCREMENT,
           auther_id mediumint(9) NOT NULL,
           query longtext NOT NULL,
           email longtext DEFAULT '' NOT NULL,
           url longtext DEFAULT '' NOT NULL,
           time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
           UNIQUE KEY id (id)
       ) $charset_collate;";

        dbDelta( $sql );

        $table_name         = $wpdb->prefix . 'houzez_threads';
        $charset_collate    = $wpdb->get_charset_collate();
        $sql                = "CREATE TABLE $table_name (
           id mediumint(9) NOT NULL AUTO_INCREMENT,
           sender_id mediumint(9) NOT NULL,
           receiver_id mediumint(9) NOT NULL,
           property_id mediumint(9) NOT NULL,
           seen mediumint(9) NOT NULL,
           time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
           UNIQUE KEY id (id)
       ) $charset_collate;";

        dbDelta( $sql );

        $table_name         = $wpdb->prefix . 'houzez_thread_messages';
        $charset_collate    = $wpdb->get_charset_collate();
        $sql                = "CREATE TABLE $table_name (
           id mediumint(9) NOT NULL AUTO_INCREMENT,
           created_by mediumint(9) NOT NULL,
           thread_id mediumint(9) NOT NULL,
           message longtext DEFAULT '' NOT NULL,
           attachments longtext DEFAULT '' NOT NULL,
           time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
           UNIQUE KEY id (id)
       ) $charset_collate;";

        dbDelta( $sql );

        
        if (!wp_next_scheduled('houzez_check_new_listing_action_hook')) {
            wp_schedule_event(time(), 'daily', 'houzez_check_new_listing_action_hook');
        }

        if (!wp_next_scheduled('houzez_check_new_listing_action_hook')) {
            wp_schedule_event(time(), 'weekly', 'houzez_check_new_listing_action_hook');
        }

    }

    public static function houzez_plugin_deactivate()
    {

        global $wpdb;

        $table_name = $wpdb->prefix . 'houzez_search';
        $sql        = "DROP TABLE ". $table_name;

        $wpdb->query( $sql );

        wp_clear_scheduled_hook('houzez_check_new_listing_action_hook');

    }

    public function redirect($plugin) {
        if ( $plugin == HOUZEZ_PLUGIN_BASENAME ) {
            wp_redirect( 'admin.php?page=houzez_dashboard' );
            wp_die();
        }
    }

}
?>