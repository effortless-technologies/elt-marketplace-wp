jQuery(document).ready(function($) {	
	$( "#wcmp_frontend_product_search_from_date" ).datepicker();
	$( "#wcmp_frontend_product_search_to_date" ).datepicker();
	
	$('.product_report_search').click(function() {
		product_id = $(this).closest('tr').find('select#search_product').val();
		
		if(!product_id) {
			alert('Choose a product first');
			return;
		}
		
		var start_date = $( "#wcmp_frontend_product_search_from_date" ).val();
		var end_date = $( "#wcmp_frontend_product_search_to_date" ).val();
		
		
		var selected_vendor_data = {
			action: 'frontend_product_search',
			product_id: product_id,
			start_date: start_date,
			end_date: end_date
		}
		
		$.post(woocommerce_params.ajax_url, selected_vendor_data, function(response) {
			$('.product_sort_chart').html(response);
		});
	
	});
	
	$('select.ajax_chosen_select_products_and_variations').ajaxChosen({
			method: 	'GET',
			url: 		woocommerce_params.ajax_url,
			dataType: 	'json',
			afterTypeDelay: 100,
			data:		{
				action: 'woocommerce_json_search_products_and_variations',
				security: wcmp_report_product_search.security
			}
	}, function (data) {
	
		var terms = {};
			$.each(data, function (i, val) {
					terms[i] = val;
			});
			return terms;
	});
});