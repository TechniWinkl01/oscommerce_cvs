<?php
/*
  $Id: pm2checkout.php,v 1.5 2002/03/07 18:25:38 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class pm2checkout {
    var $code, $title, $description, $enabled;

// class constructor
    function pm2checkout() {
      $this->code = 'pm2checkout';
      $this->title = MODULE_PAYMENT_2CHECKOUT_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_2CHECKOUT_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_2CHECKOUT_STATUS;
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
                          '    <td class="main">&nbsp;' . tep_draw_pull_down_menu('pm_2checkout_cc_expires_month', $expires_month) . '&nbsp;/&nbsp;' . tep_draw_pull_down_menu('pm_2checkout_cc_expires_year', $expires_year) . '</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }

    function pre_confirmation_check() {
      global $payment, $HTTP_POST_VARS;

      include(DIR_WS_FUNCTIONS . 'ccval.php');

      $cc_val = OnlyNumericSolution($HTTP_POST_VARS['pm_2checkout_cc_number']);
      $cc_val = CCValidationSolution($cc_val);
      if ($cc_val == '1') $cc_val = ValidateExpiry($HTTP_POST_VARS['pm_2checkout_cc_expires_month'], $HTTP_POST_VARS['pm_2checkout_cc_expires_year']);

      if ($cc_val != '1') {
        $payment_error_return = 'payment_error=' . $payment . '&pm_2checkout_cc_expires_month=' . $HTTP_POST_VARS['pm_2checkout_cc_expires_month'] . '&pm_2checkout_cc_expires_year=' . $HTTP_POST_VARS['pm_2checkout_cc_expires_year'] . '&shipping_selected=' . $HTTP_POST_VARS['shipping_selected'] . '&cc_val=' . urlencode($cc_val);
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }
    }

    function confirmation() {
      global $HTTP_POST_VARS, $CardName, $CardNumber, $checkout_form_action;

      $confirmation_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . MODULE_PAYMENT_2CHECKOUT_TEXT_TYPE . '&nbsp;' . $CardName . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_NUMBER . '&nbsp;' . $CardNumber . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;' . strftime('%B/%Y', mktime(0,0,0,$HTTP_POST_VARS['pm_2checkout_cc_expires_month'], 1, '20' . $HTTP_POST_VARS['pm_2checkout_cc_expires_year'])) . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";

      $checkout_form_action = 'https://www.2checkout.com/cgi-bin/Abuyers/purchase.2c';

      return $confirmation_string;
    }

    function process_button() {
      global $HTTP_POST_VARS, $HTTP_SERVER_VARS, $CardNumber, $total_cost, $total_tax, $shipping_cost, $customer_id, $sendto;

      $customer_query = tep_db_query("select c.customers_firstname, c.customers_lastname, c.customers_telephone, c.customers_email_address, ab.entry_street_address, ab.entry_city, ab.entry_country_id, ab.entry_zone_id, ab.entry_state, ab.entry_postcode from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " ab on c.customers_default_address_id = ab.address_book_id and c.customers_id = ab.customers_id where c.customers_id = '" . $customer_id . "'");
      $customer_values = tep_db_fetch_array($customer_query);
      $delivery_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_street_address, ab.entry_city, ab.entry_country_id, ab.entry_zone_id, ab.entry_state, ab.entry_postcode from " . TABLE_ADDRESS_BOOK . " ab where ab.address_book_id = '" . $sendto . "' and ab.customers_id = '" . $customer_id . "'");
      $delivery_values = tep_db_fetch_array($delivery_query);

      $process_button_string = tep_draw_hidden_field('x_login', MODULE_PAYMENT_2CHECKOUT_LOGIN) .
                               tep_draw_hidden_field('x_amount', number_format($total_cost + $total_tax + $shipping_cost, 2)) .
                               tep_draw_hidden_field('x_invoice_num', date('YmdHis')) .
                               tep_draw_hidden_field('x_test_request', MODULE_PAYMENT_2CHECKOUT_TESTMODE) .
                               tep_draw_hidden_field('x_card_num', $CardNumber) .
                               tep_draw_hidden_field('x_exp_date', $HTTP_POST_VARS['pm_2checkout_cc_expires_month'] . $HTTP_POST_VARS['pm_2checkout_cc_expires_year']) .
                               tep_draw_hidden_field('x_first_name', $customer_values['customers_firstname']) .
                               tep_draw_hidden_field('x_last_name', $customer_values['customers_lastname']) .
                               tep_draw_hidden_field('x_address', $customer_values['entry_street_address']) .
                               tep_draw_hidden_field('x_city', $customer_values['entry_city']) .
                               tep_draw_hidden_field('x_state', tep_get_zone_name($customer_values['entry_country_id'], $customer_values['entry_zone_id'], $customer_values['entry_state'])) .
                               tep_draw_hidden_field('x_zip', $customer_values['entry_postcode']) .
                               tep_draw_hidden_field('x_country', tep_get_country_name($customer_values['entry_country_id'])) .
                               tep_draw_hidden_field('x_email', $customer_values['customers_email_address']) .
                               tep_draw_hidden_field('x_phone', $customer_values['customers_telephone']) .
                               tep_draw_hidden_field('x_ship_to_first_name', $delivery_values['entry_firstname']) .
                               tep_draw_hidden_field('x_ship_to_last_name', $delivery_values['entry_lastname']) .
                               tep_draw_hidden_field('x_ship_to_address', $delivery_values['entry_street_address']) .
                               tep_draw_hidden_field('x_ship_to_city', $delivery_values['entry_city']) .
                               tep_draw_hidden_field('x_ship_to_state', tep_get_zone_name($delivery_values['entry_country_id'], $delivery_values['entry_zone_id'], $delivery_values['entry_state'])) .
                               tep_draw_hidden_field('x_ship_to_zip', $delivery_values['entry_postcode']) .
                               tep_draw_hidden_field('x_ship_to_country', tep_get_country_name($delivery_values['entry_country_id'])) .
                               tep_draw_hidden_field('x_receipt_link_url', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false)) .
                               tep_draw_hidden_field('x_email_merchant', MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT) .
                               tep_draw_hidden_field(tep_session_name(), tep_session_id());

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
