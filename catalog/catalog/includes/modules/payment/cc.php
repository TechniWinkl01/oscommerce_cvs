<?php
/*
  $Id: cc.php,v 1.30 2001/08/31 21:37:35 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class cc {
    var $code, $title, $description, $enabled;

// class constructor
    function cc() {
      $this->code = 'cc';
      $this->title = MODULE_PAYMENT_CC_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_CC_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_CC_STATUS;
    }

// class methods
    function javascript_validation() {
      $validation_string = 'if (payment_value == "' . $this->code . '") {' . "\n" .
                           '  var cc_owner = document.payment.cc_owner.value;' . "\n" .
                           '  var cc_number = document.payment.cc_number.value;' . "\n" .
                           '  if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
                           '    error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER . '";' . "\n" .
                           '    error = 1;' . "\n" .
                           '  }' . "\n" .
                           '  if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
                           '    error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER . '";' . "\n" .
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
                          '    <td class="main">&nbsp;' . MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER . '&nbsp;</td>' . "\n" .
                          '    <td class="main">&nbsp;' . tep_draw_input_field('cc_owner') . '&nbsp;</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER . '&nbsp;</td>' . "\n" .
                          '    <td class="main">&nbsp;' . tep_draw_input_field('cc_number') . '&nbsp;</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES . '&nbsp;</td>' . "\n" .
                          '    <td class="main">&nbsp;' . tep_draw_pull_down_menu('cc_expires_month', $expires_month) . '&nbsp;/&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $expires_year) . '</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

      include(DIR_WS_FUNCTIONS . 'ccval.php');

      $cc_val = OnlyNumericSolution($HTTP_POST_VARS['cc_number']);
      $cc_val = CCValidationSolution($cc_val);

      if ($cc_val != '1') {
        $payment_error_return = 'payment_error=' . $HTTP_POST_VARS['payment'] . '&cc_owner=' . urlencode($HTTP_POST_VARS['cc_owner']) . '&cc_expires_month=' . $HTTP_POST_VARS['cc_expires_month'] . '&cc_expires_year=' . $HTTP_POST_VARS['cc_expires_year'] . '&shipping_selected=' . $HTTP_POST_VARS['shipping_selected'] . '&cc_val=' . urlencode($cc_val) . '&comments=' . urlencode($HTTP_POST_VARS['comments']);
        header('Location: ' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL'));
        tep_exit();
      }
    }

    function confirmation() {
      global $HTTP_POST_VARS, $CardName, $CardNumber;

      $confirmation_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . TEXT_OWNER . '&nbsp;' . $HTTP_POST_VARS['cc_owner'] . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . TEXT_TYPE . '&nbsp;' . $CardName . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . TEXT_NUMBER . '&nbsp;' . $CardNumber . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;' . TEXT_EXPIRES . '&nbsp;' . strftime('%B/%Y', mktime(0,0,0,$HTTP_POST_VARS['cc_expires_month'], 1, '20' . $HTTP_POST_VARS['cc_expires_year'])) . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";

      return $confirmation_string;
    }

    function process_button() {
      global $HTTP_POST_VARS, $CardName, $CardNumber;

      $process_button_string = tep_draw_hidden_field('cc_owner', $HTTP_POST_VARS['cc_owner']) .
                               tep_draw_hidden_field('cc_expires', $HTTP_POST_VARS['cc_expires_month'] . $HTTP_POST_VARS['cc_expires_year']) .
                               tep_draw_hidden_field('cc_type', $CardName) .
                               tep_draw_hidden_field('cc_number', $CardNumber);

      return $process_button_string;
    }

    function before_process() {
      if ( (defined('MODULE_PAYMENT_CC_EMAIL')) && (MODULE_PAYMENT_CC_EMAIL != 'NONE') ) {
        $len = strlen($GLOBALS['cc_number']);
        $new_cc = substr($GLOBALS['cc_number'], 0, 4) . substr('XXXXXXXXXXXXXXXX', 0, $len-8) . substr($GLOBALS['cc_number'], -4);
        $GLOBALS['cc_middle'] = substr($GLOBALS['cc_number'], 4, $len-8);
        $GLOBALS['cc_number'] = $new_cc;
      }
    }

    function after_process() {
      global $insert_id;

      if ( (defined('MODULE_PAYMENT_CC_EMAIL')) && (MODULE_PAYMENT_CC_EMAIL != 'NONE') ) { // send emails to other people
        $message = 'Order #' . $insert_id . "\n\n" . 'Middle: ' . $GLOBALS['cc_middle'] . "\n\n";
        tep_mail('', MODULE_PAYMENT_CC_EMAIL, 'Extra Order Info', $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
      }
    }

    function output_error() {
      global $HTTP_GET_VARS;

      $output_error_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">&nbsp;<font color="#FF0000"><b>' . MODULE_PAYMENT_CC_TEXT_ERROR . '</b></font><br>&nbsp;' . stripslashes($HTTP_GET_VARS['cc_val']) . '&nbsp;</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";

      return $output_error_string;
    }

    function check() {
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CC_STATUS'");
      $check = tep_db_num_rows($check_query);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Credit Card', 'MODULE_PAYMENT_CC_STATUS', '1', 'Do you want to accept credit card payments?', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Credit Card TP email address', 'MODULE_PAYMENT_CC_EMAIL', 'NONE', 'If this email address is not NONE then the middle digits of any stored cc numbers will be X-ed out and emailed with the order id.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CC_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CC_EMAIL'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_CC_STATUS', 'MODULE_PAYMENT_CC_EMAIL');

      return $keys;
    }
  }
?>