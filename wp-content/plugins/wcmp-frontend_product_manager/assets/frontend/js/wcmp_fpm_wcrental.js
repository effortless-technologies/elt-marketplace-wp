jQuery(document).ready(function($) {
  $('#pricing_type').change(function() {
  	$('.rentel_pricing').addClass('pro_ele_hide');
    $('.rental_' + $(this).val() ).removeClass('pro_ele_hide');
  }).change();
  
});