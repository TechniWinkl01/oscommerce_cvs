<?php
/*
  $Id: nochex.php,v 1.1 2002/02/11 08:22:50 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License

  Garry Blackmore gazonice@yahoo.com
  Thanks to Mark Keith Evans for assistance.
*/

  class nochex {
    var $code, $title, $description, $enabled;

// class constructor
    function nochex() {
      $this->code = 'nochex';
      $this->title = MODULE_PAYMENT_NOCHEX_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_NOCHEX_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_NOCHEX_STATUS;
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

      $checkout_form_action = 'https://www.nochex.com/nochex.dll/checkout';
    }

    function process_button() {
      global $HTTP_POST_VARS, $shipping_selected, $shipping_cost, $shipping_method, $total_cost, $total_tax, $currencies, $customer_id;

      $nochex_return = 'payment=' . $HTTP_POST_VARS['payment'] . '&shipping_selected=' . $shipping_selected . '&shipping_cost=' . $shipping_cost . '&shipping_method=' . urlencode($shipping_method);
      $nochex_cancel_return = 'payment=' . $HTTP_POST_VARS['payment'] . '&shipping_selected=' . $shipping_selected;

      $process_button_string = tep_draw_hidden_field('cmd', '_xclick') .
                               tep_draw_hidden_field('email', MODULE_PAYMENT_NOCHEX_ID) .
                               tep_draw_hidden_field('amount', number_format(($total_cost + $total_tax + $shipping_cost) * $currencies->currencies['GBP']['value'], 2)) .
                               tep_draw_hidden_field('ordernumber', $customer_id . '-' . date('Ymdhis')) .
                               tep_draw_hidden_field('returnurl', tep_href_link(FILENAME_CHECKOUT_PROCESS, $nochex_return, 'SSL')) .
                               tep_draw_hidden_field('cancel_return', tep_href_link(FILENAME_CHECKOUT_PAYMENT, $nochex_cancel_return, 'SSL'));

      return $process_button_string;
    }

    function before_process() {
      return false;
    }

    function after_process() {
	  return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NOCHEX_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow NOCHEX', 'MODULE_PAYMENT_NOCHEX_STATUS', '1', 'Do you want to accept NOCHEX payments?', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('NOCHEX ID', 'MODULE_PAYMENT_NOCHEX_ID', 'you@yourbuisness.com', 'Your business ID at NOCHEX.  Usually the e-mail address you signed up with.  You can create a free NOCHEX account at http://www.nochex.com.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NOCHEX_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NOCHEX_ID'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_NOCHEX_STATUS', 'MODULE_PAYMENT_NOCHEX_ID');

      return $keys;
    }
  }
?>
