<?
// Language defines, should be removed from here
  if ($language == 'english') {
    define('TEXT_SAMPLE', 'Sample Payment Module');
  } elseif ($language == 'espanol') {
    define('TEXT_SAMPLE', 'Modulo de Pago de Ejemplo');
  }

  $payment_code = 'sample';
  $payment_description = TEXT_SAMPLE; 
  $payment_enabled = PAYMENT_SUPPORT_SAMPLE;

  ################################################################
  # Description: Javascript from validation
  # Called from: checkout_payment.php
  # Note: this is javascript code
  # Examples: 
  #  - validate credit card numbers 
  #  - make sure required fields are filled in
  ################################################################
  if ($payment_action == 'PM_VALIDATION' && $payment_enabled) {

  ################################################################
  # Description: Extra info for this type of payment
  # Called from: checkout_payment.php
  # Examples: 
  #  - ask for extra fields (credit card number)
  #  - display extra info 
  ################################################################
  } elseif ($payment_action == 'PM_SELECTION' && $payment_enabled) {

  ################################################################
  # Description: Things to do before displaying confirmation form
  # Called from: checkout_confirmation.php
  # Note: We can tell the form where to go by setting the variable
  #       $checkout_form_action
  # Examples: 
  #  - Validate (this time with PHP) extra fields
  #  - Redirect to online payment service (Paypal)
  ################################################################
  } elseif ($payment_action == 'PM_CONFIRMATION' && $payment_enabled) {

  ################################################################
  # Description: Things to do just before finishing the form
  # Called from: checkout_confirmation.php
  # Examples: 
  #  - Include extra fields as hidden fields in the form
  ################################################################
  } elseif ($payment_action == 'PM_PROCESS_BUTTON' && $payment_enabled) {

  ################################################################
  # Description: Things to do before processing the order
  # Called from: checkout_process.php
  # Examples: 
  #  - Get results from an online payment service
  ################################################################
  } elseif ($payment_action == 'PM_BEFORE_PROCESS' && $payment_enabled) {

  ################################################################
  # Description: Things to do after processing the order
  # Called from: checkout_process.php
  # Examples: 
  #  - Redirect the user to the success page
  ################################################################
  } elseif ($payment_action == 'PM_AFTER_PROCESS' && $payment_enabled) {

    header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')); 

  ################################################################
  # Description: Check if a module is installed
  # Called from: payment_modules.php (admin)
  ################################################################
  } elseif ($payment_action == 'PM_CHECK') {

    $check = tep_db_query("select configuration_value from configuration where configuration_key = 'PAYMENT_SUPPORT_SAMPLE'");
    $check = tep_db_num_rows($check) + 1;

  ################################################################
  # Description: Install a module
  # Called from: payment_modules.php (admin)
  ################################################################
  } elseif ($payment_action == 'PM_INSTALL') {

    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Sample Payments', 'PAYMENT_SUPPORT_SAMPLE', '0', 'Do you want to accept sample payments?', '6', '6', now())");

  ################################################################
  # Description: Remove a module
  # Called from: payment_modules.php (admin)
  ################################################################
  } elseif ($payment_action == 'PM_REMOVE') {

    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'PAYMENT_SUPPORT_SAMPLE'");

  }
?>