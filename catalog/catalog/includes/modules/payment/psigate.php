<?php
/*
  $Id: psigate.php,v 1.3 2002/04/05 01:12:40 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class psigate {
    var $code, $title, $description, $enabled;

// class constructor
    function psigate() {
      $this->code = 'psigate';
      $this->title = MODULE_PAYMENT_PSIGATE_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PSIGATE_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_PSIGATE_STATUS;
    }

// class methods
    function javascript_validation() {
      $validation_string = '';

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'local') {
        $validation_string = 'if (payment_value == "' . $this->code . '") {' . "\n" .
                             '  var psigate_cc_owner = document.payment.psigate_cc_owner.value;' . "\n" .
                             '  var psigate_cc_number = document.payment.psigate_cc_number.value;' . "\n" .
                             '  if (psigate_cc_owner == "" || psigate_cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
                             '    error_message = error_message + "' . MODULE_PAYMENT_PSIGATE_TEXT_JS_CC_OWNER . '";' . "\n" .
                             '    error = 1;' . "\n" .
                             '  }' . "\n" .
                             '  if (psigate_cc_number == "" || psigate_cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
                             '    error_message = error_message + "' . MODULE_PAYMENT_PSIGATE_TEXT_JS_CC_NUMBER . '";' . "\n" .
                             '    error = 1;' . "\n" .
                             '  }' . "\n" .
                             '}' . "\n";
      }

      return $validation_string;
    }

    function selection() {
      global $customer_id;

      $selection_string = '';

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'local') {
        $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
        $customer = tep_db_fetch_array($customer_query);

        for ($i=1; $i < 13; $i++) {
          $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
        }

        $today = getdate(); 
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
          $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_OWNER . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;' . tep_draw_input_field('psigate_cc_owner', $customer['customers_firstname'] . ' ' . $customer['customers_lastname']) . '&nbsp;</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_NUMBER . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;' . tep_draw_input_field('psigate_cc_number') . '&nbsp;</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '  <tr>' . "\n" .
                            '    <td class="main">&nbsp;' . MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;</td>' . "\n" .
                            '    <td class="main">&nbsp;' . tep_draw_pull_down_menu('psigate_cc_expires_month', $expires_month) . '&nbsp;/&nbsp;' . tep_draw_pull_down_menu('psigate_cc_expires_year', $expires_year) . '</td>' . "\n" .
                            '  </tr>' . "\n" .
                            '</table>' . "\n";

      }

      return $selection_string;
    }

    function pre_confirmation_check() {
      global $payment, $HTTP_POST_VARS;

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'local') {
        include(DIR_WS_FUNCTIONS . 'ccval.php');

        $cc_val = OnlyNumericSolution($HTTP_POST_VARS['psigate_cc_number']);
        $cc_val = CCValidationSolution($cc_val);
        if ($cc_val == '1') $cc_val = ValidateExpiry($HTTP_POST_VARS['psigate_cc_expires_month'], $HTTP_POST_VARS['psigate_cc_expires_year']);

        if ($cc_val != '1') {
          $payment_error_return = 'payment_error=' . $payment . '&cc_expires_month=' . $HTTP_POST_VARS['psigate_cc_expires_month'] . '&cc_expires_year=' . $HTTP_POST_VARS['psigate_cc_expires_year'] . '&shipping_selected=' . $HTTP_POST_VARS['shipping_selected'] . '&cc_val=' . urlencode($cc_val);
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
        }
      }
    }

    function confirmation() {
      global $HTTP_POST_VARS, $CardName, $CardNumber, $checkout_form_action;

      $confirmation_string = '';

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'local') {
        $confirmation_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                               '  <tr>' . "\n" .
                               '    <td class="main">&nbsp;' . MODULE_PAYMENT_PSIGATE_TEXT_TYPE . '&nbsp;' . $CardName . '&nbsp;</td>' . "\n" .
                               '  </tr>' . "\n" .
                               '  <tr>' . "\n" .
                               '    <td class="main">&nbsp;' . MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_OWNER . '&nbsp;' . $HTTP_POST_VARS['psigate_cc_owner'] . '&nbsp;</td>' . "\n" .
                               '  </tr>' . "\n" .
                               '  <tr>' . "\n" .
                               '    <td class="main">&nbsp;' . MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_NUMBER . '&nbsp;' . $CardNumber . '&nbsp;</td>' . "\n" .
                               '  </tr>' . "\n" .
                               '  <tr>' . "\n" .
                               '    <td class="main">&nbsp;' . MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;' . strftime('%B/%Y', mktime(0,0,0,$HTTP_POST_VARS['psigate_cc_expires_month'], 1, '20' . $HTTP_POST_VARS['psigate_cc_expires_year'])) . '&nbsp;</td>' . "\n" .
                               '  </tr>' . "\n" .
                               '</table>' . "\n";

      }

      $checkout_form_action = 'https://order.psigate.com/psigate.asp';

      return $confirmation_string;
    }

    function process_button() {
      global $HTTP_POST_VARS, $HTTP_SERVER_VARS, $CardNumber, $order, $customer_id, $currencies;

      $countries_query = tep_db_query("select countries_iso_code_2 from " . TABLE_COUNTRIES . " where countries_name in ('" . $order->customer['country'] . "', '" . $order->delivery['country'] . "')");
      while ($countries = tep_db_fetch_array($countries_query)) {
        if (isset($Bcountry)) {
          $Scountry = $countries['countries_iso_code_2'];
        } else {
          $Bcountry = $countries['countries_iso_code_2'];
        }
      }
      if (!isset($Scountry)) $Scountry = $Bcountry;

      $process_button_string = tep_draw_hidden_field('MerchantID', MODULE_PAYMENT_PSIGATE_MERCHANT_ID) .
                               tep_draw_hidden_field('FullTotal', number_format($order->info['total'] * $currencies->get_value(MODULE_PAYMENT_PSIGATE_CURRENCY), 2)) .
                               tep_draw_hidden_field('ThanksURL', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', true)) . 
                               tep_draw_hidden_field('NoThanksURL', tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL', true)) . 
                               tep_draw_hidden_field('Bname', $order->customer['firstname'] . ' ' . $order->customer['lastname']) .
                               tep_draw_hidden_field('Baddr1', $order->customer['street_address']) .
                               tep_draw_hidden_field('Bcity', $order->customer['city']) .
                               tep_draw_hidden_field('Bstate', $order->customer['state']) .
                               tep_draw_hidden_field('Bzip', $order->customer['postcode']) .
                               tep_draw_hidden_field('Bcountry', $Bcountry) .
                               tep_draw_hidden_field('Phone', $order->customer['telephone']) .
                               tep_draw_hidden_field('Email', $order->customer['email_address']) .
                               tep_draw_hidden_field('Sname', $order->delivery['firstname'] . ' ' . $order->delivery['lastname']) .
                               tep_draw_hidden_field('Saddr1', $order->delivery['street_address']) .
                               tep_draw_hidden_field('Scity', $order->delivery['city']) .
                               tep_draw_hidden_field('Sstate', $order->delivery['state']) .
                               tep_draw_hidden_field('Szip', $order->delivery['postcode']) .
                               tep_draw_hidden_field('Scountry', $Scountry) .
                               tep_draw_hidden_field('ChargeType', MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE) .
                               tep_draw_hidden_field('Result', MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE) .
                               tep_draw_hidden_field('IP', $HTTP_SERVER_VARS['REMOTE_ADDR']);

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'local') {
        $process_button_string .= tep_draw_hidden_field('CardNumber', $CardNumber) .
                                  tep_draw_hidden_field('ExpMonth', $HTTP_POST_VARS['psigate_cc_expires_month']) .
                                  tep_draw_hidden_field('ExpYear', $HTTP_POST_VARS['psigate_cc_expires_year']);
      }

      return $process_button_string;
    }

    function before_process() {
      return false;
    }

    function after_process() {
	  return false;
    }

    function output_error() {
      global $HTTP_GET_VARS;

      $output_error_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;<font color="#FF0000"><b>' . MODULE_PAYMENT_PSIGATE_TEXT_ERROR . '</b></font><br>&nbsp;' . stripslashes($HTTP_GET_VARS['cc_val']) . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";

      return $output_error_string;
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PSIGATE_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }
      return $this->check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow PSiGate', 'MODULE_PAYMENT_PSIGATE_STATUS', '1', 'Do you want to accept PSiGate payments?', '6', '1', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant ID', 'MODULE_PAYMENT_PSIGATE_MERCHANT_ID', 'teststorewithcard', 'PSiGate merchant ID', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE', '0', '0 = Live (default)<br>1 = Always Good<br>2 = Always Duplicate<br>3 = Always Decline', '6', '3', 'tep_cfg_select_option(array(\'0\', \'1\', \'2\', \'3\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Type', 'MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE', '1', '0 = Sale<br>1 = PreAuth (default)<br>2 = PostAuth', '6', '4', 'tep_cfg_select_option(array(\'0\', \'1\', \'2\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Credit Card Input Location', 'MODULE_PAYMENT_PSIGATE_INPUT_MODE', 'local', 'Should the credit card details be entered at the locally secured server, or remotely at PSiGate\'s secure server?', '6', '5', 'tep_cfg_select_option(array(\'local\', \'remote\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Currency', 'MODULE_PAYMENT_PSIGATE_CURRENCY', 'CAD', 'The currency used to charge orders', '6', '6', 'tep_cfg_select_option(array(\'CAD\', \'USD\'), ', now())");
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }

    function keys() {
      return array('MODULE_PAYMENT_PSIGATE_STATUS', 'MODULE_PAYMENT_PSIGATE_MERCHANT_ID', 'MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE', 'MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE', 'MODULE_PAYMENT_PSIGATE_INPUT_MODE', 'MODULE_PAYMENT_PSIGATE_CURRENCY');
    }
  }
?>