jQuery( document ).ready(function($) {

  if($("#woocommerce-order-actions-hide").length > 0) {

    function discount_per_item() {

      var discount = $(this).val();

      if(discount >= 0 && discount <= 100) {

        var original_total = $(this).closest("tr").find('.line_subtotal').val();

        original_total = original_total.replace(",", ".");

        var new_total = original_total - (original_total / 100 * discount);

        $(this).closest("tr").find(".line_total").val(new_total);

        $('#woocommerce-order-items').find('button.button.button-primary.save-action').click();

      } else {

        alert(hellodev_discount_manager_locales.apply_error_message);

      }

    }

    $(".hellodev-discount-manager-apply-discount-percentage").change(discount_per_item);

    function loopForever() {
      $(".hellodev-discount-manager-apply-discount-percentage").change(discount_per_item);
    }
    
    window.setInterval(function () {
    	loopForever();
    }, 1000);

  }

});
