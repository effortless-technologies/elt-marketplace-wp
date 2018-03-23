<?php
/**
 * The template for displaying vendor vacation settings.
 *
 * Override this template by copying it to yourtheme/wcmp-vendor_vacation/wcmp_vendor_vacation_settings_template.php
 *
 * @author 		WC Marketplace
 * @package 	wcmp-vendor_vacation/Templates
 * @version     1.0.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $WCMP_Vendor_Vacation, $WCMp;
?>
<div class="col-md-12 wcmp_main_holder toside_fix">
    <form method="post" name="wcmp_vendor_vacation" >
        <div class="form-group">
            <?php echo $calender_html; ?>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-6 col-md-6"><?php _e('Would you like to avoid any purchase on Holidays?', 'wcmp-vendor_vacation'); ?></label>
            <div class="col-md-6 col-sm-6">
                <input type="checkbox" class="avoid_purchase regular-checkbox" value="enable" name="avoid_purchase" <?php if (isset($wcmp_vendor_vacation['avoid_purchase'])) { echo 'checked'; }?>/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-6 col-md-6"><?php _e('Custom notification for avoid purchase', 'wcmp-vendor_vacation'); ?></label>
            <div class="col-md-6 col-sm-6">
                <textarea name="custom_notification_avoid_puchase" class="custom_notification_avoid_puchase form-control regular-textarea" rows="4" cols="45">
                    <?php echo isset($wcmp_vendor_vacation['custom_notification_avoid_puchase']) ? $wcmp_vendor_vacation['custom_notification_avoid_puchase'] : ""; ?>
                </textarea>
            </div>
        </div>  
        <div class="form-group">
            <label class="control-label col-sm-6 col-md-6"><?php _e('"Add to cart" button text', 'wcmp-vendor_vacation'); ?></label>
            <div class="col-md-6 col-sm-6">
                <input type="text" class="add_to_cart_text form-control regular-text" name="add_to_cart_text" value="<?php echo isset($wcmp_vendor_vacation['add_to_cart_text']) ? $wcmp_vendor_vacation['add_to_cart_text'] : ""; ?>"/>
            </div>
        </div> 
        <div class="form-group">
            <label class="control-label col-sm-6 col-md-6"><?php _e('Custom notification before "Add to cart button"', 'wcmp-vendor_vacation'); ?></label>
            <div class="col-md-6 col-sm-6">
                <textarea name="custom_notification" class="custom_notification form-control regular-textarea" rows="4" cols="45">
                    <?php echo isset($wcmp_vendor_vacation['custom_notification']) ? $wcmp_vendor_vacation['custom_notification'] : ""; ?>
                </textarea>
            </div>
        </div>  
        <div class="form-group">
            <label class="control-label col-sm-6 col-md-6"><?php _e('Custom notification before "Add to cart button"', 'wcmp-vendor_vacation'); ?></label>
            <div class="col-md-6 col-sm-6">
                <textarea name="custom_notification" class="custom_notification form-control regular-textarea" rows="4" cols="45">
                    <?php echo isset($wcmp_vendor_vacation['custom_notification']) ? $wcmp_vendor_vacation['custom_notification'] : ""; ?>
                </textarea>
            </div>
        </div> 
        <div id="wcmp_vendor_store_time">
            <div class="form-group is_time">
                <label class="control-label col-sm-6 col-md-6"><?php _e('Would you like to enable Store Time?', 'wcmp-vendor_vacation'); ?></label>
                <div class="col-md-6 col-sm-6">
                    <input type="checkbox" class="is_enable_store_time regular-checkbox" value="enable" id="is_enable_store_time" name="is_enable_store_time" <?php if (isset($wcmp_vendor_vacation['is_enable_store_time'])) { echo 'checked'; }?>/>
                </div>
            </div> 
            <div class="form-group store-time">
                <label class="control-label col-sm-6 col-md-6"><?php _e('Open time for your shop open days', 'wcmp-vendor_vacation'); ?></label>
                <div class="col-md-6 col-sm-6">
                    <table width="100%">
                        <tr><th>HH</th><th>MM</th></tr>
                        <tr>
                            <td><input class="form-control regular-text" type="number" class="open_time_hh" max= "24" min="0"  name="open_time_hh" value="<?php echo isset($wcmp_vendor_vacation['open_time']['hour']) ? $wcmp_vendor_vacation['open_time']['hour'] : ''; ?>"/></td>
                            <td><input class="form-control regular-text" type="number" class="open_time_mm" max= "60" min="0"  name="open_time_mm" value="<?php echo isset($wcmp_vendor_vacation['open_time']['minute']) ? $wcmp_vendor_vacation['open_time']['minute'] : ''; ?>"/></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="form-group store-time">
                <label class="control-label col-sm-6 col-md-6"><?php _e('Close time for your shop open days', 'wcmp-vendor_vacation'); ?></label>
                <div class="col-md-6 col-sm-6">
                    <table width="100%">
                        <tr><th>HH</th><th>MM</th></tr>
                        <tr>
                            <td><input class="form-control regular-text" type="number" class="close_time_hh" max= "24" min="0"  name="close_time_hh" value="<?php echo isset($wcmp_vendor_vacation['close_time']['hour']) ? $wcmp_vendor_vacation['close_time']['hour'] : ''; ?>"/></td>
                            <td><input class="form-control regular-text" type="number" class="close_time_mm" max= "60" min="0"  name="close_time_mm" value="<?php echo isset($wcmp_vendor_vacation['close_time']['minute']) ? $wcmp_vendor_vacation['close_time']['minute'] : ''; ?>"/></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="form-group store-time">
                <label class="control-label col-sm-6 col-md-6"><?php _e('Custom "Shop is Closed Now!" text', 'wcmp-vendor_vacation'); ?></label>
                <div class="col-md-6 col-sm-6">
                    <input type="text" class="shop_closed_text form-control regular-text" name="shop_closed_text" style="margin:0px;" value="<?php echo isset($wcmp_vendor_vacation['shop_closed_text']) ? $wcmp_vendor_vacation['shop_closed_text'] : ""; ?>" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <input type="hidden" name="include_dates_array" id="include_dates_array" value='<?php echo $selected_dates; ?>' />
        </div>
        <div class="action_div wcmp-action-container">
            <button type="submit" class="btn btn-default" name="wcmp_vendor_vacation_save"><?php _e('Save Options', 'wcmp-vendor_vacation'); ?></button>
            <div class="clear"></div>
        </div>
    </form>
    
    <?php
//    $html = '<form method="post" name="wcmp_vendor_vacation" >';
//    $html .= '<div class="wcmp_form1">';
//    $html .= $calender_html;
//    $html .= '<div><table style="clear: both;"><tr><td>'.__('Would you like to avoid any purchase on Holidays?', 'wcmp-vendor_vacation').'</td><td><input type="checkbox" class="avoid_purchase" value="enable" name="avoid_purchase" ';
//    if (isset($wcmp_vendor_vacation['avoid_purchase'])) {
//        $html .= 'checked';
//    }
//    $html .= '/></td></tr><tr><td>'.__('Custom notification for avoid purchase', 'wcmp-vendor_vacation').'</td><td><textarea name="custom_notification_avoid_puchase" class="custom_notification_avoid_puchase" rows="4" cols="45">';
//    $html .= isset($wcmp_vendor_vacation['custom_notification_avoid_puchase']) ? $wcmp_vendor_vacation['custom_notification_avoid_puchase'] : "";
//    $html .= '</textarea></td></tr><tr><td>'.__('"Add to cart" button text', 'wcmp-vendor_vacation').'</td><td><input type="text" class="add_to_cart_text" name="add_to_cart_text" value="';
//    $html .= isset($wcmp_vendor_vacation['add_to_cart_text']) ? $wcmp_vendor_vacation['add_to_cart_text'] : "";
//    $html .= '"/></td></tr><tr><td>'.__('Custom notification before "Add to cart button"', 'wcmp-vendor_vacation').'</td><td><textarea name="custom_notification" class="custom_notification" rows="4" cols="45">';
//    $html .= isset($wcmp_vendor_vacation['custom_notification']) ? $wcmp_vendor_vacation['custom_notification'] : "";
//    $html .= '</textarea></td></tr></table></div>';
//    $html .= '<div id="wcmp_vendor_store_time"><table>';
//    $html .= '<tr class="is_time"><td style="width: 60%;">'.__('Would you like to enable Store Time?', 'wcmp-vendor_vacation').'</td><td><input type="checkbox" id="is_enable_store_time" class="is_enable_store_time" value="enable" name="is_enable_store_time" ';
//    if (isset($wcmp_vendor_vacation['is_enable_store_time'])) {
//        $html .= 'checked';
//    }
//    $html .= '/></td></tr>';
//    $html .= '<tr><th style="width: 60%;"></th><th>HH</th><th></th><th>MM</th></tr><tr><td>'.__('Open time for all weekdays', 'wcmp-vendor_vacation').'</td><td style=" padding-right: 0px;"><input type="number" class="open_time_hh" max= "24" min="0"  name="open_time_hh" value="';
//    $html .= isset($wcmp_vendor_vacation['open_time']['hour']) ? $wcmp_vendor_vacation['open_time']['hour'] : '';
//    $html .= '" style="width: 70px;" /></td><td><span style="width: 10px; display: inline-block; text-align: center;line-height: 26px;">:</span></td><td style="width: 235px;  padding-left: 0px;"><input type="number" max= "60" min="0" class="open_time_mm" name="open_time_mm" style="width: 70px;" value="';
//    $html .= isset($wcmp_vendor_vacation['open_time']['minute']) ? $wcmp_vendor_vacation['open_time']['minute'] : '';
//    $html .= '"/></td></tr>';
//    $html .= '<tr><th style="width: 60%;"></th><th>HH</th><th></th><th>MM</th></tr><tr><td>'.__('Close time for all weekdays', 'wcmp-vendor_vacation').'</td><td style=" padding-right: 0px;"><input type="number" class="close_time_hh" max= "24" min="0" name="close_time_hh" value="';
//    $html .= isset($wcmp_vendor_vacation['close_time']['hour']) ? $wcmp_vendor_vacation['close_time']['hour'] : '';
//    $html .= '" style="width: 70px;" /></td><td><span style=" width: 10px; display: inline-block; text-align: center;line-height: 26px;">:</span></td><td style="width: 235px;  padding-left: 0px;"><input type="number" max= "60" min="0" class="close_time_mm" name="close_time_mm" style="width: 70px;" value="';
//    $html .= isset($wcmp_vendor_vacation['close_time']['minute']) ? $wcmp_vendor_vacation['close_time']['minute'] : '';
//    $html .= '"/></td></tr><tr><td style="width: 60%;">'.__('Custom "Shop is Closed Now!" text', 'wcmp-vendor_vacation').'</td><td colspan="3"><input type="text" class="shop_closed_text" name="shop_closed_text" style="margin:0px;" value="';
//    $html .= isset($wcmp_vendor_vacation['shop_closed_text']) ? $wcmp_vendor_vacation['shop_closed_text'] : "";
//    $html .= '"/></td></tr></table></div>';
//    $html .= '<p><input type="hidden" name="include_dates_array" id="include_dates_array" value=' . $selected_dates . ' />';
//    $html .= '<div class="action_div"><button name="wcmp_vendor_vacation_save" type="submit" class="wcmp_orange_btn">'.__('Save Options', 'wcmp-vendor_vacation').'</button><div class="clear"></div></div>';
//    $html .= '</p></div></form>';
//    echo $html;
    ?>
</div>