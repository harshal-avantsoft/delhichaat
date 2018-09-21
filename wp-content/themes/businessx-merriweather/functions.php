<?php
/**
 * Businessx Merriweather functions and definitions.
 *
 * @package Businessx_Mrriweather
 * @since 1.0.0
 */

define( 'BUSINESSX_MERRIWEATHER_VERSION', '1.0.0' );

/**
 * Load assets.
 *
 * @since 1.0.0
 */
function businessx_merriweather_enqueue() {
    $parent = 'businessx-style';
    $style  = '/style.css';

    // Parent style
    wp_enqueue_style( 
        $parent, 
        get_template_directory_uri() . $style,
        array(),
        BUSINESSX_VERSION
    );

    // Child style
    wp_enqueue_style( 
        'businessx-merriweather-style', 
        get_stylesheet_directory_uri() . $style, 
        array( $parent ), 
        BUSINESSX_MERRIWEATHER_VERSION 
    );
}
add_action( 'wp_enqueue_scripts', 'businessx_merriweather_enqueue' );
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
/**
 * Dequeue default Google Fonts
 *
 * @since  1.0.0
 * @return void
 */
function businessx_merriweather_dequeue_fonts() {
    wp_dequeue_style( 'businessx-fonts' );
}
add_action( 'wp_enqueue_scripts', 'businessx_merriweather_dequeue_fonts', 15 );

/**
 * Enqueue Google Fonts
 *
 * @since  1.0.0
 * @return void
 */
function businessx_merriweather_enqueue_fonts() {
    wp_enqueue_style( 
        'businessx-merriweather-fonts', 
        businessx_merriweather_fonts_setup(), 
        array(), 
        BUSINESSX_MERRIWEATHER_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'businessx_merriweather_enqueue_fonts', 5 );

/**
 * Google Fonts setup
 *
 * @since  1.0.0
 * @return string Google Fonts URL
 */
function businessx_merriweather_fonts_setup() {
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin,latin-ext';

    $fonts[] = 'Merriweather:400,700,300';
    $fonts[] = 'Open Sans:400,300,700,800,300italic,400italic,700italic';

    $fonts_args = apply_filters( 'businessx_merriweather_fonts_setup', array(
        'family' => urlencode( implode( '|', $fonts ) ),
        'subset' => urlencode( $subsets ),
    ), compact( 'fonts', 'subsets' ) );

    return add_query_arg( $fonts_args, 'https://fonts.googleapis.com/css' );
}