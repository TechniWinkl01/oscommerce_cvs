<?
  class cod {
    var $payment_code, $payment_description, $payment_enabled;

// class constructor
    function cod() {
      $this->payment_code = 'cod';
      $this->payment_description = TEXT_CASH_ON_DELIVERY;
      $this->payment_enabled = PAYMENT_SUPPORT_COD;
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
      if ($this->payment_enabled) {
        header('Location: ' . tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from configuration where configuration_key = 'PAYMENT_SUPPORT_COD'");
      $check = tep_db_num_rows($check) + 1;

      return $check;
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Allow Cash On Delivery (COD)', 'PAYMENT_SUPPORT_COD', '1', 'Do you want to accept COD (Cash On Delevery) payments?', '6', '1', now());");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key = 'PAYMENT_SUPPORT_COD'");
    }

    function keys() {
      $keys = array('PAYMENT_SUPPORT_COD');

      return $keys;
    }
  }
?>