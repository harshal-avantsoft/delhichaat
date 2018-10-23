<?php
class WAS_PostAddon
{
 public function __construct()
 {
	 add_action( 'add_meta_boxes', array( &$this, 'add_post_scheduler_metabox' ) );
	 add_action( 'save_post', array( &$this, 'save' ) ); 
 }
 public function add_post_scheduler_metabox()
 {
	/*  $options = get_option( 'WAS_options' );
	 foreach($options as $post_slug => $is_enabled)
		add_meta_box( 'woocommerce-availability-scheduler', __('Availability Scheduler', 'woocommerce-availability-scheduler'), array( &$this, 'render_scheduler_availability_box' ), $post_slug , 'advanced', 'core'); */
		
		add_meta_box( 'woocommerce-availability-scheduler', __('Availability Scheduler', 'woocommerce-availability-scheduler'), array( &$this, 'render_scheduler_availability_box' ), 'product' , 'advanced', 'high');
 }
/*  public static function get_post_expiring_date($post_id)
 {
	 return $this->get_post_meta( $post_id, '_was_expiring_date', true);
 } */
 public static function get_post_meta($post_id)
 {
	global $was_product_model;
	return $was_product_model->get_all_product_meta($post_id);
 }
 private function save_post_meta($post_id)
 {
	 global $was_product_model;
	$was_product_model->save_product_meta($post_id);
 }	
 public function save( $post_id ) 
 {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */
		
		// Check if our nonce is set.
		if ( ! isset( $_POST['was_input_fields_nonce'] ) )
			return $post_id;

		$nonce = $_POST['was_input_fields_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'was_input_fields' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Sanitize the user input.
		//$shop_page_msg = sanitize_text_field( $_POST['_was_shop_page_msg'] );
		//$product_page_msg = sanitize_text_field( $_POST['_was_product_page_msg'] );

		// Update the meta field.
		//var_dump($_POST);
		$this->save_post_meta($post_id);
	}
	
 function render_scheduler_availability_box($post) 
	{ 
		global $wp_roles,$wccm_customer_model, $was_product_model;
		/* global $wp_scripts;
		$wp_scripts->queue = array(); */
		wp_enqueue_style( 'was-select2-style', AS_PLUGIN_PATH.'/css/select2.min.css' ); 
		wp_enqueue_style( 'was-timepicker-css', AS_PLUGIN_PATH.'/css/timePicker.css' );
		wp_enqueue_style( 'was-admin', AS_PLUGIN_PATH.'/css/admin-availability-box.css' );
		wp_register_style('was-timepicker' , AS_PLUGIN_PATH. '/css/jquery-ui-timepicker-addon.css');
		wp_enqueue_style('was-timepicker');		
		
		//https://developer.wordpress.org/reference/functions/wp_enqueue_script/
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-jquery-ui-slider');
		wp_enqueue_script( 'was-select2-script', AS_PLUGIN_PATH.'/js/select2.min.js', array('jquery') );
		wp_enqueue_script('was-timepicker-js', AS_PLUGIN_PATH.'/js/jquery.timePicker.js', array('jquery'));  //adds datetime controls 
		wp_enqueue_script('was-ui-js', AS_PLUGIN_PATH.'/js/jquery-ui.js', array('jquery'));  
		wp_enqueue_script('was-admin-product-detail-page', AS_PLUGIN_PATH.'/js/was-admin-product-detail-page.js', array('jquery')); 		
		//wp_enqueue_script('was-admin-customer-auto-complete', AS_PLUGIN_PATH.'/js/was-admin-discover-customer-autocomplete.js', array('jquery'));
		wp_enqueue_script('was-jquery-time-picker' ,  AS_PLUGIN_PATH. '/js/jquery-ui-timepicker-addon.js',  array('jquery' ));	
		wp_enqueue_script('was-admin-availability-box' ,  AS_PLUGIN_PATH. '/js/was-admin-availability-box.js',  array('jquery' ));	
	
		// Use get_post_meta to retrieve an existing value from the database.
		//$value = $was_product_model->get_product_meta( $post->ID, '_my_meta_value_key', true );
		
		// Display the form, using the current value.
		$days = $was_product_model->get_product_meta( $post->ID, '_was_period', true);
		$start_times = $was_product_model->get_product_meta( $post->ID, '_was_start_time', true);
		$end_times = $was_product_model->get_product_meta( $post->ID, '_was_end_time', true);
		$product_page_msg = $was_product_model->get_product_meta( $post->ID, '_was_product_page_msg', true);
		$product_page_during_msg = $was_product_model->get_product_meta( $post->ID, '_was_product_page_during_msg', true);
		$product_page_after_msg = $was_product_model->get_product_meta( $post->ID, '_was_product_page_after_msg', true);
		$shop_page_msg = $was_product_model->get_product_meta( $post->ID, '_was_shop_page_msg', true);
		$shop_page_during_msg = $was_product_model->get_product_meta( $post->ID, '_was_shop_page_during_msg', true);
		$shop_page_after_msg = $was_product_model->get_product_meta( $post->ID, '_was_shop_page_after_msg', true);
		$countdown_to_start = $was_product_model->get_product_meta( $post->ID, '_was_countdown_to_start', true);
		$countdown_to_end = $was_product_model->get_product_meta( $post->ID, '_was_countdown_to_end', true);
		$was_hide = $was_product_model->get_product_meta( $post->ID, '_was_hide', true);
		$was_total_sales_limit = $was_product_model->get_product_meta( $post->ID, '_was_total_sales_limit', true);
		$where_countdown_to_start = $was_product_model->get_product_meta( $post->ID, '_was_where_countdown_to_start', true);
		$where_countdown_to_end = $was_product_model->get_product_meta( $post->ID, '_was_where_countdown_to_end', true);
		$where_sales_progressbar = $was_product_model->get_product_meta( $post->ID, '_was_where_sales_progressbar', true);
		$expiring_date = $was_product_model->get_product_meta( $post->ID, '_was_expiring_date', true);
		$visible_after_expiring_date = $was_product_model->get_product_meta( $post->ID, '_was_visible_after_expiring_date', true);
		$show_countdown_to_expiration_date = $was_product_model->get_product_meta( $post->ID, '_was_show_countdown_to_expiration_date', true);
		$expired_shop_message = $was_product_model->get_product_meta( $post->ID, '_was_expired_shop_message', true);
		$expired_product_message = $was_product_model->get_product_meta( $post->ID, '_was_expired_product_message', true);
		$before_expiration_shop_message = $was_product_model->get_product_meta( $post->ID, '_was_before_expiration_shop_message', true);
		$before_expiration_product_message = $was_product_model->get_product_meta( $post->ID, '_was_before_expiration_product_message', true);
		$allowed_roles = $was_product_model->get_product_meta( $post->ID, '_was_customer_roles', true);
		$customers_ids = $was_product_model->get_product_meta( $post->ID, '_was_customer_ids', true);
		$customer_roles_restriction_type = $was_product_model->get_product_meta( $post->ID, '_was_customer_roles_restriction_type', true);
		$customer_roles_restriction_message = $was_product_model->get_product_meta( $post->ID, '_was_customer_roles_restriction_message', true);
		$time_offset = WAS_OptionsMenu::get_option('time_offset');
		
		
		wp_nonce_field( 'was_input_fields', 'was_input_fields_nonce' );
		?>
		<!--<script>
			jQuery.fn.select2=null;
		</script>
		<script type='text/javascript' src='<?php echo AS_PLUGIN_PATH.'/js/select2.min.js'; ?>'></script>-->
		
		<h3 class="was-title"><?php _e('Current server date', 'woocommerce-availability-scheduler'); ?></h3>
		<p>
			<?php _e('Product availability is set using server time. Current server time is: ', 'woocommerce-availability-scheduler'); ?>
			<strong><?php echo date("l", strtotime($time_offset.' minutes')).", ".date("H:i", strtotime($time_offset.' minutes')); ?></strong> ( <?php echo $time_offset.' '.__('minute(s) offset. To change offset value, go to Bulk availability -> options menu', 'woocommerce-availability-scheduler');?> )
		</p>
		
		<h3 class="was-title"><?php _e('Today total product sales', 'woocommerce-availability-scheduler'); ?></h3>
		<p>
			<?php 
				$total_sales = $was_product_model->get_today_product_total_sales($post->ID); 
				$total_sales = isset($total_sales) ? $total_sales : 0;
				echo __('Today total sales: ', 'woocommerce-availability-scheduler').'<strong>'.$total_sales.'</strong>';
			?>
		</p>
		
		<h3 class="was-title"><?php _e('Expiring date & messages', 'woocommerce-availability-scheduler'); ?></h3>
		<p>
			<?php _e('The product will be no longer available after this date (will be set as \'draft\'). Leave empty if product hasn\'t an expiration date.', 'woocommerce-availability-scheduler'); ?>
			<br/><br/>
			<input type="text" name="_was_expiring_date" class="wcas_expiring-date" value="<?php echo $expiring_date; ?>"></input><button class="button" id="was_clear_expiring_date_button"><?php _e('Clear','woocommerce-availability-scheduler'); ?></button>
		</p>
		<p style="margin-top:20px; margin-bottom:20px;" class="was-form-field checkbox-par">	
			<label for="_was_visible_after_expiring_date"><?php _e('Leave the product visible (but unpurcasable) after expring date?', 'woocommerce-availability-scheduler'); ?></label>
			<input name="_was_visible_after_expiring_date" type="checkbox" value="1" <?php if($visible_after_expiring_date) echo 'checked="checked"';?>>
			
		</p>
		<p style="margin-top:20px; margin-bottom:20px;" class="was-form-field checkbox-par">	
			<label for="_was_show_countdown_to_expiration_date"><?php _e('Show countdown to expiration date?', 'woocommerce-availability-scheduler'); ?></label>
			<input name="_was_show_countdown_to_expiration_date" type="checkbox" value="1" <?php if($show_countdown_to_expiration_date) echo 'checked="checked"';?>>
			
		</p>
		
		<h4 class="was-title"><?php _e('Messages before product expiration ','woocommerce-availability-scheduler'); ?></h4>
		<p style="display:block; margin-top: 10px;">
			<label><?php _e('Use the following text area to display a message in the Shop (first box) and Product (second box) pages after the product exires (if still visible).', 'woocommerce-availability-scheduler'); ?></label><br/>
			<textarea placeholder="<?php _e('Shop message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_before_expiration_shop_message"  ><?php if(isset($before_expiration_shop_message)) echo $before_expiration_shop_message; ?></textarea>
			<textarea placeholder="<?php _e('Product message'); ?>" rows="5" cols="30" name="_was_before_expiration_product_message"  ><?php if(isset($before_expiration_product_message)) echo $before_expiration_product_message; ?></textarea>
		</p>
		<h4 class="was-title"><?php _e('Messages after product expiration (if still visible)','woocommerce-availability-scheduler'); ?></h4>
		<p style="display:block; margin-top: 10px;">
			<label><?php _e('Use the following text area to display a message in the Shop (first box) and Product (second box) .', 'woocommerce-availability-scheduler'); ?></label><br/>
			<textarea placeholder="<?php _e('Shop message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_expired_shop_message"  ><?php if(isset($expired_shop_message)) echo $expired_shop_message; ?></textarea>
			<textarea placeholder="<?php _e('Product message'); ?>" rows="5" cols="30" name="_was_expired_product_message"  ><?php if(isset($expired_product_message)) echo $expired_product_message; ?></textarea>
		</p>
						
					
		<div class="spacer"></div>
		
		<div id="wcas_tabs">
			<?php 
			$day_names = array(0 => 'Monday',1 => 'Tuesday',2 => 'Wednesday',3 => 'Thursday',4 => 'Friday',5 => 'Saturday',6 => 'Sunday');
			 ?>
			 <ul>
			 <?php for ($day=0; $day<7; $day++): ?>
				<li><a href="#tabs-<?php echo $day;?>"><?php _e($day_names[$day], 'woocommerce-availability-scheduler'); ?></a></li>
			 <?php endfor; ?>
			 </ul>
			
			<?php for ($day=0; $day<7; $day++): ?>
				<div id="tabs-<?php echo $day;?>">
						<p class="was-form-field">
							<label ><?php _e('Assign this configuration to the selected days', 'woocommerce-availability-scheduler'); ?></label>
							<!-- <input name="_was_assign_this_configuration_to_days" class="was_assign_this_configuration_for_all_days" type="checkbox" value="<?php echo $day; ?>"></input> -->
							
							<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][0]"  type="checkbox" value="0" ><?php _e('Monday', 'woocommerce-availability-scheduler'); ?></input>
							<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][1]"  type="checkbox" value="1" ><?php _e('Tuesday', 'woocommerce-availability-scheduler'); ?></input>
							<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][2]"  type="checkbox" value="2" ><?php _e('Wednesday', 'woocommerce-availability-scheduler'); ?></input>
							<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][3]"  type="checkbox" value="3" ><?php _e('Thursday', 'woocommerce-availability-scheduler'); ?></input>
							<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][4]"  type="checkbox" value="4" ><?php _e('Friday', 'woocommerce-availability-scheduler'); ?></input>
							<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][5]"  type="checkbox" value="5" ><?php _e('Saturday', 'woocommerce-availability-scheduler'); ?></input>
							<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][6]"  type="checkbox" value="6" ><?php _e('Sunday', 'woocommerce-availability-scheduler'); ?></input>
						
						</p>
						<p class="was-form-field">
							<label for="_was_period[<?php echo $day;?>]"><?php _e('Purchase period', 'woocommerce-availability-scheduler'); ?></label>
							<select name="_was_period[<?php echo $day;?>]" class="was_select_box" data-id="<?php echo $day;?>">
							  <option value="0" <?php if(isset($days[$day]) && $days[$day] == 0) echo 'selected'?>><?php _e('Throughout the day', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="1" <?php if(isset($days[$day]) && $days[$day] == 1) echo 'selected'?>><?php _e('Available during the selected time range', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="3" <?php if(isset($days[$day]) && $days[$day] == 3) echo 'selected'?>><?php _e('Unavailable during the selected time range', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="2" <?php if(isset($days[$day]) && $days[$day] == 2) echo 'selected'?>><?php _e('Disabled for this day', 'woocommerce-availability-scheduler'); ?></option>
							</select>
						</p>
						<p class="was-form-field ">
							<label for="_was_start_time[<?php echo $day;?>]"><?php _e('Start time', 'woocommerce-availability-scheduler'); ?></label>
							<input name="_was_start_time[<?php echo $day;?>]" class="wcas_start-time" id="time<?php echo ($day*2);?>" type="text" value="<?php if(isset($start_times[$day])) echo $start_times[$day]; else echo '08:00'; ?>" size="10" autocomplete="OFF" data-id="<?php echo $day*2;?>"disabled>
						</p>
						<p class="was-form-field ">	
							<label for="_was_end_time[<?php echo $day;?>]"><?php _e('End time', 'woocommerce-availability-scheduler'); ?></label>
							<input name="_was_end_time[<?php echo $day;?>]" class="wcas_end-time"   id="time<?php echo ($day*2)+1;?>" type="text" value="<?php if(isset($end_times[$day])) echo $end_times[$day]; else echo '09:00'; ?>" size="10" autocomplete="OFF" data-id="<?php echo ($day*2)+1;?>" disabled>
						</p>
						
						<!--<h3 class="was-title"><?php  _e('Customers (you can search typing name, last name or email)', 'woocommerce-customers-manager');?></h3>
						<select class="js-data-customers-ajax" id="was_customer_ids_<?php echo $day;?>" name="_was_customer_ids[<?php echo $day;?>][]" multiple='multiple'> 
						<?php 
							/*if(isset($customers_ids[$day]))
							{
								$customer_names_and_ids = $wccm_customer_model->get_customer_list($customers_ids[$day]);
								foreach($customer_names_and_ids as $customer_data)
								{
									echo '<option value="'.$customer_data->customer_id.'" selected="selected">'.$customer_data->first_name.' '.$customer_data->last_name.'</option>';
								}
							}*/
						?>
						</select>-->
						
						<h3 class="was-title"><?php  _e('Roles restriction', 'woocommerce-customers-manager');?></h3>
						<p><?php  _e('Apply the current availability rule to only a set of user roles or to all roles except the ones selected. ', 'woocommerce-customers-manager');?><br/>
							<?php  _e('If at least a role has been selected the product will be made unavailable for not logged users.', 'woocommerce-customers-manager');?></p>
						
						<select name="_was_customer_roles_restriction_type[<?php echo $day;?>]">
						<!--	<option value="no_restriction" <?php if(isset($customer_roles_restriction_type[$day]) && $customer_roles_restriction_type[$day] == "no_restriction") echo 'selected="selected"'; ?>><?php  _e('No restriction', 'woocommerce-customers-manager');?></option> -->
							<option value="apply_to_selected" <?php if(isset($customer_roles_restriction_type[$day]) && $customer_roles_restriction_type[$day] == "apply_to_selected") echo 'selected="selected"'; ?>><?php  _e('Apply to selected roles', 'woocommerce-customers-manager');?></option>
							<option value="except_the_selected" <?php if(isset($customer_roles_restriction_type[$day]) && $customer_roles_restriction_type[$day] == "except_the_selected") echo 'selected="selected"'; ?>><?php  _e('Apply to all except the selected roles', 'woocommerce-customers-manager');?></option>
						</select>
						<p><strong><?php  _e('Select roles (leave empty to apply rule to all logged and not logged users)', 'woocommerce-customers-manager');?></strong></p>
						<select class="js-role-select" name="_was_customer_roles[<?php echo $day;?>][]" multiple='multiple'> 
							<?php 
									//$first_time = !isset($options['_was_customer_roles']) ? true:false;
									foreach( $wp_roles->roles as $role_code => $role_data)
									{
										$selected = '';		
										if($role_code != 'administrator')
										{
											if(isset($allowed_roles[$day]))
												 foreach($allowed_roles[$day] as $role)
													if($role == $role_code)
															$selected = ' selected="selected" '; 
													
											echo '<option value="'.$role_code.'" '.$selected.'>'.$role_data['name'].'</option>';
										}
									}
									if(isset($allowed_roles[$day]))
										foreach($allowed_roles[$day] as $role)
											if($role == 'translate')
													$selected = ' selected="selected" '; 
								?>
						</select>
						<br/>
						<p><strong><?php _e('Message showed to non authorized users', 'woocommerce-availability-scheduler'); ?></strong></p>
						<textarea id="_was_customer_roles_restriction_message[<?php echo $day?>]"  placeholder="<?php _e('Type a message to show to non authorized roles', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_customer_roles_restriction_message[<?php echo $day;?>]"  ><?php if(isset($customer_roles_restriction_message) && isset($customer_roles_restriction_message[$day])) echo $customer_roles_restriction_message[$day]; ?></textarea>
						
								
						<h3 class="was-title" style="margin-top:25px;"><?php _e('Shop page messages', 'woocommerce-availability-scheduler'); ?></h3>
						<p class="was-form-field ">	
							<!-- <label for="_was_shop_page_msg[<?php echo $day;?>]"><?php _e('Shop page message', 'woocommerce-availability-scheduler'); ?><br/><small> <?php _e('(HTML code is allowed)', 'woocommerce-availability-scheduler'); ?></small></label>-->
							<p><strong>HTML code is accepted. You can also use [start_time] and [end_time] shortcodes to display start and end times directly in to the message</strong></p>
							<textarea id="shop_page_msg<?php echo $day?>" placeholder="<?php _e('Before sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_shop_page_msg[<?php echo $day;?>]" ><?php if(isset($shop_page_msg[$day])) echo $shop_page_msg[$day]; ?></textarea>
							<textarea id="shop_page_during_msg<?php echo $day?>" class="message_box_hidable" placeholder="<?php _e('During sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_shop_page_during_msg[<?php echo $day;?>]"  ><?php if(isset($shop_page_during_msg[$day])) echo $shop_page_during_msg[$day]; ?></textarea>
							<textarea id="shop_page_after_msg<?php echo $day?>" class="message_box_hidable" placeholder="<?php _e('After sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_shop_page_after_msg[<?php echo $day;?>]"  ><?php if(isset($shop_page_after_msg[$day])) echo $shop_page_after_msg[$day]; ?></textarea>
						</p>
						<!-- <p class="was-form-field checkbox-par">	
							<label for="_was_always_show_shop_message[<?php echo $day;?>]"><?php _e('By default shop message is shown only before start time and after end time. Check this box to always show it.', 'woocommerce-availability-scheduler'); ?></label>
							<input name="_was_always_show_shop_message[<?php echo $day;?>]" type="checkbox" value="1" <?php //if(isset($always_show_shop_message[$day])) echo "checked"; ?>>
						</p> -->
						<h3  class="was-title" style="margin-top:25px;"><?php _e('Product page messages', 'woocommerce-availability-scheduler'); ?></h3>
						<p class="was-form-field ">	
							<!--<label for="_was_product_page_msg[<?php echo $day;?>]"><?php _e('Product page message', 'woocommerce-availability-scheduler'); ?><br/><small> <?php _e('(HTML code is allowed)', 'woocommerce-availability-scheduler'); ?></small></label>-->
							<p><strong><?php _e('HTML code is accepted. You can also use [start_time] and [end_time] shortcodes to display start and end times directly in to the message', 'woocommerce-availability-scheduler'); ?>s</strong></p>
							<textarea id="product_page_msg<?php echo $day?>" placeholder="<?php _e('Before sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_product_page_msg[<?php echo $day;?>]"  ><?php if(isset($product_page_msg[$day])) echo $product_page_msg[$day]; ?></textarea>
							<textarea id="product_page_during_msg<?php echo $day?>" class="message_box_hidable" placeholder="<?php _e('During sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_product_page_during_msg[<?php echo $day;?>]"  ><?php if(isset($product_page_during_msg[$day])) echo $product_page_during_msg[$day]; ?></textarea>
							<textarea id="product_page_after_msg<?php echo $day?>" class="message_box_hidable" placeholder="<?php _e('After sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_product_page_after_msg[<?php echo $day;?>]"  ><?php if(isset($product_page_after_msg[$day])) echo $product_page_after_msg[$day]; ?></textarea>
						</p>
						<!--<p class="was-form-field checkbox-par">	
							<label for="_was_always_show_product_message[<?php echo $day;?>]"><?php _e('By default product message is showed only before start time and after end time. Check this box to always show it.', 'woocommerce-availability-scheduler'); ?></label>
							<input name="_was_always_show_product_message[<?php echo $day;?>]" type="checkbox" value="1" <?php //if(isset($always_show_product_message[$day])) echo "checked"; ?>>
						</p>-->
						<h3  class="was-title" style="margin-top:25px;"><?php _e('Hide product', 'woocommerce-availability-scheduler'); ?></h3>
						<p style="margin-top:50px;" class="was-form-field checkbox-par">	
							<label for="_was_hide[<?php echo $day;?>]"><?php _e('Hide product during unavailability period? (product will be setted as draft)', 'woocommerce-availability-scheduler'); ?></label>
							<input name="_was_hide[<?php echo $day;?>]" type="checkbox" value="1" <?php if(isset($was_hide[$day])) echo "checked"; ?>>
						</p>
						
						<h3  class="was-title" style="margin-top:80px;"><?php _e('Total sales limit', 'woocommerce-availability-scheduler'); ?></h3>
						<p style="margin-top:50px;" class="was-form-field checkbox-par">	
							<label style="height:90px;" for="_was_total_sales_limit[<?php echo $day;?>]"><?php _e('After the following value of total sales, the item will be unavaiable.', 'woocommerce-availability-scheduler'); ?><br/>
								<strong><?php _e('Leave 0 to disable this feature.', 'woocommerce-availability-scheduler'); ?></strong>
							</label>
							<input name="_was_total_sales_limit[<?php echo $day;?>]" type="number" value="<?php if(isset($was_total_sales_limit[$day]) && isset($was_total_sales_limit[$day])) echo $was_total_sales_limit[$day]; else echo 0; ?>" step="1" min="0"  required>
							<p><small><strong><?php _e('Note:', 'woocommerce-availability-scheduler'); ?></strong> <?php _e('Purchase will be disabled at sales limit reach even if the end time has not been reached in case of "Time range avaialability" period or it the "Throught the day" options has been selected.', 'woocommerce-availability-scheduler'); ?></small></p>
							</p>
						<p class="was-form-field" style="margin-top:40px;">
							<label for="_was_where_sales_progressbar[<?php echo $day;?>]"><?php _e('Would you like to display a progress bar display current day sales on total sale?', 'woocommerce-availability-scheduler'); ?></label>
							<select name="_was_where_sales_progressbar[<?php echo $day;?>]" class="was_select_box" >
							  <option value="0" <?php if(isset($where_sales_progressbar[$day]) && $where_sales_progressbar[$day] == 0) echo 'selected'?>><?php _e('No', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="1" <?php if(isset($where_sales_progressbar[$day]) && $where_sales_progressbar[$day] == 1) echo 'selected'?>><?php _e('Shop page', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="2" <?php if(isset($where_sales_progressbar[$day]) && $where_sales_progressbar[$day] == 2) echo 'selected'?>><?php _e('Product page', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="3" <?php if(isset($where_sales_progressbar[$day]) && $where_sales_progressbar[$day] == 3) echo 'selected'?>><?php _e('Both', 'woocommerce-availability-scheduler'); ?></option>
							</select>
						</p>
						
						
						<h3  class="was-title" style="margin-top:80px;"><?php _e('Countdown', 'woocommerce-availability-scheduler'); ?></h3>
						<p style="margin-top:50px;" class="was-form-field checkbox-par">	
							<label for="_was_countdown_to_start[<?php echo $day;?>]"><?php _e('Use countdown to start time?', 'woocommerce-availability-scheduler'); ?></label>
							<input name="_was_countdown_to_start[<?php echo $day;?>]" type="checkbox" value="1" <?php if(isset($countdown_to_start[$day])) echo "checked"; ?>>
						</p>
						<p class="was-form-field">
							<label for="_was_where_countdown_to_start[<?php echo $day;?>]"><?php _e('Where do you want to use start timer?', 'woocommerce-availability-scheduler'); ?></label>
							<select name="_was_where_countdown_to_start[<?php echo $day;?>]" class="was_select_box" >
							  <option value="0" <?php if(isset($where_countdown_to_start[$day]) && $where_countdown_to_start[$day] == 0) echo 'selected'?>><?php _e('Shop page', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="1" <?php if(isset($where_countdown_to_start[$day]) && $where_countdown_to_start[$day] == 1) echo 'selected'?>><?php _e('Product page', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="2" <?php if(isset($where_countdown_to_start[$day]) && $where_countdown_to_start[$day] == 2) echo 'selected'?>><?php _e('Both', 'woocommerce-availability-scheduler'); ?></option>
							</select>
						</p>
						<p><br/></p>
						<p class="was-form-field checkbox-par">	
							<label for="_was_countdown_to_end[<?php echo $day;?>]"><?php _e('Use countdown to end time?', 'woocommerce-availability-scheduler'); ?></label>
							<input name="_was_countdown_to_end[<?php echo $day;?>]" type="checkbox" value="1" <?php if(isset($countdown_to_end[$day])) echo "checked"; ?>>
						</p>
						<p class="was-form-field">
							<label for="_was_where_countdown_to_end[<?php echo $day;?>]"><?php _e('Where do you want to use end timer?', 'woocommerce-availability-scheduler'); ?></label>
							<select name="_was_where_countdown_to_end[<?php echo $day;?>]" class="was_select_box" >
							  <option value="0" <?php if(isset($where_countdown_to_end[$day]) && $where_countdown_to_end[$day] == 0) echo 'selected'?>><?php _e('Shop page', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="1" <?php if(isset($where_countdown_to_end[$day]) && $where_countdown_to_end[$day] == 1) echo 'selected'?>><?php _e('Product page', 'woocommerce-availability-scheduler'); ?></option>
							  <option value="2" <?php if(isset($where_countdown_to_end[$day]) && $where_countdown_to_end[$day] == 2) echo 'selected'?>><?php _e('Both', 'woocommerce-availability-scheduler'); ?></option>
							</select>
						</p>
						
						
				</div>
			<?php endfor; ?>
		</div>
		
		<!--<script>
			jQuery.ui = null;
		</script>
		<script type='text/javascript' src='<?php echo AS_PLUGIN_PATH.'/js/jquery-ui.js'; ?>'></script>-->
		
		<script>
		var was_before_message = '<?php _e('Before sale message', 'woocommerce-availability-scheduler'); ?>';
		var was_placeholder_message = '<?php _e('Message', 'woocommerce-availability-scheduler'); ?>';
        </script>
    
		<?php
	}
}
?>