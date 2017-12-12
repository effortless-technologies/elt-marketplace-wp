<?php

class WCMp_Product_Import_Export_Bundle_Import extends WP_Importer {

    var $id;
    var $file_url;
    var $delimiter;
    var $merge_empty_cells;
    var $processed_terms = array();
    var $processed_posts = array();
    var $post_orphans = array();
    var $attachments = array();
    var $upsell_skus = array();
    var $crosssell_skus = array();
    var $import_results = array();
    var $action;

    /**
     * Constructor
     */
    public function __construct() {
        $this->log = new WC_Logger();
        $this->import_page = 'wcmp_import_export_csv';
        $this->file_url_import_enabled = apply_filters('wcmp_product_file_url_import_enabled', true);
    }

    public function get_download_file_url($downloadable_files) {
        if (is_array($downloadable_files)) {
            foreach ($downloadable_files as $key => $file) {
                $arr['name'] = $file['name'];
                $arr['file'] = $file['file'];
                return $file;
            }
        }
    }

    protected function adding_wcmp_import_result($status, $reason, $post_id = '', $post_title = '', $sku = '') {
        $this->import_results[] = array(
            'post_title' => $post_title,
            'post_id' => $post_id,
            'sku' => $sku,
            'status' => $status,
            'reason' => $reason
        );
    }

    public function wcmp_request_timeout($val) {
        return 600;
    }

    public function make_url_var() {
        global $WCMp_Product_Import_Export_Bundle;
        $this->action = get_the_permalink() . '?import=wcmp_import_export_csv&amp;step=1';
        $this->bytes = apply_filters('import_upload_size_limit', wp_max_upload_size());
        $this->size = size_format($this->bytes);
        $this->upload_dir = wp_upload_dir();
        $WCMp_Product_Import_Export_Bundle->template->get_template('simple-product-file-upload-form.php');
    }

    public function format_wcmp_data_structure_from_csv($data, $enc) {
        return ( $enc == 'UTF-8' ) ? $data : utf8_encode($data);
    }

    public function wcmp_start_import($file, $mapping, $start_pos, $end_pos) {
        global $WCMp_Product_Bundle_Field;
        $memory = size_format(woocommerce_let_to_num(ini_get('memory_limit')));
        $wp_memory = size_format(woocommerce_let_to_num(WP_MEMORY_LIMIT));
        $this->parser = new WCMp_Product_Import_Export_Bundle_CSV_Field_Parser('product');
        list( $this->parsed_data, $this->rawheaders, $position ) = $this->parser->parse_wcmp_data($file, $this->delimiter, $mapping, $start_pos, $end_pos);
        unset($import_data);
        wp_defer_term_counting(true);
        wp_defer_comment_counting(true);
        return $position;
    }

    public function import_wcmp_end() {
        foreach (get_taxonomies() as $tax) {
            delete_option("{$tax}_children");
            _get_term_hierarchy($tax);
        }
        wp_defer_term_counting(false);
        wp_defer_comment_counting(false);
        do_action('import_wcmp_end');
    }

    public function wcmp_existing_product($title, $sku = '', $post_name = '') {
        global $wpdb;
        $post_title = stripslashes(sanitize_post_field('post_title', $title, 0, 'db'));
        $query = "SELECT ID FROM $wpdb->posts WHERE post_type = 'product' AND post_status IN ( 'publish', 'private', 'draft', 'pending', 'future' )";
        $args = array();
        if (!empty($title)) {
            $query .= ' AND post_title = %s';
            $args[] = $post_title;
        }
        if (!empty($post_name)) {
            $query .= ' AND post_name = %s';
            $args[] = $post_name;
        }
        if (!empty($args)) {
            $posts_that_exist = $wpdb->get_col($wpdb->prepare($query, $args));
            if ($posts_that_exist) {
                foreach ($posts_that_exist as $post_exists) {
                    $post_exists_sku = get_post_meta($post_exists, '_sku', true);
                    if ($sku == $post_exists_sku) {
                        return true;
                    }
                }
            }
        }
        if ($sku) {
            $post_exists_sku = $wpdb->get_var($wpdb->prepare("
				SELECT $wpdb->posts.ID
				FROM $wpdb->posts
				LEFT JOIN $wpdb->postmeta ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id )
				WHERE $wpdb->posts.post_status IN ( 'publish', 'private', 'draft', 'pending', 'future' )
				AND $wpdb->postmeta.meta_key = '_sku' AND $wpdb->postmeta.meta_value = '%s'", $sku)
            );
            if ($post_exists_sku) {
                return true;
            }
        }
        return false;
    }

    function get_product_by_sku($sku) {
        global $wpdb;
        $product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
        if ($product_id) {
            return $product_id;
        } else {
            return false;
        }
    }

