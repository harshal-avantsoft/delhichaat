jQuery(document).ready(function()
{
	//jQuery(document).on('click', '.was_assign_this_configuration_for_all_days', was_manage_assign_day_configration_to_all_days_checkbox);
});
function was_manage_assign_day_configration_to_all_days_checkbox()
{
  checkedState = jQuery(this).attr('checked');
  jQuery('.was_assign_this_configuration_for_all_days').each(function(index, elem)
  {
	  jQuery(this).attr('checked', false);
  });
  
  jQuery(this).attr('checked', checkedState);

}