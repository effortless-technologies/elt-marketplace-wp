<?php

class WCMp_Product_Import_Fields {

    public function data_post_defaults($post_type) {
        return array(
            'post_type' => $post_type,
            'menu_order' => '',
            'postmeta' => array(),
            'post_status' => 'publish',
            'post_title' => '',
            'post_name' => '',
            'post_date' => '',
            'post_date_gmt' => '',
            'post_content' => '',
            'post_excerpt' => '',
            'post_parent' => '',
            'post_password' => '',
            'post_author' => '',
            'comment_status' => 'open'
        );
    }

    public function data_post_meta_allowed() {
        return array(
            'downloadable' => array('yes', 'no'),
            'virtual' => array('yes', 'no'),
            'visibility' => array('visible', 'catalog', 'search', 'hidden'),
            'stock_status' => array('instock', 'outofstock'),
            'backorders' => array('yes', 'no', 'notify'),
            'manage_stock' => array('yes', 'no'),
            'tax_status' => array('taxable', 'shipping', 'none'),
            'featured' => array('yes', 'no'),
            'purchase_note' => ''
        );
    }

    public function data_post_meta_defaults() {
        return apply_filters('wcmp_product_postmeta_defaults', array(
            'parent_sku' => '',
            'sku' => '',
            'downloadable' => 'no',
            'virtual' => 'no',
            'price' => '',
            'visibility' => 'visible',
            'stock' => 0,
            'stock_status' => 'instock',
            'backorders' => 'no',
            'manage_stock' => 'no',
            'sale_price' => '',
            'regular_price' => '',
            'weight' => '',
            'length' => '',
            'width' => '',
            'height' => '',
            'tax_status' => 'taxable',
            'tax_class' => '',
            'sale_price_dates_from' => '',
            'sale_price_dates_to' => '',
            'min_variation_price' => '',
            'max_variation_price' => '',
            'min_variation_regular_price' => '',
            'max_variation_regular_price' => '',
            'min_variation_sale_price' => '',
            'max_variation_sale_price' => '',
            'featured' => 'no',
            'download_limit' => '',
            'download_expiry' => '',
            'product_url' => '',
            'button_text' => '',
            'product_version' => '',
            'downloadable_files' => '',
            'download_type' => '',
            'purchase_note' => ''
                )
        );
    }

    public function data_reserve_fields() {
        return array(
            'id',
            'product_type',
            'post_id',
            'post_type',
            'post_author',
            'menu_order',
            'postmeta',
            'post_status',
            'post_title',
            'post_name',
            'comment_status',
            'post_date',
            'post_date_gmt',
            'post_content',
            'post_excerpt',
            'post_parent',
            'post_password',
            'sku',
            'downloadable',
            'virtual',
            'visibility',
            'stock',
            'stock_status',
            'backorders',
            'manage_stock',
            'price',
            'sale_price',
            'regular_price',
            'weight',
            'length',
            'width',
            'height',
            'tax_status',
            'tax_class',
            'upsell_ids',
            'crosssell_ids',
            'upsell_skus',
            'crosssell_skus',
            'sale_price_dates_from',
            'sale_price_dates_to',
            'min_variation_price',
            'max_variation_price',
            'min_variation_regular_price',
            'max_variation_regular_price',
            'min_variation_sale_price',
            'max_variation_sale_price',
            'featured',
            'file_path',
            'file_paths',
            'download_limit',
            'download_expiry',
            'product_url',
            'button_text',
            'default_attributes',
            'purchase_note'
        );
    }

    public function exclusion_field_lists() {
        return array(
            '_min_variation_price',
            '_max_variation_price',
            '_min_variation_regular_price',
            '_max_variation_regular_price',
            '_min_variation_sale_price',
            '_max_variation_sale_price',
            '_button_text',
            '_default_attributes'
        );
    }

}
