jQuery(document).ready(function()
{
	jQuery(document).on('click', '#reset-avaiability-button', wcas_reset_products_avaiability);
});
function wcas_reset_products_avaiability()
{
	if(!window.confirm(wcas_confirm_message))
	{
		jQuery('#wcas-reset-result-box').html("");
		return false;
	}
	
	jQuery('#wcas-reset-result-box').html(wcas_wait_message);
	
	var random = Math.floor((Math.random() * 1000000) + 999);
	var formData = new FormData();
	formData.append('action', 'wcas_reset_products_avaiability'); 
	
	jQuery.ajax({
		url: ajaxurl +"?nocache="+random,
		type: 'POST',
		data: formData,
		async: true,
		success: function (data) 
		{
			//UI	
			jQuery('#wcas-reset-result-box').html(wcas_completed_message);
						
		},
		error: function (data) 
		{
			jQuery('#wcas-reset-result-box').html(wcas_error_message);
		},
		cache: false,
		contentType: false,
		processData: false
	});
	
	return false;
}