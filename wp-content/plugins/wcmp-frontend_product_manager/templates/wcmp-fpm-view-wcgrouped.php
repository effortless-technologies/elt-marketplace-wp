<?php
global $WCMp, $WCMp_Frontend_Product_Manager;
$children = array();
$current_vendor_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());
$args = array(
    'posts_per_page' => -1,
    'offset' => 0,
    'category' => '',
    'category_name' => '',
    'orderby' => 'date',
    'order' => 'DESC',
    'include' => '',
    'exclude' => '',
    'meta_key' => '',
    'meta_value' => '',
    'post_type' => 'product',
    'post_mime_type' => '',
    'post_parent' => '',
    'author' => $current_vendor_id,
    'post_status' => array('publish'),
    'suppress_filters' => true
);
$products_array = get_posts($args);
if (!empty($pro_id)) {
    $product = wc_get_product($pro_id);
    $children = get_post_meta($pro_id, '_children', true) ? get_post_meta($pro_id, '_children', true) : array();
}
?>
<h3 class="pro_ele_head grouped"><?php _e('Grouped Products', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block grouped">
    <div class="form-group">
        <label class="control-label col-md-3" for="children"><?php _e('Grouped Products', 'wcmp-frontend_product_manager'); ?>
            <span class="img_tip" data-desc="This lets you choose which products are part of this group.">
        </label>
        <div class="col-md-6 col-sm-9">
            <select id="children" name="children[]" class="regular-select pro_ele grouped" multiple="multiple">
                <?php
                if ($products_array)
                    foreach ($products_array as $products_single) {
                        echo '<option value="' . esc_attr($products_single->ID) . '"' . selected(in_array($products_single->ID, $children), true, false) . '>' . esc_html($products_single->post_title) . '</option>';
                    }
                ?>
            </select>
        </div>
    </div>
</div>