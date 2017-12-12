<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/wcmp-product-import-export-bundle/simple-product-file-upload-form.php
 *
 * @author 		dualcube
 * @package 	wcmp-product-import-export-bundle/Templates
 * @version     0.0.1
 */
global $WCMp_Product_Import_Bundle, $WCMp_Product_Import_Export_Bundle;
?>
<div>
	<p><?php _e( 'Choose a CSV (.csv) file to upload, then click Upload file and import.', 'wcmp-product-import-export-bundle' ); ?></p>

	<?php if ( ! empty( $WCMp_Product_Import_Bundle->upload_dir['error'] ) ) : ?>
		<div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:'); ?></p>
		<p><strong><?php echo $WCMp_Product_Import_Bundle->upload_dir['error']; ?></strong></p></div>
	<?php else : ?>
		<form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo esc_attr(wp_nonce_url($WCMp_Product_Import_Bundle->action, 'import-upload')); ?>">
			<table class="wcmp-table">
				<tbody>
					<tr>
						<th>
							<label for="upload"><?php _e( 'Choose a file from your computer:' ); ?></label>
						</th>
						<td>
							<input type="file" id="upload" name="import" size="25" />
							<input type="hidden" name="action" value="save" />
							<input type="hidden" name="max_file_size" value="<?php echo $WCMp_Product_Import_Bundle->bytes; ?>" /><br/>
							<small><?php printf( __('%s Max' ), $WCMp_Product_Import_Bundle->size ); ?></small>
						</td>
					</tr>
					<?php if ( $WCMp_Product_Import_Bundle->file_url_import_enabled ) : ?>
					<tr>
						<th>
							<label for="file_url"><?php _e( 'OR enter path to file:', 'wcmp-product-import-export-bundle' ); ?></label>
						</th>
						<td>
							<?php echo ' ' . ABSPATH . ' '; ?><input type="text" id="file_url" name="file_url" />
						</td>
					</tr>
					<?php endif; ?>
			
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" class="button" value="<?php esc_attr_e( 'Upload file and import' ); ?>" />
			</p>
		</form>
	<?php endif; ?>
</div>