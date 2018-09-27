<?php do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>
<div class="container-fluid packing-slip">
    <div class="container">
        <div class="row center-block">
            <div class="navbar-brand col-xs-offset-3">
                <?php
                    if( $this->has_header_logo() ) {
                        $this->header_logo();
                    } else {
                        echo $this->get_title();
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="shop-address text-center"><?php $this->shop_address(); ?></div>
    </div>
    <div class="row">
        <h3 class="document-type-label text-center">
            <?php if( $this->has_header_logo() ) echo $this->get_title(); ?>
        </h3>
    </div>
    <?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>
    <div class="row">
        <div class="col-xs-12 col-md-12"><?php _e( 'Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?><strong><?php $this->order_number(); ?></strong></div>
        <div class="col-xs-12 col-md-12"><?php _e( 'Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?><strong><?php $this->order_date(); ?></strong></div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12"><?php _e( 'Shipping Method:', 'woocommerce-pdf-invoices-packing-slips' ); ?><strong><?php $this->shipping_method(); ?></strong></div>
    </div>
    <?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>
</div>
<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>
<br />
<div class="container-fluid product-list">
    <div class="row">
        <div class="col-xs-6 col-md-6 product"><?php _e('Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></div>
        <div class="col-xs-6 col-md-6 quantity"><?php _e('Quantity', 'woocommerce-pdf-invoices-packing-slips' ); ?></div>
    </div>
    <?php $items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
    <div class="row <?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $this->type, $this->order, $item_id ); ?>">
        <div class="product col-xs-6 col-md-6">
            <?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
            <span class="item-name"><?php echo $item['name']; ?></span>
            <?php do_action( 'wpo_wcpdf_before_item_meta', $this->type, $item, $this->order  ); ?>
            <span class="item-meta"><?php echo $item['meta']; ?></span>
            <dl class="meta">
                <?php $description_label = __( 'SKU', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
                <?php if( !empty( $item['sku'] ) ) : ?><dt class="sku"><?php _e( 'SKU:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="sku"><?php echo $item['sku']; ?></dd><?php endif; ?>
                <?php if( !empty( $item['weight'] ) ) : ?><dt class="weight"><?php _e( 'Weight:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="weight"><?php echo $item['weight']; ?><?php echo get_option('woocommerce_weight_unit'); ?></dd><?php endif; ?>
            </dl>
            <?php do_action( 'wpo_wcpdf_after_item_meta', $this->type, $item, $this->order  ); ?>
        </div>
        <div class="quantity col-xs-6 col-md-6"><?php echo $item['quantity']; ?></div>
    </div>
    <?php endforeach; endif; ?>
</div>
<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>
<div class="container-fluid">
    <?php do_action( 'wpo_wcpdf_before_customer_notes', $this->type, $this->order ); ?>
    <div class="customer-notes">
        <?php if ( $this->get_shipping_notes() ) : ?>
            <h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
            <?php $this->shipping_notes(); ?>
        <?php endif; ?>
    </div>
    <?php do_action( 'wpo_wcpdf_after_customer_notes', $this->type, $this->order ); ?>
</div>