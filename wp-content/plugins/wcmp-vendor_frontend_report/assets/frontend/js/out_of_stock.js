jQuery(document).ready(function($) {
		
	if($('#more_out_of_stock').attr('data-show') == wcmp_frontend_report_low_stock.max_items ) $('#more_out_of_stock').hide(); 	
	
	$('#more_out_of_stock').click(function (e) {
		 e.preventDefault();
		 var data = {
				action : 'get_more_out_of_stock_product',
				current_page : $(this).attr('data-show'),
				max_items : wcmp_frontend_report_out_of_stock.max_items,
		 }	
		 $.post(woocommerce_params.ajax_url, data, function(response) {
		 		var offsett = parseInt($('#more_out_of_stock').attr('data-show')) + 1 ;
		 		$('#more_out_of_stock').attr("data-show", offsett);
		 		$( ".out_of_stock_report tr:last" ).after(response);
		 		
		 		if(offsett == wcmp_frontend_report_out_of_stock.max_items) {
		 				$('#more_out_of_stock').hide();
		 		}
		 });
	});
	
});