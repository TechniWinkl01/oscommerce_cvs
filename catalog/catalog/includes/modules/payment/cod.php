<?
  class cod {
    var $code, $title, $description, $enabled;

// class constructor
    function cod() {
      $this->code = 'cod';
      $this->title = MODULE_PAYMENT_COD_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_COD_TEXT_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_COD_STATUS;
    }

// class methods
    function javascript_validation() {
      return false;
    }

    function selection() {
      return false;
    }

    function confirmation() {
      return false;
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
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_COD_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Cash On Delivery (COD)', 'MODULE_PAYMENT_COD_STATUS', '1', 'Do you want to accept COD (Cash On Delevery) payments?', '6', '1', now());");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_COD_STATUS'");
    }

    function keys() {
      $keys = array('MODULE_PAYMENT_COD_STATUS');

      return $keys;
    }
  }
?>