<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/wcmp-product-import-export-bundle/variation-product-import-template.php
 *
 * @author 		dualcube
 * @package 	wcmp-product-import-export-bundle/Templates
 * @version     0.0.1
 */ 
global $WCMp_Product_Import_Export_Bundle;
 $actionurl = get_the_permalink().'?import=wcmp_import_export_variation_csv';
if(!is_admin()){ 
	echo '<div class="panel entry-content wc-tab">'; 
} ?>
<div class="tool-box">
	<h3 class="title"><?php _e('Import Product CSV', 'wcmp-product-import-export-bundle'); ?></h3>
	<p><?php _e('Import and add simple and variable products with variations using this wizards.', 'wcmp-product-import-export-bundle'); ?></p>
	<h3 class="tips"><?php _e('Tips', 'wcmp-product-import-export-bundle'); ?> </h3>
	<h5 class="tips"><?php _e('(SKU is  manadatory and SKU Parent is Manadatory for variation)', 'wcmp-product-import-export-bundle'); ?> </h5>
	<p><?php _e('For Import product just create a product in case of simple product and export the csv using simple product export wizard and add the multiple row of products as per your requirement
		and in case of varible product just add a varibale product and add variations of that varibale product images etc and just export a csv using the varible export wizard now add varible product
		as well as varitions mapping with product sku and parent sku and upload that csv in product import wizard and follow the steps instruction.','wcmp-product-import-export-bundle');?></p>
	<p class="submit"><a class="button" href="<?php echo $actionurl; ?>"><?php _e('Import Products', 'wcmp-product-import-export-bundle'); ?></a></p>    
</div>
<?php 
if(!is_admin()){ 
	echo '</div>';  
} ?>