jQuery(function( $ ) {

	function showTooltip( x, y, contents ) {
		$( '<div class="chart-tooltip-transaction">' + contents + '</div>' ).css( {
			top: y - 16,
	   		left: x + 20
		}).appendTo( 'body' ).fadeIn( 200 );
	}

	var prev_data_index = null;
	var prev_series_index = null;

	$( '.chart-placeholder-transaction' ).bind( 'plothover', function ( event, pos, item ) {
		if ( item ) {
			if ( prev_data_index !== item.dataIndex || prev_series_index !== item.seriesIndex ) {
				prev_data_index   = item.dataIndex;
				prev_series_index = item.seriesIndex;

				$( '.chart-tooltip-transaction' ).remove();

				if ( item.series.points.show || item.series.enable_tooltip ) {

					var y = item.series.data[item.dataIndex][1],
						tooltip_content = '';

					if ( item.series.prepend_label ) {
						tooltip_content = tooltip_content + item.series.label + ': ';
					}

					if ( item.series.prepend_tooltip ) {
						tooltip_content = tooltip_content + item.series.prepend_tooltip;
					}

					tooltip_content = tooltip_content + y;

					if ( item.series.append_tooltip ) {
						tooltip_content = tooltip_content + item.series.append_tooltip;
					}

					if ( item.series.pie.show ) {
						showTooltip( pos.pageX, pos.pageY, tooltip_content );
					} else {
						showTooltip( item.pageX, item.pageY, tooltip_content );
					}
				}
			}
		} else {
			$( '.chart-tooltip-transaction' ).remove();
			prev_data_index = null;
		}
	});

});
jQuery(document).ready(function($) {
	$( "#wcmp_frontend_transaction_from_date" ).datepicker();
	$( "#wcmp_frontend_transaction_to_date" ).datepicker();
});