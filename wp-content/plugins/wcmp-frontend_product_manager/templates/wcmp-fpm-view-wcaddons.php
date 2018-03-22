<?php
/**
 * WCMp Product Types plugin views
 *
 * Plugin WC Product Addons Products Manage Views
 *
 * @author 		WC Marketplace
 * @package 	wcmp-pts/views
 * @version   1.1.1
 */
global $wp, $WCMp_Frontend_Product_Manager;
$product_id = 0;
$_product_addons = array();


if (!empty($pro_id)) {
	$product = wc_get_product((int) $pro_id);
	if($product && !empty($product)) {
		$product_id = $product->get_id();
		$_product_addons = (array) get_post_meta( $product_id, '_product_addons', true );
	}
}



$group_types = array( 'custom_price'               => __( 'Additional custom price input', 'wcmp-frontend_product_manager' ),
											'input_multiplier'           => __( 'Additional price multiplier', 'wcmp-frontend_product_manager' ),
											'checkbox'                   => __( 'Checkboxes', 'wcmp-frontend_product_manager' ),
											'custom_textarea'            => __( 'Custom input (textarea)', 'wcmp-frontend_product_manager' ),
											'custom'                     => __( 'Any text', 'wcmp-frontend_product_manager' ),
											'custom_email'               => __( 'Email address', 'wcmp-frontend_product_manager' ),
											'custom_letters_only'        => __( 'Only letters', 'wcmp-frontend_product_manager' ),
											'custom_letters_or_digits'   => __( 'Only letters and numbers', 'wcmp-frontend_product_manager' ),
											'custom_digits_only'         => __( 'Only numbers', 'wcmp-frontend_product_manager' ),
											'file_upload'                => __( 'File upload', 'wcmp-frontend_product_manager' ),
											'radiobutton'                => __( 'Radio buttons', 'wcmp-frontend_product_manager' ),
											'select'                     => __( 'Select box', 'wcmp-frontend_product_manager' )
										);

?>

<h3 class="pro_ele_head products_manage_wcs_addons wcaddons"><?php _e('Add-ons', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block wcaddons">
	<?php
	$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field( array( 
		"_product_addons" =>     array('label' => __('Add-ons', 'wcmp-frontend_product_manager') , 'type' => 'multiinput', 'class' => 'pro_ele wcaddons', 'label_class' => 'pro_title wcaddons', 'value' => $_product_addons, 'options' => apply_filters( 'wcmp_fpm_addon_fields',	array(
                                    "type" => array('label' => __('Group', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $group_types, 'class' => 'regular-select addon_fields_option wcaddons', 'label_class' => 'pro_title wcaddons' ),
                                    "name" => array('label' => __('Name', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text', 'label_class' => 'pro_title' ),
                                    "position" => array( 'type' => 'hidden' ),
                                    "description" => array('label' => __('Description', 'wcmp-frontend_product_manager'), 'type' => 'textarea', 'class' => 'regular-text', 'label_class' => 'pro_title' ),
                                    "required" => array('label' => __('Required fields?', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'class' => 'regular-checkbox', 'label_class' => 'pro_title checkbox_title', 'value' => 1 ),
                                    "options" =>     array('label' => __('Options', 'wcmp_pts') , 'type' => 'multiinput', 'class' => 'pro_ele wcaddons', 'label_class' => 'pro_title wcaddons', 'options' => array(
                                          "label" => array('label' => __('Label', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text', 'label_class' => 'pro_title' ),
                                          "price" => array('label' => __('Price', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text addon_fields addon_price', 'label_class' => 'pro_title addon_fields addon_price' ),
                                          "min" => array('label' => __('Min', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text addon_fields addon_minmax', 'label_class' => 'pro_title addon_fields addon_minmax' ),
                                          "max" => array('label' => __('Max', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text addon_fields addon_minmax', 'label_class' => 'pro_title addon_fields addon_minmax' ),
                                        ) )
                                    ),$product_id ))
		));
	?>
</div>