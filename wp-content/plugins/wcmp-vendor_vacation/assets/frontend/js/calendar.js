jQuery(document).ready(function($) {
	$('.panel-calendar table td').on('click',function(e){
		e.preventDefault();
		if($(this).hasClass("include_date")) {
			$(this).removeClass("include_date");
			var item_to_remove = $(this).attr('data-date');
			var include_dates_array = $.parseJSON($('#include_dates_array').val());
			include_dates_array = $.grep(include_dates_array, function(value) {
						return value != item_to_remove;
			});
			$('#include_dates_array').val(JSON.stringify(include_dates_array));
		} else {
			var include_dates_array = $.parseJSON($('#include_dates_array').val());
			$(this).addClass("include_date");
			include_dates_array.push($(this).attr('data-date'));
			$('#include_dates_array').val(JSON.stringify(include_dates_array));
		}
	});
});
