<?
  class cc {
    var $code, $description, $enabled;

// class constructor
    function cc() {
      $this->code = 'cc';
      $this->description = MODULE_PAYMENT_CC_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_CC_STATUS;
    }

// class methods
    function javascript_validation() {
      if ($this->enabled) {
        $validation_string = 'if (payment_value == "' . $this->code . '") {' . "\n" .
                             '  var cc_owner = document.payment.cc_owner.value;' . "\n" .
                             '  var cc_number = document.payment.cc_number.value;' . "\n" .
                             '  if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
                             '    error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER . '";' . "\n" .
                             '    error = 1;' . "\n" .
                             '  }' . "\n" .
                             '  if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
                             '    error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER . '";' . "\n" .
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
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;<input type="text" name="cc_owner" value="' . $HTTP_POST_VARS['cc_owner'] . '">&nbsp;</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;<input type="text" name="cc_number">&nbsp;</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;<select name="cc_expires_month">';
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

        $confirmation_string = '          <tr>' . "\n" .
                               '            <td class="main">&nbsp;' . TEXT_OWNER . '&nbsp;' . $HTTP_POST_VARS['cc_owner'] . '&nbsp;</td>' . "\n" .
                               '          </tr>' . "\n";

        if ($cc_val == '1') {
          $confirmation_string .= '          <tr>' . "\n" .
                                  '            <td class="main">&nbsp;' . TEXT_TYPE . '&nbsp;' . $CardName . '&nbsp;</td>' . "\n" .
                                  '          </tr>' . "\n" .
                                  '          <tr>' . "\n" .
                                  '            <td class="main">&nbsp;' . TEXT_NUMBER . '&nbsp;' . $CardNumber . '&nbsp;</td>' . "\n" .
                                  '          </tr>' . "\n";
        }

        $confirmation_string .= '          <tr>' . "\n" .
                                '            <td class="main">&nbsp;' . TEXT_EXPIRES . '&nbsp;' . strftime('%B/%Y', mktime(0,0,0,$HTTP_POST_VARS['cc_expires_month'], 1, '20' . $HTTP_POST_VARS['cc_expires_year'])) . '&nbsp;</td>' . "\n" .
                                '          </tr>' . "\n";

        if ($cc_val != '1') {
          $confirmation_string .= '          <tr>' . "\n" .
                                  '            <td class="main">&nbsp;<font color="#FF0000"><b>' . TEXT_VAL . '</b></font><br>&nbsp;' . $cc_val . '&nbsp;</td>' . "\n" .
                                  '          </tr>' . "\n";
        }

        if ($cc_val != '1') {
          $checkout_form_action = tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL');
          $checkout_form_submit = tep_image_submit('button_back.gif', IMAGE_BUTTON_BACK) . '&nbsp;' . "\n";
        }

        return $confirmation_string;
      }
    }

    function process_button() {
      global $HTTP_POST_VARS, $CardName, $CardNumber, $cc_val;

      if ($this->enabled) {
        $process_button_string = '<input type="hidden" name="cc_owner" value="' . $HTTP_POST_VARS['cc_owner'] . '">' .
                                 '<input type="hidden" name="cc_expires" value="' . $HTTP_POST_VARS['cc_expires_month'] . $HTTP_POST_VARS['cc_expires_year'] . '">';

        if ($cc_val == '1') {
          $process_button_string .= '<input type="hidden" name="cc_type" value="' . $CardName . '">' .
                                    '<input type="hidden" name="cc_number" value="' . $CardNumber . '">';
        } else {
          $process_button_string .= '<input type="hidden" name="cc_expires_month" value="' . $HTTP_POST_VARS['cc_expires_month'] . '">' .
                                    '<input type="hidden" name="cc_expires_year" value="' . $HTTP_POST_VARS['cc_expires_year'] . '">';
        }

        return $process_button_string;
      }
    }

    function before_process() {
      global $HTTP_POST_VARS, $cc_number, $cc_middle;

      if ($this->enabled) {
        $cc_number = $HTTP_POST_VARS['cc_number'];
        if ( (defined('MODULE_PAYMENT_CC_EMAIL')) && (MODULE_PAYMENT_CC_EMAIL != 'NONE') ) {
          $len = strlen($cc_number);
          $new_cc = substr($cc_number, 0, 4) . substr('XXXXXXXXXXXXXXXX', 0, $len-8) . substr($cc_number, -4);
          $cc_middle = substr($cc_number, 4, $len-8);
          $cc_number = $new_cc;
        }
      }
    }

    function after_process() {
      global $insert_id, $cc_middle, $message;
      if ($this->enabled) {
        if ( (defined('MODULE_PAYMENT_CC_EMAIL')) && (MODULE_PAYMENT_CC_EMAIL != 'NONE') ) { // send emails to other people
          $message = "Order #" . $insert_id . "\nMiddle " . $cc_middle . "\n";
          tep_mail('', '', MODULE_PAYMENT_CC_EMAIL, "Extra Order Info", $message, '', EMAIL_FROM, '');
        }
      }
    }

    function output_error() {
      return false;
    }

    function check() {
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CC_STATUS'");
      $check = tep_db_num_rows($check_query);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Credit Card', 'MODULE_PAYMENT_CC_STATUS', '1', 'Do you want to accept credit card payments?', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Credit Card TP email address', 'MODULE_PAYMENT_CC_EMAIL', 'NONE', 'If this email address is not NONE then the middle digits of any stored cc numbers will be X-ed out and emailed with the order id.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CC_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CC_EMAIL'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_CC_STATUS', 'MODULE_PAYMENT_CC_EMAIL');

      return $keys;
    }
  }
?>