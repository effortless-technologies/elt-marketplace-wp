jQuery( function( $ ) { 

	// dataTable
    $('#wcmp_vendor_products_stock_instock_table').DataTable({ 
       "initComplete": function(settings, json){ 
        	var info = this.api().page.info(); 
        	if (info.recordsTotal <= wcmp_vsa_display.table_init.show_no_of_products) {
                $('#wcmp_vendor_products_stock_instock_table_wrapper .dataTables_paginate').hide();
                $('#wcmp_vendor_products_stock_instock_table_wrapper .dataTables_info').hide();
            }else{
            	$('#wcmp_vendor_products_stock_instock_table_wrapper .dataTables_paginate').show();
                $('#wcmp_vendor_products_stock_instock_table_wrapper .dataTables_info').show();
            }
    	},
        "pageLength": wcmp_vsa_display.table_init.show_no_of_products,
        "ordering": wcmp_vsa_display.table_init.order_data,
        "searching": wcmp_vsa_display.table_init.search_data,
        "lengthChange": false,
        "pagingType": "simple_numbers"
    });
    $('#wcmp_vendor_products_stock_lowstock_table').DataTable({ 
       "initComplete": function(settings, json){ 
        	var info = this.api().page.info();
        	if (info.recordsTotal <= wcmp_vsa_display.table_init.show_no_of_products) {
                $('#wcmp_vendor_products_stock_lowstock_table_wrapper .dataTables_paginate').hide();
                $('#wcmp_vendor_products_stock_lowstock_table_wrapper .dataTables_info').hide();
            }else{
            	$('#wcmp_vendor_products_stock_lowstock_table_wrapper .dataTables_paginate').show();
                $('#wcmp_vendor_products_stock_lowstock_table_wrapper .dataTables_info').show();
            }
    	},
        "pageLength": wcmp_vsa_display.table_init.show_no_of_products,
        "ordering": wcmp_vsa_display.table_init.order_data,
        "searching": wcmp_vsa_display.table_init.search_data,
        "lengthChange": false,
        "pagingType": "simple_numbers"
    });
    $('#wcmp_vendor_products_stock_outstock_table').DataTable({ 
       "initComplete": function(settings, json){ 
        	var info = this.api().page.info();
        	if (info.recordsTotal <= wcmp_vsa_display.table_init.show_no_of_products) {
                $('#wcmp_vendor_products_stock_outstock_table_wrapper .dataTables_paginate').hide();
                $('#wcmp_vendor_products_stock_outstock_table_wrapper .dataTables_info').hide();
            }else{
            	$('#wcmp_vendor_products_stock_outstock_table_wrapper .dataTables_paginate').show();
                $('#wcmp_vendor_products_stock_outstock_table_wrapper .dataTables_info').show();
            }
    	},
        "pageLength": wcmp_vsa_display.table_init.show_no_of_products,
        "ordering": wcmp_vsa_display.table_init.order_data,
        "searching": wcmp_vsa_display.table_init.search_data,
        "lengthChange": false,
        "pagingType": "simple_numbers"
    });

});