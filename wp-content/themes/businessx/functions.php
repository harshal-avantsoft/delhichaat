<?php
/**
 * Businessx Functions
 * ------
 * If you want to add/edit functions please use a child theme
 * http://codex.wordpress.org/Child_Themes
 * ------
 */



/* ------------------------------------ *
 *  Define some constants
/* ------------------------------------ */
if( ! defined( 'BUSINESSX_VERSION' ) ) {
	define( 'BUSINESSX_VERSION', '1.0.5.7' ); }

if( ! defined( 'BUSINESSX_AC_URL' ) ) {
	define( 'BUSINESSX_AC_URL', '//www.maahi.ca/' ); }

if( ! defined( 'BUSINESSX_AC_DOCS_URL' ) && defined( 'BUSINESSX_AC_URL' ) ) {
	define( 'BUSINESSX_AC_DOCS_URL', BUSINESSX_AC_URL . 'documentation/businessx/' ); }

if( ! defined( 'BUSINESSX_CUSTOMIZER_PATH' ) ) {
	define( 'BUSINESSX_CUSTOMIZER_PATH', trailingslashit( get_template_directory() ) . 'acosmin/customizer/' ); }

if( ! defined( 'BUSINESSX_FUNCTIONS_PATH' ) ) {
	define( 'BUSINESSX_FUNCTIONS_PATH', trailingslashit( get_template_directory() ) . 'acosmin/functions/' ); }

if( ! defined( 'BUSINESSX_PARTIALS_PATH' ) ) {
	define( 'BUSINESSX_PARTIALS_PATH', trailingslashit( get_template_directory() ) . 'partials/' ); }



/* ------------------------------------------------------------------------- *
 *  Required Files
/* ------------------------------------------------------------------------- */
require_once ( BUSINESSX_CUSTOMIZER_PATH . 'customizer.php' );
require_once ( BUSINESSX_FUNCTIONS_PATH . 'tgmpa.php' );
require_once ( BUSINESSX_FUNCTIONS_PATH . 'sanitization.php' );
require_once ( BUSINESSX_FUNCTIONS_PATH . 'helpers.php' );
require_once ( BUSINESSX_FUNCTIONS_PATH . 'preloader.php' );
require_once ( BUSINESSX_FUNCTIONS_PATH . 'post-options.php' );
require_once ( BUSINESSX_FUNCTIONS_PATH . 'page-options.php' );
require_once ( BUSINESSX_FUNCTIONS_PATH . 'product-options.php' );
require_once ( BUSINESSX_FUNCTIONS_PATH . 'portfolio-options.php' );
require_once ( BUSINESSX_PARTIALS_PATH . 'partial-template-css-classes.php' );
require_once ( BUSINESSX_PARTIALS_PATH . 'partial-template-functions.php' );
require_once ( BUSINESSX_PARTIALS_PATH . 'partial-template-helpers.php' );
require_once ( BUSINESSX_PARTIALS_PATH . 'partial-template-hooks.php' );



/*  Theme setup
/* ------------------------------------ */
if ( ! function_exists( 'businessx_setup' ) ) {
	function businessx_setup() {

		// Make Businessx available for translation.
		load_theme_textdomain( 'businessx', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 0, true );
		add_image_size( 'businessx-tmb-portfolio', 630, 415, true );
		add_image_size( 'businessx-tmb-blog-wide', 594 );
		add_image_size( 'businessx-tmb-blog-normal', 250, 250, true );

		// Register locations for your menus
		register_nav_menus( array(
			'primary' 	=> __( 'Primary Menu', 'businessx' ),
			'actions'	=> __( 'Actions Menu', 'businessx' ),
			'footer' 	=> __( 'Footer Menu', 'businessx' )
		) );

		// Switch default core markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Custom logo
		add_theme_support( 'custom-logo', apply_filters( 'businessx_custom_logo___options', array(
			'height'      => 100,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title' ),
		) ) );

		// Enable support for Custom_Headers
		add_theme_support( 'custom-header', apply_filters( 'businessx_custom_header___options', array(
			'width'         => 1900,
			'height'        => 800,
			'flex-height'   => true,
			'flex-width'    => true,
			'header-text' 	=> false
		)));

		// This theme uses its own gallery styles.
		add_filter( 'use_default_gallery_style', '__return_false' );

		// Globals
		global $businessx_sections;

		// Add front-page sections positions
		// This part is deprecated by Businessx Extensions v1.0.6, the plugin will handle this
		// Keeping it for backwards compatibility with older versions of Businessx Extensions
		$sections_position	= get_theme_mod( 'businessx_sections_position' );
		$businessx_sections	= apply_filters( 'businessx_sections_filter', array() );

		if( empty( $sections_position ) && ! empty( $businessx_sections ) ) {
			$sections = array();
			foreach( $businessx_sections as $key => $value ) {
				$sections[] = 'businessx_section__' . sanitize_key( $value );
			}
			set_theme_mod( 'businessx_sections_position', $sections );
		}

		// Widgets selective refresh
		add_theme_support( 'customize-selective-refresh-widgets' );

		// WooCommerce theme support
		add_theme_support( 'woocommerce' );

	}
}
add_action( 'after_setup_theme', 'businessx_setup' );



