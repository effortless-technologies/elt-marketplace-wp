jQuery(document).ready(function($) {
		
	if($('#more_most_stocked').attr('data-show') == wcmp_frontend_report_most_stocked.max_items ) $('#more_most_stocked').hide(); 	
	
	$('#more_most_stocked').click(function (e) {
		 e.preventDefault();
		 var data = {
				action : 'get_more_most_stocked_product',
				current_page : $(this).attr('data-show'),
				max_items : wcmp_frontend_report_most_stocked.max_items,
		 }	
		 $.post(woocommerce_params.ajax_url, data, function(response) {
		 		var offsett = parseInt($('#more_most_stocked').attr('data-show')) + 1 ;
		 		$('#more_most_stocked').attr("data-show", offsett);
		 		$( ".most_stocked_report tr:last" ).after(response);
		 		
		 		if(offsett == wcmp_frontend_report_most_stocked.max_items) {
		 				$('#more_most_stocked').hide();
		 		}
		 });
	});
	
});