<?php
/*
  $Id: nochex.php,v 1.4 2002/08/13 16:00:42 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

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
      global $HTTP_POST_VARS, $order, $currencies, $customer_id;

      $process_button_string = tep_draw_hidden_field('cmd', '_xclick') .
                               tep_draw_hidden_field('email', MODULE_PAYMENT_NOCHEX_ID) .
                               tep_draw_hidden_field('amount', number_format($order->info['total'] * $currencies->currencies['GBP']['value'], 2)) .
                               tep_draw_hidden_field('ordernumber', $customer_id . '-' . date('Ymdhis')) .
                               tep_draw_hidden_field('returnurl', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL')) .
                               tep_draw_hidden_field('cancel_return', tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

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
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_NOCHEX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
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
