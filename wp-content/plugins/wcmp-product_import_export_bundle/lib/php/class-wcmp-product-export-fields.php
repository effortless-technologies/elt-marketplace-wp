<?php

class WCMp_Product_Export_Fields {

    public function data_variation_columns() {
        return apply_filters('wcmp_product_variation_post_columns', array(
            'post_parent' => 'post_parent',
            'ID' => 'ID',
            'post_status' => 'post_status',
            '_sku' => 'sku',
            '_downloadable' => 'downloadable',
            '_virtual' => 'virtual',
            '_stock' => 'stock',
            '_regular_price' => 'regular_price',
            '_sale_price' => 'sale_price',
            '_weight' => 'weight',
            '_length' => 'length',
            '_width' => 'width',
            '_height' => 'height',
            '_tax_class' => 'tax_class',
            '_file_path' => 'file_path',
            '_file_paths' => 'file_paths',
                ));
    }

    public function data_post_columns() {
        return apply_filters('wcmp_product_post_columns', array(
            'post_title' => 'post_title',
            'post_name' => 'post_name',
            'ID' => 'ID',
            'post_excerpt' => 'post_excerpt',
            'post_content' => 'post_content',
            'post_status' => 'post_status',
            'menu_order' => 'menu_order',
            'post_date' => 'post_date',
            'post_parent' => 'post_parent',
            'post_author' => 'post_author',
            'comment_status' => 'comment_status',
            '_sku' => 'sku',
            '_downloadable' => 'downloadable',
            '_virtual' => 'virtual',
            '_visibility' => 'visibility',
            '_stock' => 'stock',
            '_stock_status' => 'stock_status',
            '_backorders' => 'backorders',
            '_manage_stock' => 'manage_stock',
            '_regular_price' => 'regular_price',
            '_sale_price' => 'sale_price',
            '_weight' => 'weight',
            '_length' => 'length',
            '_width' => 'width',
            '_height' => 'height',
            '_tax_status' => 'tax_status',
            '_tax_class' => 'tax_class',
            '_upsell_ids' => 'upsell_ids',
            '_crosssell_ids' => 'crosssell_ids',
            '_featured' => 'featured',
            '_sale_price_dates_from' => 'sale_price_dates_from',
            '_sale_price_dates_to' => 'sale_price_dates_to',
            '_download_limit' => 'download_limit',
            '_download_expiry' => 'download_expiry',
            '_product_version' => 'product_version',
            '_downloadable_files' => 'downloadable_files',
            '_download_type' => 'download_type',
            '_product_url' => 'product_url',
            '_button_text' => 'button_text',
                ));
    }

    public function get_meta_field() {
        return apply_filters('wcmp_product_post_columns_variable_meta', array(
            'total_sales' => 'total_sales',
            '_sku' => '_sku',
            '_downloadable' => '_downloadable',
            '_virtual' => '_virtual',
            '_visibility' => '_visibility',
            '_stock' => '_stock',
            '_sold_individually' => '_sold_individually',
            '_stock_status' => '_stock_status',
            '_backorders' => '_backorders',
            '_manage_stock' => '_manage_stock',
            '_regular_price' => '_regular_price',
            '_sale_price' => '_sale_price',
            '_weight' => '_weight',
            '_length' => '_length',
            '_width' => '_width',
            '_height' => '_height',
            '_tax_status' => '_tax_status',
            '_tax_class' => '_tax_class',
            '_upsell_ids' => '_upsell_ids',
            '_crosssell_ids' => '_crosssell_ids',
            '_featured' => '_featured',
            '_sale_price_dates_from' => '_sale_price_dates_from',
            '_sale_price_dates_to' => '_sale_price_dates_to',
            '_download_limit' => '_download_limit',
            '_download_expiry' => '_download_expiry',
            '_product_version' => '_product_version',
            '_downloadable_files' => '_downloadable_files',
            '_download_type' => '_download_type',
            '_product_url' => '_product_url',
            '_button_text' => '_button_text',
            '_file_paths' => '_file_paths',
                )
        );
    }

    public function get_core_post_field() {
        return apply_filters('wcmp_product_post_columns_variable_core', array(
            'ID' => 'ID',
            'post_title' => 'post_title',
            'post_name' => 'post_name',
            'post_excerpt' => 'post_excerpt',
            'post_content' => 'post_content',
            'post_status' => 'post_status',
            'menu_order' => 'menu_order',
            'post_date' => 'post_date',
            'post_parent' => 'post_parent',
            'post_author' => 'post_author',
            'post_type' => 'post_type',
            'comment_status' => 'comment_status',
                )
        );
    }

    public function data_post_columns_variable() {
        return apply_filters('wcmp_product_post_columns_variable', array(
            'post_title' => 'post_title',
            'post_name' => 'post_name',
            'ID' => 'ID',
            'post_excerpt' => 'post_excerpt',
            'post_content' => 'post_content',
            'post_status' => 'post_status',
            'menu_order' => 'menu_order',
            'post_date' => 'post_date',
            'post_parent' => 'post_parent',
            'post_author' => 'post_author',
            'comment_status' => 'comment_status',
            '_sku' => 'sku',
            '_downloadable' => 'downloadable',
            '_virtual' => 'virtual',
            '_visibility' => 'visibility',
            '_stock' => 'stock',
            '_stock_status' => 'stock_status',
            '_backorders' => 'backorders',
            '_manage_stock' => 'manage_stock',
            '_regular_price' => 'regular_price',
            '_sale_price' => 'sale_price',
            '_weight' => 'weight',
            '_length' => 'length',
            '_width' => 'width',
            '_height' => 'height',
            '_tax_status' => 'tax_status',
            '_tax_class' => 'tax_class',
            '_upsell_ids' => 'upsell_ids',
            '_crosssell_ids' => 'crosssell_ids',
            '_featured' => 'featured',
            '_sale_price_dates_from' => 'sale_price_dates_from',
            '_sale_price_dates_to' => 'sale_price_dates_to',
            '_download_limit' => 'download_limit',
            '_download_expiry' => 'download_expiry',
            '_product_version' => 'product_version',
            '_downloadable_files' => 'downloadable_files',
            '_download_type' => 'download_type',
            '_product_url' => 'product_url',
            '_button_text' => 'button_text',
                )
        );
    }

    public function hidden_columns() {
        return array(
            '_product_attributes',
            '_price',
            '_default_attributes',
            '_edit_last',
            '_edit_lock',
            '_wp_old_slug',
            '_product_image_gallery',
            '_max_variation_price',
            '_max_variation_regular_price',
            '_max_variation_sale_price',
            '_min_variation_price',
            '_min_variation_regular_price',
            '_min_variation_sale_price',
        );
    }

}
