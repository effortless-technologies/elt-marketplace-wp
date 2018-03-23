<?php
/*
 * The template for displaying vendor dashboard
 * Override this template by copying it to yourtheme/wcmp-pdf-invoices/wcmp_pdf_invoice_forth_template.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp PDF Invoices/Templates
 * @version     1.0.5
 */

if ($user_type == 'vendor') {
    $vendor_id = $vendor->id;
    $vendor_user_details = get_user_meta($vendor_id, 'wcmp_pdf_invoices_settings', true);
}
if (!empty($vendor)) {
    $order_total_items = $vendor->wcmp_vendor_get_order_item_totals($order, $vendor->term_id);
} else {
    $order_total_items = $order->get_order_item_totals();
}
if ($totalss = $order_total_items) {
    foreach ($totalss as $total_keyy => $totall) {
        if ($total_keyy == 'order_total' || $total_keyy == 'total') {
            $invoice_total = $totall['value'];
        }
    }
}
?>

<div style="width:100%; margin:0px auto; color:#555; line-height:24px; font-size:14px">
    <table width="100%" border="0" cellspacing="0" cellpadding="3" style="color:#666; line-height:26px;  margin:0 0 0px 0; padding:20px 4%;">
        <tr> 
          <!--<td align="left" valign="middle" style="font-size:40px; color:#444; width:25%;"></td> -->
            <td align="left" valign="top" style="font-size:30px;"><?php _e('INVOICE', 'wcmp-pdf_invoices'); ?><br>
                <span style="font-size:14px;"><?php echo __('Issued on : ', 'wcmp-pdf_invoices') . date("F j, Y, g:i a"); ?></span></td>
        </tr>
    </table>
    <table border="0" cellspacing="0" cellpadding="15"  style="margin:0; color:#666; line-height:24px; font-size:15px; width:100%; padding:5px 4%; background:#2aa8fc;">
        <tr>
            <td align="left" valign="middle" style="font-size:40px; color:#444; width:56%;">
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
                <img width="150" src="<?php echo $dataUri; ?>" alt="company_logo" />
            </td>
            <td align="right" valign="top" style="color:#fff;" ><?php echo nl2br($general_settings['company_address']); ?> <br>
                <?php echo __('Email: ', 'wcmp-pdf_invoices') . $general_settings['company_email']; ?><br><?php echo __('Ph No:', 'wcmp-pdf_invoices') . $general_settings['company_ph_no']; ?></td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="5" >
        <tr>
            <td style="padding:10px 0; width:50%" align="left" valign="top"><p style="color:#333; font-size:18px; margin:0px; padding:20px 0 5px 20px;">
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
                </p></td>
            <td style=""><div style="font-size:24px; color:#2aa8fc; padding:20px 0; text-align:right;"> <?php echo __('Invoice total:', 'wcmp-pdf_invoices') . $invoice_total; ?> </div></td>
        </tr>
        <tr>
            <td align="left" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="10">
                    <tr>
                        <td align="left" valign="top"><h3 style="color:#111; font-size:15px; font-weight:bold"><?php _e('Invoice To:', 'wcmp-pdf_invoices'); ?></h3>
                            <?php
                            if (!$order->get_formatted_billing_address())
                                _e('N/A', 'wcmp-pdf_invoices');
                            else
                                echo $order->get_formatted_billing_address();
                            ?></td>
                        <td align="left" valign="top" ><h3 style="color:#111; font-size:15px; font-weight:bold"><?php _e('Ship To:', 'wcmp-pdf_invoices'); ?></h3>
                            <?php
                            if (!$order->get_formatted_shipping_address())
                                _e('N/A', 'wcmp-pdf_invoices');
                            else
                                echo $order->get_formatted_shipping_address();
                            ?></td>
                    </tr>
                </table></td>
            <td align="left" valign="top" style=""><div style="color:#666; line-height:28px; font-size:18px;  padding:20px 0 0px 8%;"> <?php _e('Order Number: ', 'wcmp-pdf_invoices'); ?>#<?php echo $order->get_order_number(); ?><br>
                    Order Date: <?php echo $order->get_date_created(); ?><br>
                    <?php
                    if ($user_type == 'admin') {
                        if (isset($general_settings['is_payment_method_admin']) && $general_settings['is_payment_method_admin'] == 'Enable') {
                            ?>
                            <?php _e('Payment Method: ', 'wcmp-pdf_invoices'); ?><?php echo $order->get_payment_method_title(); ?>
                            <?php
                        }
                    } else if ($user_type == 'vendor') {
                        if ($vendor_user_details) {
                            if (isset($vendor_user_details['is_payment_method_vendor']) && $vendor_user_details['is_payment_method_vendor'] == 'Enable') {
                                ?>
                                <?php _e('Payment Method: ', 'wcmp-pdf_invoices'); ?><?php echo $order->get_payment_method_title(); ?>
                                <?php
                            }
                        } else {
                            if (isset($general_settings['is_payment_method_vendor']) && $general_settings['is_payment_method_vendor'] == 'Enable') {
                                ?>
                                <?php _e('Payment Method: ', 'wcmp-pdf_invoices'); ?><?php echo $order->get_payment_method_title(); ?>
                                <?php
                            }
                        }
                    } else if ($user_type == 'customer') {
                        if (isset($general_settings['is_payment_method_customer']) && $general_settings['is_payment_method_customer'] == 'Enable') {
                            ?>
                            <?php _e('Payment Method: ', 'wcmp-pdf_invoices'); ?><?php echo $order->get_payment_method_title(); ?>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div style="padding:0 1% 0px 8%; width:91%; ">
                    <?php
                    if ($user_type == 'vendor') {
                        if ($vendor_user_details) {
                            echo '<p style="color:#222; font-size:18px;"> '.__('Special Note from Admin:', 'wcmp-pdf_invoices').'</p><p style="color:#888; font-size:14px;">' . $vendor_user_details['spcl_notes_from_vendor'] . '</p>';
                        } else {
                            echo '<p style="color:#222; font-size:18px;"> '.__('Special Note from Admin:', 'wcmp-pdf_invoices').'</p><p style="color:#888; font-size:14px;">' . $general_settings['spcl_notes_from_admin'] . '</p>';
                        }
                    } else if (!empty($general_settings['spcl_note_from_admin'])) {
                        echo '<p style="color:#222; font-size:18px;"> '.__('Special Note from Admin:', 'wcmp-pdf_invoices').'</p><p style="color:#888; font-size:14px;">' . $general_settings['spcl_note_from_admin'] . '</p>';
                    }
                    ?>
                </div></td>
        </tr>
    </table>

    <table border="0" cellspacing="0" cellpadding="5" align="left" width="100%"  style=" color:#666; line-height:26px; font-size:14px;">
        <tr>
            <td style="color:#777; font-size:16px; "> <?php _e('Order Details', 'wcmp-pdf_invoices'); ?></td>
        </tr>
        <tr>
            <td><table border="0" cellspacing="0" cellpadding="10"  style="border:1px solid #ddd; background:#f8f8f8; color:#444; font-size:14px; width:100%;  ">
                    <thead>
                        <tr>
                            <td align="left" valign="top" style="border-bottom:1px solid #ddd;" ><?php _e('Item', 'wcmp-pdf_invoices'); ?></td>
                            <?php
                            if ($user_type == 'admin') {
                                if (isset($general_settings['is_sku_admin']) && $general_settings['is_sku_admin'] == 'Enable') {
                                    ?>
                                    <td align="left" valign="top" style="border-bottom:1px solid #ddd;"><?php _e('SKU', 'wcmp-pdf_invoices'); ?></td>
                                    <?php
                                }
                            } else if ($user_type == 'vendor') {
                                if ($vendor_user_details) {
                                    if (isset($vendor_user_details['is_sku_vendor']) && $vendor_user_details['is_sku_vendor'] == 'Enable') {
                                        ?>
                                        <td align="left" valign="top" style="border-bottom:1px solid #ddd;"><?php _e('SKU', 'wcmp-pdf_invoices'); ?></td>
                                        <?php
                                    }
                                } else {
                                    if (isset($general_settings['is_sku_vendor']) && $general_settings['is_sku_vendor'] == 'Enable') {
                                        ?>
                                        <td align="left" valign="top" style="border-bottom:1px solid #ddd;"><?php _e('SKU', 'wcmp-pdf_invoices'); ?></td>
                                        <?php
                                    }
                                }
                            } else if ($user_type == 'customer') {
                                if (isset($general_settings['is_sku_customer']) && $general_settings['is_sku_customer'] == 'Enable') {
                                    ?>
                                    <td align="left" valign="top" style="border-bottom:1px solid #ddd;"><?php _e('SKU', 'wcmp-pdf_invoices'); ?></td>
                                    <?php
                                }
                            }
                            ?>
                            <td align="left" valign="top" style="border-bottom:1px solid #ddd;" ><?php _e('Quantity', 'wcmp-pdf_invoices'); ?></td>
                            <td align="left" valign="top" style="border-bottom:1px solid #ddd;" ><?php _e('Cost', 'wcmp-pdf_invoices'); ?></td>
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
                                    <td align="left" valign="top" style="border-bottom:1px solid #ddd; font-size:14px; color:#444;"><?php
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
                                        ?></td>
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
                                    <td align="left" valign="top" style="border-bottom:1px solid #ddd;"  ><?php echo $order->get_formatted_line_subtotal($item) ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table></td>
        </tr>
        <tr>
            <td><table border="0" cellspacing="0" cellpadding="5" width="100%"  style=" color:#666; line-height:26px; font-size:14px;">
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
                                            ?>
                                            <tr>
                                                <td align="right" valign="top" style="width:60%" ><?php echo $total['label']; ?></td>
                                                <td align="right" valign="top" style="font-size:18px;"><?php echo $total['value']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else if ($user_type == 'vendor') {
                                        if ($vendor_user_details) {
                                            if (isset($vendor_user_details[$wcmp_pdf_setting_array[$total_key] . '_vendor']) && $vendor_user_details[$wcmp_pdf_setting_array[$total_key] . '_vendor'] == 'Enable') {
                                                ?>
                                                <tr>
                                                    <td align="right" valign="top" style="width:60%"><?php echo $total['label']; ?></td>
                                                    <td align="right" valign="top" style="font-size:18px;"><?php echo $total['value']; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            if (isset($general_settings[$wcmp_pdf_setting_array[$total_key] . '_vendor']) && $general_settings[$wcmp_pdf_setting_array[$total_key] . '_vendor'] == 'Enable') {
                                                ?>
                                                <tr>
                                                    <td align="right" valign="top" style="width:60%" ><?php echo $total['label']; ?></td>
                                                    <td align="right" valign="top" style="font-size:18px;"><?php echo $total['value']; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    } else if ($user_type == 'customer') {
                                        if (isset($general_settings[$wcmp_pdf_setting_array[$total_key] . '_customer']) && $general_settings[$wcmp_pdf_setting_array[$total_key] . '_customer'] == 'Enable') {
                                            ?>
                                            <tr>
                                                <td align="right" valign="top" style="width:60%"><?php echo $total['label']; ?></td>
                                                <td align="right" valign="top" style="font-size:18px;"><?php echo $total['value']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                } else if ($total_key == 'order_total') {
                                    ?>
                                    <tr>
                                        <td  align="right" valign="top" style="width:60%;font-size:20px; border-top:1px solid #bbb; color:#222; " ><?php echo $total['label']; ?></td>
                                        <td align="right" valign="top" style="font-size:20px; border-top:1px solid #bbb; color:#222;" ><?php echo $total['value']; ?></td>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <td align="right" valign="top"  style="width:60%"><?php echo $total['label']; ?></td>
                                        <td align="right" valign="top" style="font-size:18px;" ><?php echo $total['value']; ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table></td>
        </tr>
        <tr>
            <td><table border="0" cellspacing="0" cellpadding="5"  width="100%"  style=" color:#666; line-height:26px; font-size:14px;">
                    <tr>
                        <td>
                    <tr>
                        <td><?php if ($user_type == 'admin') { ?>
                                <p style="color:#222; font-weight:bold;"><?php _e('Risk of loss ', 'wcmp-pdf_invoices'); ?><span style="color:#666; font-weight:normal;"><?php echo $general_settings['term_and_conditions_admin']; ?></span></p>
                                <?php
                            } else if ($user_type == 'vendor') {
                                if ($vendor_user_details) {
                                    ?>
                                    <p style="color:#222; font-weight:bold;"><?php _e('Risk of loss ', 'wcmp-pdf_invoices'); ?> <span style="color:#666; font-weight:normal;"><?php echo $vendor_user_details['term_and_conditions_vendor']; ?></span></p>
                                <?php } else { ?>
                                    <p style="color:#222; font-weight:bold;"><?php _e('Risk of loss ', 'wcmp-pdf_invoices'); ?> <span style="color:#666; font-weight:normal;"><?php echo $general_settings['term_and_conditions_vendor']; ?></span></p>
                                    <?php
                                }
                            } else {
                                ?>
                                <p style="color:#222; font-weight:bold;"><?php _e('Risk of loss ', 'wcmp-pdf_invoices'); ?> <span style="color:#666; font-weight:normal;"><?php echo $general_settings['term_and_conditions_customer']; ?></span></p>
                                <?php
                            }
                            ?>
                            <?php
                            if ($user_type == 'admin') {
                                if (isset($general_settings['is_customer_note_admin']) && $general_settings['is_customer_note_admin'] == 'Enable') {
                                    ?>
                                    <p style="color:#222; font-weight:bold;"><?php _e('Customer Note', 'wcmp-pdf_invoices'); ?><span style="color:#666; font-weight:normal;"><?php echo $order->get_customer_note(); ?></span></p>
                                    <?php
                                }
                            } else if ($user_type == 'vendor') {
                                if ($vendor_user_details) {
                                    if (isset($vendor_user_details['is_customer_note_vendor']) && $vendor_user_details['is_customer_note_vendor'] == 'Enable') {
                                        ?>
                                        <p style="color:#222; font-weight:bold;"><?php _e('Customer Note', 'wcmp-pdf_invoices'); ?><span style="color:#666; font-weight:normal;"><?php echo $order->get_customer_note(); ?></span></p>
                                        <?php
                                    }
                                } else {
                                    if (isset($general_settings['is_customer_note_vendor']) && $general_settings['is_customer_note_vendor'] == 'Enable') {
                                        ?>
                                        <p style="color:#222; font-weight:bold;"><?php _e('Customer Note', 'wcmp-pdf_invoices'); ?><span style="color:#666; font-weight:normal;"><?php echo $order->get_customer_note(); ?></span></p>
                                        <?php
                                    }
                                }
                            } else if ($user_type == 'customer') {
                                if (isset($general_settings['is_customer_note_customer']) && $general_settings['is_customer_note_customer'] == 'Enable') {
                                    ?>
                                    <p style="color:#222; font-weight:bold;"><?php _e('Customer Note', 'wcmp-pdf_invoices'); ?><span style="color:#666; font-weight:normal;"><?php echo $order->get_customer_note(); ?></span></p>
                                        <?php
                                    }
                                }
                                ?></td>
                    </tr>
            </td>
        </tr>
    </table></td>
</tr>
</table>
</div>
