<?php do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>
<div class="container-fluid invoice">
	<div class="row">
        <div class="navbar-brand col-xs-3 col-md-3 col-xs-offset-2">
            <?php
                if( $this->has_header_logo() ) {
                    $this->header_logo();
                } else {
                    echo $this->get_title();
                }
            ?>
        </div>
	</div>
    <div class="row">
		<small><div class="shop-address text-center"><?php $this->shop_address(); ?></div></small>
	</div>
	<div class="row">
		<h4 class="document-type-label text-center">
			<?php if( $this->has_header_logo() ) echo $this->get_title(); ?>
		</h4>
	</div>
	<?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>
	<div class="row">
		<div class="col-xs-3 col-md-3 text-left">
			<?php _e( 'Name:', 'woocommerce-pdf-invoices-packing-slips' ); ?>
		</div>
		<div class="col-xs-9 col-md-9 text-left">
			<?php do_action( 'wpo_wcpdf_before_billing_address', $this->type, $this->order ); ?>
			<?php $this->billing_name(); ?>
			<?php do_action( 'wpo_wcpdf_after_billing_address', $this->type, $this->order ); ?>
		</div>
	</div>
	<div class="row">&nbsp;</div>
	<div class="container-fluid product-list">
		<div class="row">
			<div class="col-xs-4 col-md-4 product"><small><?php _e('Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></small></div>
			<div class="col-xs-4 col-md-4 quantity"><small><?php _e('Quantity', 'woocommerce-pdf-invoices-packing-slips' ); ?></small></div>
			<div class="col-xs-4 col-md-4 quantity"><small><?php _e('Price', 'woocommerce-pdf-invoices-packing-slips' ); ?></small></div>
		</div>
		<?php $items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
		<div class="row <?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $this->type, $this->order, $item_id ); ?>">
			<div class="product col-xs-6 col-md-6">
				<small><?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
				<?php do_action( 'wpo_wcpdf_before_item_meta', $this->type, $item, $this->order  ); ?>
				<span class="item-meta"><?php echo $item['meta']; ?></span>
				<dl class="meta">
					<?php $description_label = __( 'SKU', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
					<?php if( !empty( $item['sku'] ) ) : ?><dt class="sku"><?php _e( 'SKU:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="sku"><?php echo $item['sku']; ?></dd><?php endif; ?>
					<?php if( !empty( $item['weight'] ) ) : ?><dt class="weight"><?php _e( 'Weight:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="weight"><?php echo $item['weight']; ?><?php echo get_option('woocommerce_weight_unit'); ?></dd><?php endif; ?>
				</dl>
				<?php do_action( 'wpo_wcpdf_after_item_meta', $this->type, $item, $this->order  ); ?></small>
			</div>
			<div class="quantity col-xs-2 col-md-2 text-right"><small><?php echo $item['quantity']; ?></small></div>
			<div class="price col-xs-4 col-md-4"><small><?php echo $item['itemize_price']; ?></small></div>
		</div>
		<?php endforeach; endif; ?>
	</div>
	<div class="row">
		<div class="customer-notes">
			<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->type, $this->order ); ?>
			<?php if ( $this->get_shipping_notes() ) : ?>
				<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
				<?php $this->shipping_notes(); ?>
			<?php endif; ?>
			<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->type, $this->order ); ?>
		</div>	
	</div>
	<div class="row">
		<?php foreach( $this->get_woocommerce_totals() as $key => $total ) : ?>
			<div class="row <?php echo $key; ?>">
				<div class="no-borders"></div>
				<div class="description col-xs-6 col-md-6 text-right"><?php echo $total['label']; ?></div>
				<div class="price col-xs-5 col-md-5 text-right"><span class="totals-price"><?php echo $total['value']; ?></span></div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="row">&nbsp;</div>
	<div class="row text-center">GST# <strong>80111 2582 RT0001</strong></div>
	<div class="row text-center">Thank you</div>
	<div class="row text-center"><small>Please visit and logon our website to order more delicious dishes and get updates.</small></div>
	<div class="row text-center"><small>www.delhichaat.ca</small></div>
</div>
<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>

<?php if ( $this->get_footer() ): ?>
<div id="footer">
	<?php $this->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>
