<?
  class authorizenet {
    var $code, $description, $enabled;

// class constructor
    function authorizenet() {
      $this->code = 'authorizenet';
      $this->description = MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_AUTHORIZENET_STATUS;
    }

// class methods
    function javascript_validation() {
      if ($this->enabled) {
        $validation_string = 'if (payment_value == "' . $this->code . '") {' . "\n" .
                             '  var cc_number = document.payment.cc_number.value;' . "\n" .
                             '  if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
                             '    error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER . '";' . "\n" .
                             '    error = 1;' . "\n" .
                             '  }' . "\n" .
                             '}' . "\n";
        return $validation_string;
      }
    }

    function selection() {
      global $HTTP_POST_VARS;

      if ($this->enabled) {
        $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main" nowrap>&nbsp;' . MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER . '&nbsp;</td>' . "\n" .
                            '    <td class="main" nowrap>&nbsp;<input type="text" name="cc_number">&nbsp;</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main" nowrap>&nbsp;' . MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;</td>' . "\n" .
                            '    <td class="main" nowrap>&nbsp;<select name="cc_expires_month">';
        for ($i=1; $i < 13; $i++) {
          $selected = ($HTTP_POST_VARS['cc_expires_month'] == $i) ? ' selected' : '';
          $selection_string .= '<option' . $selected . ' value="' . sprintf('%02d', $i) . '">' . strftime("%B",mktime(0,0,0,$i,1,2000)) . '</option>';
        }
        $selection_string .= '</select>&nbsp;/&nbsp;<select name="cc_expires_year">';
        $today = getdate(); 
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
          $selected = ($HTTP_POST_VARS['cc_expires_year'] == strftime("%y",mktime(0,0,0,1,1,$i))) ? ' selected' : '';
          $selection_string .= '<option' . $selected . ' value="' . strftime("%y",mktime(0,0,0,1,1,$i)) . '">' . strftime("%Y",mktime(0,0,0,1,1,$i)) . '</option>';
        }
        $selection_string .= '</select></td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";
        return $selection_string;
      }
    }

    function confirmation() {
      global $HTTP_POST_VARS, $cc_val, $CardName, $CardNumber, $checkout_form_action, $checkout_form_submit;

      if ($this->enabled) {
        $include_file = DIR_WS_FUNCTIONS . 'ccval.php'; include(DIR_WS_INCLUDES . 'include_once.php');

        $cc_val = OnlyNumericSolution($HTTP_POST_VARS['cc_number']);
        $cc_val = CCValidationSolution($cc_val);

        if ($cc_val == '1') {
          $confirmation_string .= '          <tr>' . "\n" .
                                  '            <td class="main" nowrap>&nbsp;' . TEXT_TYPE . '&nbsp;' . $CardName . '&nbsp;</td>' . "\n" .
                                  '          </tr>' . "\n" .
                                  '          <tr>' . "\n" .
                                  '            <td class="main" nowrap>&nbsp;' . TEXT_NUMBER . '&nbsp;' . $CardNumber . '&nbsp;</td>' . "\n" .
                                  '          </tr>' . "\n";
        }

        $confirmation_string .= '          <tr>' . "\n" .
                                '            <td class="main" nowrap>&nbsp;' . TEXT_EXPIRES . '&nbsp;' . strftime('%B/%Y', mktime(0,0,0,$HTTP_POST_VARS['cc_expires_month'], 1, '20' . $HTTP_POST_VARS['cc_expires_year'])) . '&nbsp;</td>' . "\n" .
                                '          </tr>' . "\n";

        if ($cc_val != '1') {
          $confirmation_string .= '          <tr>' . "\n" .
                                  '            <td class="main">&nbsp;<font color="#FF0000"><b>' . TEXT_VAL . '</b></font><br>&nbsp;' . $cc_val . '&nbsp;</td>' . "\n" .
                                  '          </tr>' . "\n";
        }

        if ($cc_val != '1') {
          $checkout_form_action = tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');
          $checkout_form_submit = tep_image_submit(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK) . '&nbsp;' . "\n";
        } else {
          $checkout_form_action = 'https://www.authorize.net/gateway/transact.dll';
        }

        return $confirmation_string;
      }
    }

    function process_button() {
      global $HTTP_POST_VARS, $CardNumber, $cc_val, $total_cost, $total_tax, $shipping_cost;

      if ($this->enabled) {

        if ($cc_val == '1') {
          $process_button_string = '<input type="hidden" name="x_Login" value="testing">' .
                                   '<input type="hidden" name="x_Card_Num" value="' . $CardNumber . '">' .
                                   '<input type="hidden" name="x_Exp_Date" value="' . $HTTP_POST_VARS['cc_expires_month'] . '-' . $HTTP_POST_VARS['cc_expires_year'] . '">' .
                                   '<input type="hidden" name="x_Amount" value="' . number_format($total_cost + $total_tax + $shipping_cost, 2) . '">' .
                                   '<input type="hidden" name="x_ADC_Relay_Response" value="TRUE">' .
                                   '<input type="hidden" name="x_ADC_URL" value="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_CHECKOUT_PROCESS . '">' .
                                   '<input type="hidden" name="x_Version" value="3.0">' .
                                   '<input type="hidden" name="' . tep_session_name() . '" value="' . tep_session_id() . '">';
        } else {
          $process_button_string = '<input type="hidden" name="cc_owner" value="' . $HTTP_POST_VARS['cc_owner'] . '">' .
                                   '<input type="hidden" name="cc_expires" value="' . $HTTP_POST_VARS['cc_expires_month'] . $HTTP_POST_VARS['cc_expires_year'] . '">' .
                                   '<input type="hidden" name="cc_expires_month" value="' . $HTTP_POST_VARS['cc_expires_month'] . '">' .
                                   '<input type="hidden" name="cc_expires_year" value="' . $HTTP_POST_VARS['cc_expires_year'] . '">';
        }

        return $process_button_string;
      }
    }

    function before_process() {
      global $payment, $x_response_code;

      if ( ($payment == $this->code) && ($x_response_code != "1") ) {
        Header('Location: ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE), 'SSL'));
        tep_exit();
      }

    }

    function after_process() {
      if ($this->enabled) {
        header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from configuration where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Authorize.net', 'MODULE_PAYMENT_AUTHORIZENET_STATUS', '1', 'Do you want to accept Authorize.net payments?', '6', '0', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Authorize.net Login', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'testing', 'Login used for Authorize.net payments', '6', '0', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Authorize.net Test Mode', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', '1', 'Test mode for Authorize.net payments', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_STATUS'");
      tep_db_query("delete from configuration where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_LOGIN'");
      tep_db_query("delete from configuration where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_AUTHORIZENET_STATUS', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE');

      return $keys;
    }
  }
?>
