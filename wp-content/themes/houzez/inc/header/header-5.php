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
<header id="header-section" class="header-section-5 houzez-header-main <?php echo esc_attr($houzez_user_logout); ?>">
    <div class="header-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <?php get_template_part( 'inc/header/social' ); ?>
                </div>

                <div class="col-sm-4">
                    <div class="logo logo-desktop hidden-sm hidden-xs">
                        <?php get_template_part('inc/header/logo'); ?>
                    </div>
                </div>

                <div class="col-sm-4">
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
    <div class="header-bottom"  data-sticky="<?php echo esc_attr( $main_menu_sticky ); ?>">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="header-nav">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--end section header-->

<?php get_template_part( 'inc/header/mobile-header' ); ?>
