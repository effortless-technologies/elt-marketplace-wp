jQuery(document).ready(function($) {

	$("body").on("change  accommodation_booking_has_restricted_days", "#_wc_accommodation_booking_has_restricted_days",function() {
		if($(this).is(":checked")) {
	      $(this).parents().find('.restricted_days_dependency').removeClass("pro_ele_hide");
	    } else {
	      $(this).parents().find('.restricted_days_dependency').addClass("pro_ele_hide");
	    }
	});
	 // Availability rules type
	function availabilityRules1() {
		$('#_wc_accommodation_booking_availability_rules').find('.multi_input_block').each(function() {
			$(this).find('.avail_range_type').change(function() {

				$avail_range_type = $(this).val();
				$(this).parents('.multi_input_block').find('.avail_rule_field').addClass('pro_ele_hide');
				if( $avail_range_type == 'custom' || $avail_range_type == 'months' || $avail_range_type == 'weeks' || $avail_range_type == 'days' ) {
					$(this).parents('.multi_input_block').find('.avail_rule_' + $avail_range_type).removeClass('pro_ele_hide');
				} else if( $avail_range_type == 'time:range' ) {
					$(this).parents('.multi_input_block').find('.avail_rule_custom').removeClass('pro_ele_hide');
					$(this).parents('.multi_input_block').find('.avail_rule_time').removeClass('pro_ele_hide');
				} else {
					$(this).parents('.multi_input_block').find('.avail_rule_time').removeClass('pro_ele_hide');
				}
			}).change();
		});
	}

	function ratesRules() {
		$('#_wc_accommodation_booking_range_rules').find('.multi_input_block').each(function() {
			$(this).find('.avail_range_type').change(function() {
				$avail_range_type = $(this).val();
				$(this).parents('.multi_input_block').find('.avail_rule_field').addClass('pro_ele_hide');
				if( $avail_range_type == 'custom' || $avail_range_type == 'months' || $avail_range_type == 'weeks' || $avail_range_type == 'days' ) {
					$(this).parents('.multi_input_block').find('.avail_rule_' + $avail_range_type).removeClass('pro_ele_hide');
				} else if( $avail_range_type == 'time:range' ) {
					$(this).parents('.multi_input_block').find('.avail_rule_custom').removeClass('pro_ele_hide');
					$(this).parents('.multi_input_block').find('.avail_rule_time').removeClass('pro_ele_hide');
				} else {
					$(this).parents('.multi_input_block').find('.avail_rule_time').removeClass('pro_ele_hide');
				}
			}).change();
		});
	}

	availabilityRules1();
	ratesRules();
	$('#_wc_accommodation_booking_availability_rules').find('.add_multi_input_block').click(function() {
		$('#_wc_accommodation_booking_availability_rules').find('.multi_input_block:last').find('.avail_range_type').val('custom');
	  availabilityRules1();
	  $('#_wc_accommodation_booking_availability_rules').find('.multi_input_block:last').find('.avail_rule_priority').val('10');
	});

	$('#_wc_accommodation_booking_range_rules').find('.add_multi_input_block').click(function() {
		$('#_wc_accommodation_booking_range_rules').find('.multi_input_block:last').find('.avail_range_type').val('custom');
	  ratesRules();
	  //$('#_wc_accommodation_booking_range_rules').find('.multi_input_block:last').find('select').val('');
	});


	// Resources
	$('.resources').addClass('pro_ele_hide pro_block_hide pro_head_hide');
	if( $('#_wc_accommodation_booking_has_resources').length > 0 ) {		
		$('#_wc_accommodation_booking_has_resources').change(function() {			
			if($(this).is(':checked')) {
				$('.resources').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
			} else {
				$('.resources').addClass('pro_ele_hide pro_block_hide pro_head_hide');
			}
			//if( $('#product_type').val() == 'accommodation-booking' ) $('.resources').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
		}).change();
	}

	// Person Types
	$('.persons').addClass('pro_ele_hide pro_block_hide pro_head_hide');
	if( $('#_wc_accommodation_booking_has_persons').length > 0 ) {
		$('#_wc_accommodation_booking_has_persons').change(function() {
			if($(this).is(':checked')) {
				$('.persons').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
			} else {
				$('.persons').addClass('pro_ele_hide pro_block_hide pro_head_hide');
			}
			//if( $('#product_type').val() == 'accommodation-booking' ) $('.persons').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
		}).change();
	}
	

	// Product Type Change
	$('#product_type').change(function() {	
		$('.resources').addClass('pro_ele_hide pro_block_hide pro_head_hide');
		$('.persons').addClass('pro_ele_hide pro_block_hide pro_head_hide');
		if( $('#_wc_accommodation_booking_has_persons').length > 0 ) { $('#_wc_accommodation_booking_has_persons').change(); } 
		else { $('.persons').addClass('pro_ele_hide pro_block_hide pro_head_hide'); }

		if( $('#_wc_booking_has_resources').length > 0 ) { $('#_wc_booking_has_resources').change(); }
		else { $('.resources').addClass('pro_ele_hide pro_block_hide pro_head_hide'); }
	});

});

jQuery(window).load(function(){
  jQuery('body #_wc_accommodation_booking_has_restricted_days').trigger('accommodation_booking_has_restricted_days');
});