jQuery(document).ready(function($) {
	$( "#wcmp_product_frontend_product_from_date" ).datepicker();
	$( "#wcmp_product_frontend_product_to_date" ).datepicker();
	$('.product_report_sort').change(function() {
		if( $('.high_to_low').is(':checked') ) {
			selected_sorting = $(this).val();
			sorting_order = selected_sorting + '_desc';
			product_report_sorting(sorting_order);
		} else {
			selected_sorting = $(this).val();
			sorting_order = selected_sorting + '_asc';
			product_report_sorting(sorting_order);
		}
	});
	
	$('.low_to_high_btn_product').click(function() {
		$(".high_to_low").prop('checked', false);
		$(".low_to_high").prop('checked', true);
		$('.low_to_high_btn_product').css('background-color', 'rgb(145, 145, 145)');
		$('.high_to_low_btn_product').css('background-color', 'rgb(210, 210, 210)');
		selected_sorting = $('.product_report_sort').val();
		sorting_order = selected_sorting + '_asc';
		product_report_sorting(sorting_order);
	});
	
	$('.high_to_low_btn_product').click(function() {
		$(".low_to_high").prop('checked', false);
		$(".high_to_low").prop('checked', true);
		$('.high_to_low_btn_product').css('background-color', 'rgb(145, 145, 145)');
		$('.low_to_high_btn_product').css('background-color', 'rgb(210, 210, 210)');
		selected_sorting = $('.product_report_sort').val();
		sorting_order = selected_sorting + '_desc';
		product_report_sorting(sorting_order);
	});
	
	function product_report_sorting(selected_sorting) {
		
		var report_data = {
			action: 'frontend_product_report_sort',
			sort_choosen: selected_sorting,
			report_array: wcmp_report_product_overview.product_report,
			report_bk: wcmp_report_product_overview.report_bk,
			max_total_sales: wcmp_report_product_overview.max_total_sales,
			total_sales_sort: wcmp_report_product_overview.total_sales_sort,
			admin_earning_sort: wcmp_report_product_overview.admin_earning_sort,
			start_date:wcmp_report_product_overview.start_date,
			end_date : wcmp_report_product_overview.end_date
		}
		$.post(woocommerce_params.ajax_url, report_data, function(response) {
			$('.product_overview_chart').html(response);
		});
	}
	
});