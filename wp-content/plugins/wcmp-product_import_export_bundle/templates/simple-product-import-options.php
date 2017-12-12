<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/wcmp-product-import-export-bundle/simple-product-import-options.php
 *
 * @author 		dualcube
 * @package 	wcmp-product-import-export-bundle/Templates
 * @version     0.0.1
 */
global $WCMp_Product_Import_Bundle, $WCMp_Product_Import_Export_Bundle;
$url = get_the_permalink().'?import=' . $WCMp_Product_Import_Bundle->import_page . '&step=2';
?>
<form action="<?php echo $url; ?>" method="post">
	<?php wp_nonce_field( 'import-wcmp-product' ); ?>
	<input type="hidden" name="import_id" value="<?php echo $WCMp_Product_Import_Bundle->id; ?>" />
	<?php if ( $WCMp_Product_Import_Bundle->file_url_import_enabled ) : ?>
	<input type="hidden" name="import_url" value="<?php echo $WCMp_Product_Import_Bundle->file_url; ?>" />
	<?php endif; ?>

	<h3><?php _e( 'Map Fields', 'wcmp-product-import-export-bundle' ); ?></h3>
	<p><?php _e( 'Here you can map your imported columns to product data fields.', 'wcmp-product-import-export-bundle' ); ?></p>

	<table class="wcmp wcmp_importer">
		<thead>
			<tr>
				<th><?php _e( 'Map to', 'wcmp-product-import-export-bundle' ); ?></th>
				<th><?php _e( 'Column Header', 'wcmp-product-import-export-bundle' ); ?></th>
				<th><?php _e( 'Example Column Value', 'wcmp-product-import-export-bundle' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $WCMp_Product_Import_Bundle->row as $key => $value ) : ?>
			<tr>
				<td width="25%" style="vertical-align:top;">
					<?php
						if ( strstr( $key, 'meta:' ) ) {

							$column = trim( str_replace( 'meta:', '', $key ) );
							printf(__('Custom Field: <strong>%s</strong>', 'wcmp-product-import-export-bundle'), $column);

						} elseif ( strstr( $key, 'attribute_data:' ) ) {

							$column = trim( str_replace( 'attribute_data:', '', $key ) );
							printf(__('Product Attribute Data: <strong>%s</strong>', 'wcmp-product-import-export-bundle'), sanitize_title( $column ) );

						} elseif ( strstr( $key, 'attribute:' ) ) {

							$column = trim( str_replace( 'attribute:', '', $key ) );
							printf(__('Product Attribute: <strong>%s</strong>', 'wcmp-product-import-export-bundle'), sanitize_title( $column ) );

						}  elseif ( strstr( $key, 'attribute_default:' ) ) {

							$column = trim( str_replace( 'attribute_default:', '', $key ) );
							printf(__('Product Attribute default value: <strong>%s</strong>', 'wcmp-product-import-export-bundle'), sanitize_title( $column ) );

						} elseif ( strstr( $key, 'tax:' ) ) {

							$column = trim( str_replace( 'tax:', '', $key ) );
							printf(__('Taxonomy: <strong>%s</strong>', 'wcmp-product-import-export-bundle'), $column);

						} else {
							?>
							<select name="map_to[<?php echo $key; ?>]">
								<option value=""><?php _e( 'Do not import', 'wcmp-product-import-export-bundle' ); ?></option>
								<option value="import_as_images" <?php selected( $key, 'images' ); ?>><?php _e( 'Images/Gallery', 'wcmp-product-import-export-bundle' ); ?></option>
								<option value="import_as_meta"><?php _e( 'Custom Field with column name', 'wcmp-product-import-export-bundle' ); ?></option>
								<optgroup label="<?php _e( 'Taxonomies', 'wcmp-product-import-export-bundle' ); ?>">
									<?php
										foreach ($taxonomies as $taxonomy ) {
											if ( substr( $taxonomy, 0, 3 ) == 'pa_' ) continue;
											echo '<option value="tax:' . $taxonomy . '" ' . selected( $key, 'tax:' . $taxonomy, true ) . '>' . $taxonomy . '</option>';
										}
									?>
								</optgroup>
								<optgroup label="<?php _e( 'Attributes', 'wcmp-product-import-export-bundle' ); ?>">
									<?php
										foreach ($taxonomies as $taxonomy ) {
											if ( substr( $taxonomy, 0, 3 ) == 'pa_' )
												echo '<option value="attribute:' . $taxonomy . '" ' . selected( $key, 'attribute:' . $taxonomy, true ) . '>' . $taxonomy . '</option>';
										}
									?>
								</optgroup>
								<optgroup label="<?php _e( 'Map to parent ', 'wcmp-product-import-export-bundle' ); ?>">
									<option value="post_parent" <?php selected( $key, 'post_parent' ); ?>><?php _e( 'By ID', 'wcmp-product-import-export-bundle' ); ?>: post_parent</option>
									<option value="parent_sku" <?php selected( $key, 'parent_sku' ); ?>><?php _e( 'By SKU', 'wcmp-product-import-export-bundle' ); ?>: parent_sku</option>
								</optgroup>
								<optgroup label="<?php _e( 'Post data', 'wcmp-product-import-export-bundle' ); ?>">
									<option <?php selected( $key, 'post_id' ); selected( $key, 'id' ); ?>>post_id</option>
									<option <?php selected( $key, 'post_type' ); ?>>post_type</option>
									<option <?php selected( $key, 'menu_order' ); ?>>menu_order</option>
									<option <?php selected( $key, 'post_status' ); ?>>post_status</option>
									<option <?php selected( $key, 'post_title' ); ?>>post_title</option>
									<option <?php selected( $key, 'post_name' ); ?>>post_name</option>
									<option <?php selected( $key, 'post_date' ); ?>>post_date</option>
									<option <?php selected( $key, 'post_date_gmt' ); ?>>post_date_gmt</option>
									<option <?php selected( $key, 'post_content' ); ?>>post_content</option>
									<option <?php selected( $key, 'post_excerpt' ); ?>>post_excerpt</option>
									<option <?php selected( $key, 'post_author' ); ?>>post_author</option>
									<option <?php selected( $key, 'post_password' ); ?>>post_password</option>
									<option <?php selected( $key, 'comment_status' ); ?>>comment_status</option>
								</optgroup>
								<optgroup label="<?php _e( 'Product data', 'wcmp-product-import-export-bundle' ); ?>">
									<option value="tax:product_type" <?php selected( $key, 'tax:product_type' ); ?>> product_type</option>
									<option value="downloadable" <?php selected( $key, 'downloadable' ); ?>> downloadable</option>
									<option value="virtual" <?php selected( $key, 'virtual' ); ?>> virtual</option>
									<option value="sku" <?php selected( $key, 'sku' ); ?>> sku</option>
									<option value="visibility" <?php selected( $key, 'visibility' ); ?>> visibility</option>
									<option value="featured" <?php selected( $key, 'featured' ); ?>> featured</option>
									<option value="stock" <?php selected( $key, 'stock' ); ?>> stock</option>
									<option value="stock_status" <?php selected( $key, 'stock_status' ); ?>> stock_status</option>
									<option value="backorders" <?php selected( $key, 'backorders' ); ?>> backorders</option>
									<option value="manage_stock" <?php selected( $key, 'manage_stock' ); ?>> manage_stock</option>
									<option value="regular_price" <?php selected( $key, 'regular_price' ); ?>> regular_price</option>
									<option value="sale_price" <?php selected( $key, 'sale_price' ); ?>> sale_price</option>
									<option value="sale_price_dates_from" <?php selected( $key, 'sale_price_dates_from' ); ?>> sale_price_dates_from</option>
									<option value="sale_price_dates_to" <?php selected( $key, 'sale_price_dates_to' ); ?>> sale_price_dates_to</option>
									<option value="weight" <?php selected( $key, 'weight' ); ?>> weight</option>
									<option value="length" <?php selected( $key, 'length' ); ?>> length</option>
									<option value="width" <?php selected( $key, 'width' ); ?>> width</option>
									<option value="height" <?php selected( $key, 'height' ); ?>> height</option>
									<option value="tax_status" <?php selected( $key, 'tax_status' ); ?>> tax_status</option>
									<option value="tax_class" <?php selected( $key, 'tax_class' ); ?>> tax_class</option>
									<option value="upsell_ids" <?php selected( $key, 'upsell_ids' ); ?>> upsell_ids</option>
									<option value="crosssell_ids" <?php selected( $key, 'crosssell_ids' ); ?>> crosssell_ids</option>
									<option value="upsell_skus" <?php selected( $key, 'upsell_skus' ); ?>> upsell_skus</option>
									<option value="crosssell_skus" <?php selected( $key, 'crosssell_skus' ); ?>> crosssell_skus</option>
									<option value="downloadable_files" <?php selected( $key, 'downloadable_files' ); ?>> downloadable_files </option>
									<option value="product_version" <?php selected( $key, 'product_version' ); ?>> product_version </option>
									<option value="download_type" <?php selected( $key, 'download_type' ); ?>> download_type </option>
									<option value="download_limit" <?php selected( $key, 'download_limit' ); ?>> download_limit</option>
									<option value="download_expiry" <?php selected( $key, 'download_expiry' ); ?>> download_expiry</option>
									<option value="product_url" <?php selected( $key, 'product_url' ); ?>> product_url</option>
									<option value="button_text" <?php selected( $key, 'button_text' ); ?>> button_text</option>
									<?php do_action( 'wcmp_product_data_mapping', $key ); ?>
								</optgroup>
							</select>
							<?php
						}
					?>
				</td>
				<td width="25%" style="vertical-align:top;"><?php echo $WCMp_Product_Import_Bundle->rawheaders[$key]; ?></td>
				<td style="vertical-align:top;" ><code><?php if ( $value != '' ) echo esc_html( $value ); else echo '-'; ?></code></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<p class="submit">
		<input type="submit" class="button" value="<?php esc_attr_e( 'Submit', 'wcmp-product-import-export-bundle' ); ?>" />
		<input type="hidden" name="delimiter" value="<?php echo $WCMp_Product_Import_Bundle->delimiter ?>" />
		<input type="hidden" name="merge_empty_cells" value="<?php echo $WCMp_Product_Import_Bundle->merge_empty_cells ?>" />
	</p>
</form> 