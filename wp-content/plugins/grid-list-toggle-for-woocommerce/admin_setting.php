<?php
if ( ! defined( 'ABSPATH' ) )
{
	exit;   
} // Exit if accessed directly

if ( ! empty( $_POST ) && check_admin_referer( 'phoen_wgl_nonce_action', 'phoen_wgl_nonce_input_field' ) )
{

	$check_gl = isset($_POST['check_gl'])?sanitize_text_field($_POST['check_gl']):'';
	
	$choose_grid_list = isset($_POST['choose_grid_list'])?sanitize_text_field($_POST['choose_grid_list']):'';
	
	$grid_list_setting_data = array('check_gl' => $check_gl,'choose_grid_list' => $choose_grid_list);
	
	$query_check = update_option('grid_list_view_data', $grid_list_setting_data );

	
	if($query_check == 1)
	{
	?>
		<div class="updated" id="message">

			<p><strong><?php _e('Setting updated.', 'woocommerce-grid-list-toggle'); ?></strong></p>

		</div>

	<?php
	}
	else
	{
		?>
			<div class="error below-h2" id="message"><p><?php _e('Something Went Wrong Please Try Again With Valid Data.', 'woocommerce-grid-list-toggle'); ?> </p></div>
		<?php
	}
}

$data = get_option('grid_list_view_data');

if(!empty($data))
{
	extract($data);
}
 
 
?>

<div id="profile-page" class="wrap">
<?php

if( isset( $_GET['tab'] ) ) {
	
	$tab = sanitize_text_field( $_GET['tab'] );
	
}
else
{
	$tab = '';
}

?>
<h2>
	<?php _e('Grid/List Plugin  Options', 'woocommerce-grid-list-toggle'); ?> 
</h2>
	
	<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			
		<a class="nav-tab <?php if($tab == 'general' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=gridlist_toggle_setting&amp;tab=general"><?php _e('Setting', 'woocommerce-grid-list-toggle'); ?></a>
		<a class="nav-tab <?php if($tab == 'premium'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=gridlist_toggle_setting&amp;tab=premium"><?php _e('Premium', 'woocommerce-grid-list-toggle'); ?></a>
		
	</h2>

<form novalidate="novalidate" method="post" action="" >
<?php 
if($tab == 'general' || $tab == '')
{
	
?>

<style>
.phoe_video_main {
	padding: 20px;
	text-align: center;
}

.phoe_video_main h3 {
	color: #02c277;
	font-size: 28px;
	font-weight: bolder;
	margin: 20px 0;
	text-transform: capitalize
	display: inline-block;
}
</style>

<div class="phoe_video_main">
	<h3><?php _e('How to set up plugin', 'woocommerce-grid-list-toggle'); ?></h3> 
	<iframe width="800" height="360"src="https://www.youtube.com/embed/GoYtjMAORc4" allowfullscreen></iframe>
</div>

<table class="form-table">

	<tbody>

		<h3 class="setting-title"><?php _e('General Options', 'woocommerce-grid-list-toggle'); ?></h3>
		
		<tr class="user-nickname-wrap">

			<th><label for="check_gl"><?php _e('Enable Grid/List', 'woocommerce-grid-list-toggle'); ?></label></th>

			<td><input type="checkbox" value="enable" <?php if($check_gl == 'enable'){ echo "checked"; } ?> id="check_gl" name="check_gl" ></td>

		</tr>
		
		<tr class="user-user-login-wrap">

			<th><label for="choose_grid_list"><?php _e('Choose Option', 'woocommerce-grid-list-toggle'); ?></label></th>

			<td>
			
				<select name="choose_grid_list" >
					
					<option <?php if($choose_grid_list == 'phoen_grid'){ echo "selected"; } ?> value="phoen_grid"><?php _e('Grid', 'woocommerce-grid-list-toggle'); ?></option>
					
					<option <?php if($choose_grid_list == 'phoen_list'){ echo "selected"; } ?> value="phoen_list"><?php _e('List', 'woocommerce-grid-list-toggle'); ?></option>
				
				</select>
				
			</td>

		</tr>
		
		<tr class="user-user-login-wrap">
			
			<td>
			
				<?php echo wp_nonce_field( 'phoen_wgl_nonce_action', 'phoen_wgl_nonce_input_field' );?>
			
			</td>

		</tr>
		
	</tbody>	

</table>

<p class="submit"><input type="submit" value="Save changes" class="button button-primary" id="submit" name="submit"></p>
	
<?php
} 

?>

</form>
<?php 

if($tab == 'premium')
{ 
	
	require_once(dirname(__FILE__).'/phoen_premium.php');
}
	?>
</div>

<style>
.form-table th {
    width: 270px;
	padding: 25px;
}
.form-table td {
	
    padding: 20px 10px;
}
.form-table {
	background-color: #fff;
}
h3 {
    padding: 10px;
}
.px-multiply{ color:#ccc; vertical-align:bottom;}

.long{ display:inline-block; vertical-align:middle; }

.wid{ display:inline-block; vertical-align:middle; }

.up{ display:block;}

.grey{ color:#b0adad;}
</style>