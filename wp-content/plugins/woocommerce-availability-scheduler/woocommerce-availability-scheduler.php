<?php
/*
Plugin Name: WooCommerce Availability Scheduler
Description: WAS helps you to schedule product availability.
Author: Lagudi Domenico
Version: 8.5
*/

//define('AS_PLUGIN_PATH', WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) ) );
define('AS_PLUGIN_PATH', rtrim(plugin_dir_url(__FILE__), "/") ) ;
define('AS_PLUGIN_ABS_PATH', dirname( __FILE__ ) );

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ||
     (is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option('active_sitewide_plugins') ))	
	)
{
	
	if(!class_exists('WAS_Remover'))
	{
			require_once('classes/frontend/WAS_Remover.php');
			$was_post_remover = new WAS_Remover();
	}  
	if(!class_exists('WAS_PostAddon'))
	{
			require_once('classes/admin/WAS_PostAddon.php');
			$was_post_addon = new WAS_PostAddon();
	} 
	if(!class_exists('WAS_OptionsMenu'))
	{
		require_once('classes/admin/WAS_OptionsMenu.php');
		$was_options_page = new WAS_OptionsMenu();
	}
	//com 
	require_once('classes/com/WAS_Globals.php');
	if(!class_exists('WAS_Customer'))
	{
			require_once('classes/com/WAS_Customer.php');
			$was_customer_model = new WAS_Customer();
	} 
	if(!class_exists('WAS_Product'))
	{
		require_once('classes/com/WAS_Product.php');
		$was_product_model = new WAS_Product();
	}
	if(!class_exists('WAS_Shortcodes'))
	{
		require_once('classes/com/WAS_Shortcodes.php');
		$was_shortcodes = new WAS_Shortcodes();
	}
	if(!class_exists('WAS_Wpml'))
	{
		require_once('classes/com/WAS_Wpml.php');
		$was_wpml_model = new WAS_Wpml();
	}
	if(!class_exists('WAS_Order'))
	{
		require_once('classes/com/WAS_Order.php');
		$was_order_model = new WAS_Order();
	}
	add_action('admin_menu', 'AS_init_admin_panel');
	add_action( 'admin_init', 'AS_register_settings');

} 


function AS_register_settings()
{ 
	register_setting('was_options_group', 'was_options');
	
} 

function AS_init_admin_panel()
{	 
	load_plugin_textdomain('woocommerce-availability-scheduler', false, basename( dirname( __FILE__ ) ) . '/languages' );
	
	$place = was_get_free_menu_position(53);

	add_menu_page( 'Availability scheduler', __('Availability scheduler', 'woocommerce-availability-scheduler'), 'manage_woocommerce', 'woocommerce-availability-scheduler', 'render_WAS_bulkscheduler_page',  'dashicons-backup' , (string)$place);
	add_submenu_page('woocommerce-availability-scheduler', __('Bulk availability scheduler','woocommerce-availability-scheduler'), __('Bulk availability scheduler','woocommerce-availability-scheduler'), 'manage_options', 'woocommerce-availability-scheduler-bulk-editor', 'render_WAS_bulkscheduler_page');
	add_submenu_page('woocommerce-availability-scheduler', __('Options','woocommerce-availability-scheduler'), __('Options','woocommerce-availability-scheduler'), 'manage_options', 'was-options-page', 'render_WAS_Option_page');

	remove_submenu_page( 'woocommerce-availability-scheduler', 'woocommerce-availability-scheduler');
}

function render_WAS_bulkscheduler_page()
{
	if(!class_exists('WAS_BulkScheduler'))
		require_once('classes/admin/WAS_BulkScheduler.php');
	$page = new WAS_BulkScheduler();
	$page->render_page();
}
function render_WAS_Option_page()
{
	global $was_options_page;
	$was_options_page->render_page();
}
function was_get_free_menu_position($start, $increment = 0.1)
{
	foreach ($GLOBALS['menu'] as $key => $menu) {
		$menus_positions[] = $key;
	}

	if (!in_array($start, $menus_positions)) return $start;

	/* the position is already reserved find the closet one */
	while (in_array($start, $menus_positions)) {
		$start += $increment;
	}
	return $start;
}
 function wcas_var_dump($var)
{
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}
?>