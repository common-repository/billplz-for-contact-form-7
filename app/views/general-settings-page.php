<form action="options.php" method="POST">
  <?php
    settings_fields( "bcf7_general" );
    do_settings_sections( "bcf7_general_settings" );
    submit_button();
  ?>
</form>