<?
  class paypal {
    var $code, $description, $enabled;

// class constructor
    function paypal() {
      $this->code = 'paypal';
      $this->description = MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_PAYPAL_STATUS;
    }

// class methods
    function javascript_validation() {
      return false;
    }

    function selection() {
      return false;
    }

    function confirmation() {
      global $HTTP_POST_VARS, $paypal_return, $checkout_form_action, $shipping_cost, $shipping_method, $comments, $total_cost, $total_tax, $currency_rates;

      if ($this->enabled) {
        $paypal_return = urlencode($HTTP_POST_VARS['payment'] . '|' . $HTTP_POST_VARS['sendto'] . '|' . $shipping_cost . '|' . urlencode($shipping_method) . '|' . urlencode($comments) . '&' . SID);
        $checkout_form_action = 'https://secure.paypal.com/cgi-bin/webscr?cmd=_xclick&business=' . rawurlencode(MODULE_PAYMENT_PAYPAL_ID) . '&item_name=' . rawurlencode(STORE_NAME) . '&amount=' . number_format(($total_cost + $total_tax) * $currency_rates['USD'], 2) . '&shipping=' . number_format($shipping_cost * $currency_rates['USD'], 2) . '&return=' . urlencode(HTTP_SERVER . DIR_WS_CATALOG . FILENAME_CHECKOUT_PROCESS . '?paypal_return=' . $paypal_return);
      }
    }

    function process_button() {
      return false;
    }

    function before_process() {
      global $HTTP_GET_VARS, $payment, $sendto, $shipping_cost, $shipping_method, $comments;

      if ( ($HTTP_GET_VARS['paypal_return']) && ($this->enabled) ) {
        $arg = urldecode($HTTP_GET_VARS['paypal_return']);
        $args = explode('|', $arg);
        $payment = $args[0];
        $sendto = $args[1];
        $shipping_cost = $args[2];
        $shipping_method = $args[3];
        $comments = $args[4];
      }
    }

    function after_process() {
      if ($this->enabled) {
        header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from configuration where configuration_key = 'MODULE_PAYMENT_PAYPAL_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow PayPal', 'MODULE_PAYMENT_PAYPAL_STATUS', '1', 'Do you want to accept PayPal payments?', '6', '3', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal ID', 'MODULE_PAYMENT_PAYPAL_ID', 'you@yourbuisness.com', 'Your buisness ID at PayPal.  Usually the email address you signed up with.  You can create a free PayPal account at http://www.paypal.com.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key = 'MODULE_PAYMENT_PAYPAL_STATUS'");
      tep_db_query("delete from configuration where configuration_key = 'MODULE_PAYMENT_PAYPAL_ID'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_PAYPAL_STATUS', 'MODULE_PAYMENT_PAYPAL_ID');

      return $keys;
    }
  }
?>
