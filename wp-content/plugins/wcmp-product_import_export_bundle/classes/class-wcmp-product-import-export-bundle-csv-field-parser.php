<?php

class WCMp_Product_Import_Export_Bundle_CSV_Field_Parser {

    var $post_type;
    var $reserved_fields;
    var $post_defaults;
    var $postmeta_defaults;
    var $postmeta_allowed;
    var $allowed_product_types;
    var $row;

    /**
     * Constructor
     */
    public function __construct($post_type = 'product') {
        global $WCMp_Product_Import_Export_Bundle;
        $this->allowed_product_types = array();
        $simple_term = get_term_by('slug', 'simple', 'product_type');
        $variable_term = get_term_by('slug', 'variable', 'product_type');
        $grouped_term = get_term_by('slug', 'grouped', 'product_type');
        $external_term = get_term_by('slug', 'external', 'product_type');
        $allowed_product_type_by_wcmp = get_option('wcmp_capabilities_product_settings_name');
        if (isset($allowed_product_type_by_wcmp) && is_array($allowed_product_type_by_wcmp) && (!empty($allowed_product_type_by_wcmp))) {
            if (isset($allowed_product_type_by_wcmp['simple'])) {
                $ptype = array('simple' => $simple_term->term_id);
                $this->allowed_product_types = array_merge($this->allowed_product_types, $ptype);
            }
            if (isset($allowed_product_type_by_wcmp['variable'])) {
                $ptype2 = array('variable' => $variable_term->term_id);
                $this->allowed_product_types = array_merge($this->allowed_product_types, $ptype2);
            }
            if (isset($allowed_product_type_by_wcmp['grouped'])) {
                $ptype3 = array('grouped' => $grouped_term->term_id);
                $this->allowed_product_types = array_merge($this->allowed_product_types, $ptype3);
            }
            if (isset($allowed_product_type_by_wcmp['external'])) {
                $ptype4 = array('external' => $external_term->term_id);
                $this->allowed_product_types = array_merge($this->allowed_product_types, $ptype4);
            }
        }
        if (class_exists('WC_Subscriptions')) {
            $subscription_term = get_term_by('slug', 'subscription', 'product_type');
            $variable_subscription_term = get_term_by('slug', 'variable-subscription', 'product_type');
            $this->allowed_product_types['subscription'] = $subscription_term->term_id;
            $this->allowed_product_types['variable-subscription'] = $variable_subscription_term->term_id;
        }
        $this->post_type = $post_type;
        $this->reserved_fields = $WCMp_Product_Import_Export_Bundle->wcmp_import_meta_fields->data_reserve_fields();
        $this->post_defaults = $WCMp_Product_Import_Export_Bundle->wcmp_import_meta_fields->data_post_defaults($post_type);
        $this->postmeta_defaults = $WCMp_Product_Import_Export_Bundle->wcmp_import_meta_fields->data_post_meta_defaults();
        $this->postmeta_allowed = $WCMp_Product_Import_Export_Bundle->wcmp_import_meta_fields->data_post_meta_allowed();
    }

