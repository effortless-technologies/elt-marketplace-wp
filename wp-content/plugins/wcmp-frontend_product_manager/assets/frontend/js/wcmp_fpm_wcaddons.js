jQuery(document).ready(function($) {
	$( document.body ).on( 'product_type_changed', function() {
  	$('.wcaddons').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
  });
  $('.wcaddons').removeClass('pro_ele_hide pro_block_hide pro_head_hide');
  
  // Addons Field Controller
	function addonFields() {
		$('#_product_addons').find('.multi_input_block').each(function() {
			$(this).find('.addon_fields_option').change(function() {
				$addon_fields_option = $(this).val();
				$(this).parents('.multi_input_block').find('.addon_fields').addClass('pro_ele_hide');
				if( $addon_fields_option == 'input_multiplier' || $addon_fields_option == 'custom_textarea' || $addon_fields_option == 'custom' || $addon_fields_option == 'custom_letters_only' || $addon_fields_option == 'custom_letters_or_digits' || $addon_fields_option == 'custom_digits_only' ) {
					$(this).parents('.multi_input_block').find('.addon_price').removeClass('pro_ele_hide');
					$(this).parents('.multi_input_block').find('.addon_minmax').removeClass('pro_ele_hide');
				} else if( $addon_fields_option == 'checkbox' || $addon_fields_option == 'custom_email' || $addon_fields_option == 'file_upload' || $addon_fields_option == 'radiobutton' || $addon_fields_option == 'select' ) {
					$(this).parents('.multi_input_block').find('.addon_price').removeClass('pro_ele_hide');
				} else if( $addon_fields_option == 'custom_price' ) {
					$(this).parents('.multi_input_block').find('.addon_minmax').removeClass('pro_ele_hide');
				}
			}).change();
		});
	}
	addonFields();
	$('#_product_addons').find('.add_multi_input_block').click(function() {
	  addonFields();
	});
});