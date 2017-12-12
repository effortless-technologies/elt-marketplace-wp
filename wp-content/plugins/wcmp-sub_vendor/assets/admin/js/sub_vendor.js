jQuery("#newsub_vendor").submit(function($){
	jQuery(".sub_vendor_error").removeClass("sub_vendor_error");
	var sv_capabilities = [];
	jQuery('#sub_vendor_capabilities input:checked').each(function() {
	    sv_capabilities.push(jQuery(this).attr('name'));
	});

    var data = {
		action: 'add_sub_vendor_action',
		sub_vendor_username: jQuery('#sub_vendor_username').val(),      // We pass php values differently!
		sub_vendor_email: jQuery('#sub_vendor_email').val(),
		sub_vendor_fname: jQuery('#sub_vendor_fname').val(),
		sub_vendor_lname: jQuery('#sub_vendor_lname').val(),
		sub_vendor_password: jQuery('#sub_vendor_password').val(),
		sub_vendor_capabilities : sv_capabilities,
	};
	jQuery('.sv_ajax_loader').show();
	jQuery.post(ajax_object.ajax_url, data, function(response) {
	//console.log(response);
		if(response == 'success'){
			jQuery(".sub_vendor_success").html('');
			jQuery(".sub_vendor_error1").html('');
			jQuery(".sub_vendor_success").html('Sub-Vendor created successfully.');
			var url = 'admin.php?page=sub-vendors_page'
			jQuery(location).attr("href", url);
		} else if(response == 'error'){
			jQuery(".sub_vendor_error1").html('');
			jQuery(".sub_vendor_success").html('');
			jQuery(".sub_vendor_error1").html('Error while adding Sub-Vendor. Username or Email ID is already registered.');
		}
		jQuery('.sv_ajax_loader').hide();
	});

	return false;

});
jQuery("#edit_sub_vendor").submit(function($){
	jQuery(".sub_vendor_error").removeClass("sub_vendor_error");
	var sv_capabilities = [];
	jQuery('#sub_vendor_capabilities input:checked').each(function() {
	    sv_capabilities.push(jQuery(this).attr('name'));
	});
    var data = {
		action: 'edit_sub_vendor_action',
		edited_sub_vendor_username: jQuery('#sub_vendor_username').val(),      // We pass php values differently!
		edited_sub_vendor_email: jQuery('#sub_vendor_email').val(),
		edited_sub_vendor_fname: jQuery('#sub_vendor_fname').val(),
		edited_sub_vendor_lname: jQuery('#sub_vendor_lname').val(),
		edited_sub_vendor_password: jQuery('#sub_vendor_password').val(),
		edited_sub_vendor_capabilities : sv_capabilities,
	};
	jQuery('.sv_ajax_loader').show();
	jQuery.post(ajax_object.ajax_url, data, function(response) {
	console.log(response);
		if(response == 'success'){
			jQuery(".sub_vendor_success").html('');
			jQuery(".sub_vendor_error1").html('');
			jQuery(".sub_vendor_success").html('Vendor Staff details updated successfully.');
		} else if(response == 'error'){
			jQuery(".sub_vendor_error1").html('');
			jQuery(".sub_vendor_success").html('');
			jQuery(".sub_vendor_error1").html('Error while updating. Email ID is already registered.');
		}
		jQuery('.sv_ajax_loader').hide();
	});

	return false;

});
jQuery("#delete_sub_vendor").click(function($){
	var delete_sub_vendor_id = jQuery(this).val();
	var data = {
		action: 'delete_sub_vendor_action',
		delete_sub_vendor_id : delete_sub_vendor_id,
	};
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		console.log(response);
		if(response == 'deleted'){
			var url = 'admin.php?page=sub-vendors_page'
			jQuery(location).attr("href", url);
		}
	});
});	

jQuery(document).ready(function($) {

      var testEmail =    /^[ ]*([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})[ ]*$/i;
      jQuery('input#sub_vendor_email').bind('input propertychange', function() {
        if (testEmail.test(jQuery(this).val()))
        {
           jQuery(this).css({ 'border':'1px solid #DDD'});
           jQuery('button.validate').prop("disabled",false);
         } else
         {
           jQuery(this).css({ 'border':'1px solid #ff0000'});
           jQuery('button.validate').prop("disabled",true);
         }
       });
});