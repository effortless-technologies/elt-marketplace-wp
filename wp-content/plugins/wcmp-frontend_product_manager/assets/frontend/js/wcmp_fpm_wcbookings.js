jQuery(document).ready(function($) {
	 

  $('#_wc_booking_duration_type').change(function() {
    if( $(this).val() == 'customer' ) {
    	$('.duration_type_customer_ele').show();
    } else {
    	$('.duration_type_customer_ele').hide();
    }
  }).change();
  
  $('#_wc_booking_user_can_cancel').change(function() {
    if( $(this).is(':checked')) $('.can_cancel_ele').show();
    else $('.can_cancel_ele').hide();
  } ).change();
  
  $('#_wc_booking_duration_unit').change(function() {
  	$('._wc_booking_buffer_period_unit').html($(this).val() + 's ');
  }).change();
  
  // Availability rules type
	function availabilityRules() {
		$('#_wc_booking_availability_rules').find('.multi_input_block').each(function() {
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
	availabilityRules();

	//wc booking pricing
	function bookingPricing() {
		$('#_wc_booking_cost_range_types').find('.multi_input_block').each(function() {
			$(this).find('.wc_booking_pricing_type').change(function() {
				$wc_booking_pricing_type = $(this).val();
				
				$(this).parents('.multi_input_block').find('.wc_booking_pricing_field').addClass('pro_ele_hide');
				if( $wc_booking_pricing_type == 'custom' || $wc_booking_pricing_type == 'months' || $wc_booking_pricing_type == 'weeks' || $wc_booking_pricing_type == 'days' || $wc_booking_pricing_type == 'persons' || $wc_booking_pricing_type == 'blocks') {
					$(this).parents('.multi_input_block').find('.wc_booking_pricing_' + $wc_booking_pricing_type).removeClass('pro_ele_hide');
				} else if( $wc_booking_pricing_type == 'time:range' ) {
					$(this).parents('.multi_input_block').find('.wc_booking_pricing_custom').removeClass('pro_ele_hide');
					$(this).parents('.multi_input_block').find('.wc_booking_pricing_time').removeClass('pro_ele_hide');
				} else {
					$(this).parents('.multi_input_block').find('.wc_booking_pricing_time').removeClass('pro_ele_hide');
				}
			}).change();
		});
	}
	bookingPricing();
	$('#_wc_booking_cost_range_types .add_multi_input_block').on('click', function() {
		$(this).parents('.multi_input_block').find('.wc_booking_pricing_type').val('custom');
		bookingPricing();
	});
	$('#_wc_booking_availability_rules').find('.add_multi_input_block').click(function() {
	  availabilityRules();
	  $('#_wc_booking_availability_rules').find('.multi_input_block:last').find('.avail_rule_priority').val('10');
	});
	
  // Persons
  $('.persons').addClass('pro_ele_hide pro_block_hide pro_head_hide');
  if( $('#_wc_booking_has_persons').length > 0 ) {
		$('#_wc_booking_has_persons').change(function() {
			if($(this).is(':checked')) {
				$('.persons').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
			} else {
				$('.persons').addClass('pro_ele_hide pro_block_hide pro_head_hide');
			}
			if( $('#product_type').val() != 'booking' ) $('.persons').addClass('pro_ele_hide pro_block_hide pro_head_hide');
		}).change();
	}
	
	// Person Types
	$('#_wc_booking_has_person_types').change(function() {
		if($(this).is(':checked')) {
			$('.person_types').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
		} else {
			$('.person_types').addClass('pro_ele_hide pro_block_hide pro_head_hide');
		}
	}).change();
	
	// Resources
	$('.resources').addClass('pro_ele_hide pro_block_hide pro_head_hide');
	if( $('#_wc_booking_has_resources').length > 0 ) {
		$('#_wc_booking_has_resources').change(function() {
			if($(this).is(':checked')) {
				$('.resources').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
			} else {
				$('.resources').addClass('pro_ele_hide pro_block_hide pro_head_hide');
			}
			if( $('#product_type').val() != 'booking' ) $('.resources').addClass('pro_ele_hide pro_block_hide pro_head_hide');
		}).change();
	}
	
	// Product Type Change
	$('#product_type').change(function() {
		if( $('#_wc_booking_has_persons').length > 0 ) { $('#_wc_booking_has_persons').change(); } 
		else { $('.persons').addClass('pro_ele_hide pro_block_hide pro_head_hide'); }
		if( $('#_wc_booking_has_resources').length > 0 ) { $('#_wc_booking_has_resources').change(); }
		else { $('.resources').addClass('pro_ele_hide pro_block_hide pro_head_hide'); }
	});
	
	// Track Deleting Person Types
	$('#_wc_booking_person_types').find('.remove_multi_input_block').click(function() {
	  removed_person_types.push($(this).parent().find('.person_id').val());
	});
  
	// Resource Type Selection
	function trackUsedResources() {
		$('#_wc_booking_resources').find('.multi_input_block').each(function() {
			$resource_id = $(this).find( 'input[data-name="resource_id"]' ).val();
			$( 'select#_wc_booking_all_resources' ).find( 'option[value="' + $resource_id + '"]' ).attr( 'disabled','disabled' );
		});
	}
	trackUsedResources();
	
	// Resource Type selection
	$( 'select#_wc_booking_all_resources' ).change(function() {
	  $('#_wc_booking_resources').find('.multi_input_block:last').find('.add_multi_input_block').click();
	  $('#_wc_booking_resources').find('.multi_input_block:last').find('input[data-name="resource_id"]').val($(this).val());
	  $('#_wc_booking_resources').find('.multi_input_block:last').find('input[data-name="resource_title"]').val($(this).find("option:selected").html());
	  $('#_wc_booking_resources').find('.multi_input_block:last').find('.remove_multi_input_block').click(function() {
			$resource_id = $(this).parent().find( 'input[data-name="resource_id"]' ).val();
			$( 'select#_wc_booking_all_resources' ).find( 'option[value="' + $resource_id + '"]' ).removeAttr( 'disabled' );
			trackUsedResources();
		});
	  trackUsedResources();
	});
	
	// Track Deleting Resources
	$('#_wc_booking_resources').find('.remove_multi_input_block').click(function() {
		$resource_id = $(this).parent().find( 'input[data-name="resource_id"]' ).val();
		$( 'select#_wc_booking_all_resources' ).find( 'option[value="' + $resource_id + '"]' ).removeAttr( 'disabled' );
	  trackUsedResources();
	});

	$("body").on("change has_restricted_days", "#_wc_booking_has_restricted_days",function() {
		if($(this).is(":checked")) {
      $('.restricted_days_dependency').removeClass("pro_ele_hide");
    } else {
      $('.restricted_days_dependency').addClass("pro_ele_hide");
    }
	});
});
jQuery(window).load(function(){
  jQuery('body #_wc_booking_has_restricted_days').trigger('has_restricted_days');
});