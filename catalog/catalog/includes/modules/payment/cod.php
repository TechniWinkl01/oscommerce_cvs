<?
  $payment_code = 'cod';
  $payment_description = TEXT_CASH_ON_DELIVERY;
  $payment_enabled = PAYMENT_SUPPORT_COD;

  if ($payment_action == 'PM_AFTER_PROCESS' && $payment_enabled) {
    header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')); 
  } elseif ($payment_action == 'PM_CHECK') {
    $check = tep_db_query("select configuration_value from configuration where configuration_key = 'PAYMENT_SUPPORT_COD'");
    $check = tep_db_num_rows($check) + 1;
  } elseif ($payment_action == 'PM_INSTALL') {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Cash On Delivery (COD)', 'PAYMENT_SUPPORT_COD', '1', 'Do you want to accept COD (Cash On Delevery) payments?', '6', '1', now());");
  } elseif ($payment_action == 'PM_REMOVE') {
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'PAYMENT_SUPPORT_COD'");
  }
?>