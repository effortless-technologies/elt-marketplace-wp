jQuery(document).ready(function($) {
  $('#_subscription_period').change(function() {
  	$('.subscription_length_ele').addClass('pro_ele_hide pro_title_hide');
  	if( $('#product_type').val() == 'subscription' ) {
  		$('.subscription_length_' + $(this).val()).removeClass('pro_ele_hide pro_title_hide');
  	}
  }).change();
  
  $('#product_type').change(function() {
  	$('#_subscription_period').change();	
  });
  
  $('.variable-subscription_period').change(function() {
  	//$(this).parent().find('.variable-subscription_length_ele').addClass('pro_ele_hide pro_title_hide');
    $(this).parents('.multi_input_block').find('.variable-subscription_length_ele').addClass('pro_ele_hide pro_title_hide');
  	if( $('#product_type').val() == 'variable-subscription' ) {
  		//$(this).parent().find('.variable-subscription_length_' + $(this).val()).removeClass('pro_ele_hide pro_title_hide');
      $(this).parents('.multi_input_block').find('.variable-subscription_length_' + $(this).val()).removeClass('pro_ele_hide pro_title_hide');
  	}
  }).change();
  
  $('#product_type').change(function() {
  	$('.variable-subscription_period').change();
    $('#is_downloadable').change();	
  });
  
  $('#variations').find('.add_multi_input_block').bind('click',function() {
    //$('.variable-subscription_period:last').val('day').trigger('change');
    $('.variable-subscription_period:last').val('day');
    $('.variable-subscription_period').change();


    $('.multi_input_block:last').find('.variable-subscription_length_ele').addClass('pro_ele_hide pro_title_hide');
    $('.multi_input_block:last').find('.variable-subscription_length_day').removeClass('pro_ele_hide pro_title_hide').val(0);
    $('.multi_input_block:last').find('.subscription_period_interval').val(1);
    $('.multi_input_block:last').find('.subscription_trial_period').val('day');

  	$('.variable-subscription_period:last').change(function() {

			//$(this).parent().find('.variable-subscription_length_ele').addClass('pro_ele_hide pro_title_hide');
      $(this).parents('.multi_input_block').find('.variable-subscription_length_ele').addClass('pro_ele_hide pro_title_hide');
			if( $('#product_type').val() == 'variable-subscription' ) {
				//$(this).parent().find('.variable-subscription_length_' + $(this).val()).removeClass('pro_ele_hide pro_title_hide');
        $(this).parents('.multi_input_block').find('.variable-subscription_length_' + $(this).val()).removeClass('pro_ele_hide pro_title_hide');
			}
		});
  });

  // Variation Options
  
  $('#variations_options').change(function() {
    $variations_option = $(this).val();
    console.log($(this).val());
    if( $variations_option ) {
      switch( $variations_option ) {
        case 'set_regular_price':
          console.log('ffffsd');
          var regular_price = prompt( "Regular Price" );
          if( regular_price != null ) {
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

});