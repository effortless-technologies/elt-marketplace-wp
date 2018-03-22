jQuery(document).ready(function($) {	
	$('form.wcmp_vendors_settings').find('#accommodation-booking').change(function() {			
		if($(this).is(':checked')) {
			$('form.wcmp_vendors_settings').find('#booking').prop('checked', true);
			$('form.wcmp_vendors_settings').find('#booking').attr('onclick','return false');
		} else {
			$('form.wcmp_vendors_settings').find('#booking').attr('onclick','return true');
		}
	})
	if($('form.wcmp_vendors_settings').find('#accommodation-booking').is(':checked')) {
		$('form.wcmp_vendors_settings').find('#booking').attr('onclick','return false');
	}

});