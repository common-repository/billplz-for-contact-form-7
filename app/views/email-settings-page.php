<form action="options.php" method="POST">
  <?php
    settings_fields( "bcf7_email" );
    do_settings_sections( "bcf7_email_settings" );
    submit_button();
  ?>
</form>