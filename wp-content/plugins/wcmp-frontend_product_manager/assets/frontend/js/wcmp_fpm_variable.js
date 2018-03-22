

jQuery(document).ready(function($) {


	function addVariationManageStockProperty() {
		$('.variation_manage_stock_ele').each(function() {
			$(this).off('change').on('change', function() {
				if($(this).is(':checked')) {
					$(this).parents('.multi_input_block').find('.variation_non_manage_stock_ele').removeClass('non_stock_ele_hide');
				} else {
					$(this).parents('.multi_input_block').find('.variation_non_manage_stock_ele').addClass('non_stock_ele_hide');
				}
			}).change();
		});
		
		$('.variation_is_virtual_ele').each(function() {
			$(this).off('change').on('change', function() {
				if($(this).is(':checked')) {
					$(this).parents('.multi_input_block').find('.variation_non_virtual_ele').addClass('non_virtual_ele_hide');
				} else {
					$(this).parents('.multi_input_block').find('.variation_non_virtual_ele').removeClass('non_virtual_ele_hide');
				}
			}).change();
		});
		
		$('.variation_is_downloadable_ele').each(function() {
			$(this).off('change').on('change', function() {
				if($(this).is(':checked')) {					
					$(this).parents('.multi_input_block').find('.variation_downloadable_ele').parent().removeClass('downloadable_ele_hide');
					$(this).parents('.multi_input_block').find('.variation_downloadable_ele').next('.upload_button').removeClass('downloadable_ele_hide');
					$(this).parents('.multi_input_block').find('.dc-wp-fields-uploader a').removeClass('downloadable_ele_hide');

				} else {				
					$(this).parents('.multi_input_block').find('.variation_downloadable_ele').parent().addClass('downloadable_ele_hide');
					$(this).parents('.multi_input_block').find('.variation_downloadable_ele').next('.upload_button').addClass('downloadable_ele_hide');
					$(this).parents('.multi_input_block').find('.dc-wp-fields-uploader a').addClass('downloadable_ele_hide');
				}
			}).change();
		});
	}
	addVariationManageStockProperty();
	$('.multi_input_holder').each(function() {
		var multi_input_holder = $(this);
		variationmulti(multi_input_holder);
		/*multi_input_holder.children('.multi_input_block').children('.add_multi_input_block').off('click').on('click', function() {
			var multi_input_blockEle = multi_input_holder.children('.multi_input_block:first').clone(false);
			multi_input_blockEle.children('.remove_multi_input_block').off('click').on('click', function() {
			  //addVariationManageStockProperty();
		  });
		});*/
	});

	function variationmulti(multi_input_holder) {
		multi_input_holder.children('.multi_input_block').children('.add_multi_input_block').on('click', function() {
			addVariationManageStockProperty();
		});
	}

  $( "#frontend_product_manager_accordion" ).accordion({
	  heightStyle: "content",
	  activate: function( event, ui ) {
	  	if(ui.newHeader.hasClass('variations')) {

	  		resetVariationsAttributes();
	  		
	  	}
	  }
	}).bind('accordionactivate', function(event, ui) {
		//add_wrap();
		
	});



	// Add Taxonomy Attribute Rows.
	$( 'button.fpm_add_attribute' ).on( 'click', function() {
		var attribute    = $( 'select.fpm_attribute_taxonomy' ).val();
		
		if(attribute) {
			var data         = {
				action:   'generate_taxonomy_attributes',
				taxonomy: attribute
			};
	
			$('#attributes').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			
			$.ajax({
				type:		'POST',
				url: woocommerce_params.ajax_url,
				data: data,
				success:	function(response) {
					if(response) {
						$response = $(response);
						
						$('#attributes').find('.multi_input_block:last').each(function() {
							$(this).find('input[data-name="is_variation"]').change(function() {
								resetVariationsAttributes();
							});
						});
						
					}
				}
			});
		}
		
		return false;
	});

	$('#attributes').find('.multi_input_block').each(function() {
	  $(this).find( 'input[data-name="is_variation"]' ).change(function() {
	    resetVariationsAttributes();
	    //add_wrap();
	  });
	});

  function resetVariationsAttributes() {
		$('#variations').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action : 'generate_variation_attributes',
			product_manager_form : $('#product_manager_form').serialize()
		}	

   
		
		$.ajax({
			type:		'POST',
			url: woocommerce_params.ajax_url,
			data: data,
			success:	function(response) {
				
				if(response) {
					
					if(jQuery.isEmptyObject($.parseJSON(response))) {						
						$('.dflt_frm_value_lbl').addClass('pro_ele_hide');
					}

					
				
					$.each($.parseJSON(response), function(attr_name, attr_value) {
						
						
						// Default Attributes
						var default_select_html = '<select name="default_attributes[attribute_'+attr_name.toLowerCase()+']" class="regular-select form-control pro_ele attribute_ele attribute_ele_new variable multi_input_block_element default_option_sel" data-name="default_attribute_'+attr_name.toLowerCase()+'"><option value="">Any ' + jsUcfirst( attr_name.replace( "pa_", "" ) ) + ' ..</option>';
						$.each(attr_value, function(k, attr_val) {
							default_select_html += '<option value="'+k+'">'+attr_val+'</option>';
						});
						default_select_html += '</select>';

						if(default_select_html == '' || default_select_html == null) {							
							$('.dflt_frm_value_lbl').addClass('pro_ele_hide');
						} else {							
							$('.dflt_frm_value_lbl').removeClass('pro_ele_hide');
						}
						//console.log(default_select_html);
						//default_select_html = '<div class="col-sm-3 ram">'+default_select_html+'</div>';
						$('.default_attributes_holder').each(function() {
							if($(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').length > 0) {
								$attr_selected_val = $(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').val();
								$(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').replaceWith($(default_select_html));
								//$(this).find('.ram').replaceWith($(default_select_html));
								$(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').val($attr_selected_val);
							} else if($(this).find('input[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').length > 0) {
								$attr_selected_val = $(this).find('input[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').val();
								$(this).find('input[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').replaceWith($(default_select_html));
								$(this).find('select[data-name="default_attribute_'+attr_name.toLowerCase()+'"]').val($attr_selected_val);
							} else {
								$(this).append(default_select_html);
							}
						});
						
						// Variation Attributes
						var select_html = '<div class="form-group"><label class="variations_'+attr_name.toLowerCase()+' pro_title attribute_ele_new selectbox_title control-label col-sm-3 vari_select_lbl">' + jsUcfirst( attr_name.replace( "pa_", "" ) ) + '</label><select name="attribute_'+attr_name.toLowerCase()+'" class="regular-select form-control vari_select pro_ele attribute_ele attribute_ele_new variable multi_input_block_element" data-name="attribute_'+attr_name.toLowerCase()+'"><option value="">Any ' + jsUcfirst( attr_name.replace( "pa_", "" ) ) + ' ..</option>';
						$.each(attr_value, function(k, attr_val) {
							select_html += '<option value="'+k+'">'+attr_val+'</option>';
						});
						
						select_html += '</select></div>';

						
						//console.log(select_html);
						$('#variations').find('.multi_input_block').each(function() {
								//console.log('variations_'+attr_name.toLowerCase());
							if($(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').length > 0) {
								$attr_selected_val = $(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').val();
								$(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').closest('div.form-group').replaceWith($(select_html));
								$(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').val($attr_selected_val);
							} else if($(this).find('input[data-name="attribute_'+attr_name.toLowerCase()+'"]').length > 0) {
								$attr_selected_val = $(this).find('input[data-name="attribute_'+attr_name.toLowerCase()+'"]').val();
								$(this).find('input[data-name="attribute_'+attr_name.toLowerCase()+'"]').replaceWith($(select_html));
								$(this).find('select[data-name="attribute_'+attr_name.toLowerCase()+'"]').val($attr_selected_val);
							} else {
								$(this).prepend(select_html);
								//$(this).prepend('<div class="form-group jeet"><label class="control-label col-sm-3 variations_'+attr_name.toLowerCase()+' pro_title attribute_ele_new selectbox_title">' + jsUcfirst( attr_name.replace( "pa_", "" ) ) + '</label><div class="col-sm-9">'+select_html+'</div></div>');
							}
						});
					});
					
					
					

					$('.attribute_ele_old').remove();
					$('.attribute_ele_new').addClass('attribute_ele_old').removeClass('attribute_ele_new');

					
					resetMultiInputIndex($('#variations'));
					$('#variations').unblock();
				}
			},
			dataType: 'html'
		}).done(function( data ) {
			$( ".default_option_sel" ).wrapAll( "<div class='col-sm-6 dft_sel_wrap' />");
			if($(".dft_sel_wrap").find("*").first().hasClass( "dft_sel_wrap" )) {
				$(".dft_sel_wrap").find("*").first().unwrap();
			}
		});	
	
	}
	/****/
	//$('#variations').find('.multi_input_block').find('.vari_select').wrap('<p></p>');
	/*****/
	resetVariationsAttributes();

	// Creating Variation attributes
	$('#variations').find('.multi_input_block').each(function() {
		$multi_input_block = $(this);
	  $attributes = $multi_input_block.find('input[data-name="attributes"]');
	  $attributes_val = $attributes.val();
	  if($attributes_val.length > 0) {
	  	$.each($.parseJSON($attributes_val), function(attr_key, attr_val) {
	  		$multi_input_block.prepend('<input type="hidden" name="'+attr_key+'" data-name="'+attr_key+'" value="'+attr_val+'" />');
	  	});
	  }
	});
	
	// Track Deleting Variation
	//var removed_variations = [];
	$('#variations').find('.remove_multi_input_block').click(function() {
	  removed_variations.push($(this).parent().find('.variation_id').val());
	});
	
	// Variation Options
	$('#variations_options').change(function() {
		$variations_option = $(this).val();
		console.log('ager');
		if( $variations_option ) {
			switch( $variations_option ) {
			  case 'set_regular_price':
			  	var regular_price = prompt( "Regular Price" );
			  	if( regular_price != null ) {
			  		$('#variations').find('input[data-name="regular_price"]').each(function() {
			  			if( !isNaN(parseFloat(regular_price)) ) {
			  				$(this).val(parseFloat(regular_price));
			  			} else {
			  				//$(this).val(0);
			  			}
			  		});

			  		$('#variations').find('input[data-name="_subscription_price"]').each(function() {
              if( !isNaN(parseFloat(regular_price)) ) {
                $(this).val(parseFloat(regular_price));
              } else {
                //$(this).val(0);
              }
            });
			  	}
			  	break;
			  	
			  case 'regular_price_increase':
			  	var regular_price = prompt( "Regular price increase by" );
			  	if( regular_price != null ) {
			  		$('#variations').find('input[data-name="regular_price"]').each(function() {
			  			if( !isNaN(parseFloat(regular_price)) ) {
								if( $(this).val().length > 0 ) {
									$(this).val(parseFloat($(this).val()) + parseFloat(regular_price));
								} else {
									$(this).val(parseFloat(regular_price));
								}
							}
			  		});

			  		$('#variations').find('input[data-name="_subscription_price"]').each(function() {
			  			if( !isNaN(parseFloat(regular_price)) ) {
								if( $(this).val().length > 0 ) {
									$(this).val(parseFloat($(this).val()) + parseFloat(regular_price));
								} else {
									$(this).val(parseFloat(regular_price));
								}
							}
			  		});
			  	}
			  	break;
			  	
			  case 'regular_price_decrease':
			  	var regular_price = prompt( "Regular price decrease by" );
			  	if( regular_price != null ) {
			  		$('#variations').find('input[data-name="regular_price"]').each(function() {
			  			if( !isNaN(parseFloat(regular_price)) ) {
								if( $(this).val().length > 0 ) {
									$(this).val(parseFloat($(this).val()) - parseFloat(regular_price));
								} else {
									//$(this).val(parseFloat(regular_price));
								}
							}
			  		});
			  		$('#variations').find('input[data-name="_subscription_price"]').each(function() {
			  			if( !isNaN(parseFloat(regular_price)) ) {
								if( $(this).val().length > 0 ) {
									$(this).val(parseFloat($(this).val()) - parseFloat(regular_price));
								} else {
									//$(this).val(parseFloat(regular_price));
								}
							}
			  		});
			  	}
			  	break;
			  	
			  case 'set_sale_price':
			  	var sale_price = prompt( "Sale Price" );
			  	if( sale_price != null ) {
			  		$('#variations').find('input[data-name="sale_price"]').each(function() {
			  		  if( !isNaN(parseFloat(sale_price)) ) {
			  				$(this).val(parseFloat(sale_price));
			  			} else {
			  				//$(this).val(0);
			  			}
			  		});
			  	}
			  	break;
			  	
			  case 'sale_price_increase':
			  	var sale_price = prompt( "Sale price increase by" );
			  	if( sale_price != null ) {
			  		$('#variations').find('input[data-name="sale_price"]').each(function() {
			  			if( !isNaN(parseFloat(sale_price)) ) {
								if( $(this).val().length > 0 ) {
									$(this).val(parseFloat($(this).val()) + parseFloat(sale_price));
								} else {
									$(this).val(parseFloat(sale_price));
								}
							}
			  		});
			  	}
			  	break;
			  	
			  case 'sale_price_decrease':
			  	var sale_price = prompt( "Sale price decrease by" );
			  	if( sale_price != null ) {
			  		$('#variations').find('input[data-name="sale_price"]').each(function() {
			  			if( !isNaN(parseFloat(sale_price)) ) {
								if( $(this).val().length > 0 ) {
									$(this).val(parseFloat($(this).val()) - parseFloat(sale_price));
								} else {
									//$(this).val(parseFloat(sale_price));
								}
							}
			  		});
			  	}
			  	break;
			}
			$(this).val('');
		}
	});
	
	function jsUcfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
  
});

jQuery(window).on("load",function(){
	//jQuery('.vari_select').wrap('<div class="form-group roma"></div>');
	//jQuery('.default_option_sel').wrapAll('<div class="col-sm-9"></div>');
	/*jQuery('.vari_select_lbl').each(function () { 
		jQuery(this).find('.vari_select').wrap('<div class="col-sm-9"></div>'); 
	});*/
	//jQuery('.vari_select').wrapInner('<div class="col-sm-9"></div>');
	
});
