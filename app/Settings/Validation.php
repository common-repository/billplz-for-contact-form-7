<?php

namespace BillplzCF7\Settings;

class Validation
{
  private $helpers;

  public function __construct()
  {
    $this->helpers = \BillplzCF7\Helpers\Functions::get_instance();
  }

  public function register()
  {
    add_action("admin_notices", array($this, "credentials_check"));
  }

  public function credentials_check()
  {
    if ("1" == $this->helpers->general_option("bcf7_mode") and ((empty($this->helpers->api_option("bcf7_sandbox_secret_key"))) or empty($this->helpers->api_option("bcf7_sandbox_collection_id")) or empty($this->helpers->api_option("bcf7_sandbox_xsignature_key")))) {
      echo __(sprintf(
        '<div class="notice notice-warning">
              <p><strong>Billplz for Contact Form 7 -</strong>Billplz Sandbox Credentials is not set. Enter your Secret Key, Collection ID and X-Signature Key in order to use Billplz service. <a href="' . get_admin_url() . 'admin.php?page=billplz-cf7&tab=api-settings">Set Credential</a></p>
          </div>',
      ), BCF7_TEXT_DOMAIN);
    } elseif ("0" == $this->helpers->general_option("bcf7_mode") and (empty($this->helpers->api_option("bcf7_live_secret_key")) or empty($this->helpers->api_option("bcf7_live_collection_id")) or empty($this->helpers->api_option("bcf7_live_xsignature_key")))) {
      echo __(sprintf(
        '<div class="notice notice-warning">
              <p><strong>Billplz for Contact Form 7 -</strong>Billplz Live Credentials is not set. Enter your Secret Key, Collection ID and X-Signature Key in order to use Billplz service. <a href="' . get_admin_url() . 'admin.php?page=billplz-cf7&tab=api-settings">Set Credential</a></p>
          </div>',
      ), BCF7_TEXT_DOMAIN);
    }
  }
}