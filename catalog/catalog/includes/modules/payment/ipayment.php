<?
  class ipayment {
    var $code, $description, $enabled;

// class constructor
    function ipayment() {
      $this->code = 'ipayment';
      $this->description = MODULE_PAYMENT_IPAYMENT_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_IPAYMENT_STATUS;
    }

// class methods
    function javascript_validation() {
      return false;
    }

    function selection() {
      global $HTTP_POST_VARS, $HTTP_GET_VARS;

      if ($this->enabled) {
        $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_OWNER . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;<input type="text" name="cc_owner" value="' . $HTTP_GET_VARS['cc_owner'] . '">&nbsp;</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_NUMBER . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;<input type="text" name="cc_number" value="' . $HTTP_GET_VARS['cc_number'] . '">&nbsp;</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CHECKNUMBER . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;<input type="text" name="cc_checknumber" value="' . $HTTP_GET_VARS['cc_checknumber'] . '">&nbsp;</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;<select name="cc_expdate_month">';
        for ($i=1; $i < 13; $i++) {
          $selected = ($HTTP_GET_VARS['cc_expdate_month'] == $i) ? ' selected' : '';
          $selection_string .= '<option' . $selected . ' value="' . sprintf('%02d', $i) . '">' . strftime("%B",mktime(0,0,0,$i,1,2000)) . '</option>';
        }
        $selection_string .= '</select>&nbsp;/&nbsp;<select name="cc_expdate_year">';
        $today = getdate(); 
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
          $selected = ($HTTP_GET_VARS['cc_expdate_year'] == strftime("%y",mktime(0,0,0,1,1,$i))) ? ' selected' : '';
          $selection_string .= '<option' . $selected . ' value="' . strftime("%y",mktime(0,0,0,1,1,$i)) . '">' . strftime("%Y",mktime(0,0,0,1,1,$i)) . '</option>';
        }
        $selection_string .= '</select></td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";
        return $selection_string;
      }
    }

    function confirmation() {
    global $checkout_form_action;
      if ($this->enabled) {
        $checkout_form_action = 'https://ipayment.de/merchant/' . MODULE_PAYMENT_IPAYMENT_ID . '/processor.php3';
      }
    }

    function process_button() {
      global $HTTP_POST_VARS, $customer_id, $shipping_cost, $shipping_method, $total_cost, $currency_rates;
	$customer_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
	$customer_email_values = tep_db_fetch_array($customer_email);
    if ($this->enabled) {
        $ipayment_return = $HTTP_POST_VARS['payment'] . '&' . SID . '&cc_owner=' . urlencode($HTTP_POST_VARS['cc_owner']);

$process_button_string = '<input type="hidden" name="silent" value="true">' .
			  '<input type="hidden" name="cc_userid" value="' . MODULE_PAYMENT_IPAYMENT_USER_ID . '">' .
			  '<input type="hidden" name="item_name" value="' . STORE_NAME . '">' .
			  '<input type="hidden" name="cc_amount" value="' . number_format(($total_cost + $shipping_cost) * 100 * $currency_rates['EUR'], 0, '','') . '">' .
			  '<input type="hidden" name="cc_expdate_month" value="' . $HTTP_POST_VARS['cc_expdate_month'] . '">' .
			  '<input type="hidden" name="cc_expdate_year" value="' . $HTTP_POST_VARS['cc_expdate_year'] . '">' .
			  '<input type="hidden" name="cc_number" value="' . $HTTP_POST_VARS['cc_number'] . '">' .
			  '<input type="hidden" name="cc_checknumber" value="' . $HTTP_POST_VARS['cc_checknumber'] . '">' .
			  '<input type="hidden" name="cc_name" value="' . $HTTP_POST_VARS['cc_owner'] . '">' .
			  '<input type="hidden" name="cc_email" value="' . $customer_email_values['customers_email_address'] . '">' .
			  '<input type="hidden" name="redirect_action" value="GET">' .
			  '<input type="hidden" name="cc_currency" value="EUR">' .
			  '<input type="hidden" name="redirect_url" value="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_CHECKOUT_PROCESS . '">' .
			  '<input type="hidden" name="silent_error_url" value="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $ipayment_return, 'SSL') . '">' .
		          '<input type="hidden" name="' . tep_session_name() . '"" value="' . tep_session_id() . '">';
      }
        return $process_button_string;
    }

    function before_process() {
	  return false;
    }

    function after_process() {
	  return false;
    }

    function output_error() {
      global $HTTP_GET_VARS;

      $output_error_string = '      <tr>' . "\n" .
                             '        <td class="main">' . IPAYMENT_ERROR_MESSAGE . '<br><font color="#ff0000"><b>' . urldecode($HTTP_GET_VARS['cc_errormsg']) . '<br>';
      if ($HTTP_GET_VARS['cc_additional']) {
        $output_error_string .= '(' . urldecode($HTTP_GET_VARS['cc_additional']) . ')<br>';
      }
      $output_error_string .= IPAYMENT_ERROR_MESSAGE2 . '</b></font><br><br></td>' . "\n" .
                              '     </tr>' . "\n";

      return $output_error_string;
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IPAYMENT_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow iPayment', 'MODULE_PAYMENT_IPAYMENT_STATUS', '1', 'Do you want to accept iPayment payments?', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iPayment Account No.', 'MODULE_PAYMENT_IPAYMENT_ID', '99999', 'Your Account No. at iPayment.', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iPayment User ID', 'MODULE_PAYMENT_IPAYMENT_USER_ID', '99999', 'Your User ID at iPayment.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IPAYMENT_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IPAYMENT_ID'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IPAYMENT_USER_ID'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_IPAYMENT_STATUS', 'MODULE_PAYMENT_IPAYMENT_ID', 'MODULE_PAYMENT_IPAYMENT_USER_ID');

      return $keys;
    }
  }
?>
