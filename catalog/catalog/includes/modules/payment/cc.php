<?php
/*
  $Id: cc.php,v 1.44 2002/11/01 05:02:28 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class cc {
    var $code, $title, $description, $enabled;

// class constructor
    function cc() {
      $this->code = 'cc';
      $this->title = MODULE_PAYMENT_CC_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_CC_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_PAYMENT_CC_STATUS == 'True') ? true : false);
    }

// class methods
    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    function selection() {
      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate(); 
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('cc_owner')),
                                           array('title' => MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('cc_number')),
                                           array('title' => MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('cc_expires_year', $expires_year))));

      return $selection;
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

      include(DIR_WS_CLASSES . 'cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($HTTP_POST_VARS['cc_number'], $HTTP_POST_VARS['cc_expires_month'], $HTTP_POST_VARS['cc_expires_year']);

      $error = '';
      switch ($result) {
        case -1:
          $error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc_validation->cc_number, 0, 4));
          break;
        case -2:
        case -3:
        case -4:
          $error = TEXT_CCVAL_ERROR_INVALID_DATE;
          break;
        case false:
          $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
          break;
      }

      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&cc_owner=' . urlencode($HTTP_POST_VARS['cc_owner']) . '&cc_expires_month=' . $HTTP_POST_VARS['cc_expires_month'] . '&cc_expires_year=' . $HTTP_POST_VARS['cc_expires_year'];

        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
    }

    function confirmation() {
      global $HTTP_POST_VARS;

      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $HTTP_POST_VARS['cc_owner']),
                                              array('title' => MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$HTTP_POST_VARS['cc_expires_month'], 1, '20' . $HTTP_POST_VARS['cc_expires_year'])))));

      return $confirmation;
    }

    function process_button() {
      global $HTTP_POST_VARS;

      $process_button_string = tep_draw_hidden_field('cc_owner', $HTTP_POST_VARS['cc_owner']) .
                               tep_draw_hidden_field('cc_expires', $HTTP_POST_VARS['cc_expires_month'] . $HTTP_POST_VARS['cc_expires_year']) .
                               tep_draw_hidden_field('cc_type', $this->cc_card_type) .
                               tep_draw_hidden_field('cc_number', $this->cc_card_number);

      return $process_button_string;
    }

    function before_process() {
      global $HTTP_POST_VARS;

      if ( (defined('MODULE_PAYMENT_CC_EMAIL')) && (tep_validate_email(MODULE_PAYMENT_CC_EMAIL)) ) {
        $len = strlen($HTTP_POST_VARS['cc_number']);

        $GLOBALS['cc_middle'] = substr($HTTP_POST_VARS['cc_number'], 4, ($len-8));
        $GLOBALS['cc_number'] = substr($HTTP_POST_VARS['cc_number'], 0, 4) . str_repeat('X', (strlen($HTTP_POST_VARS['cc_number']) - 8)) . substr($HTTP_POST_VARS['cc_number'], -4);
      }
    }

    function after_process() {
      global $insert_id;

      if ( (defined('MODULE_PAYMENT_CC_EMAIL')) && (tep_validate_email(MODULE_PAYMENT_CC_EMAIL)) ) {
        $message = 'Order #' . $insert_id . "\n\n" . 'Middle: ' . $GLOBALS['cc_middle'] . "\n\n";
        
        tep_mail('', MODULE_PAYMENT_CC_EMAIL, 'Extra Order Info: #' . $insert_id, $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
    }

    function get_error() {
      global $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_CC_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CC_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Credit Card Module', 'MODULE_PAYMENT_CC_STATUS', 'True', 'Do you want to accept credit card payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Split Credit Card E-Mail Address', 'MODULE_PAYMENT_CC_EMAIL', '', 'If an e-mail address is entered, the middle digits of the credit card number will be sent to the e-mail address (the outside digits are stored in the database with the middle digits censored)', '6', '0', now())");
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
      return array('MODULE_PAYMENT_CC_STATUS', 'MODULE_PAYMENT_CC_EMAIL');
    }
  }
?>