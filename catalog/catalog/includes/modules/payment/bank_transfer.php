<?
  define('TEXT_BANK_TRANSFER', 'Bank Transfer');
  define('TEXT_BANK_TRANSFER_ADDITIONAL', 'Transfer the money to: 293840293840924');

  $payment_code = 'bank';
  $payment_description = TEXT_BANK_TRANSFER;
  $payment_enabled = PAYMENT_SUPPORT_BANK_TRANSFER;

  if ($payment_action == 'PM_SELECTION' && $payment_enabled) {
    echo '<font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_BANK_TRANSFER_ADDITIONAL . '&nbsp;</font>';
  } elseif ($payment_action == 'PM_AFTER_PROCESS' &&  $payment_enabled) {
    header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')); 
  } elseif ($payment_action == 'PM_CHECK') {
    $check = tep_db_query("select configuration_value from configuration where configuration_key = 'PAYMENT_SUPPORT_BANK_TRANSFER'");
    $check = tep_db_num_rows($check) + 1;
  } elseif ($payment_action == 'PM_INSTALL') {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allow Bank Transfers', 'PAYMENT_SUPPORT_BANK_TRANSFER', '1', 'Do you want to accept bank transfers?', '6', '3', now())");
  } elseif ($payment_action == 'PM_REMOVE') {
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'PAYMENT_SUPPORT_BANK_TRANSFER'");
  }
?>