<?php 
class WAS_product
{
	var $product = null;
	var $meta_saved_as_array_names = array('_was_period',
										   '_was_customer_roles_restriction_type',
										   '_was_customer_roles_restriction_message',
										   '_was_shop_page_msg',
										   '_was_shop_page_during_msg',
										   '_was_shop_page_after_msg',
										   '_was_product_page_msg',
										   '_was_product_page_during_msg',
										   '_was_product_page_after_msg',
										   '_was_total_sales_limit',
										   '_was_where_sales_progressbar',
										   '_was_where_countdown_to_start',
										   '_was_where_countdown_to_end',
										   '_was_start_time', //array in which if for a day there isn't any time, value is not setted
										   '_was_end_time',   //same as before
										   '_was_countdown_to_start',  //same as before
										   '_was_countdown_to_end'	   //same as before
										   );
	public function __construct()
	{
		add_action('wp_ajax_wcas_reset_products_avaiability', array(&$this, 'ajax_reset_products_avaiability'));
	}
	
	public function get_product_single_meta($product_id, $meta_name)
	{
		if(!isset($product_id) || !is_numeric($product_id))
			return false;
		return  $this->get_product_meta( $product_id, $meta_name, true );
	}
	public function get_product_meta($product_id, $meta_name, $single = true)
	{
		$product = wc_get_product($product_id); 
		return  version_compare( WC_VERSION, '2.7', '<' ) ? $this->get_product_meta($product_id, $meta_name,  $single) : $product->get_meta($meta_name,  $single);
	}
	public function update_product_meta($product_id, $meta_name, $value, $save = false)
	{
		if(version_compare( WC_VERSION, '2.7', '<' ))
		{
			update_post_meta( $product_id, $meta_name, isset($value) ? $value : null );
		}
		else 
		{
			if( $this->product == null || $this->product->get_id() != $product_id)
				$this->product = wc_get_product($product_id); 
			//$this->product = $product;
			
			if($this->product == false)
				return;
			
			$this->product->update_meta_data(  $meta_name, isset($value) ? $value : null);
			if($save)
				$this->product->save( );
		}
	}
	public function apply_product_modifications()
	{
		if(version_compare( WC_VERSION, '2.7', '>' ) && $this->product != null)
		{
			$this->product->save();
		}
	}

