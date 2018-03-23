jQuery(document).ready(function($) {
    $(".delete-confrm-dialog-box").click(function() {
		var deleteConfirm =  confirm('Are you sure, you want to delete this staff?');
		if(deleteConfirm == true){
			 deleteStaff($(this));
		} else {
			return false;
		}
	});
	
	function deleteStaff(item) {
		$('.woocommerce').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action : 'delete_sub_vendor_action',
			delete_sub_vendor_id : item.data('staff_id')
		}	
		$.ajax({
			type:		'POST',
			url: woocommerce_params.ajax_url,
			data: data,
			success:	function(response) {
				if(response) {
					if(response == 'deleted') {
						window.location = vendor_staff_messages.manage_staff_url;
					} else {
						$('.woocommerce').unblock();
					}
				} else {
					$('.woocommerce').unblock();
				}
			}
		});
	}
	
	$(".wcmp-action-container .add_sub_vendor").click(function(event) {
		event.preventDefault();
		$('.woocommerce').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var sv_capabilities = [];
		$('.checkbox-group-addon input:checked').each(function() {
			sv_capabilities.push($(this).attr('name'));
		});
	
		if($('#edit_staff_id').val() > 0) {
			var data = {
				action: 'edit_sub_vendor_action',
				edited_sub_vendor_username: $('.sub_vendor_username').val(),
				edited_sub_vendor_email: $('.sub_vendor_email').val(),
				edited_sub_vendor_fname: $('.sub_vendor_fname').val(),
				edited_sub_vendor_lname: $('.sub_vendor_lname').val(),
				edited_sub_vendor_password: $('.sub_vendor_password').val(),
				edited_sub_vendor_capabilities : sv_capabilities
			};
		} else {
			var data = {
				action: 'add_sub_vendor_action',
				sub_vendor_username: $('.sub_vendor_username').val(),
				sub_vendor_email: $('.sub_vendor_email').val(),
				sub_vendor_fname: $('.sub_vendor_fname').val(),
				sub_vendor_lname: $('.sub_vendor_lname').val(),
				sub_vendor_password: $('.sub_vendor_password').val(),
				sub_vendor_capabilities : sv_capabilities
			};
		}
		
		$.ajax({
			type:		'POST',
			url: woocommerce_params.ajax_url,
			data: data,
			success:	function(response, data) {
				if(response) {
					var staff_id = $('#edit_staff_id').val();
					result = response.split("::");
					if(result[0] == 'success') {
						$(".sub_vendor_error").hide();
						$(".sub_vendor_success").html('');
						if(staff_id > 0) $(".sub_vendor_success").html('Vendor Staff details updated successfully.');
						else $(".sub_vendor_success").html('Vendor Staff added successfully.');
						$(location).attr("href", vendor_staff_messages.manage_staff_url);
						$(".sub_vendor_success").show();
						$('.woocommerce').unblock();
					} else if(response == 'error') {
						$(".sub_vendor_error").html('');
						$(".sub_vendor_success").html('');
						if(staff_id > 0) $(".sub_vendor_error").html('Error while updating. Email ID is already registered.');
						else $(".sub_vendor_error").html('Error while adding Sub-Vendor. Username or Email ID is already registered.');
						$(".sub_vendor_error").show();
						$('.woocommerce').unblock();
					} else if(response == 'permission_error') {
						$(".sub_vendor_error").html('');
						$(".sub_vendor_success").html('');
						$(".sub_vendor_error").html('Error while updating. User you are trying to edit is not your staff.');
						$(".sub_vendor_error").show();
						$('.woocommerce').unblock();
					} else if(response == 'required_missing') {
						$(".sub_vendor_error").html('');
						$(".sub_vendor_success").html('');
						$(".sub_vendor_error").html('Error while adding Sub-Vendor. Required fields are missing.');
						$(".sub_vendor_error").show();
						$('.woocommerce').unblock();
					} else {
						$('.woocommerce').unblock();
					}
					
				} else {
					$('.woocommerce').unblock();
				}
			}
		});
	});
	
});
