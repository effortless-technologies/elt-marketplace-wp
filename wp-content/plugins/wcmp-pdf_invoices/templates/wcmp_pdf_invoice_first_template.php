<?php
if ($user_type == 'vendor') {
    $vendor_id = $vendor->id;
    $vendor_user_details = get_user_meta($vendor_id, 'wcmp_pdf_invoices_settings', true);
    $order_total_items = $vendor->wcmp_vendor_get_order_item_totals($order, $vendor->term_id);
} else{
    $order_total_items = $order->get_order_item_totals();
}

if ($order_total_items) {
    foreach ($order_total_items as $total_keyy => $totall) {
        if ($total_keyy == 'order_total' || $total_keyy == 'total') {
            $invoice_total = $totall['value'];
        }
    }
}
?>
<div style="width:100%; margin:0px auto; font-family:Trebuchet MS, Arial, Helvetica, sans-serif; color:#555; line-height:24px; font-size:16px">  

    <table width="100%" border="0" cellspacing="0" cellpadding="10" style=" background:#f7f7f7; color:#666; line-height:26px; font-size:16px; border-bottom:1px double #ddd; ">
        <tr>
            <td align="left" valign="middle" style="font-size:40px; color:#444; width:25%;">
                <?php
                if ($user_type == 'vendor') {
                    if ($vendor_user_details)
                        $logo = $vendor_user_details['vendor_invoice_logo'];
                    else
                        $logo = $general_settings['company_logo'];
                } else {
                    $logo = $general_settings['company_logo'];
                }
                //$logo = str_replace(get_site_url(), '..', $logo);
                $type = pathinfo($logo, PATHINFO_EXTENSION);
				$data = file_get_contents($logo);
				$dataUri = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?> 
                <img width="150" src="<?php echo $dataUri; ?>" alt="company_logo">
            </td>    
            <td align="right" valign="top" style="font-size:22px;">INVOICE<br>
                <span style="font-size:14px;"><?php echo date("F j, Y, g:i a"); ?></span>
            </td>
        </tr>
    </table> 
    <table width="100%" border="0" cellspacing="0" cellpadding="10" style="color:#666; line-height:26px; font-size:16px; margin:0 0 20px 0; padding:10px; border-bottom:1px solid #ddd;  ">
        <tr>
            <td style="width : 50%" align="left" valign="middle">
                <p style="color:#777; font-size:16px; margin:0px; padding:0px;">
                    <?php
                    if ($user_type == 'admin') {
                        echo '<strong>' . $general_settings['intro_text_admin'] . '</strong>';
                    } else if ($user_type == 'vendor') {
                        if ($vendor_user_details)
                            echo '<strong>' . $vendor_user_details['intro_text_vendor'] . '</strong>';
                        else
                            echo '<strong>' . $general_settings['intro_text_vendor'] . '</strong>';
                    } else {
                        echo '<strong>' . $general_settings['intro_text_customer'] . '</strong>';
                    }
                    ?>
                </p>
            </td>    
            <td align="right" valign="middle" style="width:50%; font-size:18px; font-weight:bold; color:#af0f38; font-family: DejaVu Sans;"><?php echo 'Invoice total : ' . $invoice_total; ?></td>
        </tr>
    </table> 

    <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
            <td style="padding:10px; width:50%" align="left" valign="top">
                <p style="color:#666; line-height:24px; font-size:18px; padding:0px 1%; margin:0;"><strong>Order Number:</strong> #<?php echo $order->get_order_number(); ?></p>
                <p style="color:#666; line-height:24px; font-size:18px; padding:0px 1%; margin:0;"><strong>Order Date: </strong> <?php echo $order->get_date_created(); ?></p>
                <p style="color:#666; line-height:24px; font-size:18px; padding:0px 1%; margin:0;">
                    <?php
                    if ($user_type == 'admin') {
                        if (isset($general_settings['is_payment_method_admin']) && $general_settings['is_payment_method_admin'] == 'Enable') {
                            ?>
                    <strong>Payment Method:</strong><?php echo $order->get_payment_method_title(); ?>
                            <?php
                        }
                    } else if ($user_type == 'vendor') {
                        if ($vendor_user_details) {
                            if (isset($vendor_user_details['is_payment_method_vendor']) && $vendor_user_details['is_payment_method_vendor'] == 'Enable') {
                                ?>
                    <strong>Payment Method:</strong><?php echo $order->get_payment_method_title(); ?>
                                <?php
                            }
                        } else {
                            if (isset($general_settings['is_payment_method_vendor']) && $general_settings['is_payment_method_vendor'] == 'Enable') {
                                ?>
                    <strong>Payment Method:</strong><?php echo $order->get_payment_method_title(); ?>
                                <?php
                            }
                        }
                    } else if ($user_type == 'customer') {
                        if (isset($general_settings['is_payment_method_customer']) && $general_settings['is_payment_method_customer'] == 'Enable') {
                            ?>
                    <strong>Payment Method:</strong><?php echo $order->get_payment_method_title(); ?>
                            <?php
                        }
                    }
                    ?>
                </p>				
                <?php
                if ($user_type == 'vendor') {
                    if ($vendor_user_details) {
                        echo '<p style="color:#777; font-size:20px;"> Special Note from Admin:</p><p style="color:#444; font-size:16px;">' . $vendor_user_details['spcl_notes_from_vendor'] . '</p></p>';
                    } else {
                        echo '<p style="color:#777; font-size:20px;"> Special Note from Admin:</p><p style="color:#444; font-size:16px;">' . $general_settings['spcl_notes_from_admin'] . '</p></p>';
                    }
                } else if (!empty($general_settings['spcl_note_from_admin'])) {
                    echo '<p style="color:#777; font-size:20px;"> Special Note from Admin:</p><p style="color:#444; font-size:16px;">' . $general_settings['spcl_note_from_admin'] . '</p></p>';
                }
                ?>
            </td>
            <td style="padding:0px 0 10px 5%; ">
                <table width="100%" border="0" cellspacing="0" cellpadding="10">
                    <tr>
                        <td align="left" valign="top">
                            <h3 style="color:#777; font-size:15px; font-weight:bold">Invoice To:</h3>
                            <?php
                            if (!$order->get_formatted_billing_address())
                                _e('N/A', 'woocommerce');
                            else
                                echo $order->get_formatted_billing_address();
                            ?>  
                        </td>
                        <td align="left" valign="top">
                            <h3 style="color:#777; font-size:15px; font-weight:bold">Ship To:</h3>
                            <?php
                            if (!$order->get_formatted_shipping_address())
                                _e('N/A', 'woocommerce');
                            else
                                echo $order->get_formatted_shipping_address();
                            ?>  
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="color:#777; font-size:18px; padding:0 4%"> Order Details</div> 
    <table width="100%" border="0" cellspacing="0" cellpadding="10"  style="border:1px solid #ddd; background:#f8f8f8; color:#666; line-height:26px; font-size:16px; ">
        <thead>
            <tr>
                <td align="left" valign="top" style="border-bottom:1px solid #ddd;" >Item</td>
                <?php
                if ($user_type == 'admin') {
                    if (isset($general_settings['is_sku_admin']) && $general_settings['is_sku_admin'] == 'Enable') {
                        ?>
                        <td align="left" valign="top" style="border-bottom:1px solid #ddd;">SKU</td>
                        <?php
                    }
                } else if ($user_type == 'vendor') {
                    if ($vendor_user_details) {
                        if (isset($vendor_user_details['is_sku_vendor']) && $vendor_user_details['is_sku_vendor'] == 'Enable') {
                            ?>
                            <td align="left" valign="top" style="border-bottom:1px solid #ddd;">SKU</td>
                            <?php
                        }
                    } else {
                        if (isset($general_settings['is_sku_vendor']) && $general_settings['is_sku_vendor'] == 'Enable') {
                            ?>
                            <td align="left" valign="top" style="border-bottom:1px solid #ddd;">SKU</td>
                            <?php
                        }
                    }
                } else if ($user_type == 'customer') {
                    if (isset($general_settings['is_sku_customer']) && $general_settings['is_sku_customer'] == 'Enable') {
                        ?>
                        <td align="left" valign="top" style="border-bottom:1px solid #ddd;">SKU</td>
                        <?php
                    }
                }
                ?>
                <td align="left" valign="top" style="border-bottom:1px solid #ddd;" >Quantity</td>
                <td align="left" valign="top" style="border-bottom:1px solid #ddd;" >Cost</td>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($vendor)) {
                $order_items = $vendor->get_vendor_items_from_order($order->get_id(), $vendor->term_id);
            } else {
                $order_items = $order->get_items();
            }
            if (!empty($order_items)) {
                foreach ($order_items as $item_id => $item) {
                    $product = get_product($item['variation_id'] ? $item['variation_id'] : $item['product_id'] );
                    ?>
                    <tr>
                        <td align="left" valign="top" style="border-bottom:1px solid #ddd; font-size:17px; color:#444;">
                            <?php
                            echo $product->get_title();
                            global $wpdb;
                            if ($metadata = $order->has_meta($item_id)) {
                                foreach ($metadata as $meta) {

                                    // Skip hidden core fields
                                    if (in_array($meta['meta_key'], apply_filters('woocommerce_hidden_order_itemmeta', array(
                                                '_qty',
                                                '_tax_class',
                                                '_product_id',
                                                '_variation_id',
                                                '_line_subtotal',
                                                '_line_subtotal_tax',
                                                '_line_total',
                                                '_line_tax',
                                                'flat_shipping_per_item'
                                            )))) {
                                        continue;
                                    }

                                    // Skip serialised meta
                                    if (is_serialized($meta['meta_value'])) {
                                        continue;
                                    }

                                    // Get attribute data
                                    if (taxonomy_exists(wc_sanitize_taxonomy_name($meta['meta_key']))) {
                                        $term = get_term_by('slug', $meta['meta_value'], wc_sanitize_taxonomy_name($meta['meta_key']));
                                        $meta['meta_key'] = wc_attribute_label(wc_sanitize_taxonomy_name($meta['meta_key']));
                                        $meta['meta_value'] = isset($term->name) ? $term->name : $meta['meta_value'];
                                    } else {
                                        $meta['meta_key'] = apply_filters('woocommerce_attribute_label', wc_attribute_label($meta['meta_key'], $product), $meta['meta_key']);
                                    }

                                    echo '<div class="item-attribute"><span style="font-weight: bold;">' . wp_kses_post(rawurldecode($meta['meta_key'])) . ': </span>' . wp_kses_post(rawurldecode($meta['meta_value'])) . '</div>';
                                }
                            }
                            ?>
                        </td>
                        <?php
                        if ($user_type == 'admin') {
                            if (isset($general_settings['is_sku_admin']) && $general_settings['is_sku_admin'] == 'Enable') {
                                ?>
                                <td align="left" valign="top" style="border-bottom:1px solid #ddd;"><?php echo ( $product->get_sku() != '' ) ? $product->get_sku() : '-'; ?></td>
                                <?php
                            }
                        } else if ($user_type == 'vendor') {
                            if ($vendor_user_details) {
                                if (isset($vendor_user_details['is_sku_vendor']) && $vendor_user_details['is_sku_vendor'] == 'Enable') {
                                    ?>
                                    <td align="left" valign="top" style="border-bottom:1px solid #ddd;"><?php echo ( $product->get_sku() != '' ) ? $product->get_sku() : '-'; ?></td>
                                    <?php
                                }
                            } else {
                                if (isset($general_settings['is_sku_vendor']) && $general_settings['is_sku_vendor'] == 'Enable') {
                                    ?>
                                    <td align="left" valign="top" style="border-bottom:1px solid #ddd;" ><?php echo ( $product->get_sku() != '' ) ? $product->get_sku() : '-'; ?></td>
                                    <?php
                                }
                            }
                        } else if ($user_type == 'customer') {
                            if (isset($general_settings['is_sku_customer']) && $general_settings['is_sku_customer'] == 'Enable') {
                                ?>
                                <td align="left" valign="top" style="border-bottom:1px solid #ddd;" ><?php echo ( $product->get_sku() != '' ) ? $product->get_sku() : '-'; ?></td>
                                <?php
                            }
                        }
                        ?>
                        <td  align="left" valign="top" style="border-bottom:1px solid #ddd;" ><?php echo $item['qty']; ?></td>
                        <td align="left" valign="top" style="border-bottom:1px solid #ddd; font-family: DejaVu Sans;"  ><?php echo $order->get_formatted_line_subtotal($item) ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <table border="0" cellspacing="0" cellpadding="5"  width="100%" style="color:#666; line-height:26px; font-size:16px;">
        <tbody>
            <?php
            $wcmp_pdf_setting_array = array('cart_subtotal' => 'is_subtotal', 'discount' => 'is_discount', 'shipping' => 'is_shipping', 'tax' => 'is_tax');

            if ($totals = $order_total_items) {
                $i = 0;
                foreach ($totals as $total_key => $total) {

                    if ($total_key == 'payment_method' || $total_key == 'order_total')
                        continue;

                    $i++;

                    if (array_key_exists($total_key, $wcmp_pdf_setting_array)) {
                        if ($user_type == 'admin') {
                            if (isset($general_settings[$wcmp_pdf_setting_array[$total_key] . '_admin']) && $general_settings[$wcmp_pdf_setting_array[$total_key] . '_admin'] == 'Enable') {
                                ?><tr>
                                    <td align="right" valign="top" style="width:60%"><?php echo $total['label']; ?></td>
                                    <td align="right" valign="top" style="font-size:18px;font-family: DejaVu Sans;"><?php echo $total['value']; ?></td>
                                </tr><?php
                            }
                        } else if ($user_type == 'vendor') {
                            if ($vendor_user_details) {
                                if (isset($vendor_user_details[$wcmp_pdf_setting_array[$total_key] . '_vendor']) && $vendor_user_details[$wcmp_pdf_setting_array[$total_key] . '_vendor'] == 'Enable') {
                                    ?><tr>
                                        <td align="right" valign="top" style="width:60%"> <?php echo $total['label']; ?></td>
                                        <td align="right" valign="top" style="font-size:18px; font-family: DejaVu Sans;"><?php echo $total['value']; ?></td>
                                    </tr><?php
                                }
                            } else {
                                if (isset($general_settings[$wcmp_pdf_setting_array[$total_key] . '_vendor']) && $general_settings[$wcmp_pdf_setting_array[$total_key] . '_vendor'] == 'Enable') {
                                    ?><tr>
                                        <td align="right" valign="top" style="width:60%"><?php echo $total['label']; ?></td>
                                        <td align="right" valign="top" style="font-size:18px; font-family: DejaVu Sans;"><?php echo $total['value']; ?></td>
                                    </tr><?php
                                }
                            }
                        } else if ($user_type == 'customer') {
                            if (isset($general_settings[$wcmp_pdf_setting_array[$total_key] . '_customer']) && $general_settings[$wcmp_pdf_setting_array[$total_key] . '_customer'] == 'Enable') {
                                ?><tr>
                                    <td align="right" valign="top" style="width:60%"><?php echo $total['label']; ?></td>
                                    <td align="right" valign="top" style="font-size:18px; font-family: DejaVu Sans;"><?php echo $total['value']; ?></td>
                                </tr><?php
                            }
                        }
                    } else if ($total_key == 'order_total') {
                        ?>
                        <tr>
                            <td align="right" valign="top" style="font-size:20px; border-top:1px solid #bbb; color:#222; width: 60%;" > <?php echo $total['label']; ?></td>
                            <td align="right" valign="top" style="font-size:20px; font-family: DejaVu Sans; border-top:1px solid #bbb; color:#222" > <?php echo $total['value']; ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td align="right" valign="top" style=" width: 60%;"><?php echo $total['label']; ?></td>
                            <td align="right" valign="top" style="font-size:18px; font-family: DejaVu Sans;" ><?php echo $total['value']; ?></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="10"  style="color:#666; line-height:26px; font-size:14px; ">

        <?php if ($user_type == 'admin') { ?>
            <tr><td align="left" valign="top" style="font-size:18px; color:#444; ">Risk of loss</td></tr>
            <tr><td align="left" valign="top" style="font-size:14px;"><?php echo $general_settings['term_and_conditions_admin']; ?></td></tr>
            <?php
        } else if ($user_type == 'vendor') {
            if ($vendor_user_details) {
                ?>
                <tr><td align="left" valign="top" style="font-size:18px; color:#444; ">Risk of loss</td></tr>
                <tr><td align="left" valign="top" style="font-size:14px;"><?php echo $general_settings['term_and_conditions_admin']; ?></td></tr>
            <?php } else { ?>
                <tr><td align="left" valign="top" style="font-size:18px; color:#444; ">Risk of loss</td></tr>
                <tr><td align="left" valign="top" style="font-size:14px;"><?php echo $general_settings['term_and_conditions_admin']; ?></td></tr>
                <?php
            }
        } else {
            ?>
            <tr><td align="left" valign="top" style="font-size:18px; color:#444; ">Risk of loss</td></tr>
            <tr><td align="left" valign="top" style="font-size:14px;"><?php echo $general_settings['term_and_conditions_admin']; ?></td></tr>
            <?php
        }
        ?>
    </table>
    <table border="0" cellspacing="0" cellpadding="5"  style="color:#666; line-height:24px; font-size:14px; width:100%; padding:5px 0;  ">
        <?php
        if ($user_type == 'admin') {
            if (isset($general_settings['is_customer_note_admin']) && $general_settings['is_customer_note_admin'] == 'Enable') {
                ?>
                <tr><td colspan="2" align="left" valign="top" style="font-size:18px; color:#444; ">Customer Note</td></tr><tr><td  colspan="2"  align="left" valign="top" style="font-size:14px;"><span style="color:#666; font-weight:normal;"><?php echo $order->get_customer_note(); ?></td></tr>
                <?php
            }
        } else if ($user_type == 'vendor') {
            if ($vendor_user_details) {
                if (isset($vendor_user_details['is_customer_note_vendor']) && $vendor_user_details['is_customer_note_vendor'] == 'Enable') {
                    ?>
                    <tr><td colspan="2" align="left" valign="top" style="font-size:18px; color:#444; ">Customer Note</td></tr><tr><td  colspan="2"  align="left" valign="top" style="font-size:14px;"><span style="color:#666; font-weight:normal;"><?php echo $order->get_customer_note(); ?></td></tr>
                    <?php
                }
            } else {
                if (isset($general_settings['is_customer_note_vendor']) && $general_settings['is_customer_note_vendor'] == 'Enable') {
                    ?>
                    <tr><td colspan="2" align="left" valign="top" style="font-size:18px; color:#444; ">Customer Note</td></tr><tr><td  colspan="2"  align="left" valign="top" style="font-size:14px;"><span style="color:#666; font-weight:normal;"><?php echo $order->get_customer_note(); ?></td></tr>
                    <?php
                }
            }
        } else if ($user_type == 'customer') {
            if (isset($general_settings['is_customer_note_customer']) && $general_settings['is_customer_note_customer'] == 'Enable') {
                ?>
                <tr><td colspan="2" align="left" valign="top" style="font-size:18px; color:#444; ">Customer Note</td></tr><tr><td  colspan="2"  align="left" valign="top" style="font-size:14px;"><span style="color:#666; font-weight:normal;"><?php echo $order->get_customer_note(); ?></td></tr>
                <?php
            }
        }
        ?>
    </table>
    <table border="0" cellspacing="0" cellpadding="5"  style="color:#666; line-height:24px; font-size:15px; width:100%; border-top:1px solid #aaa; padding:5px 0;  ">
        <tr>
            <td colspan="3" align="left" valign="top" style=" width: 78%;">
                <?php echo nl2br($general_settings['company_address']); ?>
            </td>    
            <td align="right" valign="top" >
                <?php echo 'Email: ' . $general_settings['company_email']; ?><br><?php echo 'Ph No:' . $general_settings['company_ph_no']; ?> 
            </td>
        </tr>
    </table>
</div>