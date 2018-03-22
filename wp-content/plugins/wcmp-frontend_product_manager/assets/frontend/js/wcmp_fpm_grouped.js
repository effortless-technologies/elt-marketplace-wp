jQuery(document).ready(function($) {
	if( $("#children").length > 0 ) {
		$children = $("#children").select2({
			placeholder: "Choose ..."
		});
		//$children.data('select2').$container.addClass("pro_ele grouped");
	}
	
});