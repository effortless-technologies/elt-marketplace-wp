<?php
$ini_msg = '';
    $current_user = wp_get_current_user();
    $user_firstname = $current_user->user_firstname;
    $user_lastname = $current_user->user_lastname;
    $order_item = $order->get_items();
    $order_number = $order->get_order_number();
    $order_date = $order->get_date_created();
    $payment_method = $order->get_payment_method();
    $order_date = date_format($order_date, 'Y-m-d');
    $order_billing_address1 = $order->get_billing_address_1();
    $order_billing_address2 = $order->get_billing_address_2();
    $order_billing_city = $order->get_billing_city();
    $order_billing_country = $order->get_billing_country();
    $order_billing_postcode = $order->get_billing_postcode();
    
    if(empty($order_billing_address1 = $order->get_billing_address_1())) {
        $order_billing_address1 = '';
    }

    if(empty($order_billing_address2 = $order->get_billing_address_2())) {
        $order_billing_address2 = '';
    }

    if(empty($order_billing_city = $order->get_billing_city())) {
        $order_billing_city = '';
    }

    if(empty($order_billing_country = $order->get_billing_country())) {
        $order_billing_country = '';
    }

    if(empty($order_billing_postcode = $order->get_billing_postcode())) {
        $order_billing_postcode = '';
    }
    
    $term_id = get_vendor_from_an_order($order_id);
    $vendor_id = get_term_meta( $term_id[0]);
    $vendor_id = $vendor_id['_vendor_user_id'][0];
    $term = get_term( $term_id[0] );
    $vendor_name = $term->name;
    
    $shipping_method = $order->get_shipping_method();
    $blog_title = get_bloginfo( 'name' );
    $message = '<h1 style="text-align:center;">'.$blog_title.'</h1>';
    if(empty($order_shipping_address1 = $order->get_shipping_address_1())){
        $order_shipping_address1 = '';
    }
    if(empty($order_shipping_address2 = $order->get_shipping_address_2())){
        $order_shipping_address2 = '';
    }
    if(empty($order_shipping_city = $order->get_shipping_city())){
        $order_shipping_city = '';
    }
    if(empty($order_shipping_postcode = $order->get_shipping_postcode())){
        $order_shipping_postcode = '';
    }
    if(empty($order_shipping_company = $order->get_shipping_company())){
        $order_shipping_company = '';
    }
    if(empty($order_shipping_country = $order->get_shipping_country())){
        $order_shipping_country = '';
    }
    $image_name = 'download.jpg';
    $current_date = date("Y/m");
    $uploads = wp_upload_dir();
    
    if ($user_type == 'vendor') {
        $vendor_user_details = get_user_meta($vendor_id, 'wcmp_pdf_invoices_settings',true);
        if ($vendor_user_details)
            $logo = $vendor_user_details['vendor_invoice_logo'];
        else
            $logo = $general_settings['company_logo'];
    } else {
        $logo = $general_settings['company_logo'];
    }
    
    $type = pathinfo($logo, PATHINFO_EXTENSION);
    $data = file_get_contents($logo);
    $dataUri = 'data:image/' . $type . ';base64,' . base64_encode($data);                                    
    
    $message .= '<br/><br/>'
            . '<div style="position:relative;">'
            . '     <div style="position:absolute; left: 0;">'
            . '         <img width="150" src="'.$dataUri.'" alt="" />'
            . '     </div>'
            . '     <div style="position:absolute; right: 50px;">'
            . '         <table>'
            . '             <tr><td style="text-align: right;"><strong>'.$order_shipping_company.'</strong></td></tr>'
            . '             <tr><td style="text-align: right;">'.$order_shipping_address1.'</td></tr>'
            . '             <tr><td style="text-align: right;">'.$order_shipping_address2.'</td></tr>'
            . '             <tr><td style="text-align: right;">'.$order_shipping_city.'</td></tr>'
            . '             <tr><td style="text-align: right;">'.$order_shipping_country.'</td></tr>'
            . '             <tr><td style="text-align: right;">'.$order_shipping_postcode.'</td></tr>'
            . '         </table>'
            . '     </div>'
            . ' </div>';
    $message .= '<div style="position:relative;top:200px;">'
            . '     <table style="width:100%;font-size:20px;margin-right:50px;">'
            . '         <tr><td>PACKING SLIP</td><td style="text-align: right;">User Name: '.$user_firstname.' '.$user_lastname.'</td></tr>'
            . '         <tr><td>Order Date: '.$order_date.'</td><td style="text-align: right;">'.$order_billing_address1.' '.$order_billing_address2.'</td></tr>'
            . '         <tr><td>Order number: '.$order_number.'</td><td style="text-align: right;">'.$order_billing_city.' '.$order_billing_postcode.'</td></tr>';
    $message .= '       <tr><td>Payment Method: '.$payment_method.'</td><td style="text-align: right;">'.$order_billing_country.'</td></tr>';
    $message .='     </table>';
    $message .= '   <br/><br/>'
            . '     <table style="width:100%;border-collapse:collapse;text-align:center;">'
            . '         <tr style="background-color:black;width:400px;height:40px;font-size:20px; color:white;"><td>Product Name</td><td>Product Quantity</td></tr>';
                            foreach( $order_item as $product ) {
                                $message.=  '<tr style="font-size:20px;text-align:center;"><td>'.$product['name'].'</td><td>'.$product['qty'].'</td></tr>';
                            }
    $message.= '    </table><br/>'
            .  '</div>';

    echo $message;