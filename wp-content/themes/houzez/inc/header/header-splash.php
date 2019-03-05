<?php
if ( is_page_template( 'template/template-splash.php' ) ) {
    $css_class = 'header-section splash-header';
} else {
    $css_class = 'header-section';
}

global $current_user;
wp_get_current_user();
$userID  =  $current_user->ID;
$user_custom_picture =  get_the_author_meta( 'fave_author_custom_picture' , $userID );
$splash_page_nav = houzez_option('splash_page_nav');
$splash_menu_align = houzez_option('splash_menu_align');
$create_lisiting_enable = houzez_option('create_lisiting_enable');
$header_login = houzez_option('header_login');
$houzez_user_logout = '';
if( ! is_user_logged_in() ) {
    $houzez_user_logout = 'houzez-user-logout';
    if( $header_login != 'yes' ) {
        $houzez_user_logout = 'houzez-disabled-login';
    }
    if( $create_lisiting_enable != 1 ) {
        $houzez_user_logout = 'houzez-disabled-create-listing';
    }
    if( $header_login != 'yes' && $create_lisiting_enable != 1 ) {
        $houzez_user_logout = '';
    }
}
?>
<!--start section header-->
<header id="header-section" class="clearfix houzez-header-main <?php echo esc_attr( $css_class ).' '.esc_attr( $splash_menu_align ).' '.esc_attr($houzez_user_logout); ?>">
    <div class="header-mobile visible-sm visible-xs">
        <div class="container">
            <!--start mobile nav-->
            <div class="mobile-nav">
                <span class="nav-trigger"><i class="fa fa-navicon"></i></span>
                <div class="nav-dropdown main-nav-dropdown"></div>
            </div>
            <!--end mobile nav-->
            <div class="header-logo logo-mobile-splash">
                <?php
                $mobile_logo = houzez_option( 'custom_logo_mobile_splash', false, 'url' );
                ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php if( !empty( $mobile_logo ) ) { ?>
                        <img src="<?php echo esc_url( $mobile_logo ); ?>" alt="Mobile logo">
                    <?php } ?>
                </a>
            </div>
            <?php if( houzez_option('header_login') != 'no' || houzez_option('create_lisiting_enable') != 0 ): ?>
                <div class="header-user">
                    <?php get_template_part('inc/header/login-nav', 'mobile'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="splash-header-inner hidden-sm hidden-xs">
        <div class="header-left">

            <div class="logo logo-desktop">
                <?php get_template_part('inc/header/logo'); ?>
            </div>

            <?php if( $splash_page_nav != 0 ) { ?>
            <nav class="navi main-nav">
                <?php
                // Pages Menu
                if ( has_nav_menu( 'main-menu' ) ) :
                    wp_nav_menu( array (
                        'theme_location' => 'main-menu',
                        'container' => '',
                        'container_class' => '',
                        'menu_class' => '',
                        'menu_id' => 'main-nav',
                        'depth' => 4
                    ));
                endif;
                ?>
            </nav>
            <?php } ?>

        </div>
        <div class="header-right">
            <?php if( houzez_option('header_login') != 'no' || houzez_option('create_lisiting_enable') != 0 ): ?>
                <?php get_template_part('inc/header/login', 'nav'); ?>
            <?php endif; ?>
        </div>
    </div>
</header>
<!--end section header-->