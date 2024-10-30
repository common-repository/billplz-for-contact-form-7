<?php

namespace BillplzCF7\Payment;

use BillplzCF7\Helpers\Functions;
use Exception;
use WPCF7_Submission;

class FormSubmission
{
  private $helpers;

  public function __construct()
  {
    $this->helpers = Functions::get_instance();
  }

  public function register()
  {
    add_action('wpcf7_before_send_mail', array($this, 'process_data'));
    add_filter('wpcf7_load_js', '__return_false');
  }

  public function process_data($contact_form)
  {
    $id = $contact_form->id();
    $list_of_forms = $this->helpers->general_option("bcf7_form_select");

    $setting_url = admin_url('admin.php?page=billplz-cf7&tab=general-settings');
    if (empty($list_of_forms)) wp_die("The payment form is not specified. Please go to the <a href='$setting_url'>General Settings options page</a> to set the form.");

    if ( in_array( $id, $list_of_forms )) {
      $submission = WPCF7_Submission::get_instance();

      if ( $submission && isset( $_POST['bcf7-amount'] ) ) {
        $form_id        = $submission->get_contact_form()->id();
        $form_title     = $submission->get_contact_form()->title();
        $posted_data    = $submission->get_posted_data();
        $name           = $posted_data['bcf7-name'];
        $email          = $posted_data['bcf7-email'];
        $amount         = $posted_data['bcf7-amount'];
        $phone          = $posted_data['bcf7-phone'];
        $transaction_id = '';
        $mode           = $this->helpers->get_mode();
        $status         = 'pending';

        $payment_id = $this->record_data($form_id, $form_title, $name, $phone, $email, $amount, $transaction_id, $mode, $status);
        
        $description = apply_filters('bcf7_form_description', "Payment for $form_title");
        $this->process_payment($name, $email, $phone, $amount, $description, $payment_id);
      }
    }
  }

  public function record_data($form_id, $form_title, $name, $phone, $email, $amount, $transaction_id, $mode, $status)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . "bcf7_payment";

    $wpdb->insert(
      $table_name,
      array(
        'form_id'        => $form_id,
        'form_title'     => $form_title,
        'name'           => $name,
        'phone'          => $phone,
        'amount'         => $amount,
        'transaction_id' => $transaction_id,
        'email'          => $email,
        'mode'           => $mode,
        'status'         => $status,
        'created_at'     => current_time('mysql'),
        'paid_at'        => '0000-00-00 00:00:00',
      ),
    );

    return $wpdb->insert_id;
  }

  public function process_payment($name, $email, $phone, $amount, $description, $payment_id)
  {
    $args = array(
      'headers' => array(
        'Authorization' => 'Basic ' . $this->helpers->get_api_key() . ':',
      ),
      'body' => array(
        'collection_id' => $this->helpers->get_collection_id(),
        'email' => $email,
        'name' => $name,
        'amount' => $amount * 100,
        'mobile' => (isset($phone) ? $phone : ""),
        'redirect_url' => add_query_arg(array('bcf7-listener' => 'billplz', 'payment-id' => $payment_id), site_url("?page_id=" . $this->helpers->general_option('bcf7_redirect_page') . "")),
        'callback_url' => add_query_arg(array('bcf7-listener' => 'billplz', 'payment-id' => $payment_id), site_url('index.php')),
        'description' => $description
      )
    );

    $response = wp_remote_post($this->helpers->get_url() . "/api/v3/bills", $args);
    $apiBody = json_decode(wp_remote_retrieve_body($response));
    $bill_url = $apiBody->url;

    if ($bill_url) {
      $content = require_once BCF7_PLUGIN_PATH . "app/views/splash-page.php";
      $content .= '<script>window.location.replace("' . $bill_url . '");</script>';
    }

    $allowed_tags = array('div' => array(), 'p' => array(), 'script' => array());

    echo wp_kses($content, $allowed_tags);
  }
}