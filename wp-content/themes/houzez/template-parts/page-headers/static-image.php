<?php
global $post, $splash_welcome_text, $page_head_subtitle, $adv_search_which_header_show, $adv_search_over_header_pages, $adv_search_selected_pages;

$image_id = get_post_meta( $post->ID, 'fave_page_header_image', true );
$image_height = get_post_meta( $post->ID, 'fave_page_header_image_height', true );
$img_url = wp_get_attachment_image_src( $image_id, 'full' );
$splash_welcome_text = get_post_meta( $post->ID, 'fave_page_header_title', true );
$page_head_subtitle = get_post_meta( $post->ID, 'fave_page_header_subtitle', true );
$page_head_search = get_post_meta( $post->ID, 'fave_page_header_search', true );

$page_image_overlay = get_post_meta( $post->ID, 'fave_page_header_image_overlay', true );
$page_image_opacity = get_post_meta( $post->ID, 'fave_page_header_image_opacity', true );

$header_full_screen_type = 'screen_fix';

if( !is_404() && !is_search() ) {
    $header_type = get_post_meta($post->ID, 'fave_header_type', true);
    $fave_header_full_screen = get_post_meta($post->ID, 'fave_header_full_screen', true);
    $header_full_screen_type = get_post_meta($post->ID, 'fave_header_full_screen_type', true);
}

if( isset($_GET['fullscreen']) && $_GET['fullscreen'] == 'yes') {
    $fave_header_full_screen = 'yes';
}

if( ( $fave_header_full_screen == 'yes' && $header_full_screen_type == 'screen_fix' ) ) {
    $fave_header_full_screen = 'banner-parallax-fix';
    $image_height = '';
} else if( ( $fave_header_full_screen == 'yes' && $header_full_screen_type == 'auto_fix' ) ) {
    $fave_header_full_screen = 'banner-parallax-auto';
    $image_height = '';
} else {
    $fave_header_full_screen = '';
    if( !empty($image_height) ) {
        $image_height = 'height: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $image_height ) ? $image_height : $image_height . 'px' ) . ';';
    }
}

$style = '';

if( !empty( $img_url[0] ) ) {
    $background = 'background-image: url('.esc_url( $img_url[0] ).'); ';
    $style .= $background;
}
?>

<style type="text/css" scoped>
    .banner-inner:before {
        <?php if( $page_image_overlay != '' ) { ?>
        background-color: <?php echo esc_attr( $page_image_overlay ); ?>;
        <?php } ?>
        opacity: <?php echo esc_attr( $page_image_opacity ); ?>;
    }
</style>
<div class="header-media-wrap">
    <div class="header-media">
        <div class="banner-parallax <?php echo esc_attr($fave_header_full_screen); ?>" <?php if( !empty($image_height) ) { echo "style='$image_height'"; }; ?>>
            <div class="banner-bg-wrap">
                <div class="banner-inner" <?php if( !empty($style) ) { echo "style='$style'"; }; ?>></div>
            </div>
        </div>
        <div class="banner-caption">

            <?php if( $page_head_search != 'yes' ) { ?>
                <h1><?php echo esc_attr($splash_welcome_text); ?></h1>
                <h2><?php echo esc_attr($page_head_subtitle); ?></h2>
            <?php } ?>

            <?php
            if( $page_head_search != 'no' ) {
                get_template_part( 'template-parts/splash', 'search' );
            }?>

        </div>
    </div>
    <?php
    if( $adv_search_which_header_show['header_image'] != 0 ) {
        if( $adv_search_over_header_pages == 'only_home' ) {
            if (is_front_page()) {
                get_template_part('template-parts/advanced-search/desktop', 'type2');
            }
        } else if( $adv_search_over_header_pages == 'all_pages' ) {
            get_template_part('template-parts/advanced-search/desktop', 'type2');

        } else if ( $adv_search_over_header_pages == 'only_innerpages' ){
            if (!is_front_page()) {
                get_template_part('template-parts/advanced-search/desktop', 'type2');
            }
        } else if( $adv_search_over_header_pages == 'specific_pages' ) {
            if( is_page( $adv_search_selected_pages ) ) {
                get_template_part('template-parts/advanced-search/desktop', 'type2');
            }
        }
    }
    ?>
</div>