/*  Handles JavaScript detection.
/* ------------------------------------ */
if ( ! function_exists( 'businessx_javascript_detection' ) ) {
	function businessx_javascript_detection() {
		echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
	}
}
add_action( 'wp_head', 'businessx_javascript_detection', 0 );



/*  Set the content width in pixels
/* ------------------------------------ */
if ( ! function_exists( 'businessx_content_width' ) ) {
	function businessx_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'businessx_content_width', 840 );
	}
}
add_action( 'after_setup_theme', 'businessx_content_width', 0 );



/*  Enqueues scripts and styles.
/* ------------------------------------ */
if ( ! function_exists( 'businessx_scripts' ) ) {
	function businessx_scripts() {


        // Add the following two lines //
        wp_enqueue_style('bootstrap-cdn-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
		wp_enqueue_script('bootstrap-cdn-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
		wp_enqueue_style('bootstrap-social-cdn-css', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css');
        // ------               -------//
        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
        wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
        
		// Google Fonts
		wp_enqueue_style( 'businessx-fonts', businessx_fonts_setup(), array(), null );

		// Theme stylesheet
		wp_enqueue_style( 'businessx-style', get_stylesheet_uri(), array(), BUSINESSX_VERSION );

		// Font Awesome
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/icons/css/font-awesome.min.css', array(), '4.7.0', 'all' );

		// Javascript
		wp_enqueue_script( 'businessx-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), '20160412', true );

		// Masonry
		if( is_page_template( 'template-frontpage.php') || businessx_jetpack_check( 'custom-content-types' ) ) {
		wp_enqueue_script( 'jquery-masonry' ); };

		// Comments Script
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }

		// Some variables
		wp_localize_script(
			'businessx-scripts',
			'businessx_scripts_data',
			apply_filters( 'businessx_frontend_js_data_filter', array(
				/* Search form placeholder */
				'search_placeholder' => esc_attr_x( 'Type the keywords you are searching for', 'search overlay placeholder', 'businessx' ),
				'home_url'           => esc_url( home_url() ),
			) )
		);

	}
}
add_action( 'wp_enqueue_scripts', 'businessx_scripts' );



/*  Widgets and Sidebars Setup
/* ------------------------------------ */
if ( ! function_exists( 'businessx_sidebars_and_widgets' ) ) {
	function businessx_sidebars_and_widgets() {

		// Normal sidebars
		register_sidebar( array( // Index sidebar
			'name'          => __( 'Index Sidebar', 'businessx' ),
			'id'            => 'sidebar-index',
			'description'   => __( 'This sidebar appears where the index/arhives views are shown.', 'businessx' ),
			'before_widget' => '<aside id="%1$s" class="%2$s widget clearfix">',
			'after_widget'  => '</aside><!-- END .widget -->',
			'before_title'  => '<h3 class="widget-title hs-secondary-smallest ls-min"><span>',
			'after_title'   => '</span></h3>',
		) );

		register_sidebar( array( // Posts sidebar
			'name'          => __( 'Posts Sidebar', 'businessx' ),
			'id'            => 'sidebar-single',
			'description'   => __( 'This sidebar appears when you view a post', 'businessx' ),
			'before_widget' => '<aside id="%1$s" class="%2$s widget clearfix">',
			'after_widget'  => '</aside><!-- END .widget -->',
			'before_title'  => '<h3 class="widget-title hs-secondary-smallest ls-min"><span>',
			'after_title'   => '</span></h3>',
		) );

		register_sidebar( array( // Page sidebar
			'name'          => __( 'Page Sidebar', 'businessx' ),
			'id'            => 'sidebar-page',
			'description'   => __( 'This sidebar appears when you view a page', 'businessx' ),
			'before_widget' => '<aside id="%1$s" class="%2$s widget clearfix">',
			'after_widget'  => '</aside><!-- END .widget -->',
			'before_title'  => '<h3 class="widget-title hs-secondary-smallest ls-min"><span>',
			'after_title'   => '</span></h3>',
		) );

		if( businessx_jetpack_check( 'custom-content-types' ) ) {
		register_sidebar( array( // Portfolio sidebar
			'name'          => __( 'Portfolio Sidebar', 'businessx' ),
			'id'            => 'sidebar-portfolio',
			'description'   => __( 'This sidebar appears when you view a Jetpack Portfolio item', 'businessx' ),
			'before_widget' => '<aside id="%1$s" class="%2$s widget clearfix">',
			'after_widget'  => '</aside><!-- END .widget -->',
			'before_title'  => '<h3 class="widget-title hs-secondary-smallest ls-min"><span>',
			'after_title'   => '</span></h3>',
		) ); }

		// Footer sidebars
		register_sidebar( array( // Footer #1 sidebar
			'name'          => __( 'Footer #1 Sidebar', 'businessx' ),
			'id'            => 'sidebar-footer-1',
			'description'   => __( 'First footer sidebar', 'businessx' ),
			'before_widget' => '<aside id="%1$s" class="%2$s widget clearfix">',
			'after_widget'  => '</aside><!-- END .widget -->',
			'before_title'  => '<h3 class="widget-title hs-secondary-smallest ls-min"><span>',
			'after_title'   => '</span></h3>',
		) );

		register_sidebar( array( // Footer #2 sidebar
			'name'          => __( 'Footer #2 Sidebar', 'businessx' ),
			'id'            => 'sidebar-footer-2',
			'description'   => __( 'Second footer sidebar', 'businessx' ),
			'before_widget' => '<aside id="%1$s" class="%2$s widget clearfix">',
			'after_widget'  => '</aside><!-- END .widget -->',
			'before_title'  => '<h3 class="widget-title hs-secondary-smallest ls-min"><span>',
			'after_title'   => '</span></h3>',
		) );

		register_sidebar( array( // Footer #3 sidebar
			'name'          => __( 'Footer #3 Sidebar', 'businessx' ),
			'id'            => 'sidebar-footer-3',
			'description'   => __( 'Third footer sidebar', 'businessx' ),
			'before_widget' => '<aside id="%1$s" class="%2$s widget clearfix">',
			'after_widget'  => '</aside><!-- END .widget -->',
			'before_title'  => '<h3 class="widget-title hs-secondary-smallest ls-min"><span>',
			'after_title'   => '</span></h3>',
		) );

		if( businessx_wco_is_activated() ) {
			register_sidebar( array( // Shop sidebar
				'name'          => __( 'Shop Sidebar', 'businessx' ),
				'id'            => 'sidebar-shop',
				'description'   => __( 'Shop sidebar - index/archive view', 'businessx' ),
				'before_widget' => '<aside id="%1$s" class="%2$s widget clearfix">',
				'after_widget'  => '</aside><!-- END .widget -->',
				'before_title'  => '<h3 class="widget-title hs-secondary-smallest ls-min"><span>',
				'after_title'   => '</span></h3>',
			) );
		}

	}
}
add_action( 'widgets_init', 'businessx_sidebars_and_widgets', 20 );



/*  Google fonts
/* ------------------------------------ */
if ( ! function_exists( 'businessx_fonts_setup' ) ) {
	function businessx_fonts_setup() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = apply_filters( 'businessx_fonts___subsets', $subsets = 'latin,latin-ext' );

		// Default fonts
		$fonts[] = 'Poppins:400,700,300';
		$fonts[] = 'Roboto:400,300,700,900,300italic,400italic,700italic';

		$fonts = apply_filters( 'businessx_fonts___family', $fonts );

		if ( $fonts ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', array_map( 'esc_attr', $fonts ) ) ),
				'subset' => urlencode( esc_attr( $subsets ) ),
			), 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;

	}
}



/*  TGM - Recommended plugins
/* ------------------------------------ */
if ( ! function_exists( 'businessx_register_required_plugins' ) ) {
	function businessx_register_required_plugins() {

		$plugins = array(
			array(
				'name'      => 'Businessx Extensions',
				'slug'      => 'businessx-extensions',
				'required'  => false,
			),
			array(
				'name'      => 'Jetpack by WordPress.com',
				'slug'      => 'jetpack',
				'required'  => false,
			),
		);

		$config = array(
			'id'           => 'businessx',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		);

		tgmpa( $plugins, $config );
	}
}
add_action( 'tgmpa_register', 'businessx_register_required_plugins' );




/**
 * @snippet       Automatically Update Cart on Quantity Change - WooCommerce
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=73470
 * @author        Rodolfo Melogli
 * @compatible    Woo 3.4
 */
add_action( 'wp_footer', 'bbloomer_cart_refresh_update_qty' );
 
function bbloomer_cart_refresh_update_qty() { 
    if (is_cart()) { 
        ?> 
        <script type="text/javascript"> 
            jQuery('div.woocommerce').on('click', 'input.qty', function(){ 
                jQuery("[name='update_cart']").trigger("click"); 
            }); 
        </script> 
        <?php 
    } 
}



/**
 * @snippet       Hide Price & Add to Cart for Logged Out Users
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=299
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.3.4
 */
add_action( 'init', 'bbloomer_hide_price_add_cart_not_logged_in' ); 
function bbloomer_hide_price_add_cart_not_logged_in() { 
if ( !is_user_logged_in() ) {       
 remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
 remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
 remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
 remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );  
 add_action( 'woocommerce_single_product_summary', 'bbloomer_print_login_to_see', 31 );
 add_action( 'woocommerce_after_shop_loop_item', 'bbloomer_print_login_to_see', 11 );
}
} 
function bbloomer_print_login_to_see() {
echo '<a href="' . get_permalink(wc_get_page_id('myaccount')) . '">' . __('Login to see prices', 'theme_name') . '</a>';
}
// Allows for the display/non-display of products based off days allowed to be sold.
// This only works in conjunction with editing
// the woocommerce "content-product.php" template file
function check_for_product_allowed_days ( $product ) {

	$product_id  =  $product->id;
	$product_terms  =  get_the_terms ( $product_id, 'product_tag' );
	// remove the strtolower if you capitalized your tag names
	$current_day  =  strtolower ( date ( 'l' ) );

	// $all_days value should be the name of the tag
	// that you want to be able to be ordered on all days
	$all_days  =  'all days';
	foreach ( $product_terms as $tag ) {
		if ( $tag->name  ==  $current_day || $tag->name  ==  $all_days ) {
			$product_is_visible  =  true;
			break;
		}
		else {
			$product_is_visible  =  false;
		}
	}
	return $product_is_visible;
}

/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  $cols = 100;
  return $cols;
}

