<?php 
class WAS_Order
{
	public function __construct()
	{
	}
	public function get_today_orders_by_product_id($product_ids)
	{
		global $wpdb;
		//$time_offset = WAS_OptionsMenu::get_option('time_offset');
		//$today = date('Y-m-d',strtotime($time_offset.' minutes'));
		$today = current_time( 'Y-m-d' );
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT SUM(order_itemmeta_qty.meta_value) AS quantity
				  FROM {$wpdb->posts} AS orders
				  INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON order_items.order_id = orders.ID 
				  INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta ON order_itemmeta.order_item_id = order_items.order_item_id 
				  INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta_qty ON order_itemmeta_qty.order_item_id = order_itemmeta.order_item_id 
				  WHERE orders.post_type = 'shop_order' 		
				  AND order_items.order_item_type = 'line_item'
				  AND order_itemmeta_qty.meta_key = '_qty'
				  AND order_itemmeta.meta_key = '_product_id'
				  AND order_itemmeta.meta_value IN('".implode("','",$product_ids)."')   
				  AND orders.post_date >= '{$today} 00:00' 
				  AND orders.post_date <= '{$today} 23:59' ";
				 // GROUP BY orders.id";
		$result = $wpdb->get_results($query);
		$result = isset($result[0]->quantity) ? (int)$result[0]->quantity : 0;
		return $result;
	}
}
?>