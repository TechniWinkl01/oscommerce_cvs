<?php
/*
  $Id: paypal.php,v 1.35 2002/11/25 18:23:15 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class paypal {
    var $code, $title, $description, $enabled;

// class constructor
    function paypal() {
      $this->code = 'paypal';
      $this->title = MODULE_PAYMENT_PAYPAL_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_PAYMENT_PAYPAL_STATUS == 'True') ? true : false);

      $this->form_action_url = 'https://secure.paypal.com/cgi-bin/webscr';
    }

// class methods
    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      global $order, $currencies, $currency;

      if (MODULE_PAYMENT_PAYPAL_CURRENCY == 'Selected Currency') {
        $my_currency = $currency;
      } else {
        $my_currency = substr(MODULE_PAYMENT_PAYPAL_CURRENCY, 5);
      }
      if (!in_array($my_currency, array('CAD', 'EUR', 'GBP', 'JPY', 'USD'))) {
        $my_currency = 'USD';
      }
      $process_button_string = tep_draw_hidden_field('cmd', '_xclick') .
                               tep_draw_hidden_field('business', MODULE_PAYMENT_PAYPAL_ID) .
                               tep_draw_hidden_field('item_name', STORE_NAME) .
                               tep_draw_hidden_field('amount', number_format(($order->info['total'] - $order->info['shipping_cost']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency))) .
                               tep_draw_hidden_field('shipping', number_format($order->info['shipping_cost'] * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency))) .
                               tep_draw_hidden_field('currency_code', $my_currency) .
                               tep_draw_hidden_field('return', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL')) .
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
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayPal Module', 'MODULE_PAYMENT_PAYPAL_STATUS', 'True', 'Do you want to accept PayPal payments?', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-Mail Address', 'MODULE_PAYMENT_PAYPAL_ID', 'you@yourbusiness.com', 'The e-mail address to use for the PayPal service', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_PAYPAL_CURRENCY', 'Selected Currency', 'The currency to use for credit card transactions', '6', '6', 'tep_cfg_select_option(array(\'Selected Currency\',\'Only USD\',\'Only CAD\',\'Only EUR\',\'Only GBP\',\'Only JPY\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYPAL_STATUS', 'MODULE_PAYMENT_PAYPAL_ID', 'MODULE_PAYMENT_PAYPAL_CURRENCY');
    }
  }
?>
