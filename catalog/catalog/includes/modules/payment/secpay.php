<?php
/*
  $Id: secpay.php,v 1.10 2001/08/29 23:34:24 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class secpay {
    var $code, $title, $description, $enabled;

// class constructor
    function secpay() {
      $this->code = 'secpay';
      $this->title = MODULE_PAYMENT_SECPAY_TEXT_TITLE;
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

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
	  global $checkout_form_action;

      $checkout_form_action = 'https://www.secpay.com/java-bin/ValCard?';
    }

    function process_button() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method, $comments, $total_cost, $total_tax, $currency_rates;

      $process_button_string = tep_draw_hidden_field('merchant', MODULE_PAYMENT_SECPAY_MERCHANT_ID) .
                               tep_draw_hidden_field('trans_id', STORE_NAME . date('Ymdhis')) .
                               tep_draw_hidden_field('amount', number_format(($total_cost + $total_tax + $shipping_cost) * $currency_rates[MODULE_PAYMENT_SECPAY_CURRENCY], 2)) .
                               tep_draw_hidden_field('currency', MODULE_PAYMENT_SECPAY_CURRENCY) .
                               tep_draw_hidden_field('callback', tep_href_link(FILENAME_CHECKOUT_PROCESS, 'sendto=' . $HTTP_POST_VARS['sendto'] . '&shipping_cost=' . $shipping_cost . '&shipping_method=' . urlencode($shipping_method)), true) .
                               tep_draw_hidden_field(tep_session_name(), tep_session_id()) .
                               tep_draw_hidden_field('options', 'test_status=' . MODULE_PAYMENT_SECPAY_TEST_STATUS . ',dups=false,cb_post=true,cb_flds=sendto:payment:shipping_cost:shipping_method:' . tep_session_name());

      return $process_button_string;
    }

    function before_process() {
      global $HTTP_POST_VARS;

      $remote_host = getenv('REMOTE_HOST');
      if ( ($remote_host != 'secpay.com') && ($HTTP_POST_VARS['valid'] == 'true') ) {
        $remote_host = gethostbyaddr($remote_host);
      }
      if ( ($remote_host != 'secpay.com') || ($HTTP_POST_VARS['valid'] != 'true') ) {
        header('Location: ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, tep_session_name() . '=' . $HTTP_POST_VARS['session'] . '&error_message=' . urlencode(MODULE_PAYMENT_SECPAY_TEXT_ERROR_MESSAGE), 'SSL'));
        tep_exit();
      }
    }

    function after_process() {
	  return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SECPAY_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow SECpay', 'MODULE_PAYMENT_SECPAY_STATUS', '1', 'Do you want to accept SECPay payments?', '6', '1', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('SECPay ID', 'MODULE_PAYMENT_SECPAY_ID', 'secpay-99874296', 'Your SECPay ID.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant ID', 'MODULE_PAYMENT_SECPAY_MERCHANT_ID', 'secpay', 'Your Merchant ID.', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('SECPay Currency', 'MODULE_PAYMENT_SECPAY_CURRENCY', 'GBP', 'The currency SECPay should charge in.', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('SECPay Test Status', 'MODULE_PAYMENT_SECPAY_TEST_STATUS', 'true', 'true/false/live', '6', '5', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SECPAY_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SECPAY_ID'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SECPAY_MERCHANT_ID'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SECPAY_CURRENCY'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SECPAY_TEST_STATUS'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_SECPAY_STATUS', 'MODULE_PAYMENT_SECPAY_ID', 'MODULE_PAYMENT_SECPAY_MERCHANT_ID', 'MODULE_PAYMENT_SECPAY_CURRENCY', 'MODULE_PAYMENT_SECPAY_TEST_STATUS');

      return $keys;
    }
  }
?>
