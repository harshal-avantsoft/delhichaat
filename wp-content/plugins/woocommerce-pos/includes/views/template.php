<?php
/**
 * Main POS template
 */
?>
<html class="no-js">
<head>
  <title><?php _e('Point of Sale', 'woocommerce-pos') ?> - <?php bloginfo('name') ?></title>
  <meta charset="UTF-8" />

  <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
  <meta name="apple-mobile-web-app-capable" content="yes" />

  <!-- For iPad with high-resolution Retina display running iOS ≥ 7: -->
  <link rel="apple-touch-icon-precomposed" href="<?php echo WC_POS_PLUGIN_URL ?>assets/favicon-152.png">
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo WC_POS_PLUGIN_URL ?>assets/favicon-152.png">

  <!-- For iPad with high-resolution Retina display running iOS ≤ 6: -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo WC_POS_PLUGIN_URL ?>assets/favicon-144.png">

  <!-- For iPhone with high-resolution Retina display running iOS ≥ 7: -->
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo WC_POS_PLUGIN_URL ?>assets/favicon-120.png">

  <!-- For iPhone with high-resolution Retina display running iOS ≤ 6: -->
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo WC_POS_PLUGIN_URL ?>assets/favicon-114.png">

  <!-- For first- and second-generation iPad: -->
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo WC_POS_PLUGIN_URL ?>assets/favicon-72.png">

  <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
  <link rel="apple-touch-icon-precomposed" href="<?php echo WC_POS_PLUGIN_URL ?>assets/favicon-57.png">

  <!-- IE 10 Metro tile icon -->
  <meta name="msapplication-TileColor" content="#323A46">
  <meta name="msapplication-TileImage" content="<?php echo WC_POS_PLUGIN_URL ?>assets/favicon-144.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

  <?php do_action('woocommerce_pos_head'); ?>
</head>
<body>

<div id="page"></div>

<?php do_action('woocommerce_pos_footer'); ?>

<?php
  $options = array(
    'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
    'nonce' => wp_create_nonce( WC_POS_PLUGIN_NAME )
  );
?>
<script>POS.start(<?php echo json_encode($options) ?>);</script>

<iframe name="iframe" style="visibility: hidden; right: 0px; bottom: 0px;"></iframe>
</body>
</html>