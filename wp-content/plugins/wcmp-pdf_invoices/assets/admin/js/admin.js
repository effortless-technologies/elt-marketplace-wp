jQuery(document).ready(function($) {		
	$('.wcmp_create_per_order_pdf_invoice').off().on('click', function(e) {
		 e.preventDefault();
		 var input = this;
     input.disabled = true;
		 var data = {
				action : 'create_per_order_pdf',
				order_id : $(this).attr('data-id'),
		 }	
		 $.post(ajaxurl, data, function(response) {
				window.location = window.location;
		 });
	});
	
	$('.wcmp_create_per_vendor_pdf_invoice').off().on('click', function(e) {
		 e.preventDefault();
		 var input = this;
     input.disabled = true;
		 var data = {
				action : 'create_per_vendor_pdf',
				order_id : $(this).attr('data-id'),
				user_id : $(this).attr('data-user_id')
		 }	
		 $.post(ajaxurl, data, function(response) {
				window.location = window.location;
		 });
	});
	
	$('.wcmp_cancel_per_order_pdf_invoice').off().on('click', function(e) {
		 e.preventDefault();
		 var input = this;
     input.disabled = true;
		 var data = {
				action : 'cancel_per_order_pdf',
				order_id : $(this).attr('data-id')
		 }	
		 $.post(ajaxurl, data, function(response) {
				window.location = window.location;
		 });
	});
	
	$('.wcmp_cancel_per_vendor_pdf_invoice').off().on('click', function(e) {
		 e.preventDefault();
		 var input = this;
     input.disabled = true;
		 var data = {
				action : 'cancel_per_vendor_pdf',
				order_id : $(this).attr('data-id'),
				vendor_name : $(this).attr('data-vendor')
		 }	
		 $.post(ajaxurl, data, function(response) {
				window.location = window.location;
		 });
	});
	
});		