jQuery(document).ready(function($) {		
	$( ".vendor_preferred_template" ).on('change', function() {
		var url = $('option:selected', this).attr('data-id');
		$('.vendor_choosed_template_view').attr('src', url);
	});
});