<?php
/*
Plugin Name: Woo Discount Admin Backoffice
Version: 1.1
Plugin URI: http://hellodev.us
Description: This plugin allows administrators to apply discounts on orders from the administration panel.
Author: HelloDev <hi@hellodev.us>
Text Domain: hellodev-discount-manager-apply-discount
Author URI: http://hellodev.us
License GPLv2
*/


if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

/**
*  Main Plugin class
*/
class WC_Discount_Manager
{

  // Singleton design pattern
  protected static $instance = NULL;

  // Method to return the singleton instance
  public static function get_instance() {

    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;

  }

  public function __construct()
  {
    add_action('plugins_loaded', array($this, 'init'));
  }

  public function init()
  {
    $this->includes();
    $this->define( 'DM_PLUGIN_FILE', __FILE__ );
    $this->define( 'DM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
  }

  /**
  * Define constant if not already set
  * @param  string $name
  * @param  string|bool $value
  */
  private function define( $name, $value ) {
    if ( ! defined( $name ) ) {
      define( $name, $value );
    }
  }

  // Includes of our plugin
  public function includes() {
    include_once( 'includes/class-dm-discount-calculator.php' );
    include_once( 'includes/class-dm-apply-order-discount.php' );
  }
}

WC_Discount_Manager::get_instance();
