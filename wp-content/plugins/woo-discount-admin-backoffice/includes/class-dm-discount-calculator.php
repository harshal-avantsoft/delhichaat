<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

class DM_Discount_Calculator {

  public function __construct()
  {
    add_action('woocommerce_admin_order_item_headers', array($this, 'admin_order_item_headers'), 15);
    add_action('woocommerce_admin_order_item_values', array($this, 'admin_order_item_values'), 15, 3);
    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
  }

  public function enqueue_scripts() {
    wp_enqueue_script('hellodev-discount-manager-js-per-line', plugins_url('js/hellodev-discount-manager-discount-per-line-min.js', DM_PLUGIN_FILE), array(), '1.1', false);
    wp_localize_script('hellodev-discount-manager-js-per-line', 'hellodev_discount_manager_locales', array(
      'apply_error_message' => __('Invalid discount amount.', 'hellodev-discount-manager-apply-discount')));
  }

  public function admin_order_item_headers()
  {
    ?>
    <th class="discount"><?php _e('Apply Discount'); ?></th>
    <?php
  }

  public function admin_order_item_values($product, $item, $item_id)
  {
    /* due to issue #3 */
    if(is_a($item, "WC_Order_Refund")) {
        return;
    }
    ?>
    <td class="discount-percentage-td" style="width: 120px">
      <input type="text" class="hellodev-discount-manager-apply-discount-percentage" name="hellodev-discount-manager-apply-discount-percentage" placeholder="<?php _e("Discount in percentage (%)", "hellodev-discount-manager-apply-discount") ?>" />
    </td>
    <?php
  }
}

new DM_Discount_Calculator();
