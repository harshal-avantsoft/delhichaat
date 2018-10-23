<?php
global $product;
$woosb_count = 0;
$product_id  = $product->get_id();
if ( $woosb_items = $product->get_items() ) {
	echo '<div id="woosb_wrap" class="woosb-wrap">';
	if ( $woosb_before_text = apply_filters( 'woosb_before_text', get_post_meta( $product_id, 'woosb_before_text', true ), $product_id ) ) {
		echo '<div id="woosb_before_text" class="woosb-before-text woosb-text">' . do_shortcode( stripslashes( $woosb_before_text ) ) . '</div>';
	}
	do_action( 'woosb_before_table', $product );
	?>
	<table id="woosb_products" cellspacing="0" class="woosb-table woosb-products"
		   data-percent="<?php echo esc_attr( get_post_meta( $product_id, 'woosb_price_percent', true ) ); ?>"
		   data-regular="<?php echo esc_attr( get_post_meta( $product_id, '_regular_price', true ) ); ?>"
		   data-sale="<?php echo esc_attr( get_post_meta( $product_id, '_sale_price', true ) ); ?>"
		   data-variables="<?php echo( $product->has_variables() ? 'yes' : 'no' ); ?>"
		   data-optional="<?php echo( $product->is_optional() ? 'yes' : 'no' ); ?>">
		<tbody>
		<?php foreach ( $woosb_items as $woosb_item ) {
			$woosb_product = wc_get_product( $woosb_item['id'] );
			if ( ! $woosb_product || ( $woosb_count > 2 ) ) {
				continue;
			}
			?>
			<tr class="woosb-product"
				data-id="<?php echo esc_attr( $woosb_product->is_type( 'variable' ) ? 0 : $woosb_item['id'] ); ?>"
				data-price="<?php echo esc_attr( $woosb_product->get_price() ); ?>"
				data-qty="<?php echo esc_attr( $woosb_item['qty'] ); ?>">
				<?php if ( get_option( '_woosb_bundled_thumb', 'yes' ) != 'no' ) { ?>
					<td class="woosb-thumb">
						<div class="woosb-thumb-ori">
							<?php echo apply_filters( 'woosb_item_thumbnail', get_the_post_thumbnail( $woosb_item['id'], array(
								40,
								40
							) ), $woosb_product ); ?>
						</div>
						<div class="woosb-thumb-new"></div>
					</td>
				<?php } ?>
				<td class="woosb-title">
					<?php
					do_action( 'woosb_before_item_name', $woosb_product );
					echo '<div class="woosb-title-inner">';
					if ( ( get_option( '_woosb_bundled_qty', 'yes' ) == 'yes' ) && ( get_post_meta( $product_id, 'woosb_optional_products', true ) != 'on' ) ) {
						echo apply_filters( 'woosb_item_qty', $woosb_item['qty'] . ' × ', $woosb_item['qty'], $woosb_product );
					}
					$woosb_item_name = '';
					if ( $woosb_product->is_visible() && ( get_option( '_woosb_bundled_link', 'yes' ) == 'yes' ) ) {
						$woosb_item_name .= '<a href="' . get_permalink( $woosb_item['id'] ) . '">';
					}
					if ( $woosb_product->is_in_stock() ) {
						$woosb_item_name .= $woosb_product->get_name();
					} else {
						$woosb_item_name .= '<s>' . $woosb_product->get_name() . '</s>';
					}
					if ( $woosb_product->is_visible() && ( get_option( '_woosb_bundled_link', 'yes' ) == 'yes' ) ) {
						$woosb_item_name .= '</a>';
					}
					echo apply_filters( 'woosb_item_name', $woosb_item_name, $woosb_product );
					echo '</div>';
					do_action( 'woosb_after_item_name', $woosb_product );
					if ( get_option( '_woosb_bundled_description', 'no' ) == 'yes' ) {
						echo '<div class="woosb-description">' . apply_filters( 'woosb_item_description', $woosb_product->get_description(), $woosb_product ) . '</div>';
					}
					if ( $woosb_product->is_type( 'variable' ) ) {
						$attributes           = $woosb_product->get_variation_attributes();
						$available_variations = $woosb_product->get_available_variations();
						if ( is_array( $attributes ) && ( count( $attributes ) > 0 ) ) {
							echo '<form class="variations_form cart" data-product_id="' . absint( $woosb_product->get_id() ) . '" data-product_variations="' . htmlspecialchars( wp_json_encode( $available_variations ) ) . '">';
							echo '<div class="variations">';
							foreach ( $attributes as $attribute_name => $options ) { ?>
								<div class="variation">
									<div
										class="label"><?php echo wc_attribute_label( $attribute_name ); ?></div>
									<div class="select">
										<?php
										$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $woosb_product->get_variation_default_attribute( $attribute_name );
										wc_dropdown_variation_attribute_options( array(
											'options'          => $options,
											'attribute'        => $attribute_name,
											'product'          => $woosb_product,
											'selected'         => $selected,
											'show_option_none' => esc_html__( 'Choose', 'woosb' ) . ' ' . wc_attribute_label( $attribute_name )
										) );
										?>
									</div>
								</div>
							<?php }
							echo '<div class="reset">' . apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woosb' ) . '</a>' ) . '</div>';
							echo '</div>';
							echo '</form>';
							if ( get_option( '_woosb_bundled_description', 'no' ) == 'yes' ) {
								echo '<div class="woosb-variation-description"></div>';
							}
						}
						do_action( 'woosb_after_item_variations', $woosb_product );
					}
					?>
				</td>
				<?php if ( get_post_meta( $product_id, 'woosb_optional_products', true ) == 'on' ) {
					$min_qty = 0;
					$max_qty = null;
					if ( ( $woosb_product->get_backorders() == 'no' ) && ( $woosb_product->get_stock_status() != 'onbackorder' ) && is_int( $woosb_product->get_stock_quantity() ) ) {
						$max_qty = $woosb_product->get_stock_quantity();
					} ?>
					<td class="woosb-qty">
						<input type="number" class="input-text qty text"
							   value="<?php echo esc_attr( $woosb_item['qty'] ); ?>"
							   min="<?php echo esc_attr( $min_qty ); ?>"
							   max="<?php echo esc_attr( $max_qty ); ?>"/>
					</td>
				<?php } ?>
				<?php if ( get_option( '_woosb_bundled_price', 'html' ) != 'no' ) { ?>
					<td class="woosb-price">
						<div class="woosb-price-ori">
							<?php
							$woosb_price = '';
							switch ( get_option( '_woosb_bundled_price', 'html' ) ) {
								case 'price':
									$woosb_price = wc_price( $woosb_product->get_price() );
									break;
								case 'html':
									$woosb_price = $woosb_product->get_price_html();
									break;
								case 'subtotal':
									$woosb_price = wc_price( $woosb_product->get_price() * $woosb_item['qty'] );
									break;
							}
							echo apply_filters( 'woosb_item_price', $woosb_price, $woosb_product );
							?>
						</div>
						<div class="woosb-price-new"></div>
					</td>
				<?php } ?>
			</tr>
			<?php
			$woosb_count ++;
		} ?>
		</tbody>
	</table>
	<?php
	if ( $product->has_variables() || $product->is_optional() ) {
		echo '<div id="woosb_total" class="woosb-total woosb-text"></div>';
	}
	do_action( 'woosb_after_table', $product );
	if ( $woosb_after_text = apply_filters( 'woosb_after_text', get_post_meta( $product_id, 'woosb_after_text', true ), $product_id ) ) {
		echo '<div id="woosb_after_text" class="woosb-after-text woosb-text">' . do_shortcode( stripslashes( $woosb_after_text ) ) . '</div>';
	}
	echo '</div>';
}
?>