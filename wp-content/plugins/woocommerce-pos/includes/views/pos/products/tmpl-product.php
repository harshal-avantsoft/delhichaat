{{#is type 'variable'}}
<div class="row align-items-center" data-action="variations">
{{else}}
<div class="row align-items-center" data-action="add">
{{/is}}
<div class="img col-sm-3 col-md-3"><img src="{{featured_src}}" title="#{{id}}"></div>
<div class="title col-sm-5 col-md-5 text-capitalize">
  <strong>{{name}}</strong>&nbsp;
  {{#with product_attributes}}
  <i class="icon-info-circle" data-toggle="tooltip" title="
    <dl>
      {{#each this}}
      <dt>{{name}}:</dt>
      <dd>{{#list options ', '}}{{this}}{{/list}}</dd>
      {{/each}}
    </dl>
    "></i>
  {{/with}}
  {{#with product_variations}}
  <ul class="variations">
    {{#each this}}
    <li data-toggle="buttons">
      <strong>{{name}}:</strong> {{#list options ', '}}<a href="#" data-name="{{../name}}">{{this}}</a>{{/list}}
    </li>
    {{/each}}
  </ul>
  <small>
    <a href="#" data-action="expand" class="expand-all"><?php /* translators: woocommerce */ _e( 'Expand', 'woocommerce' ); ?></a>
    <a href="#" data-action="close" class="close-all"><?php /* translators: woocommerce */ _e( 'Close', 'woocommerce' ); ?></a>
  </small>
  {{/with}}
  {{#is type 'variation'}}
  <ul class="variant">
    {{#each attributes}}
    <li>
      <strong>{{name}}:</strong> {{#if option}}{{option}}{{else}}{{#list options ', '}}{{this}}{{/list}}{{/if}}
    </li>
    {{/each}}
  </ul>
  {{/is}}
  {{#if managing_stock}}
  <small><?php /* translators: woocommerce */ printf( __( '%s in stock', 'woocommerce' ), '{{number stock_quantity precision="auto"}}' ); ?></small>
  {{/if}}
</div>
<div class="price col-sm-3 col-md-3">
  {{#if on_sale}}<del>{{#list regular_price ' - '}}{{{money this}}}{{/list}}</del>{{/if}} {{#list price ' - '}}{{{money this}}}{{/list}}
</div>
{{#is type 'variable'}}
<div class="action col-sm-1 col-md-1">
  <a href="#">
    <i class="icon-chevron-circle-right"></i>
  </a>
</div>
{{else}}
<div class="action">
  <a href="#">
    <i class="icon-plus-circle"></i>
  </a>
</div>
{{/is}}
</div>