<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 09/10/16
 * Time: 10:29 PM
 */
if ( get_option( 'houzez_1_6_db' ) == false ) :

    function houzez_db_update_notice() {

        $update_url     = add_query_arg( array(
            'houzez_update_bd' => 'true'
        ), admin_url() );

        ?>
        <div class="error notice">
            <h3><?php _e( 'Database need to be update for Houzez 1.6.0', 'houzez' ); ?></h3>
            <p><a href="<?php echo esc_url( $update_url ); ?>"><?php _e( 'Click here for database update, It is required', 'houzez' ); ?></a></p>
        </div>
        <?php

    }

    add_action( 'admin_notices', 'houzez_db_update_notice' );

    function houzez_update_bd() {

        if ( isset( $_REQUEST['houzez_update_bd'] ) && $_REQUEST['houzez_update_bd'] == true ) :

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

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

            add_option( 'houzez_1_6_db', true );

            header( 'Location: ' . admin_url() );

        endif;

    }

    add_action( 'admin_init', 'houzez_update_bd' );

endif;