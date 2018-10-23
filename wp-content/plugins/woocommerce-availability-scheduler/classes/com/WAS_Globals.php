<?php 
function was_is_checkout() 
  {
        return is_page( wc_get_page_id( 'checkout' ) ) || wc_post_content_has_shortcode( 'woocommerce_checkout' ) /*||  apply_filters( 'woocommerce_is_checkout', false ) */ || defined( 'WOOCOMMERCE_CHECKOUT' );
    }
	
?>