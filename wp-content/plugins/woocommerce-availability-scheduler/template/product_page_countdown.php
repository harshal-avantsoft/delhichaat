<?php
	$time_offset = WAS_OptionsMenu::get_option('time_offset');
	if( $current_period == "before" &&
		isset($result["countdown_to_start"][$today]) &&
		$result["countdown_to_start"][$today] == 1 && 
		$result["where_countdown_to_start"][$today] != 0):
?>
<div class="wcas-countdown">
	<span id="clock<?php echo $post->ID ?>"></span>
</div>
<script>
jQuery(document).ready(function()
{
	var serverTime = new Date('<?php echo date('Y/m/d H:i', strtotime($time_offset.' minutes'))?>');
	var endtime =  new Date('<?php echo date("Y/m/d", strtotime($time_offset.' minutes'))." ".$result["stat_time"][$today]?>');
	var difference = <?php echo strtotime(date("Y/m/d", strtotime($time_offset.' minutes'))." ".$result["stat_time"][$today].":00") - strtotime(date('Y/m/d H:i:s', strtotime($time_offset.' minutes'))); ?>;
	if(difference >0)
	{
	 display = document.querySelector('#clock<?php echo $post->ID ?>');
	 //wcas_startTimer(difference, display, function(){setTimeout(function(){jQuery(this).parent().addClass('disabled'); location.reload();}, 5000);});
	 
	  //new
	  var end_date = new Date();
	 end_date.setSeconds(end_date.getSeconds() + difference);
	 jQuery('#clock<?php echo $post->ID ?>').countdown(wcsa_data_formatter(end_date), function(event) {
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
<?php
	elseif( $current_period == "during" && 
			isset($result["countdown_to_end"][$today]) &&
		    $result["countdown_to_end"][$today] == 1 && 
			$result["where_countdown_to_end"][$today] != 0 ):
?>

<div class="wcas-countdown">
	<span id="clock<?php echo $post->ID ?>"></span>
</div>
<script>
jQuery(document).ready(function()
{
	var serverTime = new Date('<?php echo date('Y/m/d H:i', strtotime($time_offset.' minutes'))?>');
	var endtime =  new Date('<?php echo date("Y/m/d", strtotime($time_offset.' minutes'))." ".$result["end_time"][$today]?>');
	var difference = <?php echo strtotime(date("Y/m/d", strtotime($time_offset.' minutes'))." ".$result["end_time"][$today].":00") - strtotime(date('Y/m/d H:i:s', strtotime($time_offset.' minutes'))); ?>;
	if(difference >0)
	{
	 display = document.querySelector('#clock<?php echo $post->ID ?>');
	 //wcas_startTimer(difference, display, function(){setTimeout(function(){jQuery(this).parent().addClass('disabled'); location.reload();}, 5000);});
	 
	  //new
	  var end_date = new Date();
	 end_date.setSeconds(end_date.getSeconds() + difference);
	 jQuery('#clock<?php echo $post->ID ?>').countdown(wcsa_data_formatter(end_date), function(event) {
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