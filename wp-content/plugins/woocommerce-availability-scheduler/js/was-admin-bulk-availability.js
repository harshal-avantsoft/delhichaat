 jQuery(document).ready(function()
		{
				jQuery(".js-multiple").select2({'width':500});
				jQuery(".js-role-select").select2({'width':500});
				jQuery( "#tabs" ).tabs();
				jQuery(".start-time, .end-time").timePicker({step:10, endTime:"23:59"});
				jQuery(".expiring-date").datetimepicker();
				jQuery(".was_select_box").on("change",checkPeriodRange); 
				
				jQuery.each(jQuery(".was_select_box"), function(index, object)
				{
					jQuery(this).trigger("change");
				});
				
				
				function checkPeriodRange(event)
				{
					var id = jQuery(this).data('id');
					var time1 = jQuery(this).data('id')*2;
					var time2 = (jQuery(this).data('id')*2)+1;
					if(jQuery(this).val()==1 || jQuery(this).val() == 3)
					{
						
						jQuery("#time"+time1).removeAttr('disabled');
						jQuery("#time"+time2).removeAttr('disabled');
						jQuery('#shop_page_during_msg'+id).show();
						jQuery('#shop_page_after_msg'+id).show();
						jQuery('#product_page_during_msg'+id).show();
						jQuery('#product_page_after_msg'+id).show();
						jQuery('#product_page_msg'+id).attr('placeholder', was_before_message);
						jQuery('#shop_page_msg'+id).attr('placeholder', was_before_message);
					}
					else
					{
						jQuery("#time"+time1).attr('disabled','disabled');
						jQuery("#time"+time2).attr('disabled','disabled');
						jQuery('#shop_page_during_msg'+id).hide();
						jQuery('#shop_page_after_msg'+id).hide();
						jQuery('#product_page_during_msg'+id).hide();
						jQuery('#product_page_after_msg'+id).hide();
						jQuery('#product_page_msg'+id).attr('placeholder', was_placeholder_message);
						jQuery('#shop_page_msg'+id).attr('placeholder', was_placeholder_message);
					}
					
					
				}
    
				// Store time used by duration.
				var oldTime = {};
				jQuery.each(jQuery(".start-time"), function(i,j)
				{
					oldTime[jQuery(this).data('id')] = jQuery.timePicker("#time"+i).getTime();
				});

				// Keep the duration between the two inputs.
				jQuery(".start-time").change(function() {
				  if (jQuery(this).val()) { // Only update when second input has a value.
					// Calculate duration.
					var currentId = jQuery(this).data("id");
					var nexId = currentId+1;
					var duration = (jQuery.timePicker("#time"+nexId).getTime() - oldTime[jQuery(this).data('id')]);
					var time = jQuery.timePicker("#time"+currentId).getTime();
					// Calculate and update the time in the second input.
					jQuery.timePicker("#time"+nexId).setTime(new Date(new Date(time.getTime() + duration)));
					oldTime = time;
				  }
				});
				// Validate.
				jQuery(".end-time").change(function() 
				{
					var currentId = jQuery(this).data("id");
					var prevId = currentId-1;
				  if(jQuery.timePicker("#time"+prevId).getTime() > jQuery.timePicker(this).getTime()) 
				  {
					//jQuery(this).addClass("error");
					jQuery.timePicker("#time"+currentId).setTime(jQuery.timePicker("#time"+prevId).getTime());
				  }
				  else {
					//jQuery(this).removeClass("error");
				  }
				});
		}); 