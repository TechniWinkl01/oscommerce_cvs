<?
  $payment_code = 'paypal';
  $payment_description = TEXT_PAYPAL;
  $payment_enabled = PAYMENT_SUPPORT_PAYPAL;

  if ($payment_action == 'PM_CONFIRMATION' && $payment_enabled) {
    $paypal_return = urlencode($HTTP_POST_VARS['payment'] . '|' . $HTTP_POST_VARS['sendto'] . '|' . $shipping_cost . '|' . $shipping_method);
    $checkout_form_action = 'https://secure.paypal.com/xclick/business=' . rawurlencode(PAYPAL_ID) . '&item_name=' . rawurlencode(STORE_NAME) . '&amount=' . number_format(($total_cost + $total_tax),2) . '&shipping=' . number_format($shipping_cost, 2) . '&return=' . urlencode(tep_href_link(FILENAME_CHECKOUT_PROCESS, 'paypal_return=' . $paypal_return, 'NONSSL'));
  } elseif ($payment_action == 'PM_BEFORE_PROCESS' && $payment_enabled) {
    if ($HTTP_GET_VARS['paypal_return'])
    {
      $arg = urldecode($HTTP_GET_VARS['paypal_return']);
      $args = explode('|', $arg);
      $payment = $args[0];
      $sendto = $args[1];
      $shipping_cost = $args[2];
      $shipping_method = $args[3];
    }
  } elseif ($payment_action == 'PM_AFTER_PROCESS' && $payment_enabled) {
    header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')); 
  } elseif ($payment_action == 'PM_CHECK') {
    $check = tep_db_query("select configuration_value from configuration where configuration_key = 'PAYMENT_SUPPORT_PAYPAL'");
    $check = tep_db_num_rows($check) + 1;
  } elseif ($payment_action == 'PM_INSTALL') {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow PayPal', 'PAYMENT_SUPPORT_PAYPAL', '1', 'Do you want to accept PayPal payments?', '6', '3', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('PayPal ID', 'PAYPAL_ID', 'you@yourbuisness.com', 'Your buisness ID at PayPal.  Usually the email address you signed up with.  You can create a free PayPal account at http://www.paypal.com.', '6', '4', now())");
  } elseif ($payment_action == 'PM_REMOVE') {
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'PAYMENT_SUPPORT_PAYPAL'");
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'PAYPAL_ID'");
  }
?>