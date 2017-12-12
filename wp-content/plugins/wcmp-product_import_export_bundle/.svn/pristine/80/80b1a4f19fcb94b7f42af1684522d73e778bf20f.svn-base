<?php

class WCMp_Product_Import_Export_Bundle_Export {

    public static function format_wcmp_data_structure($data) {
        $data = (string) urldecode($data);
        $enc = mb_detect_encoding($data, 'UTF-8, ISO-8859-1', true);
        $data = ( $enc == 'UTF-8' ) ? $data : utf8_encode($data);
        return $data;
    }

    public static function format_wcmp_meta($meta_value, $meta) {
        switch ($meta) {
            case '_sale_price_dates_from' :
            case '_sale_price_dates_to' :
                return $meta_value ? date('Y-m-d', $meta_value) : '';
                break;
            case '_upsell_ids' :
            case '_crosssell_ids' :
                return implode('|', array_filter((array) json_decode($meta_value)));
                break;
            default :
                return $meta_value;
                break;
        }
    }

    public static function simple_export($post_type = 'product') {
        global $WCMp_Product_Import_Export_Bundle, $wpdb;
        $export_limit = !empty($_POST['limit']) ? intval($_POST['limit']) : 999999999;
        $export_count = 0;
        $limit = 100;
        $current_offset = !empty($_POST['offset']) ? intval($_POST['offset']) : 0;
        $csv_columns = $post_type == 'product' ? $WCMp_Product_Import_Export_Bundle->wcmp_export_meta_fields->data_post_columns() : $WCMp_Product_Import_Export_Bundle->wcmp_export_meta_fields->data_variation_columns();
        $product_taxonomies = get_object_taxonomies($post_type, 'name');
        $include_hidden_meta = !empty($_POST['include_hidden_meta']) ? true : false;
        $product_limit = !empty($_POST['product_limit']) ? sanitize_text_field($_POST['product_limit']) : '';
        $exclude_hidden_meta_columns = $WCMp_Product_Import_Export_Bundle->wcmp_export_meta_fields->hidden_columns();
        if ($limit > $export_limit) {
            $limit = $export_limit;
        }
        $wpdb->hide_errors();
        @set_time_limit(0);
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', 1);
        }
        @ini_set('zlib.output_compression', 0);
        @ob_clean();
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=WCMp-Simple-Product-Export-' . date('d-m-Y_H-i-s') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');
        $fp = fopen('php://output', 'w');
        $all_meta_keys = self::get_wcmp_all_product_metakeys($post_type);
        $found_attributes = self::get_wcmp_all_product_attributes($post_type);
        $found_product_meta = array();
        foreach ($all_meta_keys as $meta) {
            if (!$meta) {
                continue;
            }
            if (!$include_hidden_meta && !in_array($meta, array_keys($csv_columns)) && substr($meta, 0, 1) == '_') {
                continue;
            }
            if ($include_hidden_meta && ( in_array($meta, $exclude_hidden_meta_columns) || in_array($meta, array_keys($csv_columns)) )) {
                continue;
            }
            $found_product_meta[] = $meta;
        }
        $found_product_meta = array_diff($found_product_meta, array_keys($csv_columns));
        $row = array();
        foreach ($csv_columns as $column => $value) {
            $row[] = esc_attr($value);
        }
        $row[] = 'images';
        foreach ($product_taxonomies as $taxonomy) {
            if (strstr($taxonomy->name, 'pa_'))
                continue;
            $row[] = 'tax:' . self::format_wcmp_data_structure($taxonomy->name);
        }
        foreach ($found_product_meta as $product_meta) {
            $row[] = 'meta:' . self::format_wcmp_data_structure($product_meta);
        }
        foreach ($found_attributes as $attribute) {
            $row[] = 'attribute:' . self::format_wcmp_data_structure($attribute);
            $row[] = 'attribute_data:' . self::format_wcmp_data_structure($attribute);
            $row[] = 'attribute_default:' . self::format_wcmp_data_structure($attribute);
        }
        $row = array_map('WCMp_Product_Import_Export_Bundle_Export::wrap_wcmp_data_column', $row);
        fwrite($fp, implode(',', $row) . "\n");
        unset($row);
        $current_user = wp_get_current_user();
        while ($export_count < $export_limit) {
            $product_args = apply_filters('wcmp_product_export_args', array(
                'numberposts' => $limit,
                'post_status' => array('publish', 'pending', 'private', 'draft'),
                'post_type' => 'product',
                'orderby' => 'ID',
                'order' => 'ASC',
                'offset' => $current_offset,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_type',
                        'field' => 'slug',
                        'terms' => 'simple'
                    )
                )
                    ));
            $product_args['author'] = $current_user->ID;
            if (WC_Import_Export_Dependencies::wc_marketplace_plugin_active_check()) {
                if (is_user_wcmp_vendor($current_user)) {
                    unset($product_args['author']);
                    $term_id = get_user_meta($current_user->ID, '_vendor_term_id', true);
                    $taxquery = array(
                        array(
                            'taxonomy' => 'dc_vendor_shop',
                            'field' => 'id',
                            'terms' => array($term_id),
                            'operator' => 'IN'
                        ),
                        array(
                            'taxonomy' => 'product_type',
                            'field' => 'slug',
                            'terms' => 'simple'
                        )
                    );
                    $product_args['tax_query'] = $taxquery;
                }
            }
            $products = get_posts($product_args);
            foreach ($products as $product) {
                $parents[] = $product->ID;
            }
            if (!$products || is_wp_error($products)) {
                break;
            }
            foreach ($products as $product) {
                $row = array();
                $meta_data = get_post_custom($product->ID);
                $product->meta = new stdClass;
                $product->attributes = new stdClass;
                foreach ($meta_data as $meta => $value) {
                    if (!$meta) {
                        continue;
                    }
                    if (!$include_hidden_meta && !in_array($meta, array_keys($csv_columns)) && substr($meta, 0, 1) == '_') {
                        continue;
                    }
                    if ($include_hidden_meta && in_array($meta, $exclude_hidden_meta_columns)) {
                        continue;
                    }
                    $meta_value = maybe_unserialize(maybe_unserialize($value[0]));
                    if (is_array($meta_value)) {
                        $meta_value = json_encode($meta_value);
                    }
                    $product->meta->$meta = self::format_wcmp_meta($meta_value, $meta);
                }
                if (isset($meta_data['_product_attributes'][0])) {
                    $attributes = maybe_unserialize(maybe_unserialize($meta_data['_product_attributes'][0]));
                    if (!empty($attributes) && is_array($attributes))
                        foreach ($attributes as $key => $attribute) {
                            if (!$key || !isset($attribute['position']) || !isset($attribute['is_visible']) || !isset($attribute['is_variation'])) {
                                continue;
                            }
                            if ($attribute['is_taxonomy'] == 1) {
                                $terms = wp_get_post_terms($product->ID, $key, array("fields" => "names"));
                                if (!is_wp_error($terms)) {
                                    $attribute_value = implode('|', $terms);
                                } else {
                                    $attribute_value = '';
                                }
                            } else {
                                $key = $attribute['name'];
                                $attribute_value = $attribute['value'];
                            }
                            $attribute_data = $attribute['position'] . '|' . $attribute['is_visible'] . '|' . $attribute['is_variation'];
                            $_default_attributes = isset($meta_data['_default_attributes'][0]) ? maybe_unserialize(maybe_unserialize($meta_data['_default_attributes'][0])) : '';
                            if (is_array($_default_attributes)) {
                                $_default_attribute = isset($_default_attributes[$key]) ? $_default_attributes[$key] : '';
                            } else {
                                $_default_attribute = '';
                            }
                            $product->attributes->$key = array(
                                'value' => $attribute_value,
                                'data' => $attribute_data,
                                'default' => $_default_attribute
                            );
                        }
                }
                foreach ($csv_columns as $column => $value) {
                    if ($post_type == 'product_variation' && $column == '_regular_price' && empty($product->meta->$column)) {
                        $column = '_price';
                    }
                    if (isset($product->meta->$column)) {
                        $row[] = self::format_wcmp_data_structure($product->meta->$column);
                    } elseif (isset($product->$column) && !is_array($product->$column)) {
                        if ($column === 'post_title') {
                            $row[] = sanitize_text_field($product->$column);
                        } else {
                            $row[] = self::format_wcmp_data_structure($product->$column);
                        }
                    } else {
                        $row[] = '';
                    }
                }
                $image_file_names = array();
                if (( $featured_image_id = get_post_thumbnail_id($product->ID) ) && ( $image = wp_get_attachment_image_src($featured_image_id, 'full') )) {
                    $image_file_names[] = current($image);
                }
                if (($post_type != 'product_variation')) {
                    $_product_image_gallery = get_post_meta($product->ID, '_product_image_gallery', true);
                    $images4[] = $featured_image_id;
                    if (isset($_product_image_gallery) && (!empty($_product_image_gallery))) {
                        $images2 = explode(',', $_product_image_gallery);
                        $images3 = array_merge($images4, $images2);
                    } else {
                        $images3 = $images4;
                    }
                    $results = array();
                    if ($images3) {
                        foreach ($images3 as $key => $image) {
                            if ($featured_image_id == $image)
                                continue;
                            $image_file_names[] = current(wp_get_attachment_image_src($image, 'full'));
                        }
                    }
                }
                $row[] = implode('|', $image_file_names);
                foreach ($product_taxonomies as $taxonomy) {
                    if (strstr($taxonomy->name, 'pa_'))
                        continue; // Skip attributes
                    if (is_taxonomy_hierarchical($taxonomy->name)) {
                        $terms = wp_get_post_terms($product->ID, $taxonomy->name, array("fields" => "all"));
                        $formatted_terms = array();

                        foreach ($terms as $term) {
                            $ancestors = array_reverse(get_ancestors($term->term_id, $taxonomy->name));
                            $formatted_term = array();
                            foreach ($ancestors as $ancestor) {
                                $formatted_term[] = get_term($ancestor, $taxonomy->name)->name;
                            }
                            $formatted_term[] = $term->name;
                            $formatted_terms[] = implode(' > ', $formatted_term);
                        }
                        $row[] = self::format_wcmp_data_structure(implode('|', $formatted_terms));
                    } else {
                        $terms = wp_get_post_terms($product->ID, $taxonomy->name, array("fields" => "names"));
                        $row[] = self::format_wcmp_data_structure(implode('|', $terms));
                    }
                }
                foreach ($found_product_meta as $product_meta) {
                    if (isset($product->meta->$product_meta)) {
                        $row[] = self::format_wcmp_data_structure($product->meta->$product_meta);
                    } else {
                        $row[] = '';
                    }
                }
                foreach ($found_attributes as $attribute) {
                    if (isset($product->attributes) && isset($product->attributes->$attribute)) {
                        $values = $product->attributes->$attribute;
                        $row[] = self::format_wcmp_data_structure($values['value']);
                        $row[] = self::format_wcmp_data_structure($values['data']);
                        $row[] = self::format_wcmp_data_structure($values['default']);
                    } else {
                        $row[] = '';
                        $row[] = '';
                        $row[] = '';
                    }
                }
                $row = array_map('WCMp_Product_Import_Export_Bundle_Export::wrap_wcmp_data_column', $row);
                fwrite($fp, implode(',', $row) . "\n");
                unset($row);
            }
            $current_offset += $limit;
            $export_count += $limit;
            unset($products);
        }
        fclose($fp);
        exit;
    }

    public static function export_variable($post_type = 'product') {
        global $WCMp_Product_Import_Export_Bundle, $wpdb;
        $export_limit = !empty($_POST['limit']) ? intval($_POST['limit']) : 999999999;
        $export_count = 0;
        $limit = 100;
        $current_offset = !empty($_POST['offset']) ? intval($_POST['offset']) : 0;
        $csv_columns = $WCMp_Product_Import_Export_Bundle->wcmp_export_meta_fields->data_post_columns();
        $product_taxonomies = get_object_taxonomies($post_type, 'name');
        $include_hidden_meta = !empty($_POST['include_hidden_meta']) ? true : false;
        $product_limit = !empty($_POST['product_limit']) ? sanitize_text_field($_POST['product_limit']) : '';
        $exclude_hidden_meta_columns = $WCMp_Product_Import_Export_Bundle->wcmp_export_meta_fields->hidden_columns();
        if ($limit > $export_limit)
            $limit = $export_limit;
        $wpdb->hide_errors();
        @set_time_limit(0);
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', 1);
        }
        @ini_set('zlib.output_compression', 0);
        @ob_clean();
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=WCMp-Variable-Products-export' . date('d-m-Y_H-i-s') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');
        $fp = fopen('php://output', 'w');
        $all_meta_keys = self::get_wcmp_all_product_metakeys('product');
        $found_attributes = self::get_wcmp_all_product_attributes('product');
        $found_product_meta = array();
        foreach ($all_meta_keys as $meta) {
            if (!$meta) {
                continue;
            }
            if (!$include_hidden_meta && !in_array($meta, array_keys($csv_columns)) && substr($meta, 0, 1) == '_') {
                continue;
            }
            if ($include_hidden_meta && ( in_array($meta, $exclude_hidden_meta_columns) || in_array($meta, array_keys($csv_columns)) )) {
                continue;
            }
            $found_product_meta[] = $meta;
        }
        $found_product_meta = array_diff($found_product_meta, array_keys($csv_columns));
        $row = array();
        $row[] = 'Parent';
        $row[] = 'parent_sku';
        foreach ($csv_columns as $column => $value) {
            $row[] = esc_attr($value);
        }
        $row[] = 'images';
        foreach ($product_taxonomies as $taxonomy) {
            if (strstr($taxonomy->name, 'pa_'))
                continue;
            $row[] = 'tax:' . self::format_wcmp_data_structure($taxonomy->name);
        }
        foreach ($found_product_meta as $product_meta) {
            $row[] = 'meta:' . self::format_wcmp_data_structure($product_meta);
        }
        foreach ($found_attributes as $attribute) {
            $row[] = 'attribute:' . self::format_wcmp_data_structure($attribute);
            $row[] = 'attribute_data:' . self::format_wcmp_data_structure($attribute);
            $row[] = 'attribute_default:' . self::format_wcmp_data_structure($attribute);
        }
        $row = array_map('WCMp_Product_Import_Export_Bundle_Export::wrap_wcmp_data_column', $row);
        fwrite($fp, implode(',', $row) . "\n");
        $myrow = $row;
        unset($row);
        
        
        
        $current_user = wp_get_current_user();
        while ($export_count < $export_limit) {
            $product_args = apply_filters('wcmp_product_export_args', array(
                'numberposts' => $limit,
                'post_status' => array('publish', 'pending', 'private', 'draft'),
                'post_type' => 'product',
                'orderby' => 'ID',
                'order' => 'ASC',
                'offset' => $current_offset,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_type',
                        'field' => 'slug',
                        'terms' => 'variable'
                    )
                )
                    ));
            $product_args['author'] = $current_user->ID;
            if (WC_Import_Export_Dependencies::wc_marketplace_plugin_active_check()) {
                if (is_user_wcmp_vendor($current_user)) {
                    unset($product_args['author']);
                    $term_id = get_user_meta($current_user->ID, '_vendor_term_id', true);
                    $taxquery = array(
                        array(
                            'taxonomy' => 'dc_vendor_shop',
                            'field' => 'id',
                            'terms' => array($term_id),
                            'operator' => 'IN'
                        ),
                        array(
                            'taxonomy' => 'product_type',
                            'field' => 'slug',
                            'terms' => 'variable'
                        )
                    );
                    $product_args['tax_query'] = $taxquery;
                }
            }
            $products = get_posts($product_args);
            foreach ($products as $product) {
                $parents[] = $product->ID;
            }
            if (!$products || is_wp_error($products))
                break;
            
            foreach ($products as $product) {
                $row = array();
                $meta_data = get_post_custom($product->ID);
                $product->meta = new stdClass;
                $product->attributes = new stdClass;
                foreach ($meta_data as $meta => $value) {
                    if (!$meta) {
                        continue;
                    }
                    if (!$include_hidden_meta && !in_array($meta, array_keys($csv_columns)) && substr($meta, 0, 1) == '_') {
                        continue;
                    }
                    if ($include_hidden_meta && in_array($meta, $exclude_hidden_meta_columns)) {
                        continue;
                    }
                    $meta_value = maybe_unserialize(maybe_unserialize($value[0]));
                    if (is_array($meta_value)) {
                        $meta_value = json_encode($meta_value);
                    }
                    $product->meta->$meta = self::format_wcmp_meta($meta_value, $meta);
                }
                if (isset($meta_data['_product_attributes'][0])) {
                    $attributes = maybe_unserialize(maybe_unserialize($meta_data['_product_attributes'][0]));
                    if (!empty($attributes) && is_array($attributes))
                        foreach ($attributes as $key => $attribute) {
                            if (!$key || !isset($attribute['position']) || !isset($attribute['is_visible']) || !isset($attribute['is_variation']))
                                continue;
                            if ($attribute['is_taxonomy'] == 1) {
                                $terms = wp_get_post_terms($product->ID, $key, array("fields" => "names"));
                                if (!is_wp_error($terms)) {
                                    $attribute_value = implode('|', $terms);
                                } else {
                                    $attribute_value = '';
                                }
                            } else {
                                $key = strtolower($attribute['name']);
                                $attribute_value = $attribute['value'];
                            }
                            $attribute_data = $attribute['position'] . '|' . $attribute['is_visible'] . '|' . $attribute['is_variation'];
                            $_default_attributes = isset($meta_data['_default_attributes'][0]) ? maybe_unserialize(maybe_unserialize($meta_data['_default_attributes'][0])) : '';
                            if (is_array($_default_attributes)) {
                                $_default_attribute = isset($_default_attributes[$key]) ? $_default_attributes[$key] : '';
                            } else {
                                $_default_attribute = '';
                            }
                            $product->attributes->$key = array(
                                'value' => $attribute_value,
                                'data' => $attribute_data,
                                'default' => $_default_attribute
                            );
                        }
                }
                if ($product->post_type == 'product_variation') {
                    $post_parent_title = get_the_title($product->post_parent);
                    if (!$post_parent_title)
                        continue;
                    $row[] = self::format_wcmp_data_structure($post_parent_title);
                    $parent_sku = get_post_meta($product->post_parent, '_sku', true);
                    $row[] = $parent_sku;
                }
                else {
                    $row[] = '';
                    $row[] = '';
                }
                foreach ($csv_columns as $column => $value) {
                    if ($post_type == 'product_variation' && $column == '_regular_price' && empty($product->meta->$column)) {
                        $column = '_price';
                    }
                    if (isset($product->meta->$column)) {
                        $row[] = self::format_wcmp_data_structure($product->meta->$column);
                    } elseif (isset($product->$column) && !is_array($product->$column)) {
                        if ($column === 'post_title') {
                            $row[] = sanitize_text_field($product->$column);
                        } else {
                            $row[] = self::format_wcmp_data_structure($product->$column);
                        }
                    } else {
                        $row[] = '';
                    }
                }
                $image_file_names = array();
                if (( $featured_image_id = get_post_thumbnail_id($product->ID) ) && ( $image = wp_get_attachment_image_src($featured_image_id, 'full') )) {
                    $image_file_names[] = current($image);
                }
                if (($post_type != 'product_variation')) {
                    $_product_image_gallery = get_post_meta($product->ID, '_product_image_gallery', true);
                    $images4[] = $featured_image_id;
                    if (isset($_product_image_gallery) && (!empty($_product_image_gallery))) {
                        $images2 = explode(',', $_product_image_gallery);
                        $images3 = array_merge($images4, $images2);
                    } else {
                        $images3 = $images4;
                    }
                    $results = array();
                    if ($images3) {
                        foreach ($images3 as $key => $image) {
                            if ($featured_image_id == $image)
                                continue;
                            $image_file_names[] = current(wp_get_attachment_image_src($image, 'full'));
                        }
                    }
                }
                $row[] = implode('|', $image_file_names);
                foreach ($product_taxonomies as $taxonomy) {
                    if (strstr($taxonomy->name, 'pa_'))
                        continue; // Skip attributes
                    if (is_taxonomy_hierarchical($taxonomy->name)) {
                        $terms = wp_get_post_terms($product->ID, $taxonomy->name, array("fields" => "all"));
                        $formatted_terms = array();

                        foreach ($terms as $term) {
                            $ancestors = array_reverse(get_ancestors($term->term_id, $taxonomy->name));
                            $formatted_term = array();
                            foreach ($ancestors as $ancestor) {
                                $formatted_term[] = get_term($ancestor, $taxonomy->name)->name;
                            }
                            $formatted_term[] = $term->name;
                            $formatted_terms[] = implode(' > ', $formatted_term);
                        }
                        $row[] = self::format_wcmp_data_structure(implode('|', $formatted_terms));
                    } else {
                        $terms = wp_get_post_terms($product->ID, $taxonomy->name, array("fields" => "names"));
                        $row[] = self::format_wcmp_data_structure(implode('|', $terms));
                    }
                }
                foreach ($found_product_meta as $product_meta) {
                    if (isset($product->meta->$product_meta)) {
                        $row[] = self::format_wcmp_data_structure($product->meta->$product_meta);
                    } else {
                        $row[] = '';
                    }
                }
                foreach ($found_attributes as $attribute) {
                    $attribute2 = strtolower($attribute);
                    if (isset($product->attributes) && isset($product->attributes->$attribute)) {
                        $values = $product->attributes->$attribute2;
                        $row[] = self::format_wcmp_data_structure($values['value']);
                        $row[] = self::format_wcmp_data_structure($values['data']);
                        $row[] = self::format_wcmp_data_structure($values['default']);
                    } else {
                        $row[] = '';
                        $row[] = '';
                        $row[] = '';
                    }
                }
                $row = array_map('WCMp_Product_Import_Export_Bundle_Export::wrap_wcmp_data_column', $row);
                $product_id = $product->ID;
                fwrite($fp, implode(',', $row) . "\n");
                unset($row);
                /** product-variation code start */
                $child_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_parent IN (" . $product_id . ");");
                $product_args = array(
                    'numberposts' => $limit,
                    'post_status' => array('publish', 'pending', 'private', 'draft'),
                    'post_type' => 'product_variation',
                    'orderby' => 'ID',
                    'order' => 'ASC',
                    'offset' => 0
                        );
                $product_args['post__in'] = $child_ids;
                $products_childs = get_posts($product_args);
                foreach ($products_childs as $child_product) {
                    $meta_data_childs = get_post_custom($child_product->ID);
                    $child_product->meta = new stdClass;
                    $child_product->attributes = new stdClass;
                    foreach ($meta_data_childs as $meta => $value) {
                        if (!$meta) {
                            continue;
                        }
                        if (!$include_hidden_meta && !in_array($meta, array_keys($csv_columns)) && substr($meta, 0, 1) == '_') {
                            continue;
                        }
                        if ($include_hidden_meta && in_array($meta, $exclude_hidden_meta_columns)) {
                            continue;
                        }
                        $meta_value = maybe_unserialize(maybe_unserialize($value[0]));
                        if (is_array($meta_value)) {
                            $meta_value = json_encode($meta_value);
                        }
                        $child_product->meta->$meta = self::format_wcmp_meta($meta_value, $meta);
                    }
                    if (isset($meta_data_childs['_product_attributes'][0])) {
                        $attributes = maybe_unserialize(maybe_unserialize($meta_data_childs['_product_attributes'][0]));
                        if (!empty($attributes) && is_array($attributes))
                            foreach ($attributes as $key => $attribute) {
                                if (!$key || !isset($attribute['position']) || !isset($attribute['is_visible']) || !isset($attribute['is_variation']))
                                    continue;
                                if ($attribute['is_taxonomy'] == 1) {
                                    $terms = wp_get_post_terms($child_product->ID, $key, array("fields" => "names"));
                                    if (!is_wp_error($terms)) {
                                        $attribute_value = implode('|', $terms);
                                    } else {
                                        $attribute_value = '';
                                    }
                                } else {
                                    $key = $attribute['name'];
                                    $attribute_value = $attribute['value'];
                                }
                                $attribute_data = $attribute['position'] . '|' . $attribute['is_visible'] . '|' . $attribute['is_variation'];
                                $_default_attributes = isset($meta_data_childs['_default_attributes'][0]) ? maybe_unserialize(maybe_unserialize($meta_data_childs['_default_attributes'][0])) : '';
                                if (is_array($_default_attributes)) {
                                    $_default_attribute = isset($_default_attributes[$key]) ? $_default_attributes[$key] : '';
                                } else {
                                    $_default_attribute = '';
                                }
                                $child_product->attributes->$key = array(
                                    'value' => $attribute_value,
                                    'data' => $attribute_data,
                                    'default' => $_default_attribute
                                );
                            }
                    }
                    if ($child_product->post_type == 'product_variation') {
                        $post_parent_title = get_the_title($child_product->post_parent);
                        if (!$post_parent_title)
                            continue;
                        $row_child[] = self::format_wcmp_data_structure($post_parent_title);
                        $parent_sku = get_post_meta($child_product->post_parent, '_sku', true);
                        $row_child[] = $parent_sku;
                    }
                    else {
                        $row_child[] = '';
                        $row_child[] = '';
                    }
                    foreach ($csv_columns as $column => $value) {
                        if ($child_product->post_type == 'product_variation' && $column == '_regular_price' && empty($child_product->meta->$column)) {
                            $column = '_price';
                        }
                        if ($column == 'post_excerpt' && $child_product->post_type == 'product_variation') {
                            $column = '_variation_description';
                        }

                        if (isset($child_product->meta->$column)) {
                            $row_child[] = self::format_wcmp_data_structure($child_product->meta->$column);
                        } elseif (isset($child_product->$column) && !is_array($child_product->$column)) {
                            if ($column === 'post_title') {
                                $row_child[] = sanitize_text_field($child_product->$column);
                            } else {
                                $row_child[] = self::format_wcmp_data_structure($child_product->$column);
                            }
                        } else {
                            $row_child[] = '';
                        }
                    }
                    $image_file_names = array();
                    if (( $featured_image_id = get_post_thumbnail_id($child_product->ID) ) && ( $image = wp_get_attachment_image_src($featured_image_id, 'full') )) {
                        $image_file_names[] = current($image);
                    }
                    $row_child[] = implode('|', $image_file_names);
                    foreach ($product_taxonomies as $taxonomy) {
                        if (strstr($taxonomy->name, 'pa_'))
                            continue; // Skip attributes
                        if (is_taxonomy_hierarchical($taxonomy->name)) {
                            $terms = wp_get_post_terms($child_product->ID, $taxonomy->name, array("fields" => "all"));
                            $formatted_terms = array();

                            foreach ($terms as $term) {
                                $ancestors = array_reverse(get_ancestors($term->term_id, $taxonomy->name));
                                $formatted_term = array();
                                foreach ($ancestors as $ancestor) {
                                    $formatted_term[] = get_term($ancestor, $taxonomy->name)->name;
                                }
                                $formatted_term[] = $term->name;
                                $formatted_terms[] = implode(' > ', $formatted_term);
                            }
                            $row_child[] = self::format_wcmp_data_structure(implode('|', $formatted_terms));
                        } else {
                            $terms = wp_get_post_terms($child_product->ID, $taxonomy->name, array("fields" => "names"));
                            $row_child[] = self::format_wcmp_data_structure(implode('|', $terms));
                        }
                    }
                    foreach ($found_product_meta as $product_meta) {
                        if ($product_meta == '_commission_per_product') {
                            $product_meta = '_product_vendors_commission';
                        }
                        if (isset($child_product->meta->$product_meta)) {
                            $row_child[] = self::format_wcmp_data_structure($child_product->meta->$product_meta);
                        } else {
                            $row_child[] = '';
                        }
                    }
                    foreach ($found_attributes as $attribute) {
                        if (isset($child_product->meta)) {
                            $meta_data_key = 'attribute_' . strtolower($attribute);
                            if (isset($child_product->meta->$meta_data_key)) {
                                $values = $child_product->meta->$meta_data_key;
                                $row_child[] = $values;
                                $row_child[] = '';
                                $row_child[] = '';
                            } else {
                                $row_child[] = '';
                                $row_child[] = '';
                                $row_child[] = '';
                            }
                        } else {
                            $row_child[] = '';
                            $row_child[] = '';
                            $row_child[] = '';
                        }
                    }
                    $row_child = array_map('WCMp_Product_Import_Export_Bundle_Export::wrap_wcmp_data_column', $row_child);

                    fwrite($fp, implode(',', $row_child) . "\n");
                    unset($row_child);
                    unset($child_product);
                }
                /** product-variation code end */
            }
            $current_offset += $limit;
            $export_count += $limit;
            unset($products);
        }
        fclose($fp);
        exit;
    }

    public static function get_wcmp_all_product_attributes($post_type = 'product') {
        global $wpdb;
        $results = $wpdb->get_col($wpdb->prepare(
                        "SELECT DISTINCT pm.meta_value
			FROM {$wpdb->postmeta} AS pm
			LEFT JOIN {$wpdb->posts} AS p ON p.ID = pm.post_id
			WHERE p.post_type = %s
			AND p.post_status IN ( 'publish', 'pending', 'private', 'draft' )
			AND pm.meta_key = '_product_attributes'", $post_type
                ));
        $result = array();
        if (!empty($results)) {
            foreach ($results as $_product_attributes) {
                $attributes = maybe_unserialize(maybe_unserialize($_product_attributes));
                if (!empty($attributes) && is_array($attributes)) {
                    foreach ($attributes as $key => $attribute) {
                        if (!$key || !isset($attribute['position']) || !isset($attribute['is_visible']) || !isset($attribute['is_variation']))
                            continue;
                        if (!strstr($key, 'pa_'))
                            $key = strtolower($attribute['name']);
                        $result[$key] = $key;
                    }
                }
            }
        }
        sort($result);
        return $result;
    }

    public static function get_wcmp_all_product_metakeys($post_type = 'product') {
        global $wpdb;
        $meta = $wpdb->get_col($wpdb->prepare(
                        "SELECT DISTINCT pm.meta_key
				FROM {$wpdb->postmeta} AS pm
				LEFT JOIN {$wpdb->posts} AS p ON p.ID = pm.post_id
				WHERE p.post_type = %s
				AND p.post_status IN ( 'publish', 'pending', 'private', 'draft' )", $post_type
                ));
        sort($meta);
        return $meta;
    }

    public static function wrap_wcmp_data_column($data) {
        return '"' . str_replace('"', '""', $data) . '"';
    }

}
