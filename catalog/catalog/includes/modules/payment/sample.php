<?
  class sample {
    var $payment_code, $payment_description, $payment_enabled;

// class constructor
    function sample() {
      $this->payment_code = 'sample';
      $this->payment_description = TEXT_SAMPLE;
      $this->payment_enabled = PAYMENT_SUPPORT_SAMPLE;
    }

    function javascript_validation() {
      if ($this->payment_enabled) {
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
      if ($this->payment_enabled) {
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
      if ($this->payment_enabled) {
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
      if ($this->payment_enabled) {
/*
  Description: Things to do just before finishing the form
  Called from: checkout_confirmation.php
  Examples: 
   - Include extra fields as hidden fields in the form
*/
      }
    }

    function before_process() {
      if ($this->payment_enabled) {
/*
  Description: Things to do before processing the order
  Called from: checkout_process.php
  Examples: 
   - Get results from an online payment service
*/
      }
    }

    function after_process() {
      if ($this->payment_enabled) {
/*
  Description: Things to do after processing the order
  Called from: checkout_process.php
  Examples: 
   - Redirect the user to the success page
*/
      }
      header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')); 
    }

    function check() {
/*
  Description: Check if a module is installed
  Called from: payment_modules.php (admin)
*/
      $check = tep_db_query("select configuration_value from configuration where configuration_key = 'PAYMENT_SUPPORT_SAMPLE'");
      $check = tep_db_num_rows($check) + 1;

      return $check;
    }

    function install() {
/*
  Description: Install a module
  Called from: payment_modules.php (admin)
*/
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Sample Payments', 'PAYMENT_SUPPORT_SAMPLE', '0', 'Do you want to accept sample payments?', '6', '6', now())");
    }

    function remove() {
/*
  Description: Remove a module
  Called from: payment_modules.php (admin)
*/
      tep_db_query("delete from configuration where configuration_key = 'PAYMENT_SUPPORT_SAMPLE'");
    }

/*
  Description: Retreive module configuration keys
  Called from: payment_modules.php (admin)
*/
    function keys() {
      $keys = array('PAYMENT_SUPPORT_SAMPLE');

      return $keys;
    }
  }
?>