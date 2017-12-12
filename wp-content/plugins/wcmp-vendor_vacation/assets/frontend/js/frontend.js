jQuery(document).ready(function($){		
	var active_menu_vendor = $("#wcmp_vendor_vacation_menu_li_element a ");	
	if(active_menu_vendor.hasClass('selected_menu')){
		active_menu_vendor.parent().parent().show(); 
		var menu_to_be_active = active_menu_vendor.parent().parent().parent().find("a");
		menu_to_be_active.addClass('active');		
	}

	if($('#wcmp_vendor_store_time .is_time #is_enable_store_time').is(':checked')) {
		$("#wcmp_vendor_store_time tr").show();
  	}else{
  		$("#wcmp_vendor_store_time tr:not(.is_time)").hide();
  	}
	$('#wcmp_vendor_store_time .is_time #is_enable_store_time').change(function() {
		if($(this).is(":checked")) {
			$("#wcmp_vendor_store_time tr").show('slow');
		}
		else {
			$("#wcmp_vendor_store_time tr:not(.is_time)").hide('slow');
		}
	});		
});