    public function parse_wcmp_product($item, $merge_empty_cells = 0) {
        global $WCMp_Product_Import_Bundle, $wpdb, $WCMp_Product_Import_Export_Bundle;

        $this->row++;
        $terms_array = $attributes = $default_attributes = $postmeta = $product = array();
        $product_type_array = '';
        $merging = false;
        $post_id = 0;
        //$post_id = ( ! empty( $item['post_id'] ) ) ? $item['post_id'] : $post_id;		
        $product['merging'] = false;
        $WCMp_Product_Import_Bundle->log->add('csv-import', sprintf(__('> Row %s - preparing for import.', 'wcmp-product-import-export-bundle'), $this->row));
        if (!isset($item['parent_sku']) || $item['parent_sku'] == '') {
            $this->post_type = 'product';
            if (empty($item['post_title'])) {
                $WCMp_Product_Import_Bundle->log->add('csv-import', __('> > Skipped. No post_title set for new product.', 'wcmp-product-import-export-bundle'));
                return new WP_Error('parse-error', __('No post_title set for new product.', 'wcmp-product-import-export-bundle'));
            }
            $variation_true = 0;
        } else {
            $this->post_type = 'product_variation';
            $variation_true = 1;
        }
        $product['post_id'] = $post_id;
        foreach ($this->post_defaults as $column => $default) {
            if (isset($item[$column])) {
                if ($column == 'post_type') {
                    $product[$column] = $this->post_type;
                } else {
                    $product[$column] = $item[$column];
                }
            }
        }
        if ($variation_true == 1) {
            $product['is_variation'] = 'product_variation';
            $product['post_type'] = 'product_variation';
        } else {
            $product['is_variation'] = 'product';
            $product['post_type'] = 'product';
        }

        foreach ($this->postmeta_defaults as $column => $default) {
            if (isset($item[$column])) {
                $postmeta[$column] = (string) $item[$column];
            } elseif (isset($item['_' . $column])) {
                $postmeta[$column] = (string) $item['_' . $column];
            }
            if (isset($postmeta[$column]) && isset($this->postmeta_allowed[$column]) && !in_array($postmeta[$column], $this->postmeta_allowed[$column])) {
                $postmeta[$column] = $this->postmeta_defaults[$column];
            }
        }
        $product = wp_parse_args($product, $this->post_defaults);
        $postmeta = wp_parse_args($postmeta, $this->postmeta_defaults);



        if ($this->post_type == 'product_variation') {
            if (isset($postmeta['regular_price']) && isset($postmeta['sale_price']) && $postmeta['sale_price'] !== '') {
                $price = min($postmeta['sale_price'], $postmeta['regular_price']);
                $postmeta['price'] = $price;
            } elseif (isset($postmeta['regular_price'])) {
                $postmeta['price'] = $postmeta['regular_price'];
            }
        } else {
            if (isset($postmeta['regular_price']) && isset($postmeta['sale_price']) && $postmeta['sale_price'] !== '') {
                $price = min($postmeta['sale_price'], $postmeta['regular_price']);
                $postmeta['price'] = $price;
            } elseif (isset($postmeta['regular_price'])) {
                $postmeta['price'] = $postmeta['regular_price'];
            }
            $postmeta['min_variation_price'] = $postmeta['max_variation_price'] = $postmeta['min_variation_regular_price'] = $postmeta['max_variation_regular_price'] = $postmeta['min_variation_sale_price'] = $postmeta['max_variation_sale_price'] = '';
        }
        if (isset($postmeta['sale_price_dates_from'])) {
            $postmeta['sale_price_dates_from'] = empty($postmeta['sale_price_dates_from']) ? '' : strtotime($postmeta['sale_price_dates_from']);
        }
        if (isset($postmeta['sale_price_dates_to'])) {
            $postmeta['sale_price_dates_to'] = empty($postmeta['sale_price_dates_to']) ? '' : strtotime($postmeta['sale_price_dates_to']);
        }
        if (!empty($product['post_status'])) {
            $product['post_status'] = strtolower($product['post_status']);
            if (!in_array($product['post_status'], array('publish', 'private', 'draft', 'pending', 'future', 'inherit', 'trash'))) {
                $product['post_status'] = 'publish';
            }
        }
        foreach ($postmeta as $key => $value) {
            $product['postmeta'][] = array('key' => '_' . esc_attr($key), 'value' => $value);
        }
        foreach ($item as $key => $value) {
            if ($this->post_type == 'product' && !$merge_empty_cells && $value == "") {
                continue;
            }
            if ($key == 'file_paths') {
                $file_paths = explode('|', $value);
                $_file_paths = array();
                foreach ($file_paths as $file_path) {
                    $file_path = trim($file_path);
                    $_file_paths[md5($file_path)] = $file_path;
                }
                $value = $_file_paths;
                $product['postmeta'][] = array('key' => '_' . esc_attr($key), 'value' => $value);
            } elseif (strstr($key, 'meta:attribute_pa_')) {
                $meta_key = ( isset($WCMp_Product_Import_Bundle->rawheaders[$key]) ) ? $WCMp_Product_Import_Bundle->rawheaders[$key] : $key;
                $meta_key = trim(str_replace('meta:', '', $meta_key));
                $value = sanitize_title($value);
                $product['postmeta'][] = array(
                    'key' => esc_attr($meta_key),
                    'value' => $value
                );
            } elseif (strstr($key, 'meta:')) {
                $meta_key = ( isset($WCMp_Product_Import_Bundle->rawheaders[$key]) ) ? $WCMp_Product_Import_Bundle->rawheaders[$key] : $key;
                $meta_key = trim(str_replace('meta:', '', $meta_key));
                $json = json_decode($value, true);
                if (is_array($json) || is_object($json)) {
                    $value = (array) $json;
                }
                if (!$meta_key == '_commission_per_product') {
                    $product['postmeta'][] = array(
                        'key' => esc_attr($meta_key),
                        'value' => ''
                    );
                }
            } elseif (strstr($key, 'tax:')) {
                $taxonomy = trim(str_replace('tax:', '', $key));
                if (!taxonomy_exists($taxonomy)) {
                    $WCMp_Product_Import_Bundle->log->add('csv-import', sprintf(__('> > Skipping taxonomy "%s" - it does not exist.', 'wcmp-product-import-export-bundle'), $taxonomy));
                    continue;
                }
                if ($taxonomy == 'product_type') {
                    $term = strtolower($value);
                    if (!array_key_exists($term, $this->allowed_product_types)) {
                        $WCMp_Product_Import_Bundle->log->add('csv-import', sprintf(__('> > > Product type "%s" not allowed - using simple.', 'wcmp-product-import-export-bundle'), $term));
                        $term_id = $this->allowed_product_types['simple'];
                        $product_type_array = 'not_allowed';
                        $product_type_array2 = $value;
                    } else {
                        $term_id = $this->allowed_product_types[$term];
                        $product_type_array = 'allowed';
                        $product_type_array2 = $value;
                    }
                    $terms_array[] = array(
                        'taxonomy' => $taxonomy,
                        'terms' => array($term_id)
                    );
                    continue;
                }
                if ($taxonomy == 'product_cat') {
                    $terms = array();
                    $raw_terms = explode('|', $value);
                    $raw_terms = array_map('trim', $raw_terms);
                    foreach ($raw_terms as $raw_term) {
                        if (strstr($raw_term, '>')) {
                            $raw_term = explode('>', $raw_term);
                            $raw_term = array_map('trim', $raw_term);
                            $raw_term = array_filter($raw_term);
                            $parent = 0;
                            $loop = 0;
                            foreach ($raw_term as $term) {
                                $loop ++;
                                $term_id = '';
                                if (isset($this->inserted_terms[$taxonomy][$parent][$term])) {
                                    $term_id = $this->inserted_terms[$taxonomy][$parent][$term];
                                } elseif ($term) {
                                    $term_may_exist = term_exists($term, $taxonomy, $parent);
                                    if (is_array($term_may_exist)) {
                                        $possible_term = get_term($term_may_exist['term_id'], 'product_cat');
                                        if ($possible_term->parent == $parent) {
                                            $term_id = $term_may_exist['term_id'];
                                        }
                                    }
                                    if (!$term_id) {
                                        $WCMp_Product_Import_Bundle->log->add('CSV-Import', sprintf(__('> > Sorry  product category %s does not exits', 'wcmp-product-import-export-bundle'), esc_html($term)));
                                        break;
                                    }
                                    $this->inserted_terms[$taxonomy][$parent][$term] = $term_id;
                                }
                                if (!$term_id) {
                                    break;
                                }
                                if (sizeof($raw_term) == $loop) {
                                    $terms[] = $term_id;
                                }
                                $parent = $term_id;
                            }
                        } else {
                            $term_id = '';
                            if (isset($this->inserted_terms[$taxonomy][0][$raw_term])) {
                                $term_id = $this->inserted_terms[$taxonomy][0][$raw_term];
                            } elseif ($raw_term) {
                                $term_exists = term_exists($raw_term, $taxonomy, 0);
                                $term_id = is_array($term_exists) ? $term_exists['term_id'] : 0;
                                if (!$term_id) {
                                    $WCMp_Product_Import_Bundle->log->add('csv-import', sprintf(__('> > Sorry  product category %s does not exits', 'wcmp-product-import-export-bundle'), esc_html($raw_term)));
                                    break;
                                }
                                $this->inserted_terms[$taxonomy][0][$raw_term] = $term_id;
                            }
                            if ($term_id) {
                                $terms[] = $term_id;
                            }
                        }
                    }
                    if (sizeof($terms) == 0) {
                        continue;
                    }
                    $terms_array[] = array(
                        'taxonomy' => $taxonomy,
                        'terms' => $terms
                    );
                    continue;
                }
                if ($taxonomy == 'dc_vendor_shop') {
                    $current_user = wp_get_current_user();
                    $term_id = get_user_meta($current_user->ID, '_vendor_term_id', true);
                    $term = strtolower($value);
                    $terms_array[] = array(
                        'taxonomy' => $taxonomy,
                        'terms' => array($term_id)
                    );
                    $dc_vendor_shop_true = 1;
                    continue;
                }
                $terms = array();
                $raw_terms = explode('|', $value);
                $raw_terms = array_map('trim', $raw_terms);
                foreach ($raw_terms as $raw_term) {
                    if (strstr($raw_term, '>')) {
                        $raw_term = explode('>', $raw_term);
                        $raw_term = array_map('trim', $raw_term);
                        $raw_term = array_filter($raw_term);
                        $parent = 0;
                        $loop = 0;
                        foreach ($raw_term as $term) {
                            $loop ++;
                            $term_id = '';
                            if (isset($this->inserted_terms[$taxonomy][$parent][$term])) {
                                $term_id = $this->inserted_terms[$taxonomy][$parent][$term];
                            } elseif ($term) {
                                $term_may_exist = term_exists($term, $taxonomy, $parent);
                                if (is_array($term_may_exist)) {
                                    $possible_term = get_term($term_may_exist['term_id'], 'product_cat');
                                    if ($possible_term->parent == $parent) {
                                        $term_id = $term_may_exist['term_id'];
                                    }
                                }
                                if (!$term_id) {

                                    $slug = array();
                                    for ($i = 0; $i < $loop; $i ++) {
                                        $slug[] = $raw_term[$i];
                                    }
                                    $slug = sanitize_title(implode('-', $slug));
                                    $t = wp_insert_term($term, $taxonomy, array('parent' => $parent, 'slug' => $slug));
                                    if (!is_wp_error($t)) {
                                        $term_id = $t['term_id'];
                                    } else {
                                        $WCMp_Product_Import_Bundle->log->add('CSV-Import', sprintf(__('> > Failed to import term %s %s', 'wcmp-product-import-export-bundle'), esc_html($term), esc_html($taxonomy)));
                                        break;
                                    }
                                }
                                $this->inserted_terms[$taxonomy][$parent][$term] = $term_id;
                            }
                            if (!$term_id) {
                                break;
                            }
                            if (sizeof($raw_term) == $loop) {
                                $terms[] = $term_id;
                            }
                            $parent = $term_id;
                        }
                    } else {
                        $term_id = '';
                        if (isset($this->inserted_terms[$taxonomy][0][$raw_term])) {
                            $term_id = $this->inserted_terms[$taxonomy][0][$raw_term];
                        } elseif ($raw_term) {
                            $term_exists = term_exists($raw_term, $taxonomy, 0);
                            $term_id = is_array($term_exists) ? $term_exists['term_id'] : 0;
                            if (!$term_id) {
                                $t = wp_insert_term(trim($raw_term), $taxonomy, array('parent' => 0));
                                if (!is_wp_error($t)) {
                                    $term_id = $t['term_id'];
                                } else {
                                    $WCMp_Product_Import_Bundle->log->add('csv-import', sprintf(__('> > Failed to import term %s %s', 'wcmp-product-import-export-bundle'), esc_html($raw_term), esc_html($taxonomy)));
                                    break;
                                }
                            }
                            $this->inserted_terms[$taxonomy][0][$raw_term] = $term_id;
                        }
                        if ($term_id) {
                            $terms[] = $term_id;
                        }
                    }
                }
                if (sizeof($terms) == 0) {
                    continue;
                }
                $terms_array[] = array(
                    'taxonomy' => $taxonomy,
                    'terms' => $terms
                );
            } elseif (strstr($key, 'attribute:')) {
                $attribute_key = sanitize_title(trim(str_replace('attribute:', '', $key)));
                $attribute_name = str_replace('attribute:', '', $WCMp_Product_Import_Bundle->rawheaders[$key]);
                if (!$attribute_key) {
                    continue;
                }
                if (substr($attribute_key, 0, 3) == 'pa_') {
                    if ($variation_true == 1) {
                        $attributes[$attribute_key]['name'] = $attribute_name;
                        $attributes[$attribute_key]['value'] = $value;
                    } else {
                        $taxonomy = $attribute_key;
                        if (!taxonomy_exists($taxonomy)) {
                            $WCMp_Product_Import_Bundle->log->add('csv-import', sprintf(__('> > Attribute taxonomy "%s" does not exist. Adding it.', 'wcmp-product-import-export-bundle'), $taxonomy));
                            $nicename = strtolower(sanitize_title(str_replace('pa_', '', $taxonomy)));
                            $exists_in_db = $wpdb->get_var("SELECT attribute_id FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '" . $nicename . "';");
                            if (!$exists_in_db) {
                                $wpdb->insert($wpdb->prefix . "woocommerce_attribute_taxonomies", array('attribute_name' => $nicename, 'attribute_type' => 'select'), array('%s', '%s'));
                            }
                            register_taxonomy($taxonomy, array('product', 'product_variation'), array(
                                'hierarchical' => true,
                                'show_ui' => false,
                                'query_var' => true,
                                'rewrite' => false,
                                    )
                            );
                        }
                        $terms = array();
                        $raw_terms = explode('|', $value);
                        $raw_terms = array_filter(array_map('trim', $raw_terms));
                        if (sizeof($raw_terms) > 0) {
                            foreach ($raw_terms as $raw_term) {
                                if (!$raw_term) {
                                    continue;
                                }
                                $term_exists = term_exists($raw_term, $taxonomy, 0);
                                $term_id = is_array($term_exists) ? $term_exists['term_id'] : 0;
                                if (!$term_id) {
                                    $t = wp_insert_term(trim($raw_term), $taxonomy);
                                    if (!is_wp_error($t)) {
                                        $term_id = $t['term_id'];
                                    } else {
                                        $WCMp_Product_Import_Bundle->log->add('csv-import', sprintf(__('> > Failed to import term %s %s', 'wcmp-product-import-export-bundle'), esc_html($raw_term), esc_html($taxonomy)));
                                        break;
                                    }
                                }
                                if ($term_id) {
                                    $terms[] = $term_id;
                                }
                            }
                        }
                        $terms_array[] = array(
                            'taxonomy' => $taxonomy,
                            'terms' => $terms
                        );

                        if (!isset($attributes[$taxonomy])) {
                            $attributes[$taxonomy] = array();
                        }
                        $attributes[$taxonomy]['name'] = $taxonomy;
                        $attributes[$taxonomy]['value'] = null;
                        $attributes[$taxonomy]['is_taxonomy'] = 1;
                        if (!isset($attributes[$taxonomy]['position'])) {
                            $attributes[$taxonomy]['position'] = 0;
                        }
                        if (!isset($attributes[$taxonomy]['is_visible'])) {
                            $attributes[$taxonomy]['is_visible'] = 1;
                        }
                        if (!isset($attributes[$taxonomy]['is_variation'])) {
                            $attributes[$taxonomy]['is_variation'] = 0;
                        }
                    }
                } else {
                    if (!$value || !$attribute_key)
                        continue;
                    if (!isset($attributes[$attribute_key])) {
                        $attributes[$attribute_key] = array();
                    }
                    $attributes[$attribute_key]['name'] = $attribute_name;
                    $attributes[$attribute_key]['value'] = $value;
                    $attributes[$attribute_key]['is_taxonomy'] = 0;
                    if (!isset($attributes[$attribute_key]['position'])) {
                        $attributes[$attribute_key]['position'] = 0;
                    }
                    if (!isset($attributes[$attribute_key]['is_visible'])) {
                        $attributes[$attribute_key]['is_visible'] = 1;
                    }
                    if (!isset($attributes[$attribute_key]['is_variation'])) {
                        $attributes[$attribute_key]['is_variation'] = 0;
                    }
                }
            } elseif (strstr($key, 'attribute_data:')) {
                $attribute_key = sanitize_title(trim(str_replace('attribute_data:', '', $key)));
                if (!$attribute_key) {
                    continue;
                }
                $values = explode('|', $value);
                $position = ( isset($values[0]) ) ? (int) $values[0] : 0;
                $visible = ( isset($values[1]) ) ? (int) $values[1] : 1;
                $variation = ( isset($values[2]) ) ? (int) $values[2] : 0;
                if (!isset($attributes[$attribute_key])) {
                    $attributes[$attribute_key] = array();
                }
                $attributes[$attribute_key]['position'] = $position;
                $attributes[$attribute_key]['is_visible'] = $visible;
                $attributes[$attribute_key]['is_variation'] = $variation;
            } elseif (strstr($key, 'attribute_default:')) {
                $attribute_key = sanitize_title(trim(str_replace('attribute_default:', '', $key)));
                if (!$attribute_key) {
                    continue;
                }
                $default_attributes[$attribute_key] = $value;
            } elseif (strstr($key, 'parent_sku')) {
                if ($value) {
                    $found_product_id = $wpdb->get_var($wpdb->prepare("
						SELECT $wpdb->posts.ID
						FROM $wpdb->posts
						LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
						WHERE $wpdb->posts.post_type = 'product'
						AND $wpdb->posts.post_status IN ( 'publish', 'private', 'draft' )
						AND $wpdb->postmeta.meta_key = '_sku' AND $wpdb->postmeta.meta_value = '%s'", $value)
                    );
                    if ($found_product_id) {
                        $product['post_parent'] = $found_product_id;
                    }
                }
            } elseif (strstr($key, 'upsell_skus')) {
                if ($value) {
                    $skus = array_filter(array_map('trim', explode('|', $value)));
                    $product['upsell_skus'] = $skus;
                }
            } elseif (strstr($key, 'crosssell_skus')) {
                if ($value) {
                    $skus = array_filter(array_map('trim', explode('|', $value)));
                    $product['crosssell_skus'] = $skus;
                }
            }
        }
        foreach ($attributes as $key => $value) {
            if (!isset($value['name'])) {
                unset($attributes[$key]);
            }
        }
        if (!empty($item['images'])) {
            $images = array_map('trim', explode('|', $item['images']));
        } else {
            $images = '';
        }
        if (isset($dc_vendor_shop_true) && $dc_vendor_shop_true == 1) {
            
        } else {
            $current_user = wp_get_current_user();
            $term_id = get_user_meta($current_user->ID, '_vendor_term_id', true);
            $terms_array[] = array(
                'taxonomy' => 'dc_vendor_shop',
                'terms' => array($term_id)
            );
        }
        if (isset($item['meta:_purchase_note'])) {
            $product['postmeta'][] = array('key' => '_purchase_note', 'value' => $item['meta:_purchase_note']);
        }
        $product['postmeta'][] = array('key' => '_default_attributes', 'value' => $default_attributes);
        $product['attributes'] = $attributes;
        $product['images'] = $images;
        $product['terms'] = $terms_array;
        $product['sku'] = (!empty($item['sku']) ) ? $item['sku'] : '';
        $product['product_type_array'] = $product_type_array;
        $product['product_type_array2'] = $product_type_array2;
        unset($item, $terms_array, $postmeta, $attributes, $images, $product_type_array);
        return $product;
    }

    public function parse_wcmp_data($file, $delimiter, $mapping, $start_pos = 0, $end_pos = null) {
        global $WCMp_Product_Import_Export_Bundle;
        $enc = mb_detect_encoding($file, 'UTF-8, ISO-8859-1', true);
        if ($enc) {
            setlocale(LC_ALL, 'en_US.' . $enc);
        }
        @ini_set('auto_detect_line_endings', true);
        $parsed_data = array();
        $rawheaders = array();
        if (( $handle = fopen($file, "r") ) !== FALSE) {
            $header = fgetcsv($handle, 0, $delimiter);
            if ($start_pos != 0) {
                fseek($handle, $start_pos);
            }
            while (( $postmeta = fgetcsv($handle, 0, $delimiter) ) !== FALSE) {
                $row = array();
                foreach ($header as $key => $heading) {
                    $s_heading = strtolower($heading);
                    if (isset($mapping[$s_heading])) {
                        if ($mapping[$s_heading] == 'import_as_meta') {
                            $s_heading = 'meta:' . $s_heading;
                        } elseif ($mapping[$s_heading] == 'import_as_images') {
                            $s_heading = 'images';
                        } else {
                            $s_heading = esc_attr($mapping[$s_heading]);
                        }
                    }
                    if ($s_heading == '') {
                        continue;
                    }
                    $row[$s_heading] = ( isset($postmeta[$key]) ) ? $this->format_wcmp_data_structure_from_csv($postmeta[$key], $enc) : '';
                    $rawheaders[$s_heading] = $heading;
                }
                $parsed_data[] = $row;
                unset($postmeta, $row);
                $position = ftell($handle);
                if ($end_pos && $position >= $end_pos) {
                    break;
                }
            }
            fclose($handle);
        }
        return array($parsed_data, $rawheaders, $position);
    }

    public function format_wcmp_data_structure_from_csv($data, $enc) {
        return ( $enc == 'UTF-8' ) ? $data : utf8_encode($data);
    }

}
