jQuery(document).ready(function($) {
  // Delete Product
	$('.wcmp_fpm_delete').each(function() {
    $(this).click(function(event) {
      event.preventDefault();
      var rconfirm = confirm("Are you sure and want to delete this 'Product'?\nYou can't undo this action ...");
			if(rconfirm) deleteProduct($(this));
      return false;
    })
	});
	
	function deleteProduct(item) {
		$('.woocommerce').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action : 'delete_fpm_product',
			proid : item.data('proid')
		}	
		$.ajax({
			type:		'POST',
			url: woocommerce_params.ajax_url,
			data: data,
			success:	function(response) {
				if(response) {
					if(response == 'success') {
						window.location = fpm_messages.shop_url;
					} else {
						$('.woocommerce').unblock();
					}
				} else {
					$('.woocommerce').unblock();
				}
			}
		});
	}
});