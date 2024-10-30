<form action="options.php" method="POST">
  <?php
    settings_fields( "bcf7_api" );
    do_settings_sections( "bcf7_live_settings" );
    do_settings_sections( "bcf7_sandbox_settings" );
    submit_button();
  ?>
</form>