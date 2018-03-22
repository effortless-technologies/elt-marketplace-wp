jQuery(document).ready(function($) {
    $("#submit_add_Sub_Vendor").click(function(event) {
		event.preventDefault();
		var sv_capabilities = [];
		$('#sub_vendor_capabilities input:checked').each(function() {
			sv_capabilities.push($(this).attr('name'));
		});
	
		var data = {
			action: 'add_sub_vendor_action',
			sub_vendor_username: $('#sub_vendor_username').val(),      // We pass php values differently!
			sub_vendor_email: $('#sub_vendor_email').val(),
			sub_vendor_fname: $('#sub_vendor_fname').val(),
			sub_vendor_lname: $('#sub_vendor_lname').val(),
			sub_vendor_password: $('#sub_vendor_password').val(),
			sub_vendor_capabilities : sv_capabilities,
		};
		$('.sv_ajax_loader').show();
		
		$.ajax({
			type:		'POST',
			url: ajax_object.ajax_url,
			data: data,
			success:	function(response) {
				if(response) {
					result = response.split("::");
					if(result[0] == 'success') {
						$(".sub_vendor_error").html('');
						$(".sub_vendor_success").html('Sub-Vendor created successfully.');
						var url = 'admin.php?page=sub_vendor_details&action=edit&userid=' + result[1];
						$(location).attr("href", url);
					} else if(response == 'error') {
						$(".sub_vendor_error").html('');
						$(".sub_vendor_success").html('');
						$(".sub_vendor_error").html('Error while adding Sub-Vendor. Username or Email ID is already registered.');
					} else if(response == 'required_missing') {
						$(".sub_vendor_error").html('Error while adding Sub-Vendor. Required fields are missing.');
					}
				}
				$('.sv_ajax_loader').hide();
			}
		});
		return false;
	});
	
	$("#submit_edit_Sub_Vendor").click(function(event) {
		event.preventDefault();
		var sv_capabilities = [];
		$('#sub_vendor_capabilities input:checked').each(function() {
			sv_capabilities.push($(this).attr('name'));
		});
		var data = {
			action: 'edit_sub_vendor_action',
			edited_sub_vendor_username: $('#sub_vendor_username').val(),      // We pass php values differently!
			edited_sub_vendor_email: $('#sub_vendor_email').val(),
			edited_sub_vendor_fname: $('#sub_vendor_fname').val(),
			edited_sub_vendor_lname: $('#sub_vendor_lname').val(),
			edited_sub_vendor_password: $('#sub_vendor_password').val(),
			edited_sub_vendor_capabilities : sv_capabilities,
		};
		$('.sv_ajax_loader').show();
		$.post(ajax_object.ajax_url, data, function(response) {
		console.log(response);
			if(response == 'success'){
				$(".sub_vendor_success").html('');
				$(".sub_vendor_error").html('');
				$(".sub_vendor_success").html('Vendor Staff details updated successfully.');
			} else if(response == 'error') {
				$(".sub_vendor_error").html('');
				$(".sub_vendor_success").html('');
				$(".sub_vendor_error").html('Error while updating. Email ID is already registered.');
			} else if(response == 'permission_error') {
				$(".sub_vendor_error").html('');
				$(".sub_vendor_success").html('');
				$(".sub_vendor_error").html('Error while updating. User you are trying to edit is not your staff.');
			}
			$('.sv_ajax_loader').hide();
		});
		return false;
	});
});
jQuery("#delete_sub_vendor").click(function($){
	var delete_sub_vendor_id = $(this).val();
	var data = {
		action: 'delete_sub_vendor_action',
		delete_sub_vendor_id : delete_sub_vendor_id,
	};
	$.post(ajax_object.ajax_url, data, function(response) {
		console.log(response);
		if(response == 'deleted'){
			var url = 'admin.php?page=sub-vendors_page'
			$(location).attr("href", url);
		}
	});
});	

jQuery(document).ready(function($) {

      var testEmail =    /^[ ]*([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})[ ]*$/i;
      $('input#sub_vendor_email').bind('input propertychange', function() {
        if (testEmail.test($(this).val()))
        {
           $(this).css({ 'border':'1px solid #DDD'});
           $('button.validate').prop("disabled",false);
         } else
         {
           $(this).css({ 'border':'1px solid #ff0000'});
           $('button.validate').prop("disabled",true);
         }
       });
});