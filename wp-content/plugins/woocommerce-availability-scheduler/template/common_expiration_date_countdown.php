<!-- Timer to expiration date -->
<?php 

$product_id = isset($post) ? $post->ID : $product->get_id();
if(isset($result['show_countdown_to_expiration_date']) && $result['show_countdown_to_expiration_date'] != "" && isset($result['expiring_date']) && $result['expiring_date'] != ""): ?>
<div class="wcas-countdown wcas-countdow-to-exiration-date">
	<span id="expiratin-date-clock<?php echo $product_id ?>"></span>
</div>
<script>
jQuery(document).ready(function()
{
	var serverTime = new Date('<?php echo date('Y/m/d H:i', strtotime($time_offset.' minutes'))?>');
	var endtime =  new Date('<?php echo $result['expiring_date']; ?>');
	var difference = <?php echo strtotime($result['expiring_date']) - strtotime(date('Y/m/d H:i:s', strtotime($time_offset.' minutes'))); ?>;
	if(difference >0)
	{
	 display = document.querySelector('#expiratin-date-clock<?php echo $product_id ?>');
	 //wcas_startTimer(difference, display, function(){setTimeout(function(){jQuery(this).parent().addClass('disabled'); location.reload();}, 5000);});
	 
	  //new
	  var end_date = new Date();
	 end_date.setSeconds(end_date.getSeconds() + difference);
	 jQuery('#expiratin-date-clock<?php echo $product_id ?>').countdown(wcsa_data_formatter(end_date), function(event) {
		  var $this = jQuery(this).html(event.strftime(''
			+ (event.offset.weeks > 0 ? '<span class="wcas_timer_date_value wcsa_weeks_value">%w</span> <span class="wcas_timer_date_label wcsa_weeks_label">'+wcas_weeks_string+'</span> ' : '')
			+ (event.offset.days > 0 ? '<span class="wcas_timer_date_value wcsa_days_value">%d</span> <span class="wcas_timer_date_label wcsa_days_label">'+wcas_days_string+'</span> ': '')
			+ (event.offset.hours > 0 ?'<span class="wcas_timer_date_value wcsa_hour_value">%H</span> <span class="wcas_timer_date_label wcsa_hour_label">'+wcas_hour_string+'</span> ': '')
			+ (event.offset.minutes > 0 ?'<span class="wcas_timer_date_value wcsa_minute_value">%M</span> <span class="wcas_timer_date_label wcsa_minute_label">'+wcas_min_string+'</span> ': '')
			+ '<span class="wcas_timer_date_value wcsa_second_value">%S</span> <span class="wcas_timer_date_label wcsa_second_label">'+wcas_sec_string+'</span>'));
		}).on('finish.countdown',function(event){setTimeout(function(){jQuery(this).parent().addClass('disabled'); location.reload();}, 5000);});
	}
});
</script>

<?php endif; ?>