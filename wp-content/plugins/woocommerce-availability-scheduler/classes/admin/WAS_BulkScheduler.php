<?php class WAS_BulkScheduler
{
	var $current_lang;
	var $messages;
	public function __construct()
	{
	}
	private function save_data()
	{
		 if(!wp_verify_nonce($_REQUEST['wcas_bulkschedule'], 'wcas_action'))
			 return;
		 
		  global $was_product_model;
		 /* echo "<pre>";
		 echo var_dump($_POST['was_selected_categories']);
		 echo "</pre>"; */
		 $this->messages = array();
		 $counter = 0;
		 if(isset($_POST['was_selected_categories']))
			 foreach($_POST['was_selected_categories']['categories'] as $category)
			 {
				 /* $categories_ids = array();
				 array_push($categories_ids, $category); */
				 $include_children = false;
				 if($_POST['_was_assignment_type'] == 'categories_children')
				 {
					/*  $termchildren = get_term_children($category, 'product_cat');
					 foreach ( $termchildren as $child ) 
						  array_push($categories_ids, $child); */
					$include_children = true;
				 }				 
				$args = array(
					'posts_per_page'   => -1,
					'offset'           => 0,
					'post_type'        => 'product',
					'post_status'      => 'any',//'published',
					'suppress_filters' => true,
					'fields' => 'ids',
					'tax_query' => array(
						array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'include_children' => $include_children,
						'terms' => $category //implode(",",$categories_ids)
					) )
				);
				$products = get_posts( $args );
				//wcas_var_dump($products);
				
				foreach($products as $post_id)
				{
					$counter++;
					
					$was_product_model->save_product_meta($post_id);
					
				}
				
				
			 }
			 array_push( $this->messages, sprintf(__('%d product(s) have been updated!', 'woocommerce-availability-scheduler'),  $counter ));
	}
	public function render_page()
	{
		global $wp_roles;
		
		if(isset($_REQUEST['wcas_bulkschedule']))
			$this->save_data();
		
		$time_offset = WAS_OptionsMenu::get_option('time_offset');
		wp_enqueue_style( 'was-select2-style', AS_PLUGIN_PATH.'/css/select2.min.css' ); 
		wp_enqueue_style( 'was-bulkscheduler', AS_PLUGIN_PATH.'/css/was-bulkscheduler.css' ); 
		wp_enqueue_style( 'was-timepicker-css', AS_PLUGIN_PATH.'/css/timePicker.css' );		
		wp_enqueue_style('was-timepicker' , AS_PLUGIN_PATH. '/css/jquery-ui-timepicker-addon.css'); 
		wp_enqueue_style('was-jquery-ui' , AS_PLUGIN_PATH. '/css/jquery-ui.css');		
		wp_enqueue_style( 'was-admin', AS_PLUGIN_PATH.'/css/admin-availability-box.css' );
		
		//https://developer.wordpress.org/reference/functions/wp_enqueue_script/
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-jquery-ui-slider');
		wp_enqueue_script( 'was-select2-script', AS_PLUGIN_PATH.'/js/select2.min.js', array('jquery') );
		wp_enqueue_script('was-timepicker-js', AS_PLUGIN_PATH.'/js/jquery.timePicker.js', array('jquery'));  //adds datetime controls
		wp_enqueue_script('was-ui-js', AS_PLUGIN_PATH.'/js/jquery-ui.js', array('jquery'));  		
		wp_enqueue_script('was-jquery-time-picker' ,  AS_PLUGIN_PATH. '/js/jquery-ui-timepicker-addon.js',  array('jquery' ));
		wp_enqueue_script('was-admin-bulk-availability', AS_PLUGIN_PATH.'/js/was-admin-bulk-availability.js', array('jquery')); 	
		wp_enqueue_script('was-admin-availability-box' ,  AS_PLUGIN_PATH. '/js/was-admin-availability-box.js',  array('jquery' ));		
		//wp_enqueue_script('was-admin-customer-auto-complete', AS_PLUGIN_PATH.'/js/was-admin-discover-customer-autocomplete.js', array('jquery')); 		
		?>
		<!-- <link rel='stylesheet' id='jquery-ui-style-css'  href='//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css?ver=2.1.12' type='text/css' media='all' />-->
		<div id="wpbody">
		<h1><?php  _e('Bulk availability scheduler', 'woocommerce-availability-scheduler');?>  </h1>
		
		<?php
		if ( ! empty( $this->messages ) ) {
				foreach ( $this->messages as $msg )
					echo '<div id="message" class="updated"><p>' . $msg . '</p></div>';
			} ?>
			
		<div id="white-box-wrapper">
		<h3 class="was-title"><?php _e('Current server date', 'woocommerce-availability-scheduler'); ?></h3>
		<p>
			<?php _e('Product availability is set using server time. Current server time is: ', 'woocommerce-availability-scheduler'); ?>
			<strong><?php echo date("l", strtotime($time_offset.' minutes')).", ".date("H:i", strtotime($time_offset.' minutes')); ?></strong> ( <?php echo $time_offset.' '.__('minute(s) offset. To change offset value, go to Bulk availability -> options menu', 'woocommerce-availability-scheduler');?> )
		</p>
				
			<form method="post">
			<?php wp_nonce_field('wcas_action', 'wcas_bulkschedule'); ?>
			
			
				<div class="was_categories_box" >
					<h3 class="was-title" ><?php  _e('Select product categories', 'woocommerce-availability-scheduler');?>  </h3>
					<p>
					<?php  _e('Availability rules will be applied to all products belonging to the selected categories (and its children, according to the filtering "Apply for..." option below)', 'woocommerce-availability-scheduler');?>
						<?php  
							$this->WCAS_switch_to_default_lang();
							$select_cats = wp_dropdown_categories( array( 'echo' => 0, 'hide_empty' => 0, 'taxonomy' => 'product_cat', 'hierarchical' => 1) );
							$this->WCAS_restore_current_lang();
							
							// var_dump($select_cats);
							$select_cats = str_replace( "name='cat' id='cat' class='postform'", "style='width:200px;' name='was_selected_categories[categories][]' class='js-multiple' multiple='multiple' ", $select_cats ); 
							 echo $select_cats; 
							/* wp_dropdown_categories( array( 'class' => 'js-multiple',  'multiple' =>'multiple') );*/
							 ?>
							 <br/>
							 </br>
							 <label style="margin-top:20px;"><?php _e('Apply to products belonging to', 'woocommerce-availability-scheduler');?></label>
								<br/>
								<select  class="upload_type" data-id="0" name="_was_assignment_type">
								  <!--<option value="always" selected><?php _e('Enabled for every product', 'woocommerce-availability-scheduler');?></option> -->
								  <option value="categories" ><?php _e('Selected categories', 'woocommerce-availability-scheduler');?></option>
								  <option value="categories_children" ><?php _e('Selected categories and all its children', 'woocommerce-availability-scheduler');?></option>
								</select>
					</p>
				</div>
				
				<div id="divider"></div>
				<h3 class="was-title"><?php _e('Expiring date', 'woocommerce-availability-scheduler'); ?></h3>
				<p>
				<?php _e('The product will be no longer available after this date (will be set as \'draft\'). Leave empty if product hasn\'t an expiration date.', 'woocommerce-availability-scheduler'); ?>
				<br/><br/>
					<input type="text" name="_was_expiring_date" class="expiring-date" value=""></input>
				</p>
				<p style="margin-top:20px; margin-bottom:20px;" class="was-form-field checkbox-par">	
					<label for="_was_visible_after_expiring_date"><?php _e('Leave the product visible (but unpurcasable) after expring date?', 'woocommerce-availability-scheduler'); ?></label>
					<input name="_was_visible_after_expiring_date" type="checkbox" value="1" >
				</p>
				<p style="margin-top:20px; margin-bottom:20px;" class="was-form-field checkbox-par">	
					<label for="_was_show_countdown_to_expiration_date"><?php _e('Show countdown to expiration date?', 'woocommerce-availability-scheduler'); ?></label>
					<input name="_was_show_countdown_to_expiration_date" type="checkbox" value="1" >
					
				</p>
				<h4><?php _e('Messages before product expiration ','woocommerce-availability-scheduler'); ?></h4>
				<p style="display:block; margin-top: 10px;">
					<label><?php _e('Use the following text area to display a message in the Shop (first box) and Product (second box) pages after the product exires (if still visible).', 'woocommerce-availability-scheduler'); ?></label><br/>
					<textarea placeholder="<?php _e('Shop message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_before_expiration_shop_message"  ></textarea>
					<textarea placeholder="<?php _e('Product message'); ?>" rows="5" cols="30" name="_was_before_expiration_product_message"  ></textarea>
				</p>
				<h4><?php _e('Messages after product expiration (if still visible)','woocommerce-availability-scheduler'); ?></h4>
				<p style="display:block; margin-top:10px;">
					<label><?php _e('Use the following text area to display a message in the Shop (first box) and Product (second box) .', 'woocommerce-availability-scheduler'); ?></label><br/>
					<textarea placeholder="<?php _e('Shop message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_expired_shop_message" ></textarea>
					<textarea placeholder="<?php _e('Product message'); ?>" rows="5" cols="30" name="_was_expired_product_message" ></textarea>
				</p>
				
				<div class="spacer"></div>
				
				
				<div id="tabs">
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
									
									<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][0]"  type="checkbox" value="true"><?php _e('Monday', 'woocommerce-availability-scheduler'); ?></input>
									<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][1]"  type="checkbox" value="true"><?php _e('Tuesday', 'woocommerce-availability-scheduler'); ?></input>
									<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][2]"  type="checkbox" value="true"><?php _e('Wednesday', 'woocommerce-availability-scheduler'); ?></input>
									<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][3]"  type="checkbox" value="true"><?php _e('Thursday', 'woocommerce-availability-scheduler'); ?></input>
									<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][4]"  type="checkbox" value="true"><?php _e('Friday', 'woocommerce-availability-scheduler'); ?></input>
									<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][5]"  type="checkbox" value="true"><?php _e('Saturday', 'woocommerce-availability-scheduler'); ?></input>
									<input name="_was_assign_this_configuration_to_days[<?php echo $day; ?>][6]"  type="checkbox" value="true"><?php _e('Sunday', 'woocommerce-availability-scheduler'); ?></input>
								
								</p>
						
								<p class="was-form-field">
									<label for="_was_period[<?php echo $day;?>]"><?php _e('Purchase period', 'woocommerce-availability-scheduler'); ?></label>
									<select name="_was_period[<?php echo $day;?>]" class="was_select_box" data-id="<?php echo $day;?>">
									  <option value="0"><?php _e('Throughout the day', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="1"><?php _e('Available during the selected time range', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="3"><?php _e('Unavailable during the selected range', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="2"><?php _e('Disabled for this day', 'woocommerce-availability-scheduler'); ?></option>
									</select>
								</p>
								<p class="was-form-field ">
									<label for="_was_start_time[<?php echo $day;?>]"><?php _e('Start time', 'woocommerce-availability-scheduler'); ?></label>
									<input name="_was_start_time[<?php echo $day;?>]" class="start-time" id="time<?php echo ($day*2);?>" type="text" value="08:00" size="10" autocomplete="OFF" data-id="<?php echo $day*2;?>"disabled>
								</p>
								<p class="was-form-field ">	
									<label for="_was_end_time[<?php echo $day;?>]"><?php _e('End time', 'woocommerce-availability-scheduler'); ?></label>
									<input name="_was_end_time[<?php echo $day;?>]" class="end-time"   id="time<?php echo ($day*2)+1;?>" type="text" value="09:00" size="10" autocomplete="OFF" data-id="<?php echo ($day*2)+1;?>" disabled>
								</p>
								
								<!--<h3 class="was-title"><?php  _e('Customers (you can search typing name, last name or email)', 'woocommerce-customers-manager');?></h3>
								<select class="js-data-customers-ajax" id="was_customer_ids_<?php echo $day;?>" name="_was_customer_ids[<?php echo $day;?>][]" multiple='multiple'> 
								</select>-->
								
								<h3 class="was-title"><?php  _e('Roles restriction', 'woocommerce-customers-manager');?></h3>
								<p><?php  _e('Apply the current availability rule to only a set of user roles or to all roles except the ones selected.', 'woocommerce-customers-manager');?><br/>
								<?php // _e('If at least a role has been selected the product will be made unavailable for not logged users.', 'woocommerce-customers-manager');?></p>
								<select name="_was_customer_roles_restriction_type[<?php echo $day;?>]">
									<!-- <option value="no_restriction" ><?php  _e('No restriction', 'woocommerce-customers-manager');?></option>-->
									<option value="apply_to_selected" ><?php  _e('Apply to selected roles', 'woocommerce-customers-manager');?></option>
									<option value="except_the_selected" ><?php  _e('Apply to all except the selected roles', 'woocommerce-customers-manager');?></option>
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
													/* foreach($options['allowed_roles'] as $role)
														if($role == $role_code)
																$selected = ' selected="selected" '; */
															
													echo '<option value="'.$role_code.'" '.$selected.'>'.$role_data['name'].'</option>';
												}
											}
											/* foreach($options['allowed_roles'] as $role)
												if($role == 'translate')
														$selected = ' selected="selected" '; */
										?>
										</select>
								<br/>
								<p><strong><?php _e('Message showed to non authorized users', 'woocommerce-availability-scheduler'); ?></strong></p>
								<textarea id="_was_customer_roles_restriction_message[<?php echo $day?>]"  placeholder="<?php _e('Type a message to show to non authorized roles', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_customer_roles_restriction_message[<?php echo $day;?>]"  ></textarea>
								
								
								<h3 class="was-title" style="margin-top:25px;"><?php _e('Shop page messages', 'woocommerce-availability-scheduler'); ?></h3>
								<p class="was-form-field ">	
									<p><strong><?php _e('HTML code is accepted. You can also use [start_time] and [end_time] shortcodes to display start and end times directly in to the message', 'woocommerce-availability-scheduler'); ?></strong></p>
									<textarea id="shop_page_msg<?php echo $day?>" placeholder="<?php _e('Before sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_shop_page_msg[<?php echo $day;?>]" ></textarea>
									<textarea id="shop_page_during_msg<?php echo $day?>" class="message_box_hidable" placeholder="<?php _e('During sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_shop_page_during_msg[<?php echo $day;?>]"  ></textarea>
									<textarea id="shop_page_after_msg<?php echo $day?>" class="message_box_hidable" placeholder="<?php _e('After sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_shop_page_after_msg[<?php echo $day;?>]"  ></textarea>
								</p>
								<h3 class="was-title" style="margin-top:25px;"><?php _e('Product page messages', 'woocommerce-availability-scheduler'); ?></h3>
								<p class="was-form-field ">	
									<p><strong>HTML code is accepted. You can also use [start_time] and [end_time] shortcodes to display start and end times directly in to the message</strong></p>
									<textarea id="product_page_msg<?php echo $day?>" placeholder="<?php _e('Before sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_product_page_msg[<?php echo $day;?>]"  ></textarea>
									<textarea id="product_page_during_msg<?php echo $day?>" class="message_box_hidable" placeholder="<?php _e('During sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_product_page_during_msg[<?php echo $day;?>]"  ></textarea>
									<textarea id="product_page_after_msg<?php echo $day?>" class="message_box_hidable" placeholder="<?php _e('After sale message', 'woocommerce-availability-scheduler'); ?>" rows="5" cols="30" name="_was_product_page_after_msg[<?php echo $day;?>]"  ></textarea>
								</p>
								<h3  class="was-title" style="margin-top:25px;"><?php _e('Hide product', 'woocommerce-availability-scheduler'); ?></h3>
								<p style="margin-top:50px;" class="was-form-field checkbox-par">	
									<label for="_was_hide[<?php echo $day;?>]"><?php _e('Hide product during unavailability period? (product will be setted as draft)', 'woocommerce-availability-scheduler'); ?></label>
									<input name="_was_hide[<?php echo $day;?>]" type="checkbox" value="1" >
								</p>
								
								<h3  class="was-title" style="margin-top:80px;"><?php _e('Total sales limit', 'woocommerce-availability-scheduler'); ?></h3>
									<p style="margin-top:50px;" class="was-form-field checkbox-par">	
										<label style="height:90px;" for="_was_total_sales_limit[<?php echo $day;?>]"><?php _e('After the following value of total sales, the item will be unavaiable.', 'woocommerce-availability-scheduler'); ?><br/>
										<strong><?php _e('Leave 0 to disable this feature.', 'woocommerce-availability-scheduler'); ?></strong>
									</label>
									<input name="_was_total_sales_limit[<?php echo $day;?>]" type="number" value="0" step="1" min="0"  required>
									<p><small><strong><?php _e('Note:', 'woocommerce-availability-scheduler'); ?></strong> <?php _e('Purchase will be disabled at sales limit reach even if the end time has not been reached in case of "Time range avaialability" period or it the "Throught the day" options has been selected.', 'woocommerce-availability-scheduler'); ?></small></p>
								</p>
								<p class="was-form-field" style="margin-top:40px;">
									<label for="_was_where_sales_progressbar[<?php echo $day;?>]"><?php _e('Would you like to display a progress bar display current day sales on total sale?', 'woocommerce-availability-scheduler'); ?></label>
									<select name="_was_where_sales_progressbar[<?php echo $day;?>]" class="was_select_box" >
									  <option value="0" ><?php _e('No', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="1" ><?php _e('Shop page', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="2" ><?php _e('Product page', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="3" ><?php _e('Both', 'woocommerce-availability-scheduler'); ?></option>
									</select>
								</p>
								
								<h3  class="was-title" style="margin-top:80px;"><?php _e('Countdown', 'woocommerce-availability-scheduler'); ?></h3>
								<p style="margin-top:50px;" class="was-form-field checkbox-par">	
									<label for="_was_countdown_to_start[<?php echo $day;?>]"><?php _e('Use countdown to start time?', 'woocommerce-availability-scheduler'); ?></label>
									<input name="_was_countdown_to_start[<?php echo $day;?>]" type="checkbox" value="1">
								</p>
								<p class="was-form-field">
									<label for="_was_where_countdown_to_start[<?php echo $day;?>]"><?php _e('Where do you want to use start timer?', 'woocommerce-availability-scheduler'); ?></label>
									<select name="_was_where_countdown_to_start[<?php echo $day;?>]" class="was_select_box" >
									  <option value="0" ><?php _e('Shop page', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="1" ><?php _e('Product page', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="2" ><?php _e('Both', 'woocommerce-availability-scheduler'); ?></option>
									</select>
								</p>
								<p><br/></p>
								<p class="was-form-field checkbox-par">	
									<label for="_was_countdown_to_end[<?php echo $day;?>]"><?php _e('Use countdown to end time?', 'woocommerce-availability-scheduler'); ?></label>
									<input name="_was_countdown_to_end[<?php echo $day;?>]" type="checkbox" value="1" >
								</p>
								<p class="was-form-field">
									<label for="_was_where_countdown_to_end[<?php echo $day;?>]"><?php _e('Where do you want to use end timer?', 'woocommerce-availability-scheduler'); ?></label>
									<select name="_was_where_countdown_to_end[<?php echo $day;?>]" class="was_select_box" >
									  <option value="0" ><?php _e('Shop page', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="1" ><?php _e('Product page', 'woocommerce-availability-scheduler'); ?></option>
									  <option value="2" ><?php _e('Both', 'woocommerce-availability-scheduler'); ?></option>
									</select>
								</p>							
						</div>
					<?php endfor; ?>
				</div>
				<input type="submit" style="margin-top:35px;" class="button button-primary" value="<?php _e('Save', 'woocommerce-availability-scheduler'); ?>"></input>
			</form>
		</div>
		</div>
		<script>
		var was_before_message = '<?php _e('Before sale message', 'woocommerce-availability-scheduler'); ?>';
		var was_placeholder_message = '<?php _e('Message', 'woocommerce-availability-scheduler'); ?>';
		
		</script>
		<?php
	}
	public function WCAS_switch_to_default_lang()
	{
		if(defined("ICL_LANGUAGE_CODE") && ICL_LANGUAGE_CODE != null)
		{
			global $sitepress;
			$this->current_lang = ICL_LANGUAGE_CODE;
			$sitepress->switch_lang($sitepress->get_default_language());
		}
	}
	public function WCAS_restore_current_lang()
	{
		if(defined("ICL_LANGUAGE_CODE") && ICL_LANGUAGE_CODE != null)
		{
			global $sitepress;
			$sitepress->switch_lang($this->current_lang);
		}
	}
}?>