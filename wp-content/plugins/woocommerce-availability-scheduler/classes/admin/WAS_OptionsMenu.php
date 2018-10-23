<?php 

class WAS_OptionsMenu
{

	function __construct() 
	{
	}

	static function get_option($option_name = null)
	{
		$options = get_option( 'was_options');
		$options = isset($options) ? $options: null;
		
		$result = null;
		if($option_name)
		{
			if(isset($options[$option_name]))
				$result = $options[$option_name];
			
			if($option_name == 'time_offset')
				$result = isset($result) ? $result : 0;
			elseif($option_name == 'sales_progress_bar_color')
				$result = isset($result) ? $result : '#d3d3d3';
			elseif($option_name == 'sales_progress_bar_background_color')
				$result = isset($result) ? $result : '#3d3d3d';
			elseif($option_name == 'hide_sales_progress_bar_label')
				$result = isset($result) ? $result : false;
			elseif($option_name == 'time_format')
				$result = isset($result) ? $result : "H:i";
		}
		else
			$result = $options;
		
		return $result;
	}
	public function render_page()
	{
		if(isset($_POST['was_options']))
			update_option('was_options', $_POST['was_options']);
		
		$time_offset = $this->get_option('time_offset');
		$sales_progress_bar_color = $this->get_option('sales_progress_bar_color');
		$time_format = $this->get_option('time_format');
		$sales_progress_bar_background_color = $this->get_option('sales_progress_bar_background_color');
		$hide_sales_progress_bar_label = $this->get_option('hide_sales_progress_bar_label');
		$post_types = get_post_types();
		//var_dump($options);
		
		wp_enqueue_style('was-options-menu', AS_PLUGIN_PATH.'/css/admin-option-menu.css');
		wp_enqueue_style( 'wp-color-picker' );
		
		wp_enqueue_script( 'wcas-colorpicker-js', AS_PLUGIN_PATH.'/js/was-colorpicker.js', array( 'wp-color-picker' ), false, true );
		wp_enqueue_script( 'wcas-admin-option-page', AS_PLUGIN_PATH.'/js/was-admin-option-page.js', array( 'jquery' ));
		?>
		<div id="icon-themes" class="icon32"><br></div>
		<script>
		var wcas_confirm_message = "<?php _e('Are you sure to reset all products availability?', 'woocommerce-availability-scheduler'); ?>";
		var wcas_wait_message = "<?php _e('Resetting, please wait...', 'woocommerce-availability-scheduler'); ?>";
		var wcas_completed_message = "<?php _e('Resetting process has been completed!', 'woocommerce-availability-scheduler'); ?>";
		var wcas_error_message = "<?php _e('Error, please retry.', 'woocommerce-availability-scheduler'); ?>";
		</script>
		<div class="wrap">
			<h2><?php _e('Availability scheduler options', 'woocommerce-availability-scheduler');?></h2>
			<div id="white-box">
				<form action="" method="post" class="options-container"> <!-- options.php -->
					<?php //settings_fields('was_options_group'); ?> 
					
					
					<h4 class="wccm_small_margin_top"><?php _e('Time offset & format', 'woocommerce-availability-scheduler');?></h4>
					<p><?php _e('Product availability is set using server time. Current server time is: ', 'woocommerce-availability-scheduler'); ?>
						<strong><?php echo date("l").", ".date("H:i"); ?></strong>
					</p>
					<p>
						<?php _e('Current server time with offeset is: ', 'woocommerce-availability-scheduler'); ?>
						<strong><?php echo date("l", strtotime($time_offset.' minutes')).", ".date("H:i", strtotime($time_offset.' minutes')); ?></strong>
					</p>
					<p>
						<label><?php _e('Minutes offset (Ex.: 60 = +1 hour, 90 = +1.30 hours, -120 = -2 hours) ', 'woocommerce-availability-scheduler'); ?></label><br/>
						<input type="number" name="was_options[time_offset]" min="-1440" max="1440" value="<?php echo $time_offset; ?>"></input>
					</p>
					<p>
						<label ><?php echo __( 'Start and End shortcode time format', 'woocommerce-availability-scheduler' ); ?></label><br/>
						<select name="was_options[time_format]">
							<option value="H:i" <?php selected( $time_format, "H:i" ); ?>><?php _e('24-hour format', 'woocommerce-availability-scheduler'); ?></option>
							<option  value="h:i" <?php selected( $time_format, "h:i" ); ?>><?php _e('12-hour format', 'woocommerce-availability-scheduler'); ?></option>
							<option  value="h:i a" <?php selected( $time_format, "h:i a" ); ?>><?php _e('12-hour format with am/pm', 'woocommerce-availability-scheduler'); ?></option>
						</select>
					</p>
						
					
					<h4><?php _e('Total sales progress bar style', 'woocommerce-availability-scheduler');?></h4>
					<p>
						<label ><?php echo __( 'Progress bar color', 'woocommerce-availability-scheduler' ); ?></label><br/>
						<input type="text" class="color-field" name="was_options[sales_progress_bar_color]" value="<?php if(isset($sales_progress_bar_color) && $sales_progress_bar_color != '') echo $sales_progress_bar_color; else echo '#d3d3d3'; ?>"></input>
					</p>
					<p>
						<label ><?php echo __( 'Progress bar background color', 'woocommerce-availability-scheduler' ); ?></label><br/>
						<input type="text" class="color-field" name="was_options[sales_progress_bar_background_color]" value="<?php if(isset($sales_progress_bar_background_color) && $sales_progress_bar_background_color != '') echo $sales_progress_bar_background_color; else echo '#3d3d3d'; ?>"></input>
					</p>
					<p>
						<label ><?php echo __( 'Hide progress bar label (by default under the progress bar is displayed a label like: "3 / 10". This label report current sales on total sales limit)', 'woocommerce-availability-scheduler' ); ?></label><br/>
						<input type="checkbox" name="was_options[hide_sales_progress_bar_label]" value="true" <?php if($hide_sales_progress_bar_label) echo 'checked="checked"'; ?>></input>
					</p>	
				
				
					<p class=""><!-- submit -->
						<input name="Submit" type="submit" class="button-primary save-option-button" id="save-button" value="<?php esc_attr_e('Save', 'woocommerce-availability-scheduler'); ?>" />
					</p>		
				</form>
				
				<div class="options-container">
					<h4 class="wccm_small_margin_top"><?php _e('Reset product availability settings', 'woocommerce-availability-scheduler');?></h4>
					
						<p >
							<label ><?php echo __( 'By pressing the follow button all product availability settings will be restored to the state they were before the plugin was used. Note that if a product was setted as draft due to expiring, if you wish to make it available again you have to manually restore its status to publish.', 'woocommerce-availability-scheduler' ); ?></label><br/>
							<span id="wcas-reset-result-box"></span>
						</p>
						<p class="">
							<button  class="button-primary save-option-button" id="reset-avaiability-button" ><?php esc_attr_e('Reset', 'woocommerce-availability-scheduler'); ?></button>
						</p>
				</div>
				
			</div>
		</div>
		<?php
		
	}	
		
}
?>