	//start_time,end_time,expiring_date
	public function get_product_date_info($porduct_id, $type_of_date = 'start_time')
	{
		$result = WAS_PostAddon::get_post_meta($porduct_id);
		$time_offset = WAS_OptionsMenu::get_option('time_offset');
		$today = date("N", strtotime($time_offset.' minutes'))-1;
		$time_format = WAS_OptionsMenu::get_option('time_format');
		
		$start_time = isset($result["stat_time"][$today]) ? date($time_format, strtotime($result["stat_time"][$today]))  : "";
		$end_time = isset($result["end_time"][$today]) ?  date($time_format, strtotime($result["end_time"][$today])) : "";
		
		
		switch($type_of_date)
		{
			case 'start_time': return $start_time ; break;
			case 'end_time': return  $end_time  ; break;
			case 'expiring_date': return isset($result['expiring_date']) ? $result['expiring_date'] :  ""; break;
		}
		return "";
	}
	public function validate_current_cart_products()
	{
		//$cart = WC()->cart->get_cart();
		//$cart = WC()->cart;
		$results = array();
		$items_to_remove = array();
		foreach(WC()->cart->get_cart() as $cart_item_key => $item)
		{
			$wc_product = wc_get_product($item['product_id']);
			//$item['data']->post->post_title
			$title = isset($wc_product) && $wc_product != false ? $wc_product->get_title( ): null;
			$results[] = $this->check_if_product_can_be_purchase_due_to_purchase_limit($title, $item['product_id'], $item['variation_id'], $item['quantity']);	
			if(get_post_status($item['product_id']) != 'publish')
			{
				$temp_result_data = array();
				$temp_result_data['result'] = false;
				$temp_result_data['messages'] = array(0 => sprintf( __('You cannot buy product %s. If you procede with the checkout it will not be included in your order','woocommerce-availability-scheduler'), $item['data']->post->post_title));
				$results[] = $temp_result_data;
				//wcas_var_dump($cart_item_key);
				$items_to_remove[] = $cart_item_key;
			}
		}
		if(!empty($items_to_remove))
			foreach($items_to_remove as $item_to_remove)
				WC()->cart->remove_cart_item($item_to_remove);
		return $results;
	}
	public function get_current_and_total_sales_limit($product_id)
	{
		if(!isset($product_id) || !is_numeric($product_id))
			return false;
		//$today = date("N", strtotime($time_offset.' minutes'))-1;
		$sales_limit = $this->get_product_single_meta($product_id,'_was_total_sales_limit');
		$total_sales = $this->get_today_product_total_sales($product_id);
		$total_sales = is_numeric($total_sales) ? $total_sales : 0;
		return isset($sales_limit) ? array('sales_limit' =>$sales_limit, 'total_sales' =>$total_sales) : false; 
	}
	public function check_if_product_can_be_purchase_due_to_purchase_limit($item_name, $product_id, $variation_id = 0, $quantity = 0, $absolute_quantity = true)
	{
		$result_data = array('result' => true, 'messages' => array(), 'product_id' => $product_id, 'variation_id' => $variation_id, 'cart_item_key'=>null, 'quantity' => $quantity);
		$time_offset = WAS_OptionsMenu::get_option('time_offset');
		$today = date("N", strtotime($time_offset.' minutes'))-1;
		$sales_limit = $this->get_product_single_meta($product_id,'_was_total_sales_limit');
		//$quantity = 0;
		//wcas_var_dump($absolute_quantity );
		if(!$absolute_quantity)
		{
			$cart = WC()->cart;	
			foreach($cart->cart_contents as $cart_item_key => $item)
			{
				/* wcas_var_dump($item['product_id']." ".$product_id );
				wcas_var_dump($item['product_id'] == $product_id); */
				if($item['product_id'] == $product_id)
				{
					/* if($item['variation_id'] == 0 || $item['variation_id'] == $variation_id)
						$result_data['cart_item_key'] = $cart_item_key;
						
					$item_name = $item['data']->post->post_title; 
					if(!$absolute_quantity)*/
						 $quantity += $item['quantity'];
					
				}
			}
		}
		
		if(isset($sales_limit) && isset($sales_limit[$today]) && $sales_limit[$today] != 0)
		{
			$total_sales = $this->get_today_product_total_sales($product_id);
			/* wcas_var_dump($quantity);
			wcas_var_dump((int)$sales_limit[$today]);
			wcas_var_dump($total_sales);
			wcas_var_dump("*****"); */
			if(is_numeric($total_sales) && ($quantity > (int)$sales_limit[$today] - $total_sales || $total_sales > (int)$sales_limit[$today]))
			{
				$result_data['result'] = false;
				$name_to_output = isset($item_name) ? $item_name  : __('the selected product','woocommerce-availability-scheduler');
				$purchasable_number = $sales_limit[$today] - $total_sales > 0 ? $sales_limit[$today] - $total_sales : 0;
				$result_data['messages'][] = sprintf( __('You cannot buy more than %d for the product %s.','woocommerce-availability-scheduler'), $purchasable_number, $name_to_output);
			}
		}
		
		return $result_data;
	}
	public function ajax_reset_products_avaiability()
	{
		global $wpdb;
		$query = "DELETE 
					FROM {$wpdb->postmeta} ".
					"WHERE meta_key LIKE '_was_%' ";
					/* WHERE meta_key IN('_was_period', '_was_start_time','_was_end_time','_was_shop_page_msg','_was_shop_page_during_msg','_was_shop_page_after_msg'
									   '_was_product_page_msg','_was_product_page_during_msg','_was_product_page_after_msg','_was_countdown_to_start',
									   '_was_countdown_to_end','_was_always_show_shop_message','_was_always_show_product_message','_was_where_countdown_to_start',
									   '_was_always_show_shop_message','_was_always_show_product_message', '_was_where_countdown_to_start', '_was_where_countdown_to_end',
									   '_was_where_sales_progressbar', '_was_expiring_date', '_was_total_sales_limit', '_was_total_sales_limit', '_was_customer_roles_restriction_type',
									   '_was_customer_roles_restriction_message') " */;
		$wpdb->get_results($query);
		wp_die();
	}
	public function get_all_product_meta($post_id)
	{
		$result = array();
		if(!isset($post_id) || !is_numeric($post_id))
			return false;
		
		$result["ID"] = $post_id;
		$result["availability_strategy"] = $this->get_product_meta( $post_id, '_was_period', true );
		$result["stat_time"] = $this->get_product_meta( $post_id, '_was_start_time', true );
		$result["end_time"] = $this->get_product_meta( $post_id, '_was_end_time', true );
		
		$result["shop_page_msg"] = $this->get_product_meta( $post_id, '_was_shop_page_msg', true );
		$result["shop_page_during_msg"] = $this->get_product_meta( $post_id, '_was_shop_page_during_msg', true );
		$result["shop_page_after_msg"] = $this->get_product_meta( $post_id, '_was_shop_page_after_msg', true );
		
		$result["product_page_msg"] = $this->get_product_meta( $post_id, '_was_product_page_msg', true );
		$result["product_page_during_msg"] = $this->get_product_meta( $post_id, '_was_product_page_during_msg', true );
		$result["product_page_after_msg"] = $this->get_product_meta( $post_id, '_was_product_page_after_msg', true );
		
		$result["countdown_to_start"] = $this->get_product_meta($post_id, '_was_countdown_to_start', true);
		$result["countdown_to_end"] = $this->get_product_meta( $post_id, '_was_countdown_to_end', true);
		$result["always_show_shop_message"] = $this->get_product_meta( $post_id, '_was_always_show_shop_message', true);
		$result["always_show_product_message"] = $this->get_product_meta( $post_id, '_was_always_show_product_message', true);
		$result["where_countdown_to_start"] = $this->get_product_meta($post_id, '_was_where_countdown_to_start', true);
		$result["where_countdown_to_end"] = $this->get_product_meta( $post_id, '_was_where_countdown_to_end', true);
		$result["where_sales_progressbar"] = $this->get_product_meta( $post_id, '_was_where_sales_progressbar', true);
		$result["expiring_date"] = $this->get_product_meta( $post_id, '_was_expiring_date', true);
		$result["visible_after_expiring_date"] = $this->get_product_meta( $post_id, '_was_visible_after_expiring_date', true);
		$result["show_countdown_to_expiration_date"] = $this->get_product_meta( $post_id, '_was_show_countdown_to_expiration_date', true);
		$result["expired_shop_message"] = $this->get_product_meta( $post_id, '_was_expired_shop_message', true);
		$result["expired_product_message"] = $this->get_product_meta( $post_id, '_was_expired_product_message', true);
		$result["before_expiration_shop_message"] = $this->get_product_meta( $post_id, '_was_before_expiration_shop_message', true);
		$result["before_expiration_product_message"] = $this->get_product_meta( $post_id, '_was_before_expiration_product_message', true);
		
		$result["hide"] = $this->get_product_meta( $post_id, '_was_hide', true);
		$result["total_sales_limit"] = $this->get_product_meta( $post_id, '_was_total_sales_limit', true);
		
		$result["was_customer_roles_restriction_type"] = $this->get_product_meta( $post_id, '_was_customer_roles_restriction_type', true);
		$result["was_customer_roles"] = $this->get_product_meta( $post_id, '_was_customer_roles', true);
		$result["was_customer_roles_restriction_message"] = $this->get_product_meta( $post_id, '_was_customer_roles_restriction_message', true);
		
		if(empty($result["availability_strategy"]))
			return false;
		
		return $result;
	}
	private function copy_day_configuration_to_other_days($source_day_data, &$data_to_save)
	{
		if(!isset($source_day_data) || !is_array($source_day_data))
			return;
		
		foreach($this->meta_saved_as_array_names as $meta_name)
		{
			//wcas_var_dump($meta_name);
			if(isset($data_to_save[$meta_name]))
			{
				//for($day_index = 0; $day_index < 7; $day_index++)
				{
					foreach($source_day_data as $source_day => $dest_day_array)
						foreach((array)$dest_day_array as $dest_day)
							if(isset($data_to_save[$meta_name][$source_day]))
								$data_to_save[$meta_name][$dest_day] = $data_to_save[$meta_name][$source_day];
							else 
								unset($data_to_save[$meta_name][$dest_day]);
				}
				//wcas_var_dump($data_to_save[$meta_name]);
			}
		}
	}
	public function save_product_meta($post_id, $skip_id_translation_check = false)
	{
		if(!isset($post_id) || !is_numeric($post_id))
			return false;
		
		global $was_wpml_model;
		if($was_wpml_model->wpml_is_active() && !$skip_id_translation_check)
		{
			$translated_ids = $was_wpml_model->get_translated_id($post_id);
			//wcas_var_dump($translated_ids);
			if(!empty($translated_ids))
				foreach($translated_ids as $translation_id)
					$this->save_product_meta($translation_id, true);
		}
		
		$data_to_save = $_POST;

		if(isset($data_to_save['_was_assign_this_configuration_to_days']))
		{
			//O: Monday
			$this->copy_day_configuration_to_other_days($data_to_save['_was_assign_this_configuration_to_days'], $data_to_save);
		}
					
		$this->update_product_meta( $post_id, '_was_period', isset($data_to_save['_was_period']) ? $data_to_save['_was_period'] : null );
		$this->update_product_meta( $post_id, '_was_start_time',isset( $data_to_save['_was_start_time']) ? $data_to_save['_was_start_time'] : null);
		$this->update_product_meta( $post_id, '_was_end_time', isset( $data_to_save['_was_end_time']) ? $data_to_save['_was_end_time'] : null );
		
		if(isset($data_to_save['_was_shop_page_msg']))
			$this->update_product_meta( $post_id, '_was_shop_page_msg', $data_to_save['_was_shop_page_msg'] );
		if(isset($data_to_save['_was_shop_page_during_msg']))
			$this->update_product_meta( $post_id, '_was_shop_page_during_msg', $data_to_save['_was_shop_page_during_msg'] );
		if(isset($data_to_save['_was_shop_page_after_msg']))
			$this->update_product_meta( $post_id, '_was_shop_page_after_msg', $data_to_save['_was_shop_page_after_msg'] );
		if(isset($data_to_save['_was_product_page_msg']))
			$this->update_product_meta( $post_id, '_was_product_page_msg', $data_to_save['_was_product_page_msg'] );
		if(isset($data_to_save['_was_product_page_during_msg']))
			$this->update_product_meta( $post_id, '_was_product_page_during_msg', $data_to_save['_was_product_page_during_msg'] );
		if(isset($data_to_save['_was_product_page_after_msg']))
			$this->update_product_meta( $post_id, '_was_product_page_after_msg', $data_to_save['_was_product_page_after_msg'] );
		
		$this->update_product_meta( $post_id, '_was_hide',  isset($data_to_save['_was_hide']) ? $data_to_save['_was_hide'] : null  );
		
		$this->update_product_meta( $post_id, '_was_total_sales_limit',  isset($data_to_save['_was_total_sales_limit']) ? $data_to_save['_was_total_sales_limit'] : 0 );
		
		$this->update_product_meta( $post_id, '_was_countdown_to_start',  isset($data_to_save['_was_countdown_to_start']) ? $data_to_save['_was_countdown_to_start'] : null);
		$this->update_product_meta( $post_id, '_was_countdown_to_end',  isset($data_to_save['_was_countdown_to_end']) ? $data_to_save['_was_countdown_to_end'] : null );
		
		$this->update_product_meta( $post_id, '_was_where_countdown_to_start',   isset($data_to_save['_was_where_countdown_to_start']) ? $data_to_save['_was_where_countdown_to_start'] : null );
		$this->update_product_meta( $post_id, '_was_where_countdown_to_end',  isset($data_to_save['_was_where_countdown_to_end']) ? $data_to_save['_was_where_countdown_to_end'] : null );
		
		$this->update_product_meta( $post_id, '_was_where_sales_progressbar',  isset($data_to_save['_was_where_sales_progressbar']) ? $data_to_save['_was_where_sales_progressbar'] : null);
		
		//Expiring date
		$this->update_product_meta( $post_id, '_was_expiring_date',  isset($data_to_save['_was_expiring_date']) ? $data_to_save['_was_expiring_date'] : null );
		$this->update_product_meta( $post_id, '_was_visible_after_expiring_date',  isset($data_to_save['_was_visible_after_expiring_date']) ? $data_to_save['_was_visible_after_expiring_date'] : null );
		$this->update_product_meta( $post_id, '_was_show_countdown_to_expiration_date',  isset($data_to_save['_was_show_countdown_to_expiration_date']) ? $data_to_save['_was_show_countdown_to_expiration_date'] : null );
		$this->update_product_meta( $post_id, '_was_before_expiration_product_message',  isset($data_to_save['_was_before_expiration_product_message']) ? $data_to_save['_was_before_expiration_product_message'] : null );
		$this->update_product_meta( $post_id, '_was_before_expiration_shop_message',  isset($data_to_save['_was_before_expiration_shop_message']) ? $data_to_save['_was_before_expiration_shop_message'] : null );
		$this->update_product_meta( $post_id, '_was_expired_shop_message',  isset($data_to_save['_was_expired_shop_message']) ? $data_to_save['_was_expired_shop_message'] : null );
		$this->update_product_meta( $post_id, '_was_expired_product_message',  isset($data_to_save['_was_expired_product_message']) ? $data_to_save['_was_expired_product_message'] : null );
	
		$this->update_product_meta( $post_id, '_was_customer_roles',  isset($data_to_save['_was_customer_roles']) ? $data_to_save['_was_customer_roles'] : "" );
		if(isset($data_to_save['_was_customer_roles_restriction_type']))
			$this->update_product_meta( $post_id, '_was_customer_roles_restriction_type',  $data_to_save['_was_customer_roles_restriction_type'] );
		
		$this->update_product_meta( $post_id, '_was_customer_roles_restriction_message',  $data_to_save['_was_customer_roles_restriction_message'] );
		
		$this->apply_product_modifications();
	}
	/*
	$post_id - The ID of the post you'd like to change.
	$status -  The post status publish|pending|draft|private|static|object|attachment|inherit|future|trash.
	*/
	public function change_product_status($post_id,$status)
	{
		if(!isset($post_id) || !is_numeric($post_id))
			return false;
		
		$current_post = array();
		$current_post['ID'] = $post_id;
		$current_post['post_status'] = $status;
		wp_update_post($current_post);
	}
	public function bulk_change_products_status($ids, $status = 'publish')
	{
		/* global $wpdb;
		$query = "UPDATE {$wpdb->posts} as products
		          SET  products.post_status = '{$status}'
				  WHERE products.ID IN ('".implode("','",$ids)."')
				  ";
		$wpdb->get_results($query);  */ 
		$products_array = array();
		foreach((array)$ids as $id)
			wp_update_post( array('ID' => $id, 'post_status' => $status));  
	}
	public function get_today_product_total_sales($product_id)
	{
		global $was_wpml_model,$was_order_model;
		$all_ids = array(0 => $product_id);
		if($was_wpml_model->wpml_is_active())
		{
			$translated_ids = $was_wpml_model->get_translated_id($product_id);
			//wcas_var_dump($translated_ids);
			if(!empty($translated_ids))
				foreach($translated_ids as $translation_id)
					array_push($all_ids, $translation_id);
		}
		return $was_order_model->get_today_orders_by_product_id($all_ids);
	}
	
