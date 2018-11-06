{{#if product}}
<div class="qty col-sm-1 col-md-1"><input type="text" name="quantity" data-label="<?php /* translators: woocommerce */ _e( 'Quantity', 'woocommerce' ); ?>" data-numpad="quantity" class="form-control autogrow"></div>
<div class="title col-sm-5 col-md-5">
  <strong data-name="title" contenteditable="true">{{name}}</strong>
  <dl class="meta"></dl>
  <a data-action="more" href="#" class="btn btn-default btn-circle-sm"><i class="icon-angle-down"></i></a>
</div>
<div class="price col-sm-2 col-md-"><input type="text" name="item_price" data-label="<?php /* translators: woocommerce */ _e( 'Price', 'woocommerce' ); ?>" data-numpad="discount" data-original="regular_price" data-percentage="off" class="form-control autogrow"></div>
{{else}}
<div class="qty"></div>
<div class="title">
  {{#if method_title}}
  <strong data-name="method_title" contenteditable="true">{{method_title}}</strong>
  {{else}}
  <strong data-name="title" contenteditable="true">{{title}}</strong>
  {{/if}}
  <a data-action="more" href="#" class="btn btn-default btn-circle-sm"><i class="icon-angle-down"></i></a>
</div>
<div class="price"><input type="text" name="item_price" data-label="<?php /* translators: woocommerce */ _e( 'Price', 'woocommerce' ); ?>" data-numpad="amount" class="form-control autogrow"></div>
{{/if}}
<div class="total"></div>
<div class="action">
  <a data-action="remove" href="#">
    <i class="icon-times-circle"></i>
  </a>
</div>