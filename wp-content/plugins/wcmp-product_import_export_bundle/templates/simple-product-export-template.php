<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/wcmp-product-import-export-bundle/simple-product-export-template.php
 *
 * @author 		dualcube
 * @package 	wcmp-product-import-export-bundle/Templates
 * @version     0.0.1
 */
?>
<div class="tool-box">
<?php
global $post_columns, $WCMp_Product_Import_Export_Bundle; 
if(!is_admin()) {
	$url = get_the_permalink().'?page=wcmp-product-import-export-bundle&action=export';
}
else {
	$url = admin_url('admin.php?page=wcmp-product-import-export-bundle&action=export');
}
if(!is_admin()){      echo '<div class="panel entry-content wc-tab">';  } ?>
	<h3 class="title"><?php _e('Export Product CSV', 'wcmp-product-import-export-bundle'); ?></h3>
	<p><?php _e('Export your products here. ', 'wcmp-product-import-export-bundle'); ?></p>
	<p class="description"></p>
	<form action="<?php echo $url ?>" method="post">
		<div style="display:none">
			<input type="hidden" name="limit" id="v_limit" placeholder="<?php _e('All Product', 'wcmp-product-import-export-bundle'); ?>" class="input-text" />
			<input type="checkbox" name="include_hidden_meta" id="v_include_hidden_meta" class="checkbox" checked />
		</div>		
		<p class="submit"><input type="submit" class="button" value="<?php _e('Export Simple Products', 'wcmp-product-import-export-bundle'); ?>" /></p>	
	</form>
</div> 

<?php if(!is_admin()){      echo '</div>';
 } ?>