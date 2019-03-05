<?php
/**
 * Theme Stylesheet Options
 * Refer to Theme Options
 * @package Houzez
 * @since   Houzez 1.0
**/

function houzez_custom_styling() {
    global $post;

    $pageID = '';
    if( !is_404() && !is_search() && !is_author() ) {
        $pageID = $post->ID;
    }

    $splash_overlay_img = houzez_option( 'splash_overlay_img', false, 'url' );
    $splash_overlay_opacity = houzez_option( 'splash_overlay_opacity' );
    if( empty( $splash_overlay_opacity ) ) { $splash_overlay_opacity = '0.5'; }

    $body_bg_color = houzez_option('body_bg_color');

    $houzez_primary_color =  houzez_option('houzez_primary_color');
    $banner_text_color =  houzez_option('banner_text_color');
    $houzez_primary_color_hover =  houzez_option('houzez_primary_color_hover', false, 'rgba');

    $houzez_secondary_color =  houzez_option('houzez_secondary_color');
    $houzez_secondary_color_hover =  houzez_option('houzez_secondary_color_hover', false, 'rgba');

    $houzez_prop_details_bg =  houzez_option('houzez_prop_details_bg', false, 'rgba');

    $featured_label_bg_color =  houzez_option('featured_label_bg_color');
    $featured_label_color =  houzez_option('featured_label_color');

    $header_3_callus_color = houzez_option('header_3_callus_color');
    $header_3_callus_bg_color = houzez_option('header_3_callus_bg_color');

    $body_typo = houzez_option('typo-body');
    $typo_menu = houzez_option('typo-menu');
    $typo_headings = houzez_option('typo-headings');

    /*$typo_menu['color']};
    $typo_menu['font-family']
    $typo_menu['font-size']
    $typo_menu['font-weight']
    $typo_menu['line-height']
    $typo_menu['text-transform']*/

    $logo_desktop_dimensions = houzez_option('logo_desktop_dimensions');
    $desktop_logo_width = isset($logo_desktop_dimensions['width']) ? $logo_desktop_dimensions['width'] : '';
    $desktop_logo_height = isset($logo_desktop_dimensions['height']) ? $logo_desktop_dimensions['height'] : '';
    //$desktop_logo_units = $logo_desktop_dimensions['units'];  // px, em, %

    $logo_mobile_dimensions = houzez_option('logo_mobile_dimensions');
    $mobile_logo_width = isset( $logo_mobile_dimensions['width'] ) ? $logo_mobile_dimensions['width'] : '';
    $mobile_logo_height = isset( $logo_mobile_dimensions['height'] ) ? $logo_mobile_dimensions['height'] : '';
    //$mobile_logo_units = $logo_mobile_dimensions['units'];  // px, em, %

    $logo_desktop = $logo_mobile = '';
    /*
     * logo dimentions
     * ----------------------------------*/
    if( !empty($desktop_logo_width) && !empty($desktop_logo_height) && $desktop_logo_width != 'px') {
        $logo_desktop = "
        .logo img {
            width: {$desktop_logo_width};
            height: {$desktop_logo_height};
        }";
    }

    if( !empty($mobile_logo_width) && !empty($mobile_logo_height)  && $mobile_logo_width != 'px' ) {
        $logo_mobile = "
        .header-mobile .header-logo img {
            width: {$mobile_logo_width};
            max-height: {$mobile_logo_height};
        }";
    }

    /* Advanced Search
    -----------------------------------------------*/
    $adv_background = houzez_option('adv_background');
    $adv_btn_bg_regular = houzez_option('adv_search_btn_bg', false, 'regular');
    $adv_btn_bg_hover = houzez_option('adv_search_btn_bg', false, 'hover');
    $adv_btn_bg_active = houzez_option('adv_search_btn_bg', false, 'active');

    $adv_btn_color_regular = houzez_option('adv_search_btn_text', false, 'regular');
    $adv_btn_color_hover = houzez_option('adv_search_btn_text', false, 'hover');
    $adv_btn_color_active = houzez_option('adv_search_btn_text', false, 'active');

    $adv_button_color_regular = houzez_option('adv_button_color', false, 'regular');
    $adv_button_color_hover = houzez_option('adv_button_color', false, 'hover');
    $adv_button_color_active = houzez_option('adv_button_color', false, 'active');

    $adv_btn_border_regular = houzez_option('adv_search_border', false, 'regular');
    $adv_btn_border_hover = houzez_option('adv_search_border', false, 'hover');
    $adv_btn_border_active = houzez_option('adv_search_border', false, 'active');
    $adv_form_fields_border = houzez_option('adv_textfields_borders');
    $adv_text_color = houzez_option('adv_text_color');
    $adv_overlay_open_close_bg_color = houzez_option('adv_overlay_open_close_bg_color');
    $adv_overlay_open_close_color = houzez_option('adv_overlay_open_close_color');

    $advanced_search = "
        .advance-search-header, 
        .advanced-search-mobile, 
        .advanced-search-mobile .single-search .form-control,
        .search-expandable .advanced-search {
            background-color: {$adv_background};
        }
        .search-expand-btn {
            background-color: {$adv_overlay_open_close_bg_color};
        }
        .search-expand-btn {
            color: {$adv_overlay_open_close_color}
        }
        .advance-search-header .houzez-theme-button,
        .advanced-search-mobile .houzez-theme-button,
        .splash-search .btn-secondary,
        .advanced-search .btn-secondary,
        .gform_button, 
        .gform_wrapper .button, 
        .gform_wrapper .gform_page_footer .button.gform_next_button {
            color: {$adv_btn_color_regular};
            background-color: {$adv_btn_bg_regular};
            border: 1px solid {$adv_btn_border_regular};
        }
        .advance-search-header .houzez-theme-button:focus,
        .advanced-search-mobile .houzez-theme-button:focus,
        .advanced-search .btn-secondary:focus,
        .splash-search .btn-secondary:focus,
        .gform_wrapper .button:focus, 
        .gform_wrapper .gform_page_footer .button.gform_next_button:focus {
          color: {$adv_btn_color_hover};
          background-color: {$adv_btn_bg_hover};
          border: 1px solid {$adv_btn_border_hover};
        }
        .advance-search-header .houzez-theme-button:hover,
        .advanced-search .btn-secondary:hover,
        .advanced-search .btn-secondary:active,
        .advanced-search .btn-secondary:active:hover,
        .advanced-search .btn-secondary.active,
        .advanced-search-mobile .houzez-theme-button:hover,
        .splash-search .btn-secondary:hover,
        .gform_wrapper .button:hover, 
        .gform_wrapper .gform_page_footer .button.gform_next_button:hover,
        .gform_wrapper .button:active, 
        .gform_wrapper .gform_page_footer .button.gform_next_button:active {
          color: {$adv_btn_color_hover};
          background-color: {$adv_btn_bg_hover};
          border: 1px solid {$adv_btn_border_hover};
        }
        .advance-search-header .houzez-theme-button:active,
        .advanced-search .btn-secondary:active,
        .advanced-search-mobile .houzez-theme-button:active,
        .splash-search .btn-secondary:active {
          color: {$adv_btn_color_active};
          background-color: {$adv_btn_bg_active};
          border: 1px solid {$adv_btn_border_active};
        }
        .advanced-search .bootstrap-select .btn,
        .advanced-search .bootstrap-select.open .btn,
        .banner-search-main .dropdown-toggle,
        .advanced-search .form-control,
        .advanced-search-mobile .advance-fields .bootstrap-select .btn,
        .advanced-search-mobile .advance-fields .bootstrap-select.open .btn,
        .advanced-search-mobile .advance-fields .form-control {
            background-color: #fff;
            color: #959595;
        }
        .advanced-search .dropdown-toggle,
        .advanced-search .form-control,
        .advance-search-header .bootstrap-select .btn,
        .advance-search-header .bootstrap-select .fave-load-more a,
        .fave-load-more .advance-search-header .bootstrap-select a,
        .advance-fields .form-control,
        .advanced-search .btn-default.active:hover, .btn-default:active:hover, 
        .advanced-search .open >.btn-default.dropdown-toggle:hover
        .advanced-search .bootstrap-select .btn,
        .advanced-search .bootstrap-select.open .btn,
        .range-wrap {
            border-width: 1px;
            border-style: solid;
            border-color: {$adv_form_fields_border};
        }

        .advanced-search .input-group .form-control,        
        .search-long .search input,
        .advanced-search .search-long .search,
        .advanced-search .search-long .btn-group,
        .advanced-search .search-long .advance-btn,
        .input-group-addon {
            border-color: {$adv_form_fields_border} !important;
        }
        .advanced-search-mobile .advance-fields {
            border-top: 1px solid {$adv_form_fields_border};
        }
        .advanced-search-mobile .single-search-wrap button {
            color: {$adv_form_fields_border};
        }

        .advanced-search-mobile .advance-fields::after {
            border-bottom-color: {$adv_form_fields_border};
        }
        .advance-btn.blue {
            color: {$adv_btn_color_regular};
        }
        .advance-btn.blue:hover,
        .advance-btn.blue:focus {
            color: {$adv_btn_bg_hover};
        }
        .advanced-search .advance-btn {
            color: {$adv_button_color_regular};
        }
        .advanced-search .advance-btn:hover {
            color:{$adv_button_color_hover};
        }
        .advanced-search .advance-btn:focus,
        .advanced-search .advance-btn.active {
            color:{$adv_button_color_active};
        }
        .advanced-search .advance-fields,
        .advanced-search .features-list label.title,
        .advanced-search .features-list .checkbox-inline,
        .advanced-search-mobile .advance-fields,
        .advanced-search-mobile .features-list label.title,
        .advanced-search-mobile .features-list .checkbox-inline,
        .range-title,
        .range-text,
        .range-text p,
        .min-price-range,
        .max-price-range,
        .advanced-search-mobile {
            color: {$adv_text_color};
        }

        .auto-complete {
            background-color: #fff;
        }
        .auto-complete,
        .auto-complete .media {
            border-color: #ccc;
        }
        .bootstrap-select.btn-group .dropdown-menu li a:hover, 
        .bootstrap-select.btn-group .dropdown-menu li a:focus, 
        .bootstrap-select.btn-group .dropdown-menu li a:active {
            background-color: rgba(0, 174, 239, 0.12);
        }

            
        ";

    /*-----------------------------------------------------------------------------------
    * Top Bar
    ------------------------------------------------------------------------------------*/
    $top_bar_background_color = houzez_option('top_bar_bg');
    $top_bar_color = houzez_option('top_bar_color');
    $topbar_menu_btn_color = houzez_option('topbar_menu_btn_color');
    $top_bar_color_hover = houzez_option('top_bar_color_hover', false, 'rgba');
    $top_bar = "
        .top-bar {
            background-color: {$top_bar_background_color};
        }
        .top-bar .top-nav > ul > li > a:hover,
        .top-bar .top-nav > ul li.active > a,
        .top-bar .top-nav > ul ul a:hover,
        .top-contact a:hover,
        .top-bar .dropdown-menu > li:hover,
        .top-contact li .btn:hover {
            color: {$top_bar_color_hover};
        }
        .top-contact a,
        .top-contact li,
        .top-contact li .btn,
        .top-bar .top-nav > ul > li > a,
        .top-bar .dropdown-menu {
            color: {$top_bar_color};
            background-color: {$top_bar_background_color};
        }
        .top-bar .mobile-nav .nav-trigger {
            color: {$topbar_menu_btn_color};
        }
        ";

    /*-----------------------------------------------------------------------------------
    * Headers
    ------------------------------------------------------------------------------------*/
    $typo_headers = houzez_option('typo-headers');
    $header_style = houzez_option('header_style');
    $header_login = houzez_option('header_login');
    $create_lisiting_enable = houzez_option('create_lisiting_enable');

    $header_1_bg = houzez_option('header_1_bg');
    $header_1_links_color =  houzez_option('header_1_links_color');
    $header_1_links_hover_color =  houzez_option('header_1_links_hover_color', false, 'rgba');
    $header_1_links_hover_bg_color =  houzez_option('header_1_links_hover_bg_color', false, 'rgba');

    $header_6_bg = houzez_option('header_6_bg');
    $header_6_links_color =  houzez_option('header_6_links_color');
    $header_6_social_color =  houzez_option('header_6_social_color');
    $header_6_links_hover_color =  houzez_option('header_6_links_hover_color', false, 'rgba');
    $header_6_links_hover_bg_color =  houzez_option('header_6_links_hover_bg_color', false, 'rgba');

    $header_2_top_bg = houzez_option('header_2_top_bg');
    $header_2_top_text =  houzez_option('header_2_top_text');
    $header_2_top_icon =  houzez_option('header_2_top_icon');
    $header_2_bg = houzez_option('header_2_bg');
    $header_2_links_color =  houzez_option('header_2_links_color');
    $header_2_links_hover_color =  houzez_option('header_2_links_hover_color', false, 'rgba');
    $header_2_links_hover_bg_color =  houzez_option('header_2_links_hover_bg_color', false, 'rgba');
    $header_2_border = houzez_option('header_2_border', false, 'rgba');

    $header_3_bg = houzez_option('header_3_bg');
    $header_3_bg_menu = houzez_option('header_3_bg_menu');
    $header_3_links_color =  houzez_option('header_3_links_color');
    $header_3_links_hover_color =  houzez_option('header_3_links_hover_color', false, 'rgba');
    $header_3_links_hover_bg_color =  houzez_option('header_3_links_hover_bg_color', false, 'rgba');
    $header_3_border =  houzez_option('header_3_border');
    $header_3_social_color =  houzez_option('header_3_social_color');

    $header_123_btn_color =  houzez_option('header_123_btn_color');
    $header_123_btn_hover_color =  houzez_option('header_123_btn_hover_color', false, 'rgba');
    $header_123_btn_bg_color =  houzez_option('header_123_btn_bg_color', false, 'rgba');
    $header_123_btn_bg_hover_color =  houzez_option('header_123_btn_bg_hover_color', false, 'rgba');
    $header_123_btn_border =  houzez_option('header_123_btn_border');
    $header_123_btn_border_hover_color =  houzez_option('header_123_btn_border_hover_color');

    $header_4_bg = houzez_option('header_4_bg');
    $header_4_links_color =  houzez_option('header_4_links_color');
    $header_4_links_hover_color =  houzez_option('header_4_links_hover_color', false, 'rgba');
    $header_4_transparent_links_color =  houzez_option('header_4_transparent_links_color');
    $header_4_transparent_links_hover_color =  houzez_option('header_4_transparent_links_hover_color');

    $header_4_btn_color =  houzez_option('header_4_btn_color');
    $header_4_btn_hover_color =  houzez_option('header_4_btn_hover_color', false, 'rgba');
    $header_4_btn_bg_color =  houzez_option('header_4_btn_bg_color');
    $header_4_btn_bg_hover_color =  houzez_option('header_4_btn_bg_hover_color', false, 'rgba');
    $header_4_btn_border =  houzez_option('header_4_btn_border');
    $header_4_btn_border_hover_color =  houzez_option('header_4_btn_border_hover_color', false, 'rgba');

    $header_4_transparent_btn_color =  houzez_option('header_4_transparent_btn_color');
    $header_4_transparent_btn_hover_color =  houzez_option('header_4_transparent_btn_hover_color', false, 'rgba');
    $header_4_transparent_btn_bg_color =  houzez_option('header_4_transparent_btn_bg_color', false, 'rgba');
    $header_4_transparent_btn_bg_hover_color =  houzez_option('header_4_transparent_btn_bg_hover_color', false, 'rgba');
    $header_4_transparent_btn_border =  houzez_option('header_4_transparent_btn_border');
    $header_4_transparent_btn_border_hover_color =  houzez_option('header_4_transparent_btn_border_hover_color', false, 'rgba');
    $header_4_transparent_border_bottom =  houzez_option('header_4_transparent_border_bottom1');
    $header_4_transparent_border_bottom_color =  houzez_option('header_4_transparent_border_bottom_color', false, 'rgba');

    $splash_menu_align = houzez_option('splash_menu_align');

    $headers_1_and_4_width = '';
    if( ( $header_style == '1' || $header_style == '4' ) && ( $header_login != 'yes' && $create_lisiting_enable != 1 ) ) {
        $headers_1_and_4_width = "
        #header-section .header-left {
            width: 100%;
        }
        .header-section-4.nav-right .header-left {
            padding-right: 0px;
        }";
    }

    $headers_splash_width = '';
    if( $splash_menu_align == 'nav-right' && ( $header_login != 'yes' && $create_lisiting_enable != 1 ) ) {
        $headers_splash_width = "
        #header-section .header-left {
            width: 100%;
        }";
    }

    /*
     * Header 1
     *-----------------------------------*/
    $header_style_1 = "
        .header-section {
            background-color: {$header_1_bg};
        }
        .header-section .navi > ul > li > a {
            color: {$header_1_links_color};
            background-color: transparent;
        }
        .header-section .header-right .user a,
        .header-section .header-right span {
            color: {$header_1_links_color};
        }";

    if( !empty($header_1_links_hover_color) ) {
        $header_style_1 .= "
            .header-section .navi > ul > li > a:hover {
                color: {$header_1_links_hover_color};
                background-color: {$header_1_links_hover_bg_color};
            }
            .header-section .header-right .user a:hover,
            .header-section .header-right span:hover {
                color: {$header_1_links_hover_color};
            }";
    }

    /*
     * Header 6
     *-----------------------------------*/
    $header_style_6 = "
        .header-section-6 {
            background-color: {$header_6_bg};
        }
        .header-section-6 .navi > ul > li > a {
            color: {$header_6_links_color};
            background-color: transparent;
        }
        .header-section-6 .header-right .user a,
        .header-section-6 .header-right span {
            color: {$header_6_links_color};
        }
        .header-section-6-icons .header-top-social li a {
            color: {$header_6_social_color};
        }
        ";

    if( !empty($header_6_links_hover_color) ) {
        $header_style_6 .= "
            .header-section-6 .navi > ul > li > a:hover {
                color: {$header_6_links_hover_color};
                background-color: {$header_6_links_hover_bg_color};
            }
            .header-section-6 .header-right .user a:hover,
            .header-section-6 .header-right span:hover {
                color: {$header_6_links_hover_color};
            }";
    }

    /*
     * Header 2
     *-----------------------------------*/
    $header_style_2 = "
        .header-section-3 .header-top {
            background-color: {$header_2_top_bg};
        }
        .header-section-3 .header-top-social a,
        .header-section-3 .header-contact .contact-block .fa,
        .header-section-3 .header-top .media-heading,
        .header-contact .contact-block p{
            color: {$header_2_top_text};
        }
        .header-contact .contact-block .fa {
            color: {$header_2_top_icon};
        }
        .header-section-3 .header-bottom {
            background-color: {$header_2_bg};
        }
        .header-section-3 .navi > ul > li > a,
        .header-section-3 .header-right .user a,
        .header-section-3 .header-right span {
            color: {$header_2_links_color};
        }";

    if( !empty($header_2_links_hover_color) ) {
        $header_style_2 .= "
            .header-section-3 .navi > ul > li > a:hover,
            .header-section-3 .navi > ul > li.active > a{
                color: {$header_2_links_hover_color};
                background-color: {$header_2_links_hover_bg_color};
            }
            .header-section-3 .header-right .user a:hover,
            .header-section-3 .header-right span:hover {
                color: {$header_2_links_hover_color};
            }";
    }
    if( !empty($header_2_border) ) {
        $header_style_2 .= "
            .header-section-3 .navi > ul > li {
                border-right: 1px solid {$header_2_border};
            }
            .header-section-3 .header-bottom {
                border-top: 1px solid {$header_2_border};
            }
            .header-section-3 .navi > ul {
                border-left: 1px solid {$header_2_border};
            }";
    }

    /*
     * Header 3 and 5
     *-----------------------------------*/
    $header_style_3 = "
        .header-section-2 .header-top, .header-section-5 .header-top {
            background-color: {$header_3_bg};
        }
        .header-section-2 .header-bottom, .header-section-5 .header-bottom {
            background-color: {$header_3_bg_menu};
            border-top: {$header_3_border['border-top']} {$header_3_border['border-style']} {$header_3_border['border-color']};
            border-bottom: {$header_3_border['border-bottom']} {$header_3_border['border-style']} {$header_3_border['border-color']};
        }
        .header-section-2 .header-bottom .navi > ul > li, .header-section-5 .header-bottom .navi > ul > li {
            border-right: {$header_3_border['border-right']} {$header_3_border['border-style']} {$header_3_border['border-color']};
        }
        .header-section-2 .header-right {
            border-left: {$header_3_border['border-left']} {$header_3_border['border-style']} {$header_3_border['border-color']};
        }
        .header-section-2 .navi > ul > li > a,
        .header-section-2 .header-right .user a,
         .header-section-2 .header-right span,
         .header-section-5 .navi > ul > li > a,
        .header-section-5 .header-right .user a,
         .header-section-5 .header-right span {
            color: {$header_3_links_color};
        }
        .header-section-5 .header-top-social li a, header-section-3 .header-top-social li a {
            color: {$header_3_social_color};
        }
        ";

    if( !empty($header_3_links_hover_color) ) {
        $header_style_3 .= "
            .header-section-2 .navi > ul > li > a:hover,
             .header-section-2 .navi > ul > li.active > a,
             .header-section-5 .navi > ul > li > a:hover,
             .header-section-5 .navi > ul > li.active > a {
                color: {$header_3_links_hover_color};
                background-color: {$header_3_links_hover_bg_color};
            }
            .header-section-2 .header-right .user a:hover,
             .header-section-2 .header-right span:hover,
             .header-section-5 .header-right .user a:hover,
             .header-section-5 .header-right span:hover {
                color: {$header_3_links_hover_color};
            }";
    }

    /* Header 12356
    *-----------------------------------*/
    $header_123_listing_button = "
        .header-section .header-right a.btn,
        .header-section-2 .header-right a.btn,
        .header-section-3 .header-right a.btn,
        .header-section-5 .header-right a.btn,
        .header-section-6 .header-right a.btn {
            color: {$header_123_btn_color};
            border: {$header_123_btn_border['border-left']} {$header_123_btn_border['border-style']} {$header_123_btn_border['border-color']};
            background-color: {$header_123_btn_bg_color};
        }
        .header-section .header-right .user a.btn:hover,
        .header-section-2 .header-right .user a.btn:hover,
        .header-section-3 .header-right .user a.btn:hover,
        .header-section-5 .header-right .user a.btn:hover,
        .header-section-6 .header-right .user a.btn:hover {
            color: {$header_123_btn_hover_color};
            border-color: {$header_123_btn_border_hover_color};
            background-color: {$header_123_btn_bg_hover_color};
        }
    ";

    /* Header 4
    *-----------------------------------*/
    $header_style_4 = "
        .header-section-4,
        .header-section-4 .navi > ul ul,
        .sticky_nav.header-section-4 {
            background-color: {$header_4_bg};
        }
        .header-section-4 .navi > ul > li > a,
        .header-section-4 .navi > ul ul a,
        .header-section-4 .header-right .user a,
        .header-section-4 .header-right span {
            color: {$header_4_links_color};
        }
        .header-section-4 .header-right a.btn {
            color: {$header_4_btn_color};
            border: {$header_4_btn_border['border-left']} {$header_4_btn_border['border-style']} {$header_4_btn_border['border-color']};
            background-color: {$header_4_btn_bg_color};
        }";

    if( !empty($header_4_links_hover_color) ) {
        $header_style_4 .= "
            .header-section-4 .navi > ul > li > a:hover,
            .header-section-4 .navi > ul ul a:hover,
            .header-section-4 .navi > ul > li.active > a,
            .header-section-4 .header-right .user a:hover,
            .header-section-4 .header-right .user a:focus,
            .header-section-4 .header-right span:hover,
            .header-section-4 .header-right span:focus {
                color: {$header_4_links_hover_color};
            }";
    }

    if( !empty($header_4_btn_hover_color) ) {
        $header_style_4 .= "
            .header-section-4 .header-right .user .btn:hover {
                color: {$header_4_btn_hover_color};
                border-color: {$header_4_btn_border_hover_color};
                background-color: {$header_4_btn_bg_hover_color};
            }";
    }

    $header_style_4_transparent = "
      .houzez-header-transparent {
       background-color: transparent; position: absolute; width: 100%;
       border-bottom: {$header_4_transparent_border_bottom['border-bottom']} {$header_4_transparent_border_bottom['border-style']};
       border-color: {$header_4_transparent_border_bottom_color};
      }
      .header-section-4.houzez-header-transparent .navi > ul > li > a,

      .header-section-4.houzez-header-transparent .header-right .account-action span,
      .header-section-4.houzez-header-transparent .header-right .user span {
         color: {$header_4_transparent_links_color};
      }
    .header-section-4.houzez-header-transparent .navi > ul > li > a:hover,
        .header-section-4.houzez-header-transparent .navi > ul ul a:hover,
        .header-section-4.houzez-header-transparent .account-action li:hover,

        .header-section-4.houzez-header-transparent .header-right .user a:hover,
        .header-section-4.houzez-header-transparent .header-right .account-action span:hover,
        .header-section-4.houzez-header-transparent .header-right .user span:hover,
        .header-section-4.houzez-header-transparent .header-right .user a:focus {
            color: {$header_4_transparent_links_hover_color};
        }
    .header-section-4.houzez-header-transparent .header-right .btn {
        color: {$header_4_transparent_btn_color};
        border: {$header_4_transparent_btn_border['border-left']} {$header_4_transparent_btn_border['border-style']} {$header_4_transparent_btn_border['border-color']};
        background-color: {$header_4_transparent_btn_bg_color};
    }";

    if( !empty($header_4_transparent_btn_hover_color) ) {
        $header_style_4_transparent .= "
            .header-section-4.houzez-header-transparent .header-right .user .btn:hover {
                color: {$header_4_transparent_btn_hover_color};
                border-color: {$header_4_transparent_btn_border_hover_color};
                background-color: {$header_4_transparent_btn_bg_hover_color};
            }";
    }
    /*
     * Header 5
     *-----------------------------------*/
    /*$header_style_5 = "
        .header-section-5 .header-top {
            background-color: {$header_3_bg};
        }
        .header-section-5 .header-bottom {
            background-color: {$header_3_bg_menu};
            border-top: {$header_3_border['border-top']} {$header_3_border['border-style']} {$header_3_border['border-color']};
            border-bottom: {$header_3_border['border-bottom']} {$header_3_border['border-style']} {$header_3_border['border-color']};
        }
        .header-section-5 .header-bottom .navi > ul > li {
            border-right: {$header_3_border['border-right']} {$header_3_border['border-style']} {$header_3_border['border-color']};
        }
        .header-section-5 .header-right {
            border-left: {$header_3_border['border-left']} {$header_3_border['border-style']} {$header_3_border['border-color']};
        }
        .header-section-5 .navi > ul > li > a,
        .header-section-5 .header-right .user a,
         .header-section-5 .header-right span {
            color: {$header_3_links_color};
        }";

    if( !empty($header_3_links_hover_color) ) {
        $header_style_5 .= "
            .header-section-5 .navi > ul > li > a:hover,
             .header-section-5 .navi > ul > li.active > a{
                color: {$header_3_links_hover_color};
                background-color: {$header_3_links_hover_bg_color};
            }
            .header-section-5 .header-right .user a:hover,
             .header-section-5 .header-right span:hover {
                color: {$header_3_links_hover_color};
            }";
    }*/

    /*
     * Headers Typography
     * -----------------------------------------*/
    $headers_typography = "
        .header-section .header-right a,
        .header-section .header-right span,
        .header-section .header-right .btn-default,
        .header-section .navi ul li,
        .header-section .account-dropdown > ul > li > a,

        .header-section-3 .header-right a,
        .header-section-3 .header-right span,
        .header-section-3 .navi ul li,
        .header-section-3 .account-dropdown > ul > li > a,

        .header-section-2 .header-right a,
        .header-section-2 .header-right span,
        .header-section-2 .navi ul li,
        .header-section-2 .account-dropdown > ul > li > a,

        .header-section-4 .header-right a,
        .header-section-4 .header-right span,
        .header-section-4 .navi ul li,
        .header-section-4 .header-right .btn-default,
        .header-section-4 .account-dropdown > ul > li > a,

        .header-section-5 .header-right a,
        .header-section-5 .header-right span,
        .header-section-5 .navi ul li,
        .header-section-5 .account-dropdown > ul > li > a,

        .header-section-6 .header-right a,
        .header-section-6 .header-right span,
        .header-section-6 .navi ul li,
        .header-section-6 .account-dropdown > ul > li > a,

        .board-panel-menu > li a {
            font-family: {$typo_headers['font-family']};
            font-size: {$typo_headers['font-size']};
            font-weight: {$typo_headers['font-weight']};
            line-height: {$typo_headers['line-height']};
            text-transform: {$typo_headers['text-transform']};
            text-align: {$typo_headers['text-align']};
        }";

    /*
     * Splash Page Header
     * ----------------------------------*/
    $splash_page_header = "
        .header-section.slpash-header .navi > ul > li > a:hover,
        .slpash-header.header-section-4 .navi > ul > li > a:hover,
        .header-section.slpash-header .header-right .user > a:hover,
        .slpash-header.header-section-4 .header-right .user > a:hover,
        .header-section.slpash-header .navi > ul > li > a:focus,
        .slpash-header.header-section-4 .navi > ul > li > a:focus,
        .header-section.slpash-header .header-right .user > a:focus,
        .slpash-header.header-section-4 .header-right .user > a:focus  {
            color: rgba(255,255,255,1);
        }
        .header-section.slpash-header .navi > ul > li.active > a{
            color: {$houzez_primary_color};
        }";


    /*
     * Header Submenu
     * ----------------------------------*/
    $header_submenu_bg = houzez_option('header_submenu_bg', false, 'rgba');
    $header_submenu_links_color = houzez_option('header_submenu_links_color');
    $header_submenu_links_hover_color = houzez_option('header_submenu_links_hover_color');
    $header_submenu_border_color = houzez_option('header_submenu_border_color');

    $header_submenu_colors = "
        .navi.main-nav > ul ul {
            background-color: {$header_submenu_bg};
        }
        .navi.main-nav > ul ul a {
            color: {$header_submenu_links_color}!important;
        }
        .navi.main-nav > ul ul a:hover {
            color: {$header_submenu_links_hover_color}!important;
        }
        .navi.main-nav > ul ul li,
        .houzez-megamenu-inner .sub-menu li {
            border-color: {$header_submenu_border_color};
        }
     ";

    /*
     * Header user account submenu
     * ----------------------------------*/
    $ua_menu_bg = houzez_option('ua_menu_bg');
    $ua_menu_links_color = houzez_option('ua_menu_links_color');
    $ua_menu_links_hover_color = houzez_option('ua_menu_links_hover_color');
    $ua_menu_links_hover_bg_color = houzez_option('ua_menu_links_hover_bg_color', false, 'rgba');
    $ua_menu_border_color = houzez_option('ua_menu_border_color');

    $ua_submenu_bg = houzez_option('ua_submenu_bg');
    $ua_submenu_links_color = houzez_option('ua_submenu_links_color');
    $ua_submenu_links_hover_color = houzez_option('ua_submenu_links_hover_color', false, 'rgba');
    $ua_submenu_border_color = houzez_option('ua_submenu_border_color', false, 'rgba');

    $header_user_account_submenu = "
        .account-dropdown > ul {
            background-color: {$ua_menu_bg};
        }
        .account-dropdown > ul:before {
            border-bottom-color: {$ua_menu_bg};
        }
        .account-dropdown > ul > li > a {
            color: {$ua_menu_links_color} !important;
        }
        .account-dropdown > ul > li > a:hover, .account-dropdown > ul > li.active > a, .account-dropdown > ul > li.active > a:hover {
            color: {$ua_menu_links_hover_color} !important;
            background-color: {$ua_menu_links_hover_bg_color};
        }
        .account-dropdown > ul > li {
            border-color: {$ua_menu_border_color};
        }
        .account-dropdown > ul .sub-menu {
            background-color: {$ua_submenu_bg};
        }
        .account-dropdown > ul .sub-menu > li,
        .account-dropdown > ul .sub-menu > li a {
            color: {$ua_submenu_links_color};
        }
        .account-dropdown > ul .sub-menu > li a:hover, .account-dropdown > ul .sub-menu > li.active > a {
            background-color: {$ua_submenu_links_hover_color} !important;
            color: inherit;
        }
        .account-dropdown > ul .sub-menu > li {
            border-color: {$ua_submenu_border_color} !important;
        }
        ";


    /*-----------------------------------------------------------------------------------
    * All Mobile Navs
    ------------------------------------------------------------------------------------*/
    $mob_menu_bg_color = houzez_option('mob_menu_bg_color');
    $mob_link_color = houzez_option('mob_link_color');
    $mob_link_hover_color = houzez_option('mob_link_hover_color', false, 'rgba');
    $mob_link_hover_bg_color = houzez_option('mob_link_hover_bg_color', false, 'rgba');
    $mob_submenu_bg_color = houzez_option('mob_submenu_bg_color', false, 'rgba');
    $mob_dropdown_link_color = houzez_option('mob_dropdown_link_color');
    $mob_dropdown_links_bg_color = houzez_option('mob_dropdown_links_bg_color');
    $mobile_nav_border = houzez_option('mobile_nav_border');
    $typo_mobile_menu = houzez_option('typo-mobile-menu');
    $mob_menu_btn_color = houzez_option('mob_menu_btn_color');
    $mob_menu_btn_color_splash = houzez_option('mob_menu_btn_color_splash');

    $all_mobile_navs = "
        .header-mobile {
            background-color: {$mob_menu_bg_color};
        }
        .header-mobile .nav-dropdown > ul {
            background-color: {$mob_submenu_bg_color};
        }
        .mobile-nav .nav-trigger,
        .header-mobile .user a,
        .header-mobile .user-icon {
            color: {$mob_menu_btn_color};
        }
        .splash-header .mobile-nav .nav-trigger,
        .splash-header .header-mobile .user a,
        .splash-header .header-mobile .user-icon {
            color: {$mob_menu_btn_color_splash};
        }
        .nav-dropdown a,
        .nav-dropdown li .expand-me {
            color: {$mob_link_color};
        }
        .mobile-nav a,
        .header-mobile .account-dropdown > ul > li a {
            font-family: {$typo_mobile_menu['font-family']};
            font-size: {$typo_mobile_menu['font-size']};
            font-weight: {$typo_mobile_menu['font-weight']};
            line-height: {$typo_mobile_menu['line-height']};
            text-transform: {$typo_mobile_menu['text-transform']};
            text-align: {$typo_mobile_menu['text-align']};
        }
        .mobile-nav .nav-dropdown > ul ul a {
            color: {$mob_dropdown_link_color};
            background-color: {$mob_dropdown_links_bg_color};
        }
        .mobile-nav .nav-dropdown li {
            border-top: {$mobile_nav_border['border-top']} {$mobile_nav_border['border-style']} {$mobile_nav_border['border-color']};            
        }";

    if( !empty($mob_link_hover_bg_color) ) {
        $all_mobile_navs .= "
            .mobile-nav .nav-dropdown > ul > li:hover {
                background-color: {$mob_link_hover_bg_color};
            }";
    }

    if( !empty($mob_link_hover_color) ) {
        $all_mobile_navs .= "
            .mobile-nav .nav-dropdown li.active > a {
                color: {$mob_link_hover_color};
                background-color: {$mob_link_hover_bg_color};
            }";
    }

    /*-----------------------------------------------------------------------------------
    * All Colors
    ------------------------------------------------------------------------------------*/
    $houzez_colors = "
        body {
            background-color: {$body_bg_color};
        }
        a,        
        .blue,
        .text-primary,
        .btn-link,
        .item-body h2,
        .detail h3,
        .breadcrumb li a,
        .fave-load-more a,
        .sort-tab .btn,
        .sort-tab .fave-load-more a,
        .fave-load-more .sort-tab a,
        .pagination-main .pagination a,
        .team-caption-after .team-name a:hover,
        .team-caption-after .team-designation a:hover,
        .agent-media .view,
        .my-property-menu a.active,
        .my-property-menu a:hover,
        .search-panel .advance-trigger {        
            color: {$houzez_primary_color};
        }
        .banner-caption h1,
        .banner-caption h2  {
            color: {$banner_text_color};
        }
        .property-item h2 a,
        .property-item .property-title a,
        .widget .media-heading a {
            color: {$body_typo['color']};
        }
        .property-item h2 a:hover,
        .property-item .property-title a:hover,
        .widget .media-heading a:hover {
            color: {$houzez_primary_color};
        }
        .owl-theme .owl-nav [class*=owl-],        
        .testimonial-carousel .owl-nav [class*=owl-]:hover,
        .testimonial-carousel .owl-nav [class*=owl-]:focus,
        .gallery-thumb .icon,
        .share_tooltip {
            background-color: {$houzez_primary_color};
        }
        #sidebar .widget_tag_cloud .tagcloud a,
        .article-footer .meta-tags a,
        .pagination-main .pagination li.active a,
        .other-features .btn.btn-secondary,
        .my-menu .active a,        
        .houzez-module .module-title-nav .module-nav .btn,
        .houzez-module .module-title-nav .module-nav .fave-load-more a,
        .fave-load-more .houzez-module .module-title-nav .module-nav a {
            color: #fff;
            background-color: {$houzez_primary_color};
            border: 1px solid {$houzez_primary_color};
        }
        .plan-tabs li.active {
            box-shadow: inset 0 4px 0 {$houzez_primary_color};
            border-top-color: {$houzez_primary_color};
            background-color: #fff;
            color: {$houzez_primary_color};
        }
        .btn-primary,        
        .label-primary,
        .scrolltop-btn {
            color: #fff;
            background-color: {$houzez_primary_color};
            border-color: {$houzez_primary_color};
        }
        .btn-primary.btn-trans{
            color: {$houzez_primary_color};
        }
        .header-section-2 .header-top-call {
            color: {$header_3_callus_color};
            background-color: {$header_3_callus_bg_color};
        }
        .header-section-2 .avatar {
            color: {$header_3_callus_color};
        }
        @media (max-width: 991px) {
            .header-section-2 .header-top,
            .header-section-5 .header-top {
                background-color: {$houzez_primary_color};
            }
        }
        .modal-header,
        .ui-slider-horizontal .ui-slider-range,
        .ui-state-hover,
        .ui-widget-content .ui-state-hover,
        .ui-widget-header .ui-state-hover,
        .ui-state-focus,
        .ui-widget-content .ui-state-focus,
        .ui-widget-header .ui-state-focus,
        .list-loading-bar {
            background-color: {$houzez_primary_color};
            border-color: transparent;
        }
        .houzez-module .module-title-nav .module-nav .btn,
        #save_as_draft,
        #put_on_hold {
            color: {$houzez_primary_color};
            border: 1px solid {$houzez_primary_color};
            background-color: transparent;
        }
        .fave-load-more a,
        .fave-load-more a:hover {
            border: 1px solid {$houzez_primary_color};
        }
        #transportation,
        #supermarkets,
        #schools,
        #libraries,
        #pharmacies,
        #hospitals,
        .pay-step-block.active span,
        .loader-ripple div:nth-of-type(2){
            border-color: {$houzez_primary_color};
        }
        .loader-ripple div:nth-of-type(1){
            border-color: {$houzez_secondary_color};
        }
        .detail-block .alert-info {
            color: rgba(0,0,0,.85);
            background-color: {$houzez_prop_details_bg};
            border: 1px solid {$houzez_primary_color};
        }
        .houzez-taber-wrap .houzez-tabs li.active::before,
        .houzez-taber-wrap .houzez-tabs li:hover::before,
        .houzez-taber-wrap .houzez-tabs li:active::before,
        .profile-tabs li:hover,
        .steps-nav, .steps-progress-main .steps-progress span {
            background-color: {$houzez_primary_color};
        }
        .btn-secondary,
        .agent_contact_form.btn-secondary,
         .form-media .wpcf7-submit,
         .wpcf7-submit,
         .dsidx-resp-area-submit input[type='submit']{
            color: #fff;
            background-color: {$houzez_secondary_color};
            border-color: {$houzez_secondary_color};
        }
        .btn-secondary.btn-trans{
            color: {$houzez_secondary_color};
        }
        .item-thumb .label-featured, figure .label-featured, .carousel-module .carousel .item figure .label-featured {
            background-color: {$featured_label_bg_color};
            color: {$featured_label_color};
        }
        ";


    if( !empty($houzez_primary_color_hover) ) {
        $houzez_colors .= "
            a:hover,
            a:focus,
            a:active,
            .blue:hover,
            .btn-link:hover,
            .breadcrumb li a:hover,
            .pagination-main .pagination a:hover,
            .vc_toggle_title h4:hover ,
            .footer a:hover,
            .impress-address:hover,
            .agent-media .view:hover,
            .my-property .dropdown-menu a:hover,
            .article-detail .article-title a:hover,
            .comments-block .article-title a:hover,
            .detail-block .list-features a:hover{
                color: {$houzez_primary_color_hover};
                text-decoration: none;
            }
            
            .detail-top .media-tabs a:hover span,
            .header-section.slpash-header .header-right a.btn:hover,
            .slpash-header.header-section-4 .header-right a.btn:hover,
            .houzez-module .module-title-nav .module-nav .btn:hover,
            .houzez-module .module-title-nav .module-nav .fave-load-more a:hover,
            .fave-load-more .houzez-module .module-title-nav .module-nav a:hover,
            .houzez-module .module-title-nav .module-nav .btn:focus,
            .houzez-module .module-title-nav .module-nav .fave-load-more a:focus,
            .fave-load-more .houzez-module .module-title-nav .module-nav a:focus{
                color: #fff;
                background-color: {$houzez_primary_color_hover};
                border: 1px solid {$houzez_primary_color_hover};
            }
            .fave-load-more a:hover,
            #sidebar .widget_tag_cloud .tagcloud a:hover,
            .article-footer .meta-tags a:hover,
            .other-features .btn.btn-secondary:hover,
            .my-actions .action-btn:hover,
            .my-actions .action-btn:focus,
            .my-actions .action-btn:active,
            .my-actions .open .action-btn{
                background-color: {$houzez_primary_color_hover};
                border-color: {$houzez_primary_color_hover};
            }
            .owl-theme .owl-nav [class*=owl-]:hover,
            .owl-theme .owl-nav [class*=owl-]:focus,
            .owl-theme .owl-nav [class*=owl-]:active,
            .testimonial-carousel .owl-nav [class*=owl-]:hover,
            .testimonial-carousel .owl-nav [class*=owl-]:focus{
                border-color: {$houzez_primary_color_hover};
            }
            .owl-theme .owl-nav [class*=owl-]:hover,
            .owl-theme .owl-nav [class*=owl-]:focus,
            .owl-theme .owl-nav [class*=owl-]:active {
                background-color: {$houzez_primary_color_hover};
            }
            .btn-primary:hover,
            .btn-primary:focus,
            .btn-primary:active,
            .btn-primary.active,
            .btn-primary:active:hover,
            .btn-primary.btn-trans:hover,
            .btn-primary.btn-trans:focus,
            .btn-primary.btn-trans:active,
            .btn-primary.btn-trans.active,
            .btn-primary.btn-trans:active:hover,
            .invoice-list .btn-invoice:hover,
            #houzez-gmap-main .map-btn:hover,
            .media-tabs-list li > a:hover,
            .media-tabs-list li.active a,
            .detail-bar .detail-tabs li:hover,
            .actions li > span:hover,
            .lightbox-arrow:hover,
            .scrolltop-btn:hover {
                background-color: {$houzez_primary_color_hover};
                border-color: {$houzez_primary_color_hover};
            }";
    }

    if( !empty($houzez_secondary_color_hover) ) {
        $houzez_colors .= "
            .btn-secondary:hover,
            .btn-secondary:focus,
            .btn-secondary:active,
            .btn-secondary.active,
            .btn-secondary:active:hover,
            .btn-secondary.btn-trans:hover,
            .btn-secondary.btn-trans:focus,
            .btn-secondary.btn-trans:active,
            .btn-secondary.btn-trans.active,
            .btn-secondary.btn-trans:active:hover,
            .agent_contact_form.btn-secondary:hover,
             .form-media .wpcf7-submit:hover,
             .wpcf7-submit:hover,
             .wpcf7-submit:focus,
             .wpcf7-submit:active,
             .dsidx-resp-area-submit input[type='submit']:hover,
             .dsidx-resp-area-submit input[type='submit']:focus,
             .dsidx-resp-area-submit input[type='submit']:active{
                color: #fff;
                background-color: {$houzez_secondary_color_hover};
                border-color: {$houzez_secondary_color_hover};
            }";
    }

    /*-----------------------------------------------------------------------------------
    * Footer colors
    ------------------------------------------------------------------------------------*/
    $footer_bg_color = houzez_option('footer_bg_color');
    $footer_bottom_bg_color = houzez_option('footer_bottom_bg_color');
    $footer_color = houzez_option('footer_color');
    $footer_bottom_border = houzez_option('footer_bottom_border');
    $footer_hover_color = houzez_option('footer_hover_color', false, 'rgba');

    $houzez_footer_colors = "
        .footer {
            background-color: {$footer_bg_color};
        }
        .footer-bottom {
            background-color: {$footer_bottom_bg_color};
            border-top: {$footer_bottom_border['border-top']} {$footer_bottom_border['border-style']} {$footer_bottom_border['border-color']};
        }
        .footer,
        .footer-widget h4,
        .footer-bottom p,
        .footer-widget.widget_calendar caption  {
            color: {$footer_color};
        }
        .footer a,
        .footer-bottom .navi a,
        .footer-bottom .foot-social p a {
            color: {$footer_color};
        }
        .footer-widget .widget-title,
        .footer p, .footer p.wp-caption-text,
         .footer li,
          .footer li i {
            color: {$footer_color};
        }";

    if( !empty($footer_hover_color) ) {
        $houzez_footer_colors .= "
            .footer a:hover,
            .footer-bottom .navi a:hover,
            .footer-bottom .foot-social p a:hover  {
                color: {$footer_hover_color};
            }
            .footer-widget.widget_tag_cloud .tagcloud a {
                color: {$footer_hover_color};
                background-color: {$footer_color};
                border: 1px solid {$footer_color};
            }";
    }

    /*-----------------------------------------------------------------------------------
    * Typography
    ------------------------------------------------------------------------------------*/
    $houzez_typography = "
        body {
            color: {$body_typo['color']};
            font-family: {$body_typo['font-family']};
            font-size: {$body_typo['font-size']};
            font-weight: {$body_typo['font-weight']};
            line-height: {$body_typo['line-height']};
            text-transform: {$body_typo['text-transform']};
        }
        input, button, select, textarea {
            font-family: {$body_typo['font-family']};
        }
        h1,
        .page-title .title-head,
        .article-detail h1,
        h2,
        .article-detail h2,
        .houzez-module .module-title-nav h2,
        h3,
        .module-title h3,
        .article-detail h3,
        .detail h3,
        .caption-bottom .detail h3,

        .add-title-tab h3,
        #sidebar .widget-title,
        .footer-widget .widget-title,
        .services-module .service-block h3,
        h4,
        .article-detail h4,
        h5,
        .article-detail h5,
        h6,
        .article-detail h6,
        .item-body h2,
        .item-body .property-title,
        .post-card-description h3,
        .post-card-description .post-card-title,
        .my-property .my-heading,
        .module-title h2,
        .houzez-module .module-title-nav h2 {
            font-family: {$typo_headings['font-family']};
            font-weight: {$typo_headings['font-weight']};
            text-transform: {$typo_headings['text-transform']};
            text-align: {$typo_headings['text-align']};
        }
        .page-title .title-head,
        .article-detail h1 {
            font-size: 30px;
            line-height: 38px;
        }
        .article-detail h2,
        .houzez-module .module-title-nav h2 {
            font-size: 24px;
            line-height: 32px;
            margin: 0 0 10px 0;
        }
        .houzez-module .module-title-nav h2 {
            margin: 0;
        }
        .module-title h3,
        .article-detail h3,
        .services-module .service-block h3 {
            font-size: 20px;
            line-height: 28px;
        }
        .article-detail h4 {
            font-size: 18px;
            line-height: 26px;
            margin: 0 0 24px 0;
        }
        h5,
        .article-detail h5 {
            font-size: 16px;
            line-height: 24px;
            margin: 0 0 24px 0;
        }
        h6,
        .article-detail h6 {
            font-size: 14px;
            line-height: 20px;
            margin: 0 0 24px 0;
        }
        .item-body h2,
        .post-card-description h3,
        .my-property .my-heading {
            font-size: 16px;
            line-height: 20px;
            margin: 0 0 8px 0;
            font-weight: 500;
            text-transform: inherit;
            text-align: inherit;
        }
        .module-title h2 {
            font-size: 24px;
            line-height: 32px;
            margin: 0 0 10px 0;
            font-weight: 500;
            text-transform: inherit;
            text-align: inherit;
        }
        .module-title .sub-heading {
            font-size: 16px;
            line-height: 24px;
            font-weight: 300;
            text-transform: inherit;
            text-align: inherit;
        }
        .houzez-module .module-title-nav .sub-title {
            font-size: 16px;
            line-height: 18px;
            margin: 8px 0 0 0;
            font-weight: 300;
            text-transform: inherit;
            text-align: inherit;
        }
        .item-thumb .hover-effect:before,
        figure .hover-effect:before,
        .carousel-module .carousel .item figure .hover-effect:before,
        .item-thumb .slideshow .slideshow-nav-main .slick-slide:before,
        .slideshow .slideshow-nav-main .item-thumb .slick-slide:before,
        figure .slideshow .slideshow-nav-main .slick-slide:before,
        .slideshow .slideshow-nav-main figure .slick-slide:before {
        background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 65%, rgba(0,0,0,.75) 100%);
        }
        .slideshow .slide .slick-prev:hover,
        .slideshow .slideshow-nav .slick-prev:hover,
        .slideshow .slide .slick-next:hover,
        .slideshow .slideshow-nav .slick-next:hover,
        .slideshow .slide .slick-prev:focus,
        .slideshow .slideshow-nav .slick-prev:focus,
        .slideshow .slide .slick-next:focus,
        .slideshow .slideshow-nav .slick-next:focus
        .item-thumb:hover .hover-effect:before,
        figure:hover .hover-effect:before,
        .carousel-module .carousel .item figure:hover .hover-effect:before,
        .item-thumb:hover .slideshow .slideshow-nav-main .slick-slide:before,
        .slideshow .slideshow-nav-main .item-thumb:hover .slick-slide:before,
        figure:hover .slideshow .slideshow-nav-main .slick-slide:before,
        .slideshow .slideshow-nav-main figure:hover .slick-slide:before,
        .item-thumb:hover .hover-effect:before,
        figure:hover .hover-effect:before,
        .carousel-module .carousel .item figure:hover .hover-effect:before,
        .item-thumb:hover .slideshow .slideshow-nav-main .slick-slide:before,
        .slideshow .slideshow-nav-main .item-thumb:hover .slick-slide:before,
        figure:hover .slideshow .slideshow-nav-main .slick-slide:before,
        .slideshow .slideshow-nav-main figure:hover .slick-slide:before {
            color: #fff;
            background-color: rgba(255,255,255,.5);
        }
        .figure-grid .detail h3,
        .detail-above.detail h3 {
            color: #fff;
        }
        .detail-bottom.detail h3 {
            color: #000;
        }
        .agent-contact a {
            font-weight: 700;
        }
        .label-status {
            background-color: #333;
            font-weight: 700;
        }
        .read .fa {
            top: 1px;
            position: relative;
        }            
        .label-primary,
        .fave-load-more a,
        .widget_tag_cloud .tagcloud a,
        .pagination-main .pagination li.active a,
        .other-features .btn.btn-secondary,
        .my-menu .active am {
            font-weight: 500;
        }       
        .header-detail.table-cell .header-right {
            margin-top: 27px;
        }
        .header-detail h1 .actions span, .header-detail h4 .actions span {
            font-size: 18px;
            display: inline-block;
            vertical-align: middle;
            margin: 0 3px;
        }        
        .header-detail .property-address {
            color: #707070;
            margin-top: 12px;
        }        
        .white-block {
            padding: 40px;
        }
        .wpb_text_column ul,
        .wpb_text_column ol {
            margin-top: 20px;
            margin-bottom: 20px;
            padding-left: 20px;
        }
        #sidebar .widget_houzez_latest_posts img {
            max-width: 90px;
            margin-top: 0;
        }
        #sidebar .widget_houzez_latest_posts .media-heading,
        #sidebar .widget_houzez_latest_posts .read {
            font-size: 14px;
            line-height: 18px;
            font-weight: {$typo_headings['font-weight']};
        }        
        #sidebar .widget-range .dropdown-toggle,
        .bootstrap-select.btn-group,        
        .search-long .search input,
        .advanced-search .search-long .advance-btn,        
        .splash-search .dropdown-toggle {
            font-weight: 400;
            color: #959595 !important;
            font-size: 15px;
        }

        .advanced-search .input-group .form-control {
            border-left-width: 0;
        }        
        .location-select {
            max-width: 170px;
        }             
        ";
    /*-----------------------------------------------------------------------------------
    * iHomeFinder
    ------------------------------------------------------------------------------------*/
    $houzez_ihomefinder = " 
        #ihf-main-container .btn-primary,
        #ihf-main-container .dropdown-menu>.active>a, 
        #ihf-main-container .dropdown-menu>.active>a:focus {
            background-color: {$houzez_primary_color};
            border-color: {$houzez_primary_color};
            color: #fff !important;
        } 
        #ihf-main-container .btn-primary:hover, 
        #ihf-main-container .btn-primary:focus, 
        #ihf-main-container .btn-primary:active, 
        #ihf-main-container .btn-primary.active,
        #ihf-main-container .dropdown-menu>.active>a:hover,
        #ihf-main-container .dropdown-menu>li>a:focus, 
        #ihf-main-container .dropdown-menu>li>a:hover {
            background-color: {$houzez_primary_color_hover};
            border-color: {$houzez_primary_color_hover};
        }
        #ihf-main-container .ihf-map-search-refine-link,
        #ihf-main-container .ihf-map-search-refine-link,
        #ihf-main-container .ihf-results-links a {
            background-color: {$houzez_secondary_color} !important;
            border-color: {$houzez_secondary_color} !important;
            color: #fff !important;
        }
        #ihf-main-container #saveSearchButton {
            background-color: transparent;
            border: none;
            color: {$houzez_primary_color} !important;
        }
        #ihf-main-container a,
        #ihf-main-container .btn-link {
            color: {$houzez_primary_color};       
        }
        #ihf-main-container a:hover,
        #ihf-main-container a:active,
        #ihf-main-container .btn-link:hover,
        #ihf-main-container .btn-link:active {
            color: {$houzez_primary_color_hover};
        }
        .ihf-grid-result-photocount a {       
            border: 1px solid {$houzez_primary_color};   
        }
        .ihf-grid-result-basic-info-container,
        #ihf-main-container {
            color: {$body_typo['color']};
            font-family: {$body_typo['font-family']};
            font-size: {$body_typo['font-size']};
            font-weight: {$body_typo['font-weight']};
            line-height: {$body_typo['line-height']};
            text-transform: {$body_typo['text-transform']};
        }
        #ihf-main-container .fs-12,
        .ihf-tab-pane,
        #ihf-agent-sellers-rep,
        #ihf-board-detail-disclaimer,
        #ihf-board-detail-updatetext  {
            font-size: {$body_typo['font-size']};
        }
        #ihf-main-container .title-bar-1,
        .ihf-map-icon{
            background-color: {$houzez_primary_color};
        }
        .ihf-map-icon{
            border-color: {$houzez_primary_color};
        }
        .ihf-map-icon:after{
            border-top-color: {$houzez_primary_color};
        }
        #ihf-main-container h1, 
        #ihf-main-container h2, 
        #ihf-main-container h3, 
        #ihf-main-container h4, 
        #ihf-main-container h5, 
        #ihf-main-container h6, 
        #ihf-main-container .h1, 
        #ihf-main-container .h2, 
        #ihf-main-container .h3, 
        #ihf-main-container .h4, 
        #ihf-main-container .h5, 
        #ihf-main-container .h6,
        #ihf-main-container h4.ihf-address,
        #ihf-main-container h4.ihf-price  {
            font-family: {$typo_headings['font-family']};
            font-weight: {$typo_headings['font-weight']};
            text-transform: {$typo_headings['text-transform']};
            text-align: {$typo_headings['text-align']};
        }
    ";

    $dm_background = houzez_option('dm_background');
    $dm_color = houzez_option('dm_color');
    $dm_hover_color = houzez_option('dm_hover_color');
    $dm_submenu_bg_color = houzez_option('dm_submenu_bg_color');

    $dasboard_menu = "
        .user-dashboard-left,
        .board-header {
            background-color:{$dm_background};
        }
        .board-panel-menu > li a,
        .board-header .board-title,
        .board-header .breadcrumb > .active,
        .board-header .breadcrumb li:after,
        .board-header .steps-progress-main { 
            color:{$dm_color}; 
         }
        .board-panel-menu > li.active {
            color: {$dm_hover_color};
        }
        .board-panel-menu .sub-menu {
            background-color: {$dm_submenu_bg_color};
        }
        .board-panel-menu .sub-menu > li.active > a, .board-panel-menu > li a:hover {
            color: {$dm_hover_color};
        }
    ";

    $splash_page = '';
    if( !empty( $splash_overlay_img  ) ) {
        $splash_page = "
            .splash-inner-media .vegas-overlay {
               opacity: {$splash_overlay_opacity};
               background: url({$splash_overlay_img});
           }";
    }

    //Overlays
    $general_options = '';
    $images_overlay = houzez_option('images_overlay');
    $featured_image_overlay = houzez_option('featured_image_overlay');

    if( $featured_image_overlay != 0 ) {
        $general_options .= "
            .detail-top-full #gallery:before {
              background-image: none !important;
            }
        ";
    }
    if( $images_overlay != 0 ) {
        $general_options .= "
            .item-thumb .hover-effect:after, figure .hover-effect:after, .carousel-module .carousel .item figure .hover-effect:after, .item-thumb .slideshow .slideshow-nav-main .slick-slide:after, .slideshow .slideshow-nav-main .item-thumb .slick-slide:after, figure .slideshow .slideshow-nav-main .slick-slide:after, .slideshow .slideshow-nav-main figure .slick-slide:after{
                background-image: none !important;
            }
        ";
    }

    // Property status color
    if( taxonomy_exists('property_status') ) {

        $prop_status = get_terms( 'property_status' );
        $prop_status_label = '';

        if( $prop_status ) {
            foreach( $prop_status as $term ) {

                $houzez_term_id = $term->term_id;
                $meta = get_option( '_houzez_property_status_'.$houzez_term_id );

                if ( $meta['color_type'] == 'custom' ) {

                    $prop_status_label .= "
                    .label-status-{$houzez_term_id} {
                        background-color: {$meta['color']};
                    }
                    ";

                }
            }
        }

    }

    // Marker color based on type
    if( taxonomy_exists('property_type') ) {

        $marker_type = get_terms( 'property_type' );
        $marker_type_color = '';

        if( $marker_type ) {
            foreach( $marker_type as $term ) {

                $houzez_term_id = $term->term_id;
                $meta = get_option( '_houzez_property_type_'.$houzez_term_id );

                if ( $meta['color_type'] == 'custom' ) {

                    $marker_type_color .= "
                    .gm-marker-color-{$houzez_term_id} {
                        background-color: {$meta['color']};
                    }
                    ";
                    $marker_type_color .="
                    .gm-marker-color-{$houzez_term_id}:after {
                        border-top-color: {$meta['color']};
                    }
                    ";

                }
            }
        }

    }

    // Property label color
    if( taxonomy_exists('property_label') ) {

        $taxonomy_label = get_terms( 'property_label' );
        $prop_label = '';

        if( $taxonomy_label ) {
            foreach( $taxonomy_label as $term ) {

                $houzez_term_id = $term->term_id;
                $meta = get_option( '_houzez_property_label_'.$houzez_term_id );

                if ( $meta['color_type'] == 'custom' ) {

                    $prop_label .= "
                    .label-color-{$houzez_term_id} {
                        background-color: {$meta['color']};
                    }
                    ";

                }
            }
        }

    }


    $houzez_custom_css = houzez_option('custom_css');

    wp_add_inline_style( 'houzez-style',
        $advanced_search.
        $headers_1_and_4_width.
        $headers_splash_width.
        $top_bar.
        $houzez_colors.
        $header_style_1.
        $header_style_2.
        $header_style_3.
        $header_123_listing_button.
        $header_style_4.
        $header_style_4_transparent.
        $header_style_6.
        $header_submenu_colors.
        $headers_typography.
        $splash_page_header.
        $all_mobile_navs.
        $header_user_account_submenu.
        $houzez_footer_colors.
        $houzez_typography.
        $splash_page.
        $prop_status_label.
        $prop_label.
        $marker_type_color.
        $general_options.
        $logo_desktop.
        $logo_mobile.
        $dasboard_menu.
        $houzez_ihomefinder.
        $houzez_custom_css
    );

}
add_action( 'wp_enqueue_scripts', 'houzez_custom_styling', 21 );
?>