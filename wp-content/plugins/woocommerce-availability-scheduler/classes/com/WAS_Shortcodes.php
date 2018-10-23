<?php 
class WAS_Shortcodes
{
	public function __construct()
	{
		add_shortcode( 'was_expiring_datetime', array(&$this, 'was_expiring_datetime' ));
		add_shortcode( 'was_start_time', array(&$this,'was_start_time' ));
		add_shortcode( 'was_end_time', array(&$this,'was_end_time' ));
	}
	
	public function was_expiring_datetime($atts)
	{
		$a = shortcode_atts( array(
        'id' => get_the_ID(),
			), $atts );
			
		if(!isset($a['id']))
			return "";
		
		global $was_product_model;
		$expiring_date = $was_product_model->get_product_date_info($a['id'], 'expiring_date');
		if($expiring_date != "" && isset($atts['format']))
		{
			$date = new DateTime($expiring_date);
			$expiring_date = $date->format($atts['format']);
		}
		
		ob_start();
		echo $expiring_date;
		return ob_get_clean();
	}
	public function was_start_time($atts)
	{
		$a = shortcode_atts( array(
        'id' => get_the_ID(),
			), $atts );
			
		if(!isset($a['id']))
			return "";
		
		global $was_product_model;
		
		ob_start();
		echo $was_product_model->get_product_date_info($a['id'], 'start_time');
		return ob_get_clean();
	}
	public function was_end_time($atts)
	{
		$a = shortcode_atts( array(
        'id' => get_the_ID(),
			), $atts );
			
		if(!isset($a['id']))
			return "";
		global $was_product_model;
		
		ob_start();
		echo $was_product_model->get_product_date_info($a['id'], 'end_time');
		return ob_get_clean();
	}
}
?>