<?
  class secpay {
    var $code, $description, $enabled;

// class constructor
    function secpay() {
      $this->code = 'secpay';
      $this->description = MODULE_PAYMENT_SECPAY_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_SECPAY_STATUS;
    }

// class methods
    function javascript_validation() {
      return false;
    }

    function selection() {
      return false;
    }

    function confirmation() {
	  global $checkout_form_action;
      if ($this->enabled) {
        $checkout_form_action = 'https://www.secpay.com/java-bin/ValCard?';
      }
    }

    function process_button() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method, $comments, $total_cost, $total_tax, $currency_rates, $customer_id, $sendto;

	  if ($this-->enabled) {
           $SID = tep_session_id();
?>
	  <input type="hidden" name="merchant" value="<? echo MODULE_PAYMENT_SECPAY_ID; ?>">
	  <input type="hidden" name="trans_id" value="<? echo STORE_NAME; ?>">
	  <input type="hidden" name="amount" value="<? echo $total_cost + $total_tax + $shipping_cost; ?>">
	  <input type="hidden" name="callback" value="<? echo HTTP_SERVER . DIR_WS_CATALOG . FILENAME_CHECKOUT_PROCESS . '?' . 'PHPSESSID=' . $SID . '&customer_id=' . $customer_id . '&sendto=' . $sendto . '&shipping_cost=' . $shipping_cost . '&shipping_method=' . $shipping_method; ?>">
	  <input type="hidden" name="cb_flds" value="customer_id:sendto:payment:shipping_cost:shipping_method:PHPSESSID">
	  <input type="hidden" name="session" value="<? echo $SID;?>">
	  <input type="hidden" name="options" value="test_status=false,dups=false,cb_post=true">
<?
      }
      return false;
    }

    function before_process() {
      global $HTTP_POST_VARS, $payment, $sendto, $shipping_cost, $shipping_method, $comments, $customer_id;
      $remote_host = getenv ("REMOTE_HOST"); // get the ip number of the user
      if ($payment == $this->code) { 
        if ( ($remote_host != "secpay.com") OR ($HTTP_POST_VARS['valid'] != "true") ) {
          Header('Location: ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'PHPSESSID=' . $HTTP_POST_VARS['session'] . '&error_message=' . urlencode(MODULE_PAYMENT_SECPAY_TEXT_ERROR_MESSAGE), 'SSL'));
          tep_exit();
        }
        elseif ( ($remote_host = "secpay.com") && ($HTTP_POST_VARS['valid'] = "true") ) {
          $customer_id=$HTTP_POST_VARS['customer_id'];
          $sendto=$HTTP_POST_VARS['sendto'];
        }
      }
    }

    function after_process() {
	  return false;
    }

    function check() {
      $check = tep_db_query("select configuration_value from configuration where configuration_key = 'MODULE_PAYMENT_SECPAY_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Secpay', 'MODULE_PAYMENT_SECPAY_STATUS', '1', 'Do you want to accept Worldpay payments?', '6', '5', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secpay ID', 'MODULE_PAYMENT_SECPAY_ID', 'test', 'Your Merchant ID from Secpay.', '6', '6', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key = 'MODULE_PAYMENT_SECPAY_STATUS'");
      tep_db_query("delete from configuration where configuration_key = 'MODULE_PAYMENT_SECPAY_ID'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_SECPAY_STATUS', 'MODULE_PAYMENT_SECPAY_ID');

      return $keys;
    }
  }
?>
