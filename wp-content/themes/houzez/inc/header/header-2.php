<?php
$social_pull_right = $contact_pull_right = '';
$contact_info = true;
$social = true;
if( houzez_option('hd2_contact_info') != '1' && houzez_option('hd2_address_info') != '1' && houzez_option('hd2_timing_info') != '1' ) {
    $social_pull_right = 'pull-right';
    $contact_info = false;
}

if( houzez_option('social-header') != '1' ) {
    $contact_pull_right = 'pull-right';
    $social = false;
}

$main_menu_sticky = houzez_option('main-menu-sticky');

global $current_user;
wp_get_current_user();
$userID  =  $current_user->ID;
$user_custom_picture =  get_the_author_meta( 'fave_author_custom_picture' , $userID );
$top_bar = houzez_option('top_bar');

if( $top_bar != 0 ) {
    get_template_part('inc/header/top', 'bar');
}
$houzez_user_logout = '';
if( ! is_user_logged_in() ) {
    $houzez_user_logout = 'houzez-user-logout';
}
?>
<header id="header-section" class="header-section-3 houzez-header-main <?php echo esc_attr($houzez_user_logout); ?>">

    <div class="header-top">
        <div class="header-top-inner">
            
            <div class="logo logo-desktop">
                <?php get_template_part('inc/header/logo'); ?>
            </div>

        <?php if( $contact_info != false ) { ?>
        
            <?php get_template_part( 'inc/header/contact', 'info' ); ?>
        
        <?php } ?>

        <?php if( $social != false ) { ?>
        
            <?php get_template_part( 'inc/header/social' ); ?>
        
        <?php } ?>
        </div>
    </div>
    <div class="header-bottom" data-sticky="<?php echo esc_attr( $main_menu_sticky ); ?>">
        <div class="container">
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
                <!--start mobile nav-->
                <?php get_template_part( 'inc/header/mobile', 'menu' ); ?>
                <!--end mobile nav-->
            </div>
            <?php if( class_exists('Houzez_login_register') ): ?>
                <?php if( houzez_option('header_login') != 'no' || houzez_option('create_lisiting_enable') != 0 ): ?>
                    <div class="header-right">
                        <?php get_template_part('inc/header/login', 'nav'); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</header>

<?php get_template_part( 'inc/header/mobile-header' ); ?>
