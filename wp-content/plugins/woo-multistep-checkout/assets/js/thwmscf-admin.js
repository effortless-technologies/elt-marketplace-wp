jQuery(document).ready(function($){ 
    //$('.thpladmin-colorpick').wpColorPicker();
    $( ".thpladmin-colorpick" ).each(function() {     	
		var value = $(this).val();
		$( this ).parent().find( '.thpladmin-colorpickpreview' ).css({ backgroundColor: value });
	});

    $('.thpladmin-colorpick').iris({
		change: function( event, ui ) {
			$( this ).parent().find( '.thpladmin-colorpickpreview' ).css({ backgroundColor: ui.color.toString() });
		},
		hide: true,
		border: true
	}).click( function() {
		$('.iris-picker').hide();
		$(this ).closest('td').find('.iris-picker').show(); 
	});

	$('body').click( function() {
		$('.iris-picker').hide();
	});

	$('.thpladmin-colorpick').click( function( event ) {
		event.stopPropagation();
	});	 

});