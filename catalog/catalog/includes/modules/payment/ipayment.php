<?php
/*
  $Id: ipayment.php,v 1.21 2002/04/19 14:28:25 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class ipayment {
    var $code, $title, $description, $enabled;

// class constructor
    function ipayment() {
      $this->code = 'ipayment';
      $this->title = MODULE_PAYMENT_IPAYMENT_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_IPAYMENT_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_IPAYMENT_STATUS;
    }

// class methods
    function javascript_validation() {
      return false;
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
                          '    <td class="main">&nbsp;' . MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_OWNER . '&nbsp;</td>' . "\n" .
                          '    <td class="main">&nbsp;' . tep_draw_input_field('ipayment_cc_owner') . '&nbsp;</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_NUMBER . '&nbsp;</td>' . "\n" .
                          '    <td class="main">&nbsp;' . tep_draw_input_field('ipayment_cc_number') . '&nbsp;</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CHECKNUMBER . '&nbsp;</td>' . "\n" .
                          '    <td class="main">&nbsp;' . tep_draw_input_field('ipayment_cc_checknumber') . '&nbsp;</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;</td>' . "\n" .
                          '    <td class="main">&nbsp;' . tep_draw_pull_down_menu('ipayment_cc_expires_month', $expires_month) . '&nbsp;/&nbsp;' . tep_draw_pull_down_menu('ipayment_cc_expires_year', $expires_year) . '</td>' . "\n" .
                           '  </tr>' . "\n" .
                           '</table>' . "\n";

      return $selection_string;
    }

    function pre_confirmation_check() {
      global $payment, $HTTP_POST_VARS;

      include(DIR_WS_FUNCTIONS . 'ccval.php');

      $cc_val = OnlyNumericSolution($HTTP_POST_VARS['ipayment_cc_number']);
      $cc_val = CCValidationSolution($cc_val);
      if ($cc_val == '1') $cc_val = ValidateExpiry($HTTP_POST_VARS['ipayment_cc_expires_month'], $HTTP_POST_VARS['ipayment_cc_expires_year']);

      if ($cc_val != '1') {
        $payment_error_return = 'payment_error=' . $payment . '&cc_owner=' . urlencode($HTTP_POST_VARS['ipayment_cc_owner']) . '&cc_checknumber=' . $HTTP_POST_VARS['ipayment_cc_checknumber'] . '&cc_expires_month=' . $HTTP_POST_VARS['ipayment_cc_expires_month'] . '&cc_expires_year=' . $HTTP_POST_VARS['ipayment_cc_expires_year'] . '&shipping_selected=' . $HTTP_POST_VARS['shipping_selected'] . '&cc_val=' . urlencode($cc_val);
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }
    }

    function confirmation() {
      global $checkout_form_action;

      $checkout_form_action = 'https://ipayment.de/merchant/' . MODULE_PAYMENT_IPAYMENT_ID . '/processor.php3';
    }

    function process_button() {
      global $payment, $HTTP_POST_VARS, $order, $currencies;

      $process_button_string = tep_draw_hidden_field('silent', 'true') .
                               tep_draw_hidden_field('cc_userid', MODULE_PAYMENT_IPAYMENT_USER_ID) .
                               tep_draw_hidden_field('item_name', STORE_NAME) .
                               tep_draw_hidden_field('cc_amount', number_format($order->info['total'] * 100 * $currencies->get_value(MODULE_PAYMENT_IPAYMENT_CURRENCY), 0, '','')) .
                               tep_draw_hidden_field('cc_expdate_month', $HTTP_POST_VARS['ipayment_cc_expires_month']) .
                               tep_draw_hidden_field('cc_expdate_year', $HTTP_POST_VARS['ipayment_cc_expires_year']) .
                               tep_draw_hidden_field('cc_number', $HTTP_POST_VARS['ipayment_cc_number']) .
                               tep_draw_hidden_field('cc_checknumber', $HTTP_POST_VARS['ipayment_cc_checknumber']) .
                               tep_draw_hidden_field('cc_name', $HTTP_POST_VARS['ipayment_cc_owner']) .
                               tep_draw_hidden_field('cc_email', $order->customer['email_address']) .
                               tep_draw_hidden_field('redirect_action', 'GET') .
                               tep_draw_hidden_field('cc_currency', MODULE_PAYMENT_IPAYMENT_CURRENCY) .
                               tep_draw_hidden_field('redirect_url', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', true)) .
                               tep_draw_hidden_field('silent_error_url', tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $payment . '&cc_owner=' . urlencode($HTTP_POST_VARS['ipayment_cc_owner']), 'SSL', true));

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

      if ($HTTP_GET_VARS['cc_errormsg']) {
        $cc_errormsg = urldecode($HTTP_GET_VARS['cc_errormsg']);
      } elseif ($HTTP_GET_VARS['cc_val']) {
        $cc_errormsg = stripslashes($HTTP_GET_VARS['cc_val']);
      }

      $output_error_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">' . IPAYMENT_ERROR_MESSAGE . '<br><font color="#ff0000"><b>' . $cc_errormsg . '<br>';
      if ($HTTP_GET_VARS['cc_additional']) {
        $output_error_string .= '(' . urldecode($HTTP_GET_VARS['cc_additional']) . ')<br>';
      }
      $output_error_string .= IPAYMENT_ERROR_MESSAGE2 . '</b></font></td>' . "\n" .
                              '  </tr>' . "\n" .
                              '</table>' . "\n";

      return $output_error_string;
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IPAYMENT_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }
      return $this->check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow iPayment', 'MODULE_PAYMENT_IPAYMENT_STATUS', '1', 'Do you want to accept iPayment payments?', '6', '1', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iPayment Account No.', 'MODULE_PAYMENT_IPAYMENT_ID', '99999', 'Your Account No. at iPayment.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iPayment User ID', 'MODULE_PAYMENT_IPAYMENT_USER_ID', '99999', 'Your User ID at iPayment.', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('iPayment Currency', 'MODULE_PAYMENT_IPAYMENT_CURRENCY', 'EUR', 'iPayment can charge in EUR, DEM, or USD.', '6', '4', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IPAYMENT_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IPAYMENT_ID'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IPAYMENT_USER_ID'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_IPAYMENT_CURRENCY'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_IPAYMENT_STATUS', 'MODULE_PAYMENT_IPAYMENT_ID', 'MODULE_PAYMENT_IPAYMENT_USER_ID', 'MODULE_PAYMENT_IPAYMENT_CURRENCY');

      return $keys;
    }
  }
?>