    public function process_wcmp_product($post) {
        global $WCMp_Product_Import_Export_Bundle;
        $merging = false;
        $exclusion_fields_list = $WCMp_Product_Import_Export_Bundle->wcmp_import_meta_fields->exclusion_field_lists();

        if ($post['post_type'] == 'product') {
            if ($post['product_type_array'] != 'allowed') {
                $this->adding_wcmp_import_result('skipped', __('Product type ' . $post['product_type_array2'] . ' not allowed', 'wcmp-product-import-export-bundle'), '', '', $post['sku']);
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $this->log->add('csv-import', __('> ' . 'Product type ' . $post['product_type_array2'] . ' not allowed Skipped', 'wcmp-product-import-export-bundle'), true);
                }
                unset($post);
                return;
            }
            if (empty($post['post_title'])) {
                $this->adding_wcmp_import_result('skipped', __('Product title can not empty', 'wcmp-product-import-export-bundle'), '', '', $post['sku']);
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $this->log->add('csv-import', __('> Product title can not empty. Skipping.', 'wcmp-product-import-export-bundle'), true);
                }
                unset($post);
                return;
            }
            if (isset($post['post_id'])) {
                $post['post_id'] = '';
            } else {
                $post['post_id'] = '';
            }
            if (!isset($post['sku'])) {
                $this->adding_wcmp_import_result('skipped', __('sku is required filed', 'wcmp-product-import-export-bundle'), '', '', $post['post_title']);
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $this->log->add('csv-import', __('> Skipping sku not found.', 'wcmp-product-import-export-bundle'), true);
                }
                unset($post);
                return;
            } else {
                if ($post['sku'] == '') {
                    $this->adding_wcmp_import_result('skipped', __('sku is required filed', 'wcmp-product-import-export-bundle'), '', '', $post['post_title']);
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        $this->log->add('csv-import', __('> Skipping sku not found.', 'wcmp-product-import-export-bundle'), true);
                    }
                    unset($post);
                    return;
                } else {
                    if ($this->get_product_by_sku($post['sku'])) {
                        $this->adding_wcmp_import_result('skipped', __('Skipping sku already exist', 'wcmp-product-import-export-bundle'), '', $post['post_title'], $post['sku']);
                        if (defined('WP_DEBUG') && WP_DEBUG) {
                            $this->log->add('csv-import', __('> Skipping sku already exist.', 'wcmp-product-import-export-bundle'), true);
                        }
                        unset($post);
                        return;
                    }
                }
            }
            if (!empty($post['post_status']) && $post['post_status'] == 'auto-draft') {
                $this->adding_wcmp_import_result('skipped', __('Skipping auto-draft', 'wcmp-product-import-export-bundle'), '', $post['post_title'], $post['sku']);
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $this->log->add('csv-import', __('> Skipping auto-draft.', 'wcmp-product-import-export-bundle'), true);
                }
                unset($post);
                return;
            }
            if ($this->wcmp_existing_product($post['post_title'], $post['sku'], $post['post_name'])) {
                $this->adding_wcmp_import_result('skipped', __('Product already exists', 'wcmp-product-import-export-bundle'), $post['post_id'], $post['post_title'], $post['sku']);
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $this->log->add('csv-import', sprintf(__('> &#8220;%s&#8221; already exists.', 'wcmp-product-import-export-bundle'), esc_html($post['post_title'])), true);
                }
                unset($post);
                return;
            }
            $current_user = wp_get_current_user();
            $setting_capabilities = get_option('wcmp_capabilities_product_settings_name');
            $direct_publish = 0;
            if (isset($setting_capabilities['is_published_product'])) {
                $direct_publish = 1;
                $is_publish = get_user_meta($current_user->ID, '_vendor_publish_product', true);
                if (isset($is_publish) && $is_publish == 'Enable') {
                    $direct_publish = 1;
                }
            }

            if (defined('WP_DEBUG') && WP_DEBUG) {
                $this->log->add('csv-import', sprintf(__('> Inserting %s', 'wcmp-product-import-export-bundle'), esc_html($post['post_title'])), true);
            }
            $postdata = array(
                'post_author' => get_current_user_id(),
                'post_date' => date('Y-m-d H:i:s', strtotime("now")),
                'post_date_gmt' => date('Y-m-d H:i:s', strtotime("now")),
                'post_content' => $post['post_content'],
                'post_excerpt' => $post['post_excerpt'],
                'post_title' => $post['post_title'],
                'post_status' => ( $direct_publish ) ? $post['post_status'] : 'pending',
                'menu_order' => $post['menu_order'],
                'post_type' => 'product',
                'post_password' => $post['post_password'],
                'comment_status' => $post['comment_status'],
            );
            $post_id = wp_insert_post($postdata, true);
            if (is_wp_error($post_id)) {
                $this->adding_wcmp_import_result('failed', __('Failed to import product', 'wcmp-product-import-export-bundle'), $post['post_id'], $post['post_title'], $post['sku']);
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $this->log->add('csv-import', sprintf(__('Failed to import product &#8220;%s&#8221;', 'wcmp-product-import-export-bundle'), esc_html($post['post_title'])));
                }
                unset($post);
                return;
            } else {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $this->log->add('csv-import', sprintf(__('> Inserted - post ID is %s.', 'wcmp-product-import-export-bundle'), $post_id));
                }
            }
            unset($postdata);
            if (empty($post['post_id'])) {
                $post['post_id'] = (int) $post_id;
            }
            $this->processed_posts[intval($post['post_id'])] = (int) $post_id;
            if (!empty($post['terms']) && is_array($post['terms'])) {
                $terms_to_set = array();
                foreach ($post['terms'] as $term_group) {
                    $taxonomy = $term_group['taxonomy'];
                    $terms = $term_group['terms'];
                    if ((!$taxonomy) || (!taxonomy_exists($taxonomy))) {
                        continue;
                    }
                    if (!is_array($terms)) {
                        $terms = array($terms);
                    }
                    $terms_to_set[$taxonomy] = array();
                    foreach ($terms as $term_id) {
                        if (!$term_id) {
                            continue;
                        }
                        $terms_to_set[$taxonomy][] = intval($term_id);
                    }
                }
                foreach ($terms_to_set as $tax => $ids) {
                    $tt_ids = wp_set_post_terms($post_id, $ids, $tax, false);
                }
                unset($post['terms'], $terms_to_set);
            }
            if (!empty($post['postmeta']) && is_array($post['postmeta'])) {
                foreach ($post['postmeta'] as $meta) {
                    $key = apply_filters('import_post_meta_key', $meta['key']);
                    if ($key) {
                        update_post_meta($post_id, $key, maybe_unserialize($meta['value']));
                    }
                    if ($key == '_file_paths') {
                        do_action('woocommerce_process_wcmp_product_file_download_paths', $post_id, 0, maybe_unserialize($meta['value']));
                    }
                    if ($key == '_downloadable_files') {
                        $files = $this->get_download_file_url(json_decode($meta['value'], true));
                        $attachment = array(
                            'post_title' => preg_replace('/\.[^.]+$/', '', $post['post_title'] . ' ' . rand(4, 99)),
                            'post_content' => '',
                            'post_status' => 'inherit',
                            'post_parent' => $post_id
                        );
                        $attachment_id = $this->process_wcmp_attachment($attachment, $files['file'], $post_id);
                        $attchurl = wp_get_attachment_url($attachment_id);
                        $woo_files[md5($attchurl)] = array(
                            'file' => $attchurl,
                            'name' => $files['name']
                        );
                        update_post_meta($post_id, $key, $woo_files);
                    }
                }
                unset($post['postmeta']);
            }
            if (!empty($post['images']) && is_array($post['images'])) {
                $featured = true;
                $gallery_ids = array();
                if ($post['images'])
                    foreach ($post['images'] as $image_key => $image) {
                        if (defined('WP_DEBUG') && WP_DEBUG) {
                            $this->log->add('csv-import', sprintf(__('> > Importing image "%s"', 'wcmp-product-import-export-bundle'), $image));
                        }
                        $filename = basename($image);
                        $attachment = array(
                            'post_title' => preg_replace('/\.[^.]+$/', '', $post['post_title'] . ' ' . ( $image_key + 1 )),
                            'post_content' => '',
                            'post_status' => 'inherit',
                            'post_parent' => $post_id
                        );
                        $attachment_id = $this->process_wcmp_attachment($attachment, $image, $post_id);
                        if (!is_wp_error($attachment_id) && $attachment_id) {
                            if (defined('WP_DEBUG') && WP_DEBUG) {
                                $this->log->add('csv-import', sprintf(__('> > Imported image "%s"', 'wcmp-product-import-export-bundle'), $image));
                            }
                            update_post_meta($attachment_id, '_wp_attachment_image_alt', $post['post_title']);
                            if ($featured) {
                                update_post_meta($post_id, '_thumbnail_id', $attachment_id);
                            } else {
                                $gallery_ids[$image_key] = $attachment_id;
                            }
                            update_post_meta($attachment_id, '_woocommerce_exclude_image', 0);
                            $featured = false;
                        } else {
                            if (defined('WP_DEBUG') && WP_DEBUG) {
                                $this->log->add('csv-import', sprintf(__('> > Error importing image "%s"', 'wcmp-product-import-export-bundle'), $image));
                            }
                            if (defined('WP_DEBUG') && WP_DEBUG) {
                                $this->log->add('csv-import', '> > ' . $attachment_id->get_error_message());
                            }
                        }
                        unset($attachment, $attachment_id);
                    }
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $this->log->add('csv-import', __('> > Images set', 'wcmp-product-import-export-bundle'));
                }
                ksort($gallery_ids);
                update_post_meta($post_id, '_product_image_gallery', implode(',', $gallery_ids));
                unset($post['images'], $featured, $gallery_ids);
            }
            if (!empty($post['attributes']) && is_array($post['attributes'])) {
                if ($merging) {
                    $attributes = array_filter((array) maybe_unserialize(get_post_meta($post_id, '_product_attributes', true)));
                    $attributes = array_merge($attributes, $post['attributes']);
                } else {
                    $attributes = $post['attributes'];
                }
                if (!function_exists('attributes_sort')) {

                    function attributes_sort($a, $b) {
                        if ($a['position'] == $b['position'])
                            return 0;
                        return ( $a['position'] < $b['position'] ) ? -1 : 1;
                    }

                }
                uasort($attributes, 'attributes_sort');
                update_post_meta($post_id, '_product_attributes', $attributes);
                unset($post['attributes'], $attributes);
            }
            $this->adding_wcmp_import_result('imported', 'Import successful', $post_id, $post['post_title'], $post['sku']);
            if (defined('WP_DEBUG') && WP_DEBUG)
                $this->log->add('csv-import', sprintf(__('> Finished importing post ID %s.', 'wcmp-product-import-export-bundle'), $post_id));
        }
        else {
            $current_user = wp_get_current_user();
            $parent_sku = $this->find_parent_sku_in_data($post['postmeta']);
            if ($parent_sku == '') {
                $this->adding_wcmp_import_result('skipped', __('No product variation parent sku set', 'wcmp-product-import-export-bundle'), $post['post_id'], 'Not set', $post['sku']);
                if (defined('WP_DEBUG') && WP_DEBUG)
                    $this->log->add('csv-import', __('> Skipping - no post parent sku set.', 'wcmp-product-import-export-bundle'));
                {
                    return;
                }
            }
            $post_parent = $this->get_product_by_sku($parent_sku);
            if (!$post_parent) {
                $this->adding_wcmp_import_result('skipped', __('parent product not found', 'wcmp-product-import-export-bundle'), $post['post_id'], 'Not set', $post['sku']);
                if (defined('WP_DEBUG') && WP_DEBUG)
                    $this->log->add('csv-import', __('> Skipping - no parent product found.', 'wcmp-product-import-export-bundle'));
                {
                    return;
                }
            }
            $post['post_parent'] = $post_parent;
            $post_author = get_post_field('post_author', $post_parent);
            if ($current_user->ID != $post_author) {
                $this->adding_wcmp_import_result('skipped', __('Parent sku is related to other vendor', 'wcmp-product-import-export-bundle'), 'Not Set', 'Not set', $post['sku']);
                if (defined('WP_DEBUG') && WP_DEBUG)
                    $this->log->add('csv-import', __('> Skipping - Parent sku is related to other vendor.', 'wcmp-product-import-export-bundle'));
                {
                    return;
                }
            }
            if (defined('WP_DEBUG') && WP_DEBUG) {
                $this->log->add('csv-import', __('> Inserting variation.', 'wcmp-product-import-export-bundle'));
            }
            $postdata = array(
                'post_date' => date('Y-m-d H:i:s', strtotime('now')),
                'post_date_gmt' => date('Y-m-d H:i:s', strtotime('now')),
                'post_status' => 'publish',
                'post_parent' => $post_parent,
                'menu_order' => $post['menu_order'],
                'post_type' => 'product_variation',
            );
            $post_id = wp_insert_post($postdata, true);
            if (is_wp_error($post_id)) {
                $this->adding_wcmp_import_result('failed', __('Failed to import product variation', 'wcmp-product-import-export-bundle'), $post['post_id'], get_the_title($post['post_parent']), $post['sku']);
                if (defined('WP_DEBUG') && WP_DEBUG)
                    $this->log->add('csv-import', sprintf(__('Failed to import product &#8220;%s&#8221;', 'wcmp-product-import-export-bundle'), esc_html($post['post_title'])));
                return;
            }
            else {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    $this->log->add('csv-import', sprintf(__('> Inserted - post ID is %s.', 'wcmp-product-import-export-bundle'), $post_id));
                }
            }
            $post['post_id'] = (int) $post_id;
            $this->processed_posts[intval($post['post_id'])] = (int) $post_id;
            $postdata['ID'] = $post_id;
            $postdata['post_title'] = sprintf(__('Variation #%s of %s', 'woocommerce'), $post_id, get_the_title($post_parent));
            wp_update_post($postdata);
            if (isset($post['post_excerpt'])) {
                update_post_meta($post_id, '_variation_description', $post['post_excerpt']);
            }
            if (!empty($post['terms'])) {
                $terms_to_set = array();
                foreach ($post['terms'] as $term_group) {
                    $taxonomy = $term_group['taxonomy'];
                    $terms = $term_group['terms'];
                    if (!$taxonomy || !taxonomy_exists($taxonomy))
                        continue;
                    if (!is_array($terms))
                        $terms = array($terms);
                    foreach ($terms as $term_id) {
                        if (!$term_id)
                            continue;
                        $terms_to_set[$taxonomy][] = intval($term_id);
                    }
                }
                foreach ($terms_to_set as $tax => $ids) {
                    $tt_ids = wp_set_post_terms($post_id, $ids, $tax, false);
                }
                if (defined('WP_DEBUG') && WP_DEBUG)
                    $this->log->add('csv-import', __('> > Terms set', 'wcmp-product-import-export-bundle'));
                unset($post['terms'], $terms_to_set);
            }
            if (!empty($post['attributes']) && is_array($post['attributes'])) {
                foreach ($post['attributes'] as $attb) {
                    $key_name = 'attribute_' . strtolower($attb['name']);
                    update_post_meta($post_id, $key_name, $attb['value']);
                }
            }
            if (!empty($post['postmeta'])) {
                foreach ($post['postmeta'] as $meta) {
                    $key = apply_filters('import_post_meta_key', $meta['key']);
                    if (is_array($exclusion_fields_list)) {
                        if (in_array($key, $exclusion_fields_list)) {
                            continue;
                        }
                    }
                    if ($key) {
                        if (defined('WP_DEBUG') && WP_DEBUG)
                            $this->log->add('csv-import', sprintf(__('> > Updating custom field - %s.', 'wcmp-product-import-export-bundle'), $key));
                        $value = maybe_unserialize($meta['value']);
                        update_post_meta($post_id, $key, $value);
                    }
                    if ($key == '_regular_price' || $key == '_sale_price') {
                        update_post_meta($post['post_parent'], '_min_variation_price', '');
                        update_post_meta($post['post_parent'], '_max_variation_price', '');
                        update_post_meta($post['post_parent'], '_min_variation_regular_price', '');
                        update_post_meta($post['post_parent'], '_max_variation_regular_price', '');
                        update_post_meta($post['post_parent'], '_min_variation_sale_price', '');
                        update_post_meta($post['post_parent'], '_max_variation_sale_price', '');
                    }
                }
                unset($post['postmeta']);
            }
            $this->adding_wcmp_import_result('imported', 'variation Import successful', $post_id, $postdata['post_title'], $post['sku']);
            if (!empty($post['images'])) {
                $featured = true;
                if ($post['images'])
                    foreach ($post['images'] as $image) {
                        if (defined('WP_DEBUG') && WP_DEBUG)
                            $this->log->add('csv-import', sprintf(__('> > Importing image "%s"', 'wcmp-product-import-export-bundle'), $image));
                        $wp_filetype = wp_check_filetype(basename($image), null);
                        $wp_upload_dir = wp_upload_dir();
                        $filename = basename($image);
                        $attachment = array(
                            'post_mime_type' => $wp_filetype['type'],
                            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                            'post_content' => '',
                            'post_status' => 'inherit'
                        );
                        $attachment_id = $this->process_wcmp_attachment($attachment, $image, $post_id);
                        if (!is_wp_error($attachment_id)) {
                            if ($featured)
                                update_post_meta($post_id, '_thumbnail_id', $attachment_id);
                            update_post_meta($attachment_id, '_woocommerce_exclude_image', 0);
                            $featured = false;
                        }
                        else {
                            if (defined('WP_DEBUG') && WP_DEBUG)
                                $this->log->add('csv-import', '> > ' . $attachment_id->get_error_message());
                        }
                    }
                if (defined('WP_DEBUG') && WP_DEBUG)
                    $this->log->add('csv-import', __('> > Images set', 'wcmp-product-import-export-bundle'));
                unset($post['images']);
            }
        }
        unset($post);
    }

    public function find_parent_sku_in_data($post_meta) {
        $found = '';
        if ($post_meta && is_array($post_meta) && (!empty($post_meta))) {
            foreach ($post_meta as $meta) {
                if ($meta['key'] == '_parent_sku') {
                    $found = $meta['value'];
                    return $found;
                }
            }
        } else {
            return $found;
        }
    }

    public function link_wcmp_product_skus($type, $product_id, $skus) {
        global $wpdb;
        $ids = array();
        foreach ($skus as $sku) {
            $ids[] = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_sku' AND meta_value = %s;", $sku));
        }
        $ids = array_filter($ids);
        update_post_meta($product_id, "_{$type}_ids", $ids);
    }

    public function process_wcmp_attachment($post, $url, $post_id) {
        global $WCMp_Product_Import_Export_Bundle;
        $attachment_id = '';
        $attachment_url = '';
        $attachment_file = '';
        $upload_dir = wp_upload_dir();
        if (strstr($url, site_url())) {
            $abs_url = str_replace(trailingslashit(site_url()), trailingslashit(ABSPATH), $url);
            $new_name = wp_unique_filename($upload_dir['path'], basename($url));
            $new_url = trailingslashit($upload_dir['path']) . $new_name;
            if (copy($abs_url, $new_url)) {
                $url = basename($new_url);
            }
        }
        if (!strstr($url, 'http')) {
            $attachment_file = trailingslashit($upload_dir['basedir']) . 'product_images/' . $url;
            if (!file_exists($attachment_file)) {
                $attachment_file = trailingslashit($upload_dir['path']) . $url;
            }
            if (file_exists($attachment_file)) {
                $attachment_url = str_replace(trailingslashit(ABSPATH), trailingslashit(site_url()), $attachment_file);
                if ($info = wp_check_filetype($attachment_file)) {
                    $post['post_mime_type'] = $info['type'];
                } else {
                    return new WP_Error('attachment_processing_error', __('Invalid file type', 'wordpress-importer'));
                }
                $post['guid'] = $attachment_url;
                $attachment_id = wp_insert_attachment($post, $attachment_file, $post_id);
            } else {
                return new WP_Error('attachment_processing_error', __('Local image did not exist!', 'wordpress-importer'));
            }
        } else {
            if (preg_match('|^/[\w\W]+$|', $url)) {
                $url = rtrim(site_url(), '/') . $url;
            }
            $upload = $this->download_remote_file($url, $post);
            if (is_wp_error($upload)) {
                return $upload;
            }
            if ($info = wp_check_filetype($upload['file'])) {
                $post['post_mime_type'] = $info['type'];
            } else {
                return new WP_Error('attachment_processing_error', __('Invalid file type', 'wordpress-importer'));
            }
            $post['guid'] = $upload['url'];
            $attachment_file = $upload['file'];
            $attachment_url = $upload['url'];
            $attachment_id = wp_insert_attachment($post, $upload['file'], $post_id);
            unset($upload);
        }
        if (!is_wp_error($attachment_id) && $attachment_id > 0) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                $this->log->add('csv-import', sprintf(__('> > Inserted image attachment "%s"', 'wcmp-product-import-export-bundle'), $url));
            }
            $this->attachments[] = $attachment_id;
        }
        return $attachment_id;
    }

    public function handle_file_upload() {
        global $WCMp_Product_Import_Export_Bundle;
        if (empty($_POST['file_url']) && !empty($_FILES['import'])) {
            $filename = $_FILES['import']['name'];
            $filename_ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($filename_ext == 'csv'){
                $file = wp_import_handle_upload();
                if (isset($file['error'])) {
                    echo '<p><strong>' . __('Sorry, an error occur.', 'wcmp-product-import-export-bundle') . '</strong><br />';
                    echo esc_html($file['error']) . '</p>';
                    return false;
                }
                $this->id = (int) $file['id'];
                return true;
            } else {
                echo '<p><strong>' . __('Sorry, Please upload a csv file.', 'wcmp-product-import-export-bundle') . '</strong><br />';
            }

        } else {
            if (file_exists(ABSPATH . $_POST['file_url'])) {
                $file_ext = end(explode('.', $_POST['file_url']));
                if($file_ext == 'csv'){
                    $this->file_url = esc_attr($_POST['file_url']);
                    return true;
                } else{
                    echo '<p><strong>' . __('Sorry, Please upload a csv file.', 'wcmp-product-import-export-bundle') . '</strong><br />';
                }
                
            } else {
                echo '<p><strong>' . __('Sorry, an error occur.', 'wcmp-product-import-export-bundle') . '</strong></p>';
                return false;
            }
        }
        return false;
    }

    public function download_remote_file($url, $post) {
        global $WCMp_Product_Import_Export_Bundle;
        $file_name = basename(current(explode('?', $url)));
        $wp_filetype = wp_check_filetype($file_name, null);
        $parsed_url = @parse_url($url);
        if (!$parsed_url || !is_array($parsed_url)) {
            return new WP_Error('import_file_error', 'Invalid URL');
        }
        $url = str_replace(" ", '%20', $url);
        $response = wp_remote_get($url, array('timeout' => 10));
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return new WP_Error('import_file_error', 'Error getting remote image');
        }
        if (!$wp_filetype['type']) {
            $headers = wp_remote_retrieve_headers($response);
            if (isset($headers['content-disposition']) && strstr($headers['content-disposition'], 'filename=')) {
                $disposition = end(explode('filename=', $headers['content-disposition']));
                $disposition = sanitize_file_name($disposition);
                $file_name = $disposition;
            } elseif (isset($headers['content-type']) && strstr($headers['content-type'], 'image/')) {
                $file_name = 'image.' . str_replace('image/', '', $headers['content-type']);
            }
            unset($headers);
        }
        $upload = wp_upload_bits($file_name, '', wp_remote_retrieve_body($response));
        if ($upload['error']) {
            return new WP_Error('upload_dir_error', $upload['error']);
        }
        $filesize = filesize($upload['file']);
        if (0 == $filesize) {
            @unlink($upload['file']);
            unset($upload);
            return new WP_Error('import_file_error', __('Zero size file downloaded', 'wcmp-product-import-export-bundle'));
        }
        unset($response);
        return $upload;
    }

    public function wcmp_header_section() {
        global $WCMp_Product_Import_Export_Bundle;
        echo '<h2>' . ( empty($_GET['merge']) ? __('Import Products', 'wcmp-product-import-export-bundle') : __('Merge Products', 'wcmp-product-import-export-bundle') ) . '</h2>';
    }

    public function wcmp_footer_section() {
        
    }

    public function max_wcmp_attachment_size() {
        return apply_filters('import_attachment_size_limit', 0);
    }

    public function backfill_wcmp_parents() {
        global $wpdb;
        if (!empty($this->post_orphans) && is_array($this->post_orphans)) {
            foreach ($this->post_orphans as $child_id => $parent_id) {
                $local_child_id = $local_parent_id = false;
                if (isset($this->processed_posts[$child_id])) {
                    $local_child_id = $this->processed_posts[$child_id];
                }
                if (isset($this->processed_posts[$parent_id])) {
                    $local_parent_id = $this->processed_posts[$parent_id];
                }
                if ($local_child_id && $local_parent_id) {
                    $wpdb->update($wpdb->posts, array('post_parent' => $local_parent_id), array('ID' => $local_child_id), '%d', '%d');
                }
            }
        }
    }

    public function import_wcmp_options() {
        global $WCMp_Product_Import_Export_Bundle;
        $j = 0;
        if ($this->id) {
            $file = get_attached_file($this->id);
        } else if ($this->file_url_import_enabled) {
            $file = ABSPATH . $this->file_url;
        } else {
            return;
        }
        $enc = mb_detect_encoding($file, 'UTF-8, ISO-8859-1', true);
        if ($enc)
            setlocale(LC_ALL, 'en_US.' . $enc);
        @ini_set('auto_detect_line_endings', true);
        if (( $handle = fopen($file, "r") ) !== FALSE) {
            $row = $rawheaders = array();
            $header = fgetcsv($handle, 0, $this->delimiter);
            while (( $postmeta = fgetcsv($handle, 0, $this->delimiter) ) !== FALSE) {
                foreach ($header as $key => $heading) {
                    if (!$heading) {
                        continue;
                    }
                    $s_heading = strtolower($heading);
                    $row[$s_heading] = ( isset($postmeta[$key]) ) ? $this->format_wcmp_data_structure_from_csv($postmeta[$key], $enc) : '';
                    $rawheaders[$s_heading] = $heading;
                }
                break;
            }
            fclose($handle);
        }
        $merge = (!empty($_GET['merge']) && $_GET['merge']) ? 1 : 0;
        $taxonomies = get_taxonomies('', 'names');
        $this->row = $row;
        $this->rawheaders = $rawheaders;
        $WCMp_Product_Import_Export_Bundle->template->get_template('simple-product-import-options.php');
    }

    public function execute_import() {
        global $woocommerce, $wpdb;
        wp_suspend_cache_invalidation(true);
        foreach ($this->parsed_data as $key => &$item) {
            $product = $this->parser->parse_wcmp_product($item, $this->merge_empty_cells);
            if (!is_wp_error($product)) {
                $this->process_wcmp_product($product);
            } else {
                $this->adding_wcmp_import_result('failed', $product->get_error_message(), 'Not parsed', json_encode($item), '-');
            }
            unset($item, $product);
        }
        wp_suspend_cache_invalidation(false);
    }

    public function trigger() {
        global $woocommerce, $wpdb, $WCMp_Product_Import_Export_Bundle;
        if (!empty($_POST['delimiter'])) {
            $this->delimiter = stripslashes(trim($_POST['delimiter']));
        }
        if (!$this->delimiter) {
            $this->delimiter = ',';
        }
        if (!empty($_POST['merge_empty_cells'])) {
            $this->merge_empty_cells = 1;
        } else {
            $this->merge_empty_cells = 0;
        }
        $step = empty($_GET['step']) ? 0 : (int) $_GET['step'];
        switch ($step) {
            case 0 :
                $this->wcmp_header_section();
                $this->make_url_var();
                break;
            case 1 :
                $this->wcmp_header_section();
                check_admin_referer('import-upload');
                if ($this->handle_file_upload()) {
                    $this->import_wcmp_options();
                } else {
                    _e('Error with handle_file_upload!', 'wcmp-product-import-export-bundle');
                }
                break;
            case 2 :
                $this->wcmp_header_section();
                check_admin_referer('import-wcmp-product');
                $this->id = (int) $_POST['import_id'];
                if ($this->file_url_import_enabled) {
                    $this->file_url = esc_attr($_POST['import_url']);
                }
                if ($this->id) {
                    $file = get_attached_file($this->id);
                } else if ($this->file_url_import_enabled) {
                    $file = ABSPATH . $this->file_url;
                }
                $file = str_replace("\\", "/", $file);
                if ($file) {
                    ?>
                    <table id="wcmp-progress"class="wcmp_importer wcmp">
                                <thead>
                            <tr>
                                <th class="wcmp_status">&nbsp;</th>
                                <th class="wcmp_row"><?php _e('Row', 'wcmp-product-import-export-bundle'); ?></th>
                                <th><?php _e('SKU', 'wcmp-product-import-export-bundle'); ?></th>
                                <th><?php _e('Product', 'wcmp-product-import-export-bundle'); ?></th>
                                <th class="wcmp_reason"><?php _e('Status Msg', 'wcmp-product-import-export-bundle'); ?></th>
                            </tr>
                                        </thead>
                        <tfoot>
                            <tr class="wcmp-loader">
                                        <td colspan="5"></td>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                    <script type="text/javascript">
                                        jQuery(document).ready(function($) {
                                        if (! window.console) { window.console = function(){}; }
                        var processed_terms = [];
                        var processed_posts = [];
                                        var post_orphans = [];
                        var attachments = [];
                                        var upsell_skus = [];
                        var crosssell_skus = [];
                                var i = 1;
                        var done_count = 0;
                                function wcmp_rows_import(start_pos, end_pos) {
                        var data = {
                        action: 	'wcmp_import_request',
                                file:       '<?php echo addslashes($file); ?>',
                                mapping:    '<?php echo json_encode($_POST['map_to']); ?>',
                                        delimiter:  '<?php echo $this->delimiter; ?>',
                                        merge_empty_cells: '<?php echo $this->merge_empty_cells; ?>',
                                start_pos:  start_pos,
                                        end_pos:    end_pos,
                        };
                        return $.ajax({
                                        url:       '<?php echo add_query_arg(array('import_page' => $this->import_page, 'step' => '3', 'merge' => !empty($_GET['merge']) ? '1' : '0'), admin_url('admin-ajax.php')); ?>',
                               data:       data,
                                        type:      'POST',
                                success:    function(response) {
                                console.log(response);
                                        if (response) {            try {
                                        if (response.indexOf("<!--WCMP_START-->") >= 0) {
                                response = response.split("<!--WCMP_START-->")[1];
                                }
                                        if (response.indexOf("<!--WCMP_END-->") >= 0) {
                                response = response.split("<!--WCMP_END-->")[0];
                                        }
                                        var results = $.parseJSON(response);
                                        if (results.error) {
                                        $('#wcmp-progress tbody').append('<tr id="row-' + i + '" class="error"><td class="wcmp_status" colspan="5">' + results.error + '</td></tr>');
                                                            i++;
                                                           }
                                                            else if (results.import_results && $(results.import_results).size() > 0) {
                                                            $.each(results.processed_terms, function(index, value) {
                                                            processed_terms.push(value);
                                                            });
                                                            $.each(results.processed_posts, function(index, value) {
                                                            processed_posts.push(value);
                                                            });
                                                            $.each(results.post_orphans, function(index, value) {
                                                            post_orphans.push(value);
                                                            });
                                                            $.each(results.attachments, function(index, value) {
                                                            attachments.push(value);
                                                            });
                                                            upsell_skus = jQuery.extend({}, upsell_skus, results.upsell_skus);
                                                            crosssell_skus = jQuery.extend({}, crosssell_skus, results.crosssell_skus);
                                                            $(results.import_results).each(function(index, row) {
                                                            $('#wcmp-progress tbody').append('<tr id="row-' + i + '" class="' + row['status'] + '"><td><mark class="result" title="' + row['status'] + '">' + row['status'] + '</mark></td><td class="wcmp_row">' + i + '</td><td>' + row['sku'] + '</td><td>' + row['post_id'] + ' - ' + row['post_title'] + '</td><td class="wcmp_reason">' + row['reason'] + '</td></tr>');
                                                                                i++;
                                                                                });
                                                                                }
                                                                                }
                                                                               catch (err) {

                                                                                }
                                                                                }
                                                                               else {
                                                                                $('#wcmp-progress tbody').append('<tr class="error"><td class="wcmp_status" colspan="5">' +  '<?php _e('AJAX Error', 'wcmp-product-import-export-bundle'); ?>' + '</td></tr>');
                                                                                                    }
                                                                                                    var w = $(window);
                                                                                                    var row = $("#row-" + (i - 1));
                                                                                                    if (row.length) {
                                                                                                    w.scrollTop(row.offset().top - (w.height() / 2));
                                                                                                    }
                                                                                                    done_count++;
                                                                                                    $('body').trigger('import_request_complete');
                                                                                                    }
                                                                                            });
                                                                                            }
                                                                                            var rows = [];
                    <?php
                    $limit = apply_filters('woocommerce_csv_import_limit_per_request', 10);
                    $enc = mb_detect_encoding($file, 'UTF-8, ISO-8859-1', true);
                    if ($enc) {
                        setlocale(LC_ALL, 'en_US.' . $enc);
                    }
                    @ini_set('auto_detect_line_endings', true);
                    $count = 0;
                    $previous_position = 0;
                    $position = 0;
                    $import_count = 0;
                    if (( $handle = fopen($file, "r") ) !== FALSE) {
                        while (( $postmeta = fgetcsv($handle, 0, $this->delimiter) ) !== FALSE) {
                            $count++;
                            if ($count >= $limit) {
                                $previous_position = $position;
                                $position = ftell($handle);
                                $count = 0;
                                $import_count ++;
                                ?> rows.push([ <?php echo $previous_position; ?>, <?php echo $position; ?> ]); <?php
                            }
                        }
                        if ($count > 0) {
                            ?>rows.push([ <?php echo $position; ?>, '' ]); <?php
                            $import_count ++;
                        }
                        fclose($handle);
                    }
                    ?>
                                                                                            var data = rows.shift();
                                                                                            var regen_count = 0;
                                                                                            wcmp_rows_import(data[0], data[1]);
                                                                                            $('body').on('import_request_complete', function() {
                                                                                            if (done_count == <?php echo $import_count; ?>) {
                                                                                            if (attachments.length) {
                                                                                            $('#wcmp-progress tbody').append('<tr class="generating"><td colspan="5"><div class="progress"></div></td></tr>');
                                                                                            index = 0;
                                                                                            $.each(attachments, function(i, value) {
                                                                                            generate_thumbnail_img(value);
                                                                                            index ++;
                                                                                            if (index == attachments.length) {
                                                                                            wcmp_import_done();
                                                                                            }
                                                                                            });
                                                                                            }
                                                                                           else {
                                                                                            wcmp_import_done();
                                                                                            }
                                                                                            }
                                                                                           else {
                                                                                            data = rows.shift();
                                                                                            wcmp_rows_import(data[0], data[1]);
                                                                                            }
                                                                                            });
                                                                                            function generate_thumbnail_img(id) {
                                                                                            $.ajax({
                                                                                            type: 'POST',
                                                                                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                                                                                    data: { action: "wcmp_generate_thumbnail_img", id: id },
                                                                                                    success: function(response) {
                                                                                                    if (response !== Object(response) || (typeof response.success === "undefined" && typeof response.error === "undefined")) {
                                                                                                    response = new Object;
                                                                                                    response.success = false;
                                                                                                    response.error = "<?php printf(esc_js(__('The resize request was abnormally terminated (ID %s). This is likely due to the image exceeding available memory or some other type of fatal error.', 'wcmp-product-import-export-bundle')), '" + id + "'); ?>";
                                                                                                    }
                                                                                                    regen_count ++;
                                         $('#wcmp-progress tbody .generating .progress').css( 'width', ( ( regen_count / attachments.length ) * 100 ) + '%' ).html( regen_count + ' / ' + attachments.length + ' <?php echo esc_js(__('thumbnails generated', 'wcmp-product-import-export-bundle')); ?>' );
                                                            if ( ! response.success ) {
                                                                    $('#wcmp-progress tbody').append( '<tr><td colspan="5">' + response.error + '</td></tr>' );
                                                            }
                                                    },
                                                    error: function( response ) {
                                                            $('#wcmp-progress tbody').append( '<tr><td colspan="5">' + response.error + '</td></tr>' );
                                                    }
                                            });
                                    }

                                    function wcmp_import_done() {
                                            var data = {
                                                    action: 'wcmp_import_request',
                                                    file: '<?php echo $file; ?>',
                                                    processed_terms: processed_terms,
                                                    processed_posts: processed_posts,
                                                    post_orphans: post_orphans,
                                                    upsell_skus: upsell_skus,
                                                    crosssell_skus: crosssell_skus
                                            };
                                            $.ajax({
                                                    url: '<?php echo add_query_arg(array('import_page' => $this->import_page, 'step' => '4', 'merge' => !empty($_GET['merge']) ? 1 : 0), admin_url('admin-ajax.php')); ?>',
                                                    data:       data,
                                                    type:       'POST',
                                                    success:    function( response ) {
                                                            console.log( response );
                                                            $('#wcmp-progress tbody').append( '<tr class="complete"><td colspan="5">' + response + '</td></tr>' );
                                                            $('.wcmp-loader').hide();
                                                    }
                                            });
                                    }
                            });
                    </script>
                    <?php
                } else {
                    echo '<p class="error">' . __('Error finding uploaded file!', WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_TEXT_DOMAIN) . '</p>';
                }
                break;
            case 3 :
                add_filter('http_request_timeout', array($this, 'wcmp_request_timeout'));
                if (function_exists('gc_enable')) {
                    gc_enable();
                }
                @set_time_limit(0);
                @ob_flush();
                @flush();
                $wpdb->hide_errors();
                $file = stripslashes($_POST['file']);
                $mapping = json_decode(stripslashes($_POST['mapping']), true);
                $start_pos = isset($_POST['start_pos']) ? absint($_POST['start_pos']) : 0;
                $end_pos = isset($_POST['end_pos']) ? absint($_POST['end_pos']) : '';
                $position = $this->wcmp_start_import($file, $mapping, $start_pos, $end_pos);
                $this->execute_import();
                $this->import_wcmp_end();
                $results = array();
                $results['import_results'] = $this->import_results;
                $results['processed_terms'] = $this->processed_terms;
                $results['processed_posts'] = $this->processed_posts;
                $results['post_orphans'] = $this->post_orphans;
                $results['attachments'] = $this->attachments;
                $results['upsell_skus'] = $this->upsell_skus;
                $results['crosssell_skus'] = $this->crosssell_skus;
                echo "<!--WCMP_START-->";
                echo json_encode($results);
                echo "<!--WCMP_END-->";
                exit;
                break;
            case 4 :
                add_filter('http_request_timeout', array($this, 'wcmp_request_timeout'));
                if (function_exists('gc_enable')) {
                    gc_enable();
                }
                @set_time_limit(0);
                @ob_flush();
                @flush();
                $wpdb->hide_errors();
                $this->processed_terms = isset($_POST['processed_terms']) ? $_POST['processed_terms'] : array();
                $this->processed_posts = isset($_POST['processed_posts']) ? $_POST['processed_posts'] : array();
                $this->post_orphans = isset($_POST['post_orphans']) ? $_POST['post_orphans'] : array();
                $this->crosssell_skus = isset($_POST['crosssell_skus']) ? array_filter((array) $_POST['crosssell_skus']) : array();
                $this->upsell_skus = isset($_POST['upsell_skus']) ? array_filter((array) $_POST['upsell_skus']) : array();
                _e('Processing Tables... Upload products...', WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_TEXT_DOMAIN) . ' ';
                wp_defer_term_counting(true);
                wp_defer_comment_counting(true);
                if (function_exists('wc_delete_product_transients')) {
                    wc_delete_product_transients();
                } else {
                    $woocommerce->clear_product_transients();
                }
                $wpdb->query("DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_wc_product_type_%')");
                $this->backfill_wcmp_parents();
                if (!empty($this->upsell_skus)) {
                    _e('Upload upsells...', WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_TEXT_DOMAIN) . ' ';
                    foreach ($this->upsell_skus as $post_id => $skus) {
                        $this->link_wcmp_product_skus('upsell', $post_id, $skus);
                    }
                }
                if (!empty($this->crosssell_skus)) {
                    _e('Upload crosssells...', WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_TEXT_DOMAIN) . ' ';
                    foreach ($this->crosssell_skus as $post_id => $skus) {
                        $this->link_wcmp_product_skus('crosssell', $post_id, $skus);
                    }
                }

                if ('wcmp_import_export_variation_csv' == $this->import_page && !empty($this->processed_posts)) {
                    _e('Importing variations...', WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_TEXT_DOMAIN) . ' ';
                    $synced = array();
                    foreach ($this->processed_posts as $post_id) {
                        $parent = wp_get_post_parent_id($post_id);
                        if (!in_array($parent, $synced)) {
                            WC_Product_Variable::sync($parent);
                            $synced[] = $parent;
                        }
                    }
                }
                _e('Finished. Import complete.', WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_TEXT_DOMAIN);
                $this->import_wcmp_end();
                exit;
                break;
        }
        $this->wcmp_footer_section();
    }

}
