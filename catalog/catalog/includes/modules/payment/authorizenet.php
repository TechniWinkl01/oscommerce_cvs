<?php
/*
  $Id: authorizenet.php,v 1.16 2001/08/25 12:00:14 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class authorizenet {
    var $code, $title, $description, $enabled;

// class constructor
    function authorizenet() {
      $this->code = 'authorizenet';
      $this->title = MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE;
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
        for ($i=1; $i < 13; $i++) {
          $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
        }

        $today = getdate(); 
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
          $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;' . tep_draw_input_field('cc_number') . '&nbsp;</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;' . tep_draw_pull_down_menu('cc_expires_month', $expires_month) . '&nbsp;/&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $expires_year) . '</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '</table>' . "\n";
        return $selection_string;
      }
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

      if ($this->enabled) {
        include(DIR_WS_FUNCTIONS . 'ccval.php');

        $cc_val = OnlyNumericSolution($HTTP_POST_VARS['cc_number']);
        $cc_val = CCValidationSolution($cc_val);

        if ($cc_val != '1') {
          $payment_error_return = 'payment_error=' . $HTTP_POST_VARS['payment'] . '&cc_expires_month=' . $HTTP_POST_VARS['cc_expires_month'] . '&cc_expires_year=' . $HTTP_POST_VARS['cc_expires_year'] . '&shipping_selected=' . $HTTP_POST_VARS['shipping_selected'] . '&cc_val=' . urlencode($cc_val) . '&comments=' . urlencode($HTTP_POST_VARS['comments']);
          header('Location: ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL'));
        }
      }
    }

    function confirmation() {
      global $HTTP_POST_VARS, $CardName, $CardNumber, $checkout_form_action, $checkout_form_submit;

      if ($this->enabled) {
        $confirmation_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                               '  <tr>' . "\n" .
                               '    <td class="main">&nbsp;' . TEXT_TYPE . '&nbsp;' . $CardName . '&nbsp;</td>' . "\n" .
                               '  </tr>' . "\n" .
                               '  <tr>' . "\n" .
                               '    <td class="main">&nbsp;' . TEXT_NUMBER . '&nbsp;' . $CardNumber . '&nbsp;</td>' . "\n" .
                               '  </tr>' . "\n" .
                               '  <tr>' . "\n" .
                               '    <td class="main">&nbsp;' . TEXT_EXPIRES . '&nbsp;' . strftime('%B/%Y', mktime(0,0,0,$HTTP_POST_VARS['cc_expires_month'], 1, '20' . $HTTP_POST_VARS['cc_expires_year'])) . '&nbsp;</td>' . "\n" .
                               '  </tr>' . "\n" .
                               '</table>' . "\n";

        $checkout_form_action = 'https://www.authorize.net/gateway/transact.dll';

        return $confirmation_string;
      }
    }

    function process_button() {
      global $HTTP_POST_VARS, $CardNumber, $total_cost, $total_tax, $shipping_cost;

      if ($this->enabled) {
        $process_button_string = tep_draw_hidden_field('x_Login', 'testing') .
                                 tep_draw_hidden_field('x_Card_Num', $CardNumber) .
                                 tep_draw_hidden_field('x_Exp_Date', $HTTP_POST_VARS['cc_expires_month'] . $HTTP_POST_VARS['cc_expires_year']) .
                                 tep_draw_hidden_field('x_Amount', number_format($total_cost + $total_tax + $shipping_cost, 2)) .
                                 tep_draw_hidden_field('x_ADC_Relay_Response', 'TRUE') .
                                 tep_draw_hidden_field('x_ADC_URL', HTTP_SERVER . DIR_WS_CATALOG . FILENAME_CHECKOUT_PROCESS) .
                                 tep_draw_hidden_field('x_Version', '3.0') .
                                 tep_draw_hidden_field(tep_session_name(), tep_session_id());

        return $process_button_string;
      }
    }

    function before_process() {
      global $payment, $x_response_code;

      if ( ($payment == $this->code) && ($x_response_code != '1') ) {
        Header('Location: ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE), 'SSL'));
        tep_exit();
      }

    }

    function after_process() {
	  return false;
    }

    function output_error() {
      global $HTTP_GET_VARS;

      $output_error_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;<font color="#FF0000"><b>' . MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR . '</b></font><br>&nbsp;' . stripslashes($HTTP_GET_VARS['cc_val']) . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";

      return $output_error_string;
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Authorize.net', 'MODULE_PAYMENT_AUTHORIZENET_STATUS', '1', 'Do you want to accept Authorize.net payments?', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Authorize.net Login', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'testing', 'Login used for Authorize.net payments', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Authorize.net Test Mode', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', '1', 'Test mode for Authorize.net payments', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_LOGIN'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_AUTHORIZENET_STATUS', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE');

      return $keys;
    }
  }
?>
