<?php
/*
  $Id: pm2checkout.php,v 1.11 2002/07/27 13:39:28 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class pm2checkout {
    var $code, $title, $description, $enabled;
    var $cc_number, $cc_expires_month, $cc_expires_year;

// class constructor
    function pm2checkout() {
      global $HTTP_POST_VARS;

      $this->code = 'pm2checkout';
      $this->title = MODULE_PAYMENT_2CHECKOUT_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_2CHECKOUT_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_2CHECKOUT_STATUS;
      $this->cc_number = tep_db_prepare_input($HTTP_POST_VARS['pm_2checkout_cc_number']);
      $this->cc_expires_month = tep_db_prepare_input($HTTP_POST_VARS['pm_2checkout_cc_expires_month']);
      $this->cc_expires_year = tep_db_prepare_input($HTTP_POST_VARS['pm_2checkout_cc_expires_year']);
    }

// class methods
    function javascript_validation() {
      $validation_string = 'if (payment_value == "' . $this->code . '") {' . "\n" .
                           '  var pm_2checkout_cc_number = document.payment.pm_2checkout_cc_number.value;' . "\n" .
                           '  if (pm_2checkout_cc_number == "" || pm_2checkout_cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
                           '    error_message = error_message + "' . MODULE_PAYMENT_2CHECKOUT_TEXT_JS_CC_NUMBER . '";' . "\n" .
                           '    error = 1;' . "\n" .
                           '  }' . "\n" .
                           '}' . "\n";
      return $validation_string;
    }

    function selection() {
      for ($i=1; $i < 13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate(); 
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_NUMBER . '&nbsp;</td>' . "\n" .
                          '    <td class="main">&nbsp;' . tep_draw_input_field('pm_2checkout_cc_number') . '&nbsp;</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;</td>' . "\n" .
                          '    <td class="main">&nbsp;' . tep_draw_pull_down_menu('pm_2checkout_cc_expires_month', $expires_month, date('m')) . '&nbsp;/&nbsp;' . tep_draw_pull_down_menu('pm_2checkout_cc_expires_year', $expires_year) . '</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }

    function pre_confirmation_check() {

      include(DIR_WS_FUNCTIONS . 'ccval.php');

      $cc_val = CCValidationSolution($this->cc_number);
      if ($cc_val == '1') {
        $cc_val = ValidateExpiry($this->cc_expires_month, $this->cc_expires_year);
      }
      if ($cc_val != '1') {
        $payment_error_return = 'payment_error=' . $this->code . '&pm_2checkout_cc_expires_month=' . $this->cc_expires_month . '&pm_2checkout_cc_expires_year=' . $this->cc_expires_year . '&cc_val=' . urlencode($cc_val);
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }
    }

    function confirmation() {
      global $CardName, $CardNumber, $checkout_form_action;

      $confirmation_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . MODULE_PAYMENT_2CHECKOUT_TEXT_TYPE . '&nbsp;' . $CardName . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_NUMBER . '&nbsp;' . $CardNumber . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;' . strftime('%B/%Y', mktime(0,0,0,$this->cc_expires_month, 1, '20' . $this->cc_expires_year)) . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";

      $checkout_form_action = 'https://www.2checkout.com/cgi-bin/Abuyers/purchase.2c';

      return $confirmation_string;
    }

    function process_button() {
      global $CardNumber, $order;

      $process_button_string = tep_draw_hidden_field('x_login', MODULE_PAYMENT_2CHECKOUT_LOGIN) .
                               tep_draw_hidden_field('x_amount', number_format($order->info['total'], 2)) .
                               tep_draw_hidden_field('x_invoice_num', date('YmdHis')) .
                               tep_draw_hidden_field('x_test_request', MODULE_PAYMENT_2CHECKOUT_TESTMODE) .
                               tep_draw_hidden_field('x_card_num', $CardNumber) .
                               tep_draw_hidden_field('x_exp_date', $this->cc_expires_month . $this->cc_expires_year) .
                               tep_draw_hidden_field('x_first_name', $order->customer['firstname']) .
                               tep_draw_hidden_field('x_last_name', $order->customer['lastname']) .
                               tep_draw_hidden_field('x_address', $order->customer['street_address']) .
                               tep_draw_hidden_field('x_city', $order->customer['city']) .
                               tep_draw_hidden_field('x_state', $order->customer['state']) .
                               tep_draw_hidden_field('x_zip', $order->customer['postcode']) .
                               tep_draw_hidden_field('x_country', $order->customer['country']['title']) .
                               tep_draw_hidden_field('x_email', $order->customer['email_address']) .
                               tep_draw_hidden_field('x_phone', $order->customer['telephone']) .
                               tep_draw_hidden_field('x_ship_to_first_name', $order->delivery['firstname']) .
                               tep_draw_hidden_field('x_ship_to_last_name', $order->delivery['lastname']) .
                               tep_draw_hidden_field('x_ship_to_address', $order->delivery['street_address']) .
                               tep_draw_hidden_field('x_ship_to_city', $order->delivery['city']) .
                               tep_draw_hidden_field('x_ship_to_state', $order->delivery['state']) .
                               tep_draw_hidden_field('x_ship_to_zip', $order->delivery['postcode']) .
                               tep_draw_hidden_field('x_ship_to_country', $order->delivery['country']['title']) .
                               tep_draw_hidden_field('x_receipt_link_url', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL')) .
                               tep_draw_hidden_field('x_email_merchant', MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT);

      return $process_button_string;
    }

    function before_process() {
      global $HTTP_POST_VARS;

      if ($HTTP_POST_VARS['x_response_code'] != '1') {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_2CHECKOUT_TEXT_ERROR_MESSAGE), 'SSL', true, false));
      }
    }

    function after_process() {
  	  return false;
    }

    function output_error() {
      global $HTTP_GET_VARS;

      $output_error_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;<font color="#FF0000"><b>' . MODULE_PAYMENT_2CHECKOUT_TEXT_ERROR . '</b></font><br>&nbsp;' . stripslashes($HTTP_GET_VARS['cc_val']) . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";

      return $output_error_string;
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_2CHECKOUT_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }
      return $this->check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Allow 2CheckOut', 'MODULE_PAYMENT_2CHECKOUT_STATUS', '1', 'Do you want to accept 2CheckOut payments?', '6', '0', 'tep_cfg_select_option(array(\'1\', \'0\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('2CheckOut Login/Store Number', 'MODULE_PAYMENT_2CHECKOUT_LOGIN', '18157', 'Login/Store Number used for 2CheckOut payments', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('2CheckOut Test Mode', 'MODULE_PAYMENT_2CHECKOUT_TESTMODE', 'Y', 'Test mode for 2CheckOut payments (Y/N)', '6', '0', 'tep_cfg_select_option(array(\'Y\', \'N\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Email Merchant Every Order', 'MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT', 'FALSE', 'Email the merchant on every order made', '6', '0', 'tep_cfg_select_option(array(\'TRUE\', \'FALSE\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_2CHECKOUT_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_2CHECKOUT_LOGIN'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_2CHECKOUT_TESTMODE'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT'");
    }

    function keys() {
      return array('MODULE_PAYMENT_2CHECKOUT_STATUS', 'MODULE_PAYMENT_2CHECKOUT_LOGIN', 'MODULE_PAYMENT_2CHECKOUT_TESTMODE', 'MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT');
    }
  }
?>
