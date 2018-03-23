<?php
/**
 * Admin View: Importer - Done!
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wc-progress-form-content woocommerce-importer">
	<section class="woocommerce-importer-done">
		<?php
			$results   = array();

			if ( 0 < $imported ) {
				$results[] = sprintf(
					/* translators: %d: products count */
					_n( '%s product imported', '%s products imported', $imported, 'wcmp-frontend_product_manager' ),
					'<strong>' . number_format_i18n( $imported ) . '</strong>'
				);
			}

			if ( 0 < $updated ) {
				$results[] = sprintf(
					/* translators: %d: products count */
					_n( '%s product updated', '%s products updated', $updated, 'wcmp-frontend_product_manager' ),
					'<strong>' . number_format_i18n( $updated ) . '</strong>'
				);
			}

			if ( 0 < $skipped ) {
				$results[] = sprintf(
					/* translators: %d: products count */
					_n( '%s product was skipped', '%s products were skipped', $skipped, 'wcmp-frontend_product_manager' ),
					'<strong>' . number_format_i18n( $skipped ) . '</strong>'
				);
			}

			if ( 0 < $failed ) {
				$results [] = sprintf(
					/* translators: %d: products count */
					_n( 'Failed to import %s product', 'Failed to import %s products', $failed, 'wcmp-frontend_product_manager' ),
					'<strong>' . number_format_i18n( $failed ) . '</strong>'
				);
			}

			if ( 0 < $failed || 0 < $skipped ) {
				$results[] = '<a href="#" class="woocommerce-importer-done-view-errors">' . __( 'View import log', 'wcmp-frontend_product_manager' ) . '</a>';
			}

			/* translators: %d: import results */
			echo wp_kses_post( __( 'Import complete!', 'wcmp-frontend_product_manager' ) . ' ' . implode( '. ', $results ) );
		?>
	</section>
	<section class="wc-importer-error-log" style="display:none">
		<table class="widefat wc-importer-error-log-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Product', 'wcmp-frontend_product_manager' ); ?></th>
					<th><?php esc_html_e( 'Reason for failure', 'wcmp-frontend_product_manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if ( count( $errors ) ) {
						foreach ( $errors as $error ) {
							if ( ! is_wp_error( $error ) ) {
								continue;
							}
							$error_data = $error->get_error_data();
							?>
							<tr>
								<th><code><?php echo esc_html( $error_data['row'] ); ?></code></th>
								<td><?php echo esc_html( $error->get_error_message() ); ?></td>
							</tr>
							<?php
						}
					}
				?>
			</tbody>
		</table>
	</section>
	<script type="text/javascript">
		jQuery(function() {
			jQuery( '.woocommerce-importer-done-view-errors' ).on( 'click', function() {
				jQuery( '.wc-importer-error-log' ).slideToggle();
				return false;
			} );
		} );
	</script>
	<div class="wc-actions">
		<a class="button button-primary" href="<?php
			if (!class_exists('wcmp-frontend_product_manager')) { 
				echo esc_url( admin_url( 'edit.php?post_type=product' ) );
			} else {
			//$url=strtok($_SERVER["REQUEST_URI"],'?');
			//$wcmp_caps = get_option('wcmp_vendor_general_settings_name', array());
			//$page_id = $wcmp_caps['wcmp_pending_products'];
			//echo get_permalink().'?page_id='.$page_id;
			echo get_permalink().get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'products');
			}
		?>"><?php esc_html_e( 'View products', 'wcmp-frontend_product_manager' ); ?></a>
	</div>
</div>
