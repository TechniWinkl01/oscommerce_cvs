<?php
// sample.php - Sample structure of what a payment module should follow.

  class sample {
    var $code, $description, $enabled;

////
// !Class constructor -> initialize class variables.
// Sets the class code, description, and status.
    function sample() {
      $this->code = 'sample';
      $this->description = MODULE_PAYMENT_SAMPLE_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_SAMPLE_STATUS;
    }

////
// !Javascript form validation
// Check the user input submited on checkout_payment.php with javascript (client-side).
// Examples: validate credit card number, make sure required fields are filled in
    function javascript_validation() {
      if ($this->enabled) {
// insert code here
      }
    }

////
// !Form fields for user input
// Output any required information in form fields
// Examples: ask for extra fields (credit card number), display extra information
    function selection() {
      if ($this->enabled) {
// insert code here
      }
    }

////
// !Functions to execute before displaying the checkout confirmation page
// Note: Set the variable $checkout_form_action to set the form action value
// Examples: validate (server-side with PHP) extra fields, redirect to online payment services (eg, PayPal)
    function confirmation() {
      if ($this->enabled) {
// insert code here
      }
    }

////
// !Functions to execute before finishing the form
// Examples: add extra hidden fields to the form
    function process_button() {
      if ($this->enabled) {
// insert code here
      }
    }

////
// !Functions to execute before processing the order
// Examples: retreive result from online payment services
    function before_process() {
      if ($this->enabled) {
// insert code here
      }
    }

////
// !Functions to execute after processing the order
// Examples: email part of the credit card number
    function after_process() {
      if ($this->enabled) {
// insert code here
      }
    }

////
// !Check if module is installed (Administration Tool)_
// TABLES: configuration
    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SAMPLE_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

////
// !Install the module (Administration Tool)_
// TABLES: configuration
    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Sample Payments', 'MODULE_PAYMENT_SAMPLE_STATUS', '0', 'Do you want to accept sample payments?', '6', '6', now())");
    }

////
// !Remove the module (Administration Tool)_
// TABLES: configuration
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SAMPLE_STATUS'");
    }

////
// !Retreive the modules configuration keys (Administration Tool)_
// Check the user input submited on checkout_payment.php with javascript (client-side).
    function keys() {
      $keys = array('MODULE_PAYMENT_SAMPLE_STATUS');

      return $keys;
    }
  }
?>