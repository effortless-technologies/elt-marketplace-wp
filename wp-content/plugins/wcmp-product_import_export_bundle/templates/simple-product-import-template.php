<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/wcmp-product-import-export-bundle/simple-product-import-template.php
 *
 * @author 		dualcube
 * @package 	wcmp-product-import-export-bundle/Templates
 * @version     0.0.1
 */
global $WCMp_Product_Import_Export_Bundle;
	if(is_admin()) {
		$actionurl = admin_url('admin.php?page=wcmp-product-import-export-bundle&tab=import&import=wcmp_import_export_csv');
	}
	else {
	$actionurl = get_the_permalink().'?import=wcmp_import_export_csv';
	}

?>
<?php if(!is_admin()){      echo '<div class="panel entry-content wc-tab">';
 } ?>
<div class="tool-box">
	<h3 class="title"><?php _e('Import Product CSV', 'wcmp-product-import-export-bundle'); ?></h3>
	<p><?php _e('Import simple, grouped, external and variable products into your store using this wizard.', 'wcmp-product-import-export-bundle'); ?></p>
	<p class="submit"><a class="button" href="<?php echo $actionurl; ?>"><?php _e('Import Simple Products', 'wcmp-product-import-export-bundle'); ?></a>
	</p>
</div>
<?php if(!is_admin()){      echo '</div>';
 } ?>