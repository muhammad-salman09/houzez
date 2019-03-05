<?php
global $current_user;
wp_get_current_user();
$userID  =  $current_user->ID;
$user_custom_picture =  get_the_author_meta( 'fave_author_custom_picture' , $userID );
$header_layout = houzez_option('header_1_width');
if( empty($header_layout) ) { $header_layout = 'container'; }

$main_menu_sticky = houzez_option('main-menu-sticky');
$header_1_menu_align = houzez_option('header_1_menu_align');
$top_bar = houzez_option('top_bar');

if( $top_bar != 0 ) {
	get_template_part('inc/header/top', 'bar');
}
$menu_righ_no_user = '';
$create_lisiting_enable = houzez_option('create_lisiting_enable');
$header_login = houzez_option('header_login');
if( $header_1_menu_align == 'nav-right' && $header_login != 'yes' && $create_lisiting_enable != 1 ) {
	$menu_righ_no_user = 'menu-right-no-user';
}
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
if( houzez_is_dashboard() ) {
	$header_layout = 'container-fluid';
}
?>

<!--start section header-->
<header id="header-section" data-sticky="<?php echo esc_attr( $main_menu_sticky ); ?>" class="houzez-header-main header-section header-section-1 <?php echo esc_attr( $header_1_menu_align ).' '.esc_attr($menu_righ_no_user).' '.esc_attr($houzez_user_logout); ?>">
	<div class="<?php echo esc_attr( $header_layout ); ?>">
		<div class="header-left">

			<div class="logo logo-desktop">
				<?php get_template_part('inc/header/logo'); ?>
			</div>


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
		<?php if( class_exists('Houzez_login_register') ): ?>
			<?php if( $header_login != 'no' || $create_lisiting_enable != 0 ): ?>
				<div class="header-right">
					<?php get_template_part('inc/header/login', 'nav'); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</header>
<!--end section header-->

<?php get_template_part( 'inc/header/mobile-header' ); ?>
