<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

class DM_Apply_Order_Discount {

  public function __construct()
  {
    $this->init();
  }

  public function init() {
    add_action('add_meta_boxes', array($this, 'add_apply_discount_container'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
  }

  public function enqueue_scripts() {
    wp_enqueue_script('hellodev-discount-manager-js', plugins_url('js/hellodev-discount-manager-apply-discount-min.js', DM_PLUGIN_FILE), array(), '1.0', false);
    wp_localize_script('hellodev-discount-manager-js',
    'hellodev_discount_manager_locales',
    array(
      'confirm_apply_discount' => __('Applying the discount will overwrite existing discounts. Do you want to continue?', 'hellodev-discount-manager-apply-discount'),
      'apply_success_message' => __('Saved with success.', 'hellodev-discount-manager-apply-discount'))
    );
  }

  public function add_apply_discount_container() {
    global $post_id_discount;
    $order = new WC_Order($post_id_discount);
    if (!$order->is_editable()) {
      return;
    }
    add_meta_box('hellodev-discount-manager-apply-discount-container', __('Apply discount to all items', 'hellodev-discount-manager-apply-discount'),
    array($this, 'create_apply_discount_container'), 'shop_order', 'side');
  }

  public function create_apply_discount_container() {
    ?>
    <ul id="hellodev-discount-manager-apply-discount" class="hellodev-discount-manager-apply-discount">
      <li>
        <input id="hellodev-discount-manager-apply-discount-percentage" type="number" name="discount" min="0" max="100" step="1"
        placeholder="<?php _e("Discount in percentage (%)", "hellodev-discount-manager-apply-discount") ?>" style="width: 100%"/>
      </li>
    </ul>
    <?php
  }
}

new DM_Apply_Order_Discount();
