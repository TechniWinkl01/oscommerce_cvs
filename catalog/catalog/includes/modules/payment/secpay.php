<?php
/*
  $Id: secpay.php,v 1.7 2001/08/25 12:00:15 hpdl Exp $

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
      if ($this->enabled) {
        $checkout_form_action = 'https://www.secpay.com/java-bin/ValCard?';
      }
    }

    function process_button() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method, $comments, $total_cost, $total_tax, $currency_rates, $customer_id, $sendto;

	  if ($this->enabled) {
        $process_button_string = tep_draw_hidden_field('merchant', MODULE_PAYMENT_SECPAY_ID) .
                                 tep_draw_hidden_field('trans_id', STORE_NAME) .
                                 tep_draw_hidden_field('amount', $total_cost + $total_tax + $shipping_cost) .
                                 tep_draw_hidden_field('callback', HTTP_SERVER . DIR_WS_CATALOG . FILENAME_CHECKOUT_PROCESS . '?' . tep_session_name() . '=' . tep_session_id() . '&customer_id=' . $customer_id . '&sendto=' . $sendto . '&shipping_cost=' . $shipping_cost . '&shipping_method=' . $shipping_method) .
                                 tep_draw_hidden_field('cb_flds', 'customer_id:sendto:payment:shipping_cost:shipping_method:' . tep_session_name()) .
                                 tep_draw_hidden_field('session', tep_session_id) .
                                 tep_draw_hidden_field('options', 'test_status=false,dups=false,cb_post=true');
      }

      return $process_button_string;
    }

    function before_process() {
      global $HTTP_POST_VARS, $payment, $sendto, $shipping_cost, $shipping_method, $comments, $customer_id;

      $remote_host = getenv('REMOTE_HOST'); // get the ip number of the user
      if ($payment == $this->code) { 
        if ( ($remote_host != 'secpay.com') || ($HTTP_POST_VARS['valid'] != 'true') ) {
          header('Location: ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, tep_session_name() . '=' . $HTTP_POST_VARS['session'] . '&error_message=' . urlencode(MODULE_PAYMENT_SECPAY_TEXT_ERROR_MESSAGE), 'SSL'));
          tep_exit();
        } elseif ( ($remote_host = 'secpay.com') && ($HTTP_POST_VARS['valid'] = 'true') ) {
          $customer_id = $HTTP_POST_VARS['customer_id'];
          $sendto = $HTTP_POST_VARS['sendto'];
        }
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
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Secpay', 'MODULE_PAYMENT_SECPAY_STATUS', '1', 'Do you want to accept SECPay payments?', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secpay ID', 'MODULE_PAYMENT_SECPAY_ID', 'test', 'Your Merchant ID from SECPay.', '6', '6', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SECPAY_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SECPAY_ID'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_SECPAY_STATUS', 'MODULE_PAYMENT_SECPAY_ID');

      return $keys;
    }
  }
?>
