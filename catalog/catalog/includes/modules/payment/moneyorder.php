<?php

  class moneyorder {
    var $code, $title, $description, $enabled;

// class constructor
    function moneyorder() {
      $this->code = 'moneyorder';
      $this->title = MODULE_PAYMENT_MONEYORDER_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION;
      $this->email_footer = MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER;
      $this->enabled = MODULE_PAYMENT_MONEYORDER_STATUS;
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
      $confirmation_string = '          <tr>' . "\n" .
                             '            <td class="main">&nbsp;' . MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION . '&nbsp;</td>' . "\n" .
                             '          </tr>' . "\n";
      return $confirmation_string;
    }

    function process_button() {
      return false;
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
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_MONEYORDER_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Check/Money Order', 'MODULE_PAYMENT_MONEYORDER_STATUS', '1', 'Do you want to accept Check and Money Order payments?', '6', '1', now());");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_MONEYORDER_STATUS'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_MONEYORDER_STATUS');

      return $keys;
    }
  }
?>
