<?
  class sample {
    var $code, $description, $enabled;

// class constructor
    function sample() {
      $this->code = 'sample';
      $this->description = MODULE_PAYMENT_SAMPLE_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_SAMPLE_STATUS;
    }

    function javascript_validation() {
      if ($this->enabled) {
/*
  Description: Javascript from validation
  Called from: checkout_payment.php
  Note: this is javascript code
  Examples: 
   - validate credit card numbers 
   - make sure required fields are filled in
*/
      }
    }

    function selection() {
      if ($this->enabled) {
/*
  Description: Extra info for this type of payment
  Called from: checkout_payment.php
  Examples: 
   - ask for extra fields (credit card number)
   - display extra info 
*/
      }
    }

    function confirmation() {
      if ($this->enabled) {
/*
  Description: Things to do before displaying confirmation form
  Called from: checkout_confirmation.php
  Note: We can tell the form where to go by setting the variable
        $checkout_form_action
  Examples: 
   - Validate (this time with PHP) extra fields
   - Redirect to online payment service (Paypal)
*/
      }
    }

    function process_button() {
      if ($this->enabled) {
/*
  Description: Things to do just before finishing the form
  Called from: checkout_confirmation.php
  Examples: 
   - Include extra fields as hidden fields in the form
*/
      }
    }

    function before_process() {
      if ($this->enabled) {
/*
  Description: Things to do before processing the order
  Called from: checkout_process.php
  Examples: 
   - Get results from an online payment service
*/
      }
    }

    function after_process() {
      if ($this->enabled) {
/*
  Description: Things to do after processing the order
  Called from: checkout_process.php
  Examples: 
   - Email part of the credit number
*/
      }
    }

    function check() {
/*
  Description: Check if a module is installed
  Called from: payment_modules.php (admin)
*/
      $check = tep_db_query("select configuration_value from configuration where configuration_key = 'MODULE_PAYMENT_SAMPLE_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
/*
  Description: Install a module
  Called from: payment_modules.php (admin)
*/
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Sample Payments', 'MODULE_PAYMENT_SAMPLE_STATUS', '0', 'Do you want to accept sample payments?', '6', '6', now())");
    }

    function remove() {
/*
  Description: Remove a module
  Called from: payment_modules.php (admin)
*/
      tep_db_query("delete from configuration where configuration_key = 'MODULE_PAYMENT_SAMPLE_STATUS'");
    }

/*
  Description: Retreive module configuration keys
  Called from: payment_modules.php (admin)
*/
    function keys() {
      $keys = array('MODULE_PAYMENT_SAMPLE_STATUS');

      return $keys;
    }
  }
?>