	public function is_product_expired_but_visible($product_id)
	{
		if(!isset($product_id) || !is_numeric($product_id))
			return false;
		if($this->product_has_expired($product_id))
		{
			$still_visible = $this->get_product_meta( $product_id, '_was_visible_after_expiring_date', true);
			if(isset($still_visible))
				return true;
		}
		return false;
	}
	//hides expired product (due to expiring date or for today unavailability)
	public function hide_expired_products()
	{
		global $wpdb, $was_post_remover; 
		//$wpdb->query('SET OPTION SQL_BIG_SELECTS = 1');
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT products.ID as ID,  productmeta_expiring_date.meta_value as expiring_date, productmeta_hide.meta_value as hide, 
				  productmeta_period.meta_value as availability_strategy, productmeta_start_time.meta_value as stat_time, productmeta_end.meta_value as end_time,
				  productmeta_total_sale_limit.meta_value as total_sales_limit, productmeta_visible_after_expiring_date.meta_value as visible_after_expiring_date
				  FROM {$wpdb->posts} as products
				  LEFT JOIN {$wpdb->postmeta} as productmeta_hide ON productmeta_hide.post_id = products.ID  AND productmeta_hide.meta_key = '_was_hide'
				  LEFT JOIN {$wpdb->postmeta} as productmeta_expiring_date ON productmeta_expiring_date.post_id = products.ID  AND productmeta_expiring_date.meta_key = '_was_expiring_date' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_period ON productmeta_period.post_id = products.ID  AND productmeta_period.meta_key = '_was_period' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_start_time ON productmeta_start_time.post_id = products.ID AND productmeta_start_time.meta_key = '_was_start_time'
				  LEFT JOIN {$wpdb->postmeta} as productmeta_end ON productmeta_end.post_id = products.ID AND productmeta_end.meta_key = '_was_end_time' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_total_sale_limit ON productmeta_total_sale_limit.post_id = products.ID AND productmeta_total_sale_limit.meta_key = '_was_total_sales_limit' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_visible_after_expiring_date ON productmeta_visible_after_expiring_date.post_id = products.ID AND productmeta_visible_after_expiring_date.meta_key = '_was_visible_after_expiring_date' 
				  WHERE 
				  products.post_status = 'publish'
				  GROUP BY products.ID"; 
		$products = $wpdb->get_results($query, 'ARRAY_A');	 
		//wcas_var_dump($wpdb->show_errors());
		
		//wcas_var_dump($products); 
		if(empty($products))
			return false;
	
		$time_offset = WAS_OptionsMenu::get_option('time_offset');
		$today = date("Y-m-d G:i", strtotime($time_offset.' minutes'));
		$today_number = date("N", strtotime($time_offset.' minutes'))-1;
		$removed_expired = false;
		$products_ids = array();
		
		foreach($products as $product)
		{
			$today_day = date("N", strtotime($time_offset.' minutes'))-1;
			$product['availability_strategy'] = isset($product['availability_strategy']) && $product['availability_strategy'] != null ? unserialize($product['availability_strategy']) : null;
			$product['hide'] = isset($product['hide']) && $product['hide'] != null? unserialize($product['hide']) : null;
			$product['stat_time'] = isset($product['stat_time']) && $product['stat_time'] != null ? unserialize($product['stat_time']) : null;
			$product['end_time'] = isset($product['end_time']) && $product['end_time'] != null ? unserialize($product['end_time']) : null;			
			$product['expiring_date'] = isset($product['expiring_date']) && $product['expiring_date'] != null ? $product['expiring_date'] : null;
			$product['visible_after_expiring_date'] = isset($product['visible_after_expiring_date']) && $product['visible_after_expiring_date'] != null ? $product['visible_after_expiring_date'] : null;
			$product['total_sales_limit'] = isset($product['total_sales_limit']) && $product['total_sales_limit'] != null ? unserialize($product['total_sales_limit']) : null;
			$hide_option =  isset($product["hide"]) && isset($product["hide"][$today_number]) && $product["hide"][$today_number] != null ? $product["hide"][$today_number]: null;
			$current_period = $was_post_remover->AS_get_current_period($product);
			
			//var_dump($hide_option." ".$current_period." ".$was_post_remover->AS_is_disabled_for_today($product));
			//var_dump($product);
			/* if($product['ID'] == 12)
			{
				wcas_var_dump($product['ID']);
				wcas_var_dump($product['availability_strategy'][$today_day]);
				wcas_var_dump($current_period);
				wcas_var_dump($hide_option); 
			}  */
			
			/* if($product['ID'] == 32)
			{
				 wcas_var_dump(strtotime($today));
				 wcas_var_dump(strtotime($product['expiring_date']));
				 wcas_var_dump(strtotime($product['expiring_date']) <= strtotime($today));
			} */
			if((isset($product['expiring_date']) && !isset($product['visible_after_expiring_date']) && $product['expiring_date'] != "" && strtotime($product['expiring_date']) <= strtotime($today)) ||
			   (isset($hide_option) && ( ($product['availability_strategy'][$today_day] == 1 && ($current_period != "during" && $current_period != "all_day")) ||
										 ($product['availability_strategy'][$today_day] == 3 && $current_period == "during") ||
										  $was_post_remover->AS_is_disabled_for_today($product))
			   ))
			   {
				  
					array_push($products_ids, $product['ID']);
					$removed_expired = true;
				}
		}
		if($removed_expired)
			$this->bulk_change_products_status($products_ids, 'draft');
		
		return $removed_expired;
	}
	public function product_has_expired($post_id)
	{
		global $wpdb;
		if(!isset($post_id) || !is_numeric($post_id))
			return false;
		//$wpdb->query('SET OPTION SQL_BIG_SELECTS = 1');
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT products.ID as ID, productmeta_hide.meta_value as hide, productmeta_expiring_date.meta_value as expiring_date     
				  FROM {$wpdb->posts} as products
				  LEFT JOIN {$wpdb->postmeta} as productmeta_hide ON productmeta_hide.post_id = products.ID  AND productmeta_hide.meta_key = '_was_hide' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_expiring_date ON productmeta_expiring_date.post_id = products.ID AND productmeta_expiring_date.meta_key = '_was_expiring_date'
				  WHERE products.ID = {$post_id}
				  AND products.post_status = 'publish'
				  ";
		$product = $wpdb->get_results($query, 'ARRAY_A');		
		$product = isset($product[0]) ? $product[0]:null;
		if($product == null)
			return false;
		
		$time_offset = WAS_OptionsMenu::get_option('time_offset');
		$today = date("Y-m-d G:i", strtotime($time_offset.' minutes'));
	
		if(isset($product['expiring_date']) && $product['expiring_date'] != "" && strtotime($product['expiring_date']) <= strtotime($today))
		{
			return true;
		}
		return false;
	}

