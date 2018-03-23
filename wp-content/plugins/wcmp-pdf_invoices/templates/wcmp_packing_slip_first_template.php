<?php
/*
 * The template for displaying vendor dashboard
 * Override this template by copying it to yourtheme/wcmp-pdf-invoices/wcmp_packing_slip_first_template.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp PDF Invoices/Templates
 * @version     1.0.5
 */


$ini_msg = '';
    $current_user = wp_get_current_user();
    $order_firstname = $order->get_billing_first_name();
    $order_lastname = $order->get_billing_first_name();
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
    
    /*$term_id = get_vendor_from_an_order($order_id);
    $vendor_id = get_term_meta( $term_id[0]);
    $vendor_id = $vendor_id['_vendor_user_id'][0];*/
    $vendor_id = get_current_user_id();
    $vendor_details =  get_user_meta($vendor_id);
    //print_r($vendor_details);
    $vendor_company = $vendor_details['_vendor_company'][0];
    $vendor_address1 = $vendor_details['_vendor_address_1'][0];
    $vendor_address2 = $vendor_details['_vendor_address_2'][0];
    $vendor_city = $vendor_details['_vendor_city'][0];
    $vendor_postcode = $vendor_details['_vendor_postcode'][0];
    $vendor_state = $vendor_details['_vendor_state'][0];
    $vendor_address = '<br/><strong>'.$vendor_company.'</strong><br>'.$vendor_address1.' '.$vendor_address2.'<br>'.$vendor_city.'<br>'.$vendor_postcode.'<br>'.$vendor_state;
    /*echo $vendor_address;
    die();*/
    /*$term = get_term( $term_id[0] );
    $vendor_name = $term->name;*/
    
    $shipping_method = $order->get_shipping_method();
    $blog_title = get_bloginfo( 'name' );
    $message = '<h1 style="text-align:center;">'.$blog_title.'</h1>';
    //$message.='Current_id: '.$vendor_id.' vendor_name: '.$current_user->user_firstname.$current_user->user_lastname.' general_settings: '.$general_settings['company_logo'];
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

    $buyer_address = '';
    if ( $order->get_formatted_shipping_address() ) {
      $buyer_address = wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ); 
    }elseif($order->get_formatted_billing_address()){
       $buyer_address = wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ); 
    }


    //$image_name = 'download.jpg';
    $current_date = date("Y/m");
    $uploads = wp_upload_dir();
    
    if ($user_type == 'vendor') {
        $vendor_user_details = get_user_meta($vendor_id, 'wcmp_pdf_invoices_settings',true);
        if ($vendor_user_details['vendor_invoice_logo']) {
            $logo = $vendor_user_details['vendor_invoice_logo'];
        }
        else {
            $logo = $general_settings['company_logo'];
        }
    } else {
        $logo = $general_settings['company_logo'];
    }
    
    $type = pathinfo($logo, PATHINFO_EXTENSION);
    $data = file_get_contents($logo);
    $dataUri = 'data:image/' . $type . ';base64,' . base64_encode($data);                                    
    $image_attributes = getimagesize( $logo );
        $image_width = $image_attributes[0];
        $image_height = $image_attributes[1];
        if($image_height > $image_width) {
            $ratio = number_format((float)$image_width/$image_height, 2, '.', '');
            $image_height = '200';
            $image_width = $image_height*$ratio;
            $image_width = round($image_width);
        } else {
            $ratio = number_format((float)$image_height/$image_width, 2, '.', '');
            $image_width = '200';
            $image_height = $image_width*$ratio;
            $image_height = round($image_height);
        }
        //print_r($image_width);print_r($image_height);die();
    $message .= '<br/><br/>'
            . '<div style="position:relative;">'
            . '     <div style="position:absolute; left: 0;">'
            . '         <img width="'.$image_width.'" height="'.$image_height.'" src="'.$dataUri.'" alt="" />'
            . '     </div>'
            . '     <div style="position:absolute; right: 50px;">'
            . '         <table>'
            . '             <tr><td style="text-align: right;">'.$vendor_address.'</td></tr>'
            /*. '             <tr><td style="text-align: right;">'.$order_shipping_address1.'</td></tr>'
            . '             <tr><td style="text-align: right;">'.$order_shipping_address2.'</td></tr>'
            . '             <tr><td style="text-align: right;">'.$order_shipping_city.'</td></tr>'
            . '             <tr><td style="text-align: right;">'.$order_shipping_country.'</td></tr>'
            . '             <tr><td style="text-align: right;">'.$order_shipping_postcode.'</td></tr>'*/
            . '         </table>'
            . '     </div>'
            . ' </div>';
    $message .= '<div style="position:relative;top:200px;">'
            . '     <table style="width:100%;font-size:20px;margin-right:50px;">'
            . '         <tr><td>'.__('PACKING SLIP', 'wcmp-pdf_invoices').'</td><td style="text-align: right;">'.__('Address: ', 'wcmp-pdf_invoices').'</td></tr>'
            . '         <tr><td>'.__('Order Date: ', 'wcmp-pdf_invoices').$order_date.'<br>'.__('Order Number: ', 'wcmp-pdf_invoices').$order_number.'<br>'.__('Payment Method: ', 'wcmp-pdf_invoices').$payment_method.'</td><td style="text-align: right;">'.$buyer_address.'</td></tr>';
    $message .='     </table>';
    $message .= '   <br/><br/>'
            . '     <table style="width:100%;border-collapse:collapse;text-align:center;">'
            . '         <tr style="background-color:black;width:400px;height:40px;font-size:20px; color:white;"><td>'.__('Product Name', 'wcmp-pdf_invoices').'</td><td>'.__('Product Quantity', 'wcmp-pdf_invoices').'</td></tr>';
                            foreach( $order_item as $key => $product ) {
                                $vendor_id = wc_get_order_item_meta( $key, '_vendor_id' );
                                if($vendor_id){
                                    $vendor = get_wcmp_vendor($vendor_id);
                                }
                                //$seller = $product->get_meta_data()[0]->value;
                                $vendor_name = $vendor->user_data->data->display_name;
                                $message.=  '<tr style="font-size:20px;text-align:center;"><td>'.$product['name'].'</td><td>'.$product['qty'].'</td></tr><tr><td>'.__('Sold by: ', 'wcmp-pdf_invoices').$vendor_name.'</td><td></td></tr>';
                            }
    $message.= '    </table><br/>'
            .  '</div>';

    echo $message;