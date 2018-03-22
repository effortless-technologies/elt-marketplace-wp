<?php
/*
 * The template for displaying vendor dashboard
 * Override this template by copying it to yourtheme/wcmp-pdf-invoices/wcmp_pdf_invoice_fifth_template.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp PDF Invoices/Templates
 * @version     1.0.5
 */

if ($user_type == 'vendor') {
    $vendor_id = $vendor->id;
    $vendor_user_details = get_user_meta($vendor_id, 'wcmp_pdf_invoices_settings', true);
}
?>
<div style="width:100%; margin:0px auto; color:#555; line-height:24px; font-size:12px" class="alignCenter profLogo">


    <div style="width:100%; margin:0px auto; color:#555; line-height:24px; font-size:12px" class="alignCenter profLogo">
        <table width="100%" border="0" cellspacing="0" cellpadding="10"  style="color:#666; line-height:26px; font-size:12px; margin:10px 0; padding:10px; border-bottom:1px double #ddd; ">
            <tbody>
                <tr>
                    <td align="left" valign="top" style="font-size:35px; color:#444; width:25%; padding-bottom:15px;"><?php _e('INVOICE', 'wcmp-pdf_invoices'); ?></td>   
                    <td align="right" valign="middle" style="font-size:12px;"><?php echo date("F j, Y, g:i a"); ?></td>
                </tr>
            </tbody>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tbody>
                <tr>
                    <td style="padding:10px; width:45%" align="left" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="10">
                            <tbody>
                                <tr>
                                    <td align="left" valign="middle" style="padding:10px;">
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
                                    <td align="left" valign="top">
                                        <h3><?php _e('Company Details', 'wcmp-pdf_invoices'); ?> </h3>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="top"><?php _e('Name - ', 'wcmp-pdf_invoices'); ?></td><td align="left" valign="top"><?php echo $general_settings['company_name']; ?></td>
                                                </tr>  
                                                <tr>
                                                    <td  align="left" valign="top"><?php _e('Address - ', 'wcmp-pdf_invoices'); ?></td><td align="left" valign="top"><?php echo nl2br($general_settings['company_address']); ?></td>
                                                </tr>  
                                                <tr>
                                                    <td align="left" valign="top"><?php _e('Email - ', 'wcmp-pdf_invoices'); ?></td><td align="left" valign="top"><?php echo $general_settings['company_email']; ?></td>
                                                </tr>  
                                                <tr>
                                                    <td align="left" valign="top"><?php _e('Ph No. - ', 'wcmp-pdf_invoices'); ?></td><td align="left" valign="top"><?php echo $general_settings['company_ph_no']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="background:#f8f8f8; padding:10px; border-left:1px solid #ddd; ">
                        <table width="100%" border="0" cellspacing="0" cellpadding="10">
                            <tbody>
                                <tr>
                                    <td align="left" valign="top">
                                        <h3><?php _e('Invoice To:', 'wcmp-pdf_invoices'); ?></h3>
                                        <?php
                                        if (!$order->get_formatted_billing_address())
                                            _e('N/A', 'wcmp-pdf_invoices');
                                        else
                                            echo $order->get_formatted_billing_address();
                                        ?>   
                                    </td>
                                    <td align="left" valign="top">
                                        <h3><?php _e('Ship To:', 'wcmp-pdf_invoices'); ?></h3>
                                        <?php
                                        if (!$order->get_formatted_shipping_address())
                                            _e('N/A', 'wcmp-pdf_invoices');
                                        else
                                            echo $order->get_formatted_shipping_address();
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="background:#f8f8f8; padding:20px 3%; border-top:1px solid #222; border-bottom:1px solid #222; width:94%; color:#444; line-height:20px; font-size:18px; margin:0px 0 20px 0; margin-bottom: 50px;">
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
    </div>
    <div>
        <table width="100%" border="0" cellspacing="0" cellpadding="10" style="margin-top: 400px;">
            <tbody>
                <tr>
                    <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php _e('Order Number:', 'wcmp-pdf_invoices'); ?></td>
                    <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" class="orderNumber"><?php echo $order->get_order_number(); ?></td>
                    <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php _e('Order Date:', 'wcmp-pdf_invoices'); ?></td>
                    <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" class="order_date"><?php echo $order->get_date_created(); ?></td>
                    <?php
                    if ($user_type == 'admin') {
                        if (isset($general_settings['is_payment_method_admin']) && $general_settings['is_payment_method_admin'] == 'Enable') {
                            ?>
                            <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php _e('Payment Method:', 'wcmp-pdf_invoices'); ?></td>
                            <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php echo $order->get_payment_method_title(); ?></td>
                            <?php
                        }
                    } else if ($user_type == 'vendor') {
                        if ($vendor_user_details) {
                            if (isset($vendor_user_details['is_payment_method_vendor']) && $vendor_user_details['is_payment_method_vendor'] == 'Enable') {
                                ?>
                                <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php _e('Payment Method:', 'wcmp-pdf_invoices'); ?></td>
                                <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php echo $order->get_payment_method_title(); ?></td>
                                <?php
                            }
                        } else {
                            if (isset($general_settings['is_payment_method_vendor']) && $general_settings['is_payment_method_vendor'] == 'Enable') {
                                ?>
                                <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php _e('Payment Method:', 'wcmp-pdf_invoices'); ?></td>
                                <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php echo $order->get_payment_method_title(); ?></td>
                                <?php
                            }
                        }
                    } else if ($user_type == 'customer') {
                        if (isset($general_settings['is_payment_method_customer']) && $general_settings['is_payment_method_customer'] == 'Enable') {
                            ?>
                            <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php _e('Payment Method:', 'wcmp-pdf_invoices'); ?></td>
                            <td  style="color:#666; line-height:24px; font-size:14px; padding:30px 1%;width:30%;" ><?php echo $order->get_payment_method_title(); ?></td>
                            <?php
                        }
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
    <?php if ($user_type == 'vendor') { ?>
        <div style="padding:20px 1%; width:98%; line-height:24px; ">
            <div style="color:#777; font-size:18px;"> <?php _e('Special Note from Admin:', 'wcmp-pdf_invoices'); ?></div>
            <?php
            if ($vendor_user_details) {
                echo '<p style="color:#444; font-size:12px;">' . $vendor_user_details['spcl_notes_from_vendor'];
            } else {
                echo '<p style="color:#444; font-size:12px;">' . $general_settings['spcl_notes_from_admin'];
            }
            ?>
        </div> 
    <?php } else if (!empty($general_settings['spcl_notes_from_admin'])) { ?>
        <div style="padding:20px 1%; width:98%; line-height:24px; ">
            <h3 style="color:#777; font-size:22px;"> <?php _e('Special Note from Admin:', 'wcmp-pdf_invoices'); ?></h3>
            <?php
            echo '<p style="color:#444; font-size:12px;">' . $general_settings['spcl_notes_from_admin'];
            ?>
        </div>
    <?php } ?>

    <div style="color:#777; font-size:22px; padding-bottom:20px;"> <?php _e('Order Details', 'wcmp-pdf_invoices'); ?></div> 

    <table width="100%" border="0" cellspacing="0" cellpadding="10"  style="border:4px solid #bbb; color:#666; line-height:26px; font-size:12px;background-color: #f4f8ff;">
        <thead>
            <tr>
                <td scope="col" align="left" valign="top"><strong><?php _e('Item', 'wcmp-pdf_invoices'); ?></strong></td>
                <td scope="col" align="left" valign="top"><strong><?php _e('SKU', 'wcmp-pdf_invoices'); ?></strong></td>
                <td scope="col" align="left" valign="top"><strong><?php _e('Quantity', 'wcmp-pdf_invoices'); ?></strong></td>
                <td scope="col" align="left" valign="top"><strong><?php _e('Cost', 'wcmp-pdf_invoices'); ?></strong></td>
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
                        <td align="left" valign="top" style="border-bottom:1px solid #bbb; font-size:14px; ">
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

                                    echo '<div class="item-attribute"><span style="">' . wp_kses_post(rawurldecode($meta['meta_key'])) . ': </span>' . wp_kses_post(rawurldecode($meta['meta_value'])) . '</div>';
                                }
                            }
                            ?>
                        </td>
                        <?php
                        if ($user_type == 'admin') {
                            if (isset($general_settings['is_sku_admin']) && $general_settings['is_sku_admin'] == 'Enable') {
                                ?>
                                <td align="left" valign="top" style="border-bottom:1px solid #bbb;"><?php echo ( $product->get_sku() != '' ) ? $product->get_sku() : '-'; ?></td>
                                <?php
                            } else {
                                ?>
                                <td align="left" valign="top" style="border-bottom:1px solid #bbb;"><?php _e('NA', 'wcmp-pdf_invoices'); ?></td><?php
                            }
                        } else if ($user_type == 'vendor') {
                            if ($vendor_user_details) {
                                if (isset($vendor_user_details['is_sku_vendor']) && $vendor_user_details['is_sku_vendor'] == 'Enable') {
                                    ?>
                                    <td  align="left" valign="top" style="border-bottom:1px solid #bbb;"><?php echo ( $product->get_sku() != '' ) ? $product->get_sku() : '-'; ?></td>
                                    <?php
                                } else {
                                    ?>
                                <td align="left" valign="top" style="border-bottom:1px solid #bbb;"><?php _e('NA', 'wcmp-pdf_invoices'); ?></td><?php
                                }
                            } else {
                                if (isset($general_settings['is_sku_vendor']) && $general_settings['is_sku_vendor'] == 'Enable') {
                                    ?>
                                    <td   align="left" valign="top" style="border-bottom:1px solid #bbb;" ><?php echo ( $product->get_sku() != '' ) ? $product->get_sku() : '-'; ?></td>
                                    <?php
                                } else {
                                    ?>
                                <td align="left" valign="top" style="border-bottom:1px solid #bbb;"><?php _e('NA', 'wcmp-pdf_invoices'); ?></td><?php
                                }
                            }
                        } else if ($user_type == 'customer') {
                            if (isset($general_settings['is_sku_customer']) && $general_settings['is_sku_customer'] == 'Enable') {
                                ?>
                                <td  align="left" valign="top" style="border-bottom:1px solid #bbb;" ><?php echo ( $product->get_sku() != '' ) ? $product->get_sku() : '-'; ?></td>
                                <?php
                            } else {
                                ?>
                                <td align="left" valign="top" style="border-bottom:1px solid #bbb;"><?php _e('NA', 'wcmp-pdf_invoices'); ?></td><?php
                            }
                        }
                        ?>
                        <td  align="left" valign="top" style="border-bottom:1px solid #bbb;" ><?php echo $item['qty']; ?></td>
                        <td  align="left" valign="top" style="border-bottom:1px solid #bbb;" ><?php echo $order->get_formatted_line_subtotal($item) ?></td>
                    </tr>
                    <?php
                }
            }
            ?>

            <?php
            $wcmp_pdf_setting_array = array('cart_subtotal' => 'is_subtotal', 'discount' => 'is_discount', 'shipping' => 'is_shipping', 'tax' => 'is_tax');

            if (!empty($vendor)) {
                $order_total_items = $vendor->wcmp_vendor_get_order_item_totals($order, $vendor->term_id);
            } else {
                $order_total_items = $order->get_order_item_totals();
            }
            if ($totals = $order_total_items) {
                $i = 0;
                foreach ($totals as $total_key => $total) {

                    if ($total_key == 'payment_method')
                        continue;

                    $i++;

                    if (array_key_exists($total_key, $wcmp_pdf_setting_array)) {
                        if ($user_type == 'admin') {
                            if (isset($general_settings[$wcmp_pdf_setting_array[$total_key] . '_admin']) && $general_settings[$wcmp_pdf_setting_array[$total_key] . '_admin'] == 'Enable') {
                                ?><tr>
                                    <td colspan="3" align="left" valign="top" style="font-size:14px; "><?php echo $total['label']; ?></td>
                                    <td align="left" valign="top" style="font-size:15px;"><?php echo $total['value']; ?></td>
                                </tr><?php
                            }
                        } else if ($user_type == 'vendor') {
                            if ($vendor_user_details) {
                                if (isset($vendor_user_details[$wcmp_pdf_setting_array[$total_key] . '_vendor']) && $vendor_user_details[$wcmp_pdf_setting_array[$total_key] . '_vendor'] == 'Enable') {
                                    ?><tr>
                                        <td colspan="3" align="left" valign="top" style="font-size:14px; " ><?php echo $total['label']; ?></td>
                                        <td align="left" valign="top" style="font-size:15px;"><?php echo $total['value']; ?></td>
                                    </tr><?php
                                }
                            } else {
                                if (isset($general_settings[$wcmp_pdf_setting_array[$total_key] . '_vendor']) && $general_settings[$wcmp_pdf_setting_array[$total_key] . '_vendor'] == 'Enable') {
                                    ?><tr>
                                        <td colspan="3" align="left" valign="top" style="font-size:14px; "><?php echo $total['label']; ?></td>
                                        <td align="left" valign="top" style="font-size:15px;"><?php echo $total['value']; ?></td>
                                    </tr><?php
                                }
                            }
                        } else if ($user_type == 'customer') {
                            if (isset($general_settings[$wcmp_pdf_setting_array[$total_key] . '_customer']) && $general_settings[$wcmp_pdf_setting_array[$total_key] . '_customer'] == 'Enable') {
                                ?><tr>
                                    <td colspan="3" align="left" valign="top" style="font-size:14px; "><?php echo $total['label']; ?></td>
                                    <td align="left" valign="top" style="font-size:15px;"><?php echo $total['value']; ?></td>
                                </tr><?php
                            }
                        }
                    } else if ($total_key == 'order_total') {
                        ?>
                        <tr>
                            <td colspan="3" align="left" valign="top" style="font-size:18px; border-top:1px solid #bbb; color:#222" > <?php echo $total['label']; ?></td>
                            <td align="left" valign="top" style="font-size:18px; border-top:1px solid #bbb; color:#222;" > <?php echo $total['value']; ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3" align="left" valign="top" style="font-size:14px; "><?php echo $total['label']; ?></td>
                            <td align="left" valign="top" style="font-size:15px;" ><?php echo $total['value']; ?></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </tbody>
    </table>

    <table width="100%" border="0" cellspacing="0" cellpadding="10"  style="color:#666; line-height:16px; font-size:12px; margin:30px 0; ">
        <tbody>
            <tr>
                <?php if ($user_type == 'admin') { ?>
                    <td align="left" valign="top" style="font-size:17px; color:#444; " ><strong><?php _e('Risk of loss', 'wcmp-pdf_invoices'); ?></strong></td>
                    <td align="left" valign="top" style="font-size:12px;" ><?php echo $general_settings['term_and_conditions_admin']; ?></td>
                    <?php
                } else if ($user_type == 'vendor') {
                    if ($vendor_user_details) {
                        ?>
                        <td align="left" valign="top" style="font-size:17px; color:#444;" ><strong><?php _e('Risk of loss', 'wcmp-pdf_invoices'); ?></strong></td>
                        <td align="left" valign="top" style="font-size:12px;" ><?php echo $vendor_user_details['term_and_conditions_vendor'] ? $vendor_user_details['term_and_conditions_vendor'] : "NA"; ?></td>
                    <?php } else { ?>
                        <td align="left" valign="top" style="font-size:17px; color:#444;" ><strong><?php _e('Risk of loss', 'wcmp-pdf_invoices'); ?></strong></td>
                        <td align="left" valign="top" style="font-size:12px;" ><?php echo $general_settings['term_and_conditions_vendor']; ?></td>
                        <?php
                    }
                } else {
                    ?>
                    <td align="left" valign="top" style="font-size:17px; color:#444;" ><strong><?php _e('Risk of loss', 'wcmp-pdf_invoices'); ?></strong></td>
                    <td align="left" valign="top" style="font-size:12px;" ><?php echo $general_settings['term_and_conditions_customer']; ?></td>
                    <?php
                }
                ?>

            </tr>

            <?php
            if ($user_type == 'admin') {
                if (isset($general_settings['is_customer_note_admin']) && $general_settings['is_customer_note_admin'] == 'Enable') {
                    ?>
                    <tr>
                        <td colspan="2" align="left" valign="top" style="font-size:17px; color:#777; " ><strong><?php _e('Customer Note', 'wcmp-pdf_invoices'); ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left" valign="top" style="font-size:12px;"><?php echo $order->get_customer_note(); ?></td>
                    </tr>
                    <?php
                }
            } else if ($user_type == 'vendor') {
                if ($vendor_user_details) {
                    if (isset($vendor_user_details['is_customer_note_vendor']) && $vendor_user_details['is_customer_note_vendor'] == 'Enable') {
                        ?>
                        <tr>
                            <td colspan="2" align="left" valign="top" style="font-size:17px; color:#777; " ><strong><?php _e('Customer Note', 'wcmp-pdf_invoices'); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left" valign="top" style="font-size:12px;"><?php echo $order->get_customer_note(); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    if (isset($general_settings['is_customer_note_vendor']) && $general_settings['is_customer_note_vendor'] == 'Enable') {
                        ?>
                        <tr>
                            <td colspan="2" align="left" valign="top" style="font-size:17px; color:#777; " ><strong><?php _e('Customer Note', 'wcmp-pdf_invoices'); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left" valign="top" style="font-size:12px;"><?php echo $order->get_customer_note(); ?></td>
                        </tr>
                        <?php
                    }
                }
            } else if ($user_type == 'customer') {
                if (isset($general_settings['is_customer_note_customer']) && $general_settings['is_customer_note_customer'] == 'Enable') {
                    ?>
                    <tr>
                        <td colspan="2" align="left" valign="top" style="font-size:17px; color:#777; " ><strong><?php _e('Customer Note', 'wcmp-pdf_invoices'); ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left" valign="top" style="font-size:12px;"><?php echo $order->get_customer_note(); ?></td>
                    </tr>
                    <?php
                }
            }
            ?>

        </tbody>
    </table>
</div>