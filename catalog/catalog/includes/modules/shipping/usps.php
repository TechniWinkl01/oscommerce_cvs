<?
  class usps {
    var $code, $description, $enabled;

// class constructor
    function usps() {
      $this->code = 'usps';
      $this->description = MODULE_SHIPPING_USPS_TEXT_DESCRIPTION;
      $this->enabled = MODULE_SHIPPING_USPS_STATUS;
    }

// class methods
    function select() {
      $select_string = '<TR><TD class="main">&nbsp;' . MODULE_SHIPPING_USPS_TEXT_DESCRIPTION . '</td>' .
                       '<td><select name="shipping_usps_prod">' .
                         '<option value="Parcel">' . MODULE_SHIPPING_USPS_TEXT_OPT_PP . '</option>' .
                         '<option value="Priority" SELECTED>' . MODULE_SHIPPING_USPS_TEXT_OPT_PM . '</option>' .
                         '<option value="Express">' . MODULE_SHIPPING_USPS_TEXT_OPT_EX . '</option>' .
                       '</select></td>' .
                       '<td align="right">&nbsp;<input type="checkbox" name="shipping_quote_usps" value="1" CHECKED></td></tr>' . "\n";

      return $select_string;
    }

    function quote() {
      global $HTTP_POST_VARS, $shipping_quote_usps, $shipping_quote_all, $address_values, $shipping_weight, $rate, $shipping_usps_cost, $shipping_usps_method, $shipping_num_boxes, $shipping_quoted;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_usps) ) {
        if (!$HTTP_POST_VARS['shipping_usps_prod']){
          $prod = 'priority';
        } else {
          $prod = $HTTP_POST_VARS['shipping_usps_prod'];
        }
        include(DIR_WS_CLASSES . '_usps.php');
        $rate = new _USPS;
        $rate->SetServer(MODULE_SHIPPING_USPS_SERVER);
        $rate->setUserName(MODULE_SHIPPING_USPS_USERID);
        $rate->setPass(MODULE_SHIPPING_USPS_PASSWORD);
        $rate->SetService($prod);
        $rate->setMachinable("False");
        $rate->SetOrigZip(STORE_ORIGIN_ZIP);
        $rate->SetDestZip($address_values['postcode']);
        $rate->setWeight($shipping_weight);
        $quote = $rate->getPrice();
        $shipping_usps_cost = SHIPPING_HANDLING + $quote;
        if ($prod != 'Parcel') {
          $shipping_usps_method = 'USPS ' . $prod . ' Mail';
        } else {
          $shipping_usps_method = 'USPS ' . $prod . ' Post';
        }
        $shipping_usps_method = $shipping_usps_method . ' ' . $shipping_num_boxes . ' X ' . $shipping_weight;
        if ($shipping_usps_cost == SHIPPING_HANDLING) {
          $shipping_usps_method = "USPS " . $quote;
        } else {
          $shipping_quoted = 'usps';
          $shipping_usps_cost = $shipping_usps_cost * $shipping_num_boxes;
        }
      }
    }

    function cheapest() {
      global $shipping_quote_usps, $shipping_quote_all, $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_usps_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_usps) ) {
        if ($shipping_count == 0) {
          $shipping_cheapest = 'usps';
          $shipping_cheapest_cost = $shipping_usps_cost;
        } else {
          if ($shipping_usps_cost < $shipping_cheapest_cost) {
            $shipping_cheapest = 'usps';
            $shipping_cheapest_cost = $shipping_usps_cost;
          }
        }
        $shipping_count++;
      }
    }

    function display() {
      global $shipping_quote_usps, $shipping_quote_all, $shipping_usps_cost, $shipping_usps_method, $shipping_cheapest;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_usps) ) {
        $display_string = '<tr>' . "\n" .
                          '  <td class="main">&nbsp;' . MODULE_SHIPPING_USPS_TEXT_DESCRIPTION . '</td>' . "\n" .
                          '  <td class="main">' . $shipping_usps_method . '</td>' . "\n" .
                          '  <td align="right" class="main">' . tep_currency_format($shipping_usps_cost) . '</td>' . "\n" .
                          '  <td align="right">&nbsp;<input type="radio" name="shipping_selected" value="usps"';
        if ($shipping_cheapest == 'usps') $display_string .= ' CHECKED';
        $display_string .= '>&nbsp;</td>' . "\n" .
                           '</tr>' . "\n" .
                           '<input type="hidden" name="shipping_usps_cost" value="' . $shipping_usps_cost . '">' .
                           '<input type="hidden" name="shipping_usps_method" value="' . $shipping_usps_method . '">' . "\n";
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method;

      if ($HTTP_POST_VARS['shipping_selected'] == 'usps') {
        $shipping_cost = $HTTP_POST_VARS['shipping_usps_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_usps_method'];
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_USPS_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable USPS Shipping', 'MODULE_SHIPPING_USPS_STATUS', '1', 'Do you want to offer USPS shipping?', '6', '10', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS USERID', 'MODULE_SHIPPING_USPS_USERID', 'NONE', 'Enter the USPS USERID assigned to you. Register at http://www.uspsprioritymail.com/et_regcert.html and also tell them you are an end user not developer.', '6', '11', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS Password', 'MODULE_SHIPPING_USPS_PASSWORD', 'NONE', 'See USERID, above.', '6', '12', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS URL of the production Server', 'MODULE_SHIPPING_USPS_SERVER', 'NONE', 'See above', '6', '13', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_USPS_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_USPS_USERID'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_USPS_PASSWORD'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_USPS_SERVER'");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_USPS_STATUS', 'MODULE_SHIPPING_USPS_USERID', 'MODULE_SHIPPING_USPS_PASSWORD', 'MODULE_SHIPPING_USPS_SERVER');

      return $keys;
    }
  }
?>
