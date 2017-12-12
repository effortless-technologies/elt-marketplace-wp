jQuery(document).ready(function($) {

  	if($('#low_stock_enabled').is(':checked')) {
		var parrent_ele = $('#low_stock_limit').parents('tr');
		parrent_ele.show();
  	}
  	else {
  		var parrent_ele = $('#low_stock_limit').parents('tr');
		parrent_ele.hide();
  	}

  	if($('#out_of_stock_enabled').is(':checked')) {
		var parrent_ele = $('#out_of_stock_limit').parents('tr');
		parrent_ele.show();
  	}
  	else {
  		var parrent_ele = $('#out_of_stock_limit').parents('tr');
		parrent_ele.hide();
  	}
  	
	$('#low_stock_enabled').change(function() {
		if($(this).is(":checked")) {
			var parrent_ele = $('#low_stock_limit').parents('tr');
			parrent_ele.show('slow');
		}
		else {
			var parrent_ele = $('#low_stock_limit').parents('tr');
			parrent_ele.hide('slow');
		}
	});
	$('#out_of_stock_enabled').change(function() {
		if($(this).is(":checked")) {
			var parrent_ele = $('#out_of_stock_limit').parents('tr');
			parrent_ele.show('slow');
		}
		else {
			var parrent_ele = $('#out_of_stock_limit').parents('tr');
			parrent_ele.hide('slow');
		}
	});
	
});
