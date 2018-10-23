jQuery(document).ready(function()
{
	//for(var i=0; i<7;i++)
		jQuery(".js-data-customers-ajax").select2(
		//jQuery("#was_customer_ids_"+i).select2(
		{
		  width:500,
		 /*  ajax: {
			url: ajaxurl,
			dataType: 'json',
			delay: 250,
			multiple: true,
			data: function (params) {
			  return {
				customer: params.term, // search term
				page: params.page,
				action: 'was_get_customers_list'
			  };
			},
			processResults: function (data, page) 
			{
		   
			   return {
				results: jQuery.map(data, function(obj) {
					return { id: obj.customer_id, text: obj.first_name+" "+obj.last_name+" ("+obj.email+")" };
				})
				};
			},
			cache: true
		  }, */
		  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		  minimumInputLength: 3,
		  templateResult: was_formatRepo, 
		  templateSelection: was_formatRepoSelection  
		}
		);

});


function was_formatRepo (repo) 
{
	if (repo.loading) return repo.text;
	
	var markup = '<div class="clearfix">' +
			'<div class="col-sm-12">' + repo.text + '</div>';
    markup += '</div>'; 
	
    return markup;
  }

  function was_formatRepoSelection (repo) 
  {
	  return repo.full_name || repo.text;
  }