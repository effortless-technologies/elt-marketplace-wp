<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/wcmp-product-import-export-bundle/variation-product-export-template.php
 *
 * @author 		dualcube
 * @package 	wcmp-product-import-export-bundle/Templates
 * @version     0.0.1
 */
global $variation_columns, $WCMp_Product_Import_Export_Bundle;
if(!is_admin())
	$url = get_the_permalink().'?page=wcmp-product-import-export-bundle&action=export_variations';
else
	$url = admin_url('admin.php?page=wcmp-product-import-export-bundle&action=export_variations');
?>

<?php if(!is_admin()){      echo '<div class="panel entry-content wc-tab">';
 } ?>
<div class="tool-box">
	<h3 class="title"><?php _e('Export Variable Products CSV', 'wcmp-product-import-export-bundle'); ?></h3>
	<p><?php _e('Export your variable products here.', 'wcmp-product-import-export-bundle'); ?></p>
	<p class="description"></p>
	<form action="<?php echo $url; ?>" method="post">			
		<div style="display:none">
		<input type="hidden" name="limit" id="limit" placeholder="<?php _e('ALL Variable Products', 'wcmp-product-import-export-bundle'); ?>" class="input-text" />	
		<input type="checkbox" name="include_hidden_meta" id="v_include_hidden_meta" class="checkbox" checked />
		</div>
		<p class="submit"><input type="submit" class="button" value="<?php _e('Export Variable Products', 'wcmp-product-import-export-bundle'); ?>" /></p>
	</form>
</div>
<?php if(!is_admin()){      echo '</div>';
 } ?>