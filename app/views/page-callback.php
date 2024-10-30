<div class="wrap">
    <h1><?php _e("Billplz for Contact Form 7", BCF7_TEXT_DOMAIN); ?></h1>
  <?php $active_tab = isset($_GET["tab"])
      ? $_GET["tab"]
      : "payments"; ?>
  <h2 class="nav-tab-wrapper">
  <a href="?page=billplz-cf7&tab=payments" class="nav-tab <?php echo $active_tab ==
    "payments"
        ? "nav-tab-active"
        : ""; ?>">Payments</a>
    <a href="?page=billplz-cf7&tab=general-settings" class="nav-tab <?php echo $active_tab ==
    "general-settings"
        ? "nav-tab-active"
        : ""; ?>">General Settings</a>
    <a href="?page=billplz-cf7&tab=api-settings" class="nav-tab <?php echo $active_tab ==
    "api-settings"
        ? "nav-tab-active"
        : ""; ?>">API Settings</a>
    <a href="?page=billplz-cf7&tab=email-settings" class="nav-tab <?php echo $active_tab ==
    "email-settings"
        ? "nav-tab-active"
        : ""; ?>">Email Settings</a>
  </h2>

  <?php if ($active_tab == "payments") {
   
    $bcf7_table = new \BillplzCF7\Admin\PaymentTable();
    $php_self = esc_attr( $_SERVER['PHP_SELF'] );
    $page = esc_attr( 'page=billplz-cf7&tab=payments' );
    ?>
      <br>
      <?php $bcf7_table->views(); ?>
      <form action="<?php echo $php_self.'?'.$page; ?>" method="post">
        <?php $bcf7_table->prepare_items(); ?>
        <?php $bcf7_table->search_box( "Search Customer or Bill ID", "payment-search-id"); ?>
        <?php $bcf7_table->display(); ?>
      </form>
    <?php

  } elseif ($active_tab == "general-settings") {
      require_once BCF7_PLUGIN_PATH . "app/views/general-settings-page.php";

  } elseif ($active_tab == "api-settings"){
    require_once BCF7_PLUGIN_PATH . "app/views/api-settings-page.php";

  } else {
    require_once BCF7_PLUGIN_PATH . "app/views/email-settings-page.php";
  }
  ?>
</div>