jQuery( document ).ready(function($) {

  if($("#hellodev-discount-manager-apply-discount").length > 0) {

    function apply_discount() {

      var answer = confirm(hellodev_discount_manager_locales.confirm_apply_discount);
      if (!answer) {
        return false;
      }

      var discount = jQuery('#hellodev-discount-manager-apply-discount').find('input[name=discount]').val();
      if (discount.length == 0) {
        discount = 0;
      }

      $('#order_line_items').find('tr.item').each(function (i, element) {
        var original_total = jQuery(element).find('.line_subtotal').val();
        original_total = original_total.replace(",", ".");
        var new_total = original_total - (original_total / 100 * discount);
        $(element).find('.line_total').val(new_total);
      });

      $('#woocommerce-order-items').find('button.button.button-primary.save-action').click();
      alert(hellodev_discount_manager_locales.apply_success_message);
      return false;
    }

    $("#hellodev-discount-manager-apply-discount-percentage").change( function() {
      apply_discount();
    });

    $('#woocommerce-apply-discount').find('button.apply_discount').click(apply_discount);

  }

});