/**
 * Remove the password strength meter script from the scripts queue
 * you can also use wp_print_scripts hook
 */
add_action( 'wp_enqueue_scripts', 'misha_deactivate_pass_strength_meter', 10 );
function misha_deactivate_pass_strength_meter() {
 
	wp_dequeue_script( 'wc-password-strength-meter' );
 
}
/**
 * Change the placeholder image
 */
add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');

function custom_woocommerce_placeholder_img_src( $src ) {
	$upload_dir = wp_upload_dir();
	$uploads = untrailingslashit( $upload_dir['baseurl'] );
	// replace with path to your image
	$src = $uploads . '/Image_coming_soon.png';
	 
	return $src;
}
// show online only or POS / Online items in category page as well
add_action( 'woocommerce_product_query', 'hss_shop_query', 10 , 2);
function hss_shop_query( $q, $that )
{
// $q->set( 'author', $vendor_id );
    if ( ! is_admin()  ) {
        $meta_query = $q->get( 'meta_query' );

        if (!is_array($meta_query)){
            $meta_query = array();
        }
        $bHasPOSVisibility = false;
        $bHasOutOfStock = false;

        foreach ($meta_query as $mq) {
            if ($q->key == '_pos_visibility'){
                $bHasPOSVisibility = true;
            } else if ($q->key == '_stock_status'){
                $bHasOutOfStock = true;
            }
        }

        if (!$bHasPOSVisibility){
            $meta_query[] = array(
                'key'       => '_pos_visibility',
                'value'     => 'pos_only',
                'compare'   => '!='
                );
        }
        if (!$bHasOutOfStock){
            $meta_query[] = array(
                'key'       => '_stock_status',
                'value'     => 'outofstock',
                'compare'   => '!='
                );
        }
        $q->set( 'meta_query', $meta_query);
    }

//error_log("Query: ".var_export($q, true));
}


///TEMPORARY
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');