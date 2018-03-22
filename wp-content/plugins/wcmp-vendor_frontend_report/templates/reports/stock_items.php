<?php 
if(!empty($items)) { 
	foreach($items as $item) {
		$product = wc_get_product( $item['id'] );
		?>
			<tr>
				<td>
					<?php
						if ( $sku = $product->get_sku() ) {
							echo $sku . ' - ';
						}

						echo $product->get_title();

						// Get variation data
						if ( $product->is_type( 'variation' ) ) {
							$list_attributes = array();
							$attributes = $product->get_variation_attributes();
		
							foreach ( $attributes as $name => $attribute ) {
								$list_attributes[] = wc_attribute_label( str_replace( 'attribute_', '', $name ) ) . ': <strong>' . $attribute . '</strong>';
							}
		
							echo '<div class="description">' . implode( ', ', $list_attributes ) . '</div>';
						}
					?>
				</td>
				<td>
					<?php
						if ( $item['parent'] ) {
							echo get_the_title( $item['parent'] );
						} else {
							echo '-';
						}
					?>
				</td>
				<td>		
					<?php
						if ( $product->is_in_stock() ) {
							echo '<mark class="instock">' . __( 'In stock', 'wcmp-vendor_frontend_report' ) . '</mark>';
						} else {
							echo '<mark class="outofstock">' . __( 'Out of stock', 'wcmp-vendor_frontend_report' ) . '</mark>';
						}
					?>
				</td>
				<td>
					<?php
						echo $product->get_stock_quantity();
					?>
				</td>
				<td>			
					<p>
						<?php
							$actions = array();
							$action_id = $product->is_type( 'variation' ) ? $item['parent'] : $item['id'];
	
							$actions['edit'] = array(
								'url'       => admin_url( 'post.php?post=' . $action_id . '&action=edit' ),
								'name'      => '<i class="fa fa-pencil"></i>',
								'action'    => "edit"
							);
	
							if ( $product->is_visible() ) {
								$actions['view'] = array(
									'url'       => get_permalink( $action_id ),
									'name'      => '<i class="fa fa-eye"></i>',
									'action'    => "view"
								);
							}
	
							$actions = apply_filters( 'woocommerce_admin_stock_report_product_actions', $actions, $product );
	
							foreach ( $actions as $action ) {
								printf( '<a class="button tips %s" href="%s" data-tip="%s ' . __( 'product', 'wcmp-vendor_frontend_report' ) . '">%s</a>', $action['action'], esc_url( $action['url'] ), esc_attr( $action['name'] ), $action['name'] );
							}
						?>
					</p>
				</td>
			</tr>
		<?php
	}
}
?>