	public function check_which_product_unhide()
	{
		global $wpdb, $was_post_remover;
		
		//$wpdb->query('SET OPTION SQL_BIG_SELECTS = 1');
		$wpdb->query('SET SQL_BIG_SELECTS=1');
		$query = "SELECT products.ID as ID, productmeta_hide.meta_value as hide, productmeta_period.meta_value as availability_strategy,
		          productmeta_start_time.meta_value as stat_time, productmeta_end.meta_value as end_time, productmeta_expiring_date.meta_value as expiring_date,
				  productmeta_total_sale_limit.meta_value as total_sales_limit , productmeta_visible_after_expiring_date.meta_value as visible_after_expiring_date      
				  FROM {$wpdb->posts} as products
				  LEFT JOIN {$wpdb->postmeta} as productmeta_hide ON productmeta_hide.post_id = products.ID AND productmeta_hide.meta_key = '_was_hide' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_period ON productmeta_period.post_id = products.ID AND productmeta_period.meta_key = '_was_period' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_start_time ON productmeta_start_time.post_id = products.ID AND productmeta_start_time.meta_key = '_was_start_time' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_end ON productmeta_end.post_id = products.ID AND productmeta_end.meta_key = '_was_end_time' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_total_sale_limit ON productmeta_total_sale_limit.post_id = products.ID AND productmeta_total_sale_limit.meta_key = '_was_total_sales_limit' 
				  LEFT JOIN {$wpdb->postmeta} as productmeta_visible_after_expiring_date ON productmeta_visible_after_expiring_date.post_id = products.ID AND productmeta_visible_after_expiring_date.meta_key = '_was_visible_after_expiring_date'
				  LEFT JOIN {$wpdb->postmeta} as productmeta_expiring_date ON productmeta_expiring_date.post_id = products.ID  AND productmeta_expiring_date.meta_key = '_was_expiring_date' 
				  WHERE products.post_status = 'draft'
				  GROUP BY products.ID ";
		$product_drafts = $wpdb->get_results($query, 'ARRAY_A');
		
		$time_offset = WAS_OptionsMenu::get_option('time_offset');
		$today = date("N", strtotime($time_offset.' minutes'))-1;
		$today_for_expiration = date("Y-m-d G:i", strtotime($time_offset.' minutes'));
		$time = date("H:i", strtotime($time_offset.' minutes'));
		$unhide_results = false;
		$products_ids = array();
		foreach($product_drafts as $product)
		{
			$product['availability_strategy'] = isset($product['availability_strategy']) ? unserialize($product['availability_strategy']) : null;
			$product['hide'] = isset($product['hide']) ? unserialize($product['hide']) : null;
			$product['stat_time'] = isset($product['stat_time']) ? unserialize($product['stat_time']) : null;
			$product['end_time'] = isset($product['end_time']) ? unserialize($product['end_time']) : null;
			$product['expiring_date'] = isset($product['expiring_date']) ? $product['expiring_date'] : null;
			$product['visible_after_expiring_date'] = isset($product['visible_after_expiring_date']) ? $product['visible_after_expiring_date'] : null;
			$product['total_sales_limit'] = isset($product['total_sales_limit']) ? unserialize($product['total_sales_limit']) : null;
			/* echo "<pre>";
			var_dump($product);
			echo "</pre>"; */
	
			//$result = WAS_PostAddon::get_post_meta($product->ID); 
			$current_period = $was_post_remover->AS_get_current_period($product); //$result["availability_strategy"]
			$hide_option =  isset($product["hide"]) && isset($product["hide"][$today]) ? $product["hide"][$today]: null;	
			/* wcas_var_dump($product['ID']);
			wcas_var_dump($current_period);
			wcas_var_dump($hide_option);  
			wcas_var_dump($product['availability_strategy'][$today]);  
			wcas_var_dump($product['availability_strategy'][$today] == 3 && ($current_period != "during")));   */
			if(isset($product['expiring_date']) && $product['expiring_date'] != "" && strtotime($product['expiring_date']) <= strtotime($today_for_expiration))
			{
				$unhide_results = !isset($product['visible_after_expiring_date']) ? false : true;
			}
			else if(/* (!isset($hide_option) && $was_post_remover->AS_is_disabled_for_today($product)) || */ isset($hide_option) && ((/* isset($hide_option) && */ (($product['availability_strategy'][$today] == 1 && ($current_period == "during")) ||
																										($product['availability_strategy'][$today] == 3 && ($current_period != "during")))  
																				) || 
																				($current_period == "all_day" && !$was_post_remover->AS_is_disabled_for_today($product)) )
				)
			{
				//wcas_var_dump($product);
				array_push($products_ids, $product['ID']);
				$unhide_results = true;
			} 
		}
		
		if(!empty($products_ids))
			$this->bulk_change_products_status($products_ids);
		
		return $unhide_results;
	}
}
?>