<?php
/*
  $Id: paypal.php,v 1.20 2001/08/25 16:35:21 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class paypal {
    var $code, $title, $description, $enabled;

// class constructor
    function paypal() {
      $this->code = 'paypal';
      $this->title = MODULE_PAYMENT_PAYPAL_TEXT_TITLE;
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

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
	  global $checkout_form_action;

      if ($this->enabled) {
        $checkout_form_action = 'https://secure.paypal.com/cgi-bin/webscr';
      }
    }

    function process_button() {
      global $HTTP_POST_VARS, $shipping_selected, $shipping_cost, $shipping_method, $comments, $total_cost, $total_tax, $currency_rates;

	  if ($this->enabled) {
        $paypal_return = 'payment=' . $HTTP_POST_VARS['payment'] . '&sendto=' . $HTTP_POST_VARS['sendto'] . '&shipping_selected=' . $shipping_selected . '&shipping_cost=' . $shipping_cost . '&shipping_method=' . urlencode($shipping_method) . '&comments=' . urlencode($comments);
        $paypal_cancel_return = 'payment=' . $HTTP_POST_VARS['payment'] . '&sendto=' . $HTTP_POST_VARS['sendto'] . '&shipping_selected=' . $shipping_selected . '&comments=' . urlencode($comments);

        $process_button_string = tep_draw_hidden_field('cmd', '_xclick') .
                                 tep_draw_hidden_field('business', MODULE_PAYMENT_PAYPAL_ID) .
                                 tep_draw_hidden_field('item_name', STORE_NAME) .
                                 tep_draw_hidden_field('amount', number_format(($total_cost + $total_tax) * $currency_rates['USD'], 2)) .
                                 tep_draw_hidden_field('shipping', number_format($shipping_cost * $currency_rates['USD'], 2)) .
                                 tep_draw_hidden_field('return', tep_href_link(FILENAME_CHECKOUT_PROCESS, $paypal_return, 'SSL')) .
                                 tep_draw_hidden_field('cancel_return', tep_href_link(FILENAME_CHECKOUT_PAYMENT, $paypal_cancel_return, 'SSL'));
      }

      return $process_button_string;
    }

    function before_process() {
      global $HTTP_GET_VARS, $payment, $sendto, $shipping_cost, $shipping_method, $comments;

      if ($this->enabled) {
        $payment = $HTTP_GET_VARS['payment'];
        $sendto = $HTTP_GET_VARS['sendto'];
        $shipping_cost = $HTTP_GET_VARS['shipping_cost'];
        $shipping_method = urldecode($HTTP_GET_VARS['shipping_method']);
        $comments = urldecode($HTTP_GET_VARS['comments']);
      }
    }

    function after_process() {
	  return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow PayPal', 'MODULE_PAYMENT_PAYPAL_STATUS', '1', 'Do you want to accept PayPal payments?', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal ID', 'MODULE_PAYMENT_PAYPAL_ID', 'you@yourbuisness.com', 'Your buisness ID at PayPal.  Usually the email address you signed up with.  You can create a free PayPal account at http://www.paypal.com.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_ID'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_PAYPAL_STATUS', 'MODULE_PAYMENT_PAYPAL_ID');

      return $keys;
    }
  }
?>
