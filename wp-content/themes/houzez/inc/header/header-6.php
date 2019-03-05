<?php
global $current_user;
wp_get_current_user();
$userID  =  $current_user->ID;
$user_custom_picture =  get_the_author_meta( 'fave_author_custom_picture' , $userID );

$call_us_img = houzez_option( 'hd3_call_us_image', false, 'url' );
$main_menu_sticky = houzez_option('main-menu-sticky');
$top_bar = houzez_option('top_bar');

if( $top_bar != 0 ) {
    get_template_part('inc/header/top', 'bar');
}
$houzez_user_logout = '';
if( ! is_user_logged_in() ) {
    $houzez_user_logout = 'houzez-user-logout';
}
?>
<!--start section header-->
<header id="header-section" class="header-section-6 houzez-header-main" data-sticky="<?php echo esc_attr( $main_menu_sticky ); ?>">
    <div class="header-top">
        <div class="container-fluid">
            <div class="header-section-6-wrap header-section-6-icons">
                <?php get_template_part( 'inc/header/social' ); ?>
            </div>
            <div class="header-section-6-wrap header-section-6-left-menu">
                <div class="header-nav header-nav-left">
                    <nav class="navi main-nav">
                        <?php
                        // Pages Menu
                        if ( has_nav_menu( 'main-menu-left' ) ) :
                            wp_nav_menu( array (
                                'theme_location' => 'main-menu-left',
                                'container' => '',
                                'container_class' => '',
                                'menu_class' => '',
                                'menu_id' => 'main-nav-left',
                                'depth' => 4
                            ));
                        endif;
                        ?>
                    </nav>
                </div> 
            </div>
            <div class="header-section-6-wrap header-section-6-logo">
                <div class="logo logo-desktop hidden-sm hidden-xs">
                    <?php get_template_part('inc/header/logo'); ?>
                </div>
            </div>
            <div class="header-section-6-wrap header-section-6-right-menu">
                <div class="header-nav header-nav-right">
                    <nav class="navi main-nav">
                        <?php
                        // Pages Menu
                        if ( has_nav_menu( 'main-menu-right' ) ) :
                            wp_nav_menu( array (
                                'theme_location' => 'main-menu-right',
                                'container' => '',
                                'container_class' => '',
                                'menu_class' => '',
                                'menu_id' => 'main-nav-right',
                                'depth' => 4
                            ));
                        endif;
                        ?>
                    </nav>
                </div>
            </div>
            <div class="header-section-6-wrap header-section-6-user-tools">
                <?php if( class_exists('Houzez_login_register') ): ?>
                    <?php if( houzez_option('header_login') != 'no' || houzez_option('create_lisiting_enable') != 0 ): ?>
                        <div class="header-right">
                            <?php get_template_part('inc/header/login', 'nav'); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>                    
<!--end section header-->

<?php get_template_part( 'inc/header/mobile-menu-header-6' ); ?>
