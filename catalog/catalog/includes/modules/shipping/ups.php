<?php
  class ups {
    var $code, $descrption, $enabled;

// class constructor
    function ups() {
      $this->code = 'ups';
      $this->description = MODULE_SHIPPING_UPS_TEXT_DESCRIPTION;
      $this->enabled = MODULE_SHIPPING_UPS_STATUS;
    }

// class methods
    function select() {
      $select_string = '<TR><TD class="main">&nbsp;' . MODULE_SHIPPING_UPS_TEXT_DESCRIPTION . '</td>' .
                       '<td><select name="shipping_ups_prod">' .
                         '<option value="GND" SELECTED>' . MODULE_SHIPPING_UPS_TEXT_OPT_GND . '</option>' .
                         '<option value="1DM">' . MODULE_SHIPPING_UPS_TEXT_OPT_1DM . '</option>' .
                         '<option value="1DA">' . MODULE_SHIPPING_UPS_TEXT_OPT_1DA . '</option>' .
                         '<option value="1DP">' . MODULE_SHIPPING_UPS_TEXT_OPT_1DP . '</option>' .
                         '<option value="2DM">' . MODULE_SHIPPING_UPS_TEXT_OPT_2DM . '</option>' .
                         '<option value="3DS">' . MODULE_SHIPPING_UPS_TEXT_OPT_3DS . '</option>' .
                         '<option value="STD">' . MODULE_SHIPPING_UPS_TEXT_OPT_STD . '</option>' .
                         '<option value="XPR">' . MODULE_SHIPPING_UPS_TEXT_OPT_XPR . '</option>' .
                         '<option value="XDM">' . MODULE_SHIPPING_UPS_TEXT_OPT_XDM . '</option>' .
                         '<option value="XPD">' . MODULE_SHIPPING_UPS_TEXT_OPT_XPD . '</option>' .
                       '</select></td>' .
                       '<td align="right">&nbsp;<input type="checkbox" name="shipping_quote_ups" value="1" CHECKED></td></tr>' . "\n";

      return $select_string;
    }

    function quote() {
      global $HTTP_POST_VARS, $shipping_quote_ups, $shipping_quote_all, $address_values, $shipping_weight, $shipping_ups_cost, $shipping_ups_method, $shipping_num_boxes, $shipping_quoted;

      $prod = $HTTP_POST_VARS['shipping_ups_prod'];
      if ( ($shipping_quote_all == '1') || ($shipping_quote_ups) ) {
        $prod = 'GND';
        include(DIR_WS_CLASSES . '_ups.php');
        $rate = new _Ups;
        $rate->upsProduct($prod); // See upsProduct() function for codes
        $rate->origin(STORE_ORIGIN_ZIP, STORE_ORIGIN_COUNTRY); // Use ISO country codes!
        $country_name = tep_get_countries($address_values['country_id'], '1');
        $country_post = str_replace(' ', '', $address_values['postcode']);
        // $rate->dest($address_values['postcode'], STORE_ORIGIN_COUNTRY);      // Use ISO country codes!
        $rate->dest($country_post, $country_name['countries_iso_code_2']);      // Use ISO country codes!
        $rate->rate(MODULE_SHIPPING_UPS_PICKUP);        // See the rate() function for codes
        $rate->container(MODULE_SHIPPING_UPS_PACKAGE);    // See the container() function for codes
        $rate->weight($shipping_weight);
        $rate->rescom(MODULE_SHIPPING_UPS_RES);    // See the rescom() function for codes
        $quote = $rate->getQuote();
        $shipping_ups_cost = SHIPPING_HANDLING + $quote;
        $shipping_ups_method = 'UPS ' . $prod . ' ' . $shipping_num_boxes . ' X ' . $shipping_weight;
        if ($shipping_ups_cost == SHIPPING_HANDLING) {
          $shipping_ups_method = 'UPS ' . $quote;
        } else {
          $shipping_quoted = 'ups';
          $shipping_ups_cost = $shipping_ups_cost * $shipping_num_boxes;
        }
      }
    }

    function cheapest() {
      global $shipping_quote_ups, $shipping_quote_all, $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_ups_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_ups) ) {
        if ($shipping_count == 0) {
          $shipping_cheapest = 'ups';
          $shipping_cheapest_cost = $shipping_ups_cost;
        } else {
          if ($shipping_ups_cost < $shipping_cheapest_cost) {
            $shipping_cheapest = 'ups';
            $shipping_cheapest_cost = $shipping_ups_cost;
          }
        }
        $shipping_count++;
      }
    }

    function display() {
      global $shipping_quote_ups, $shipping_quote_all, $shipping_ups_cost, $shipping_ups_method, $shipping_cheapest;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_ups) ) {
        $display_string = '<tr>' . "\n" .
                          '  <td class="main">&nbsp;' . MODULE_SHIPPING_UPS_TEXT_DESCRIPTION . '</td>' . "\n" .
                          '  <td class="main">' . $shipping_ups_method . '</td>' . "\n" .
                          '  <td align="right" class="main">' . tep_currency_format($shipping_ups_cost) . '</td>' . "\n" .
                          '  <td align="right" nowrap>&nbsp;<input type="radio" name="shipping_selected" value="ups"';
        if ($shipping_cheapest == 'ups') $display_string .= ' CHECKED';
        $display_string .= '>&nbsp;</td>' . "\n" .
                           '</tr>' . "\n" .
                           '<input type="hidden" name="shipping_ups_cost" value="' . $shipping_ups_cost . '">' .
                           '<input type="hidden" name="shipping_ups_method" value="' . $shipping_ups_method . '">' . "\n";
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method;

      if ($HTTP_POST_VARS['shipping_selected'] == 'ups') {
        $shipping_cost = $HTTP_POST_VARS['shipping_ups_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_ups_method'];
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_UPS_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable UPS Shipping', 'MODULE_SHIPPING_UPS_STATUS', '1', 'Do you want to offer UPS shipping?', '6', '9', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Pickup Method', 'MODULE_SHIPPING_UPS_PICKUP', 'CC', 'How do you give packages to UPS? CC - Customer Counter, RDP - Daily Pickup, OTP - One Time Pickup, LC - Letter Center, OCA - On Call Air', '6', '10', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('UPS Packaging?', 'MODULE_SHIPPING_UPS_PACKAGE', 'CP', 'CP - Your Packaging, ULE - UPS Letter, UT - UPS Tube, UBE - UPS Express Box', '6', '11', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Residential Delivery?', 'MODULE_SHIPPING_UPS_RES', 'RES', 'Quote for Residential (RES) or Commercial Delivery (COM)', '6', '12', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_UPS_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_UPS_PICKUP'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_UPS_PACKAGE'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_UPS_RES'");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_UPS_STATUS', 'MODULE_SHIPPING_UPS_PICKUP', 'MODULE_SHIPPING_UPS_PACKAGE', 'MODULE_SHIPPING_UPS_RES');

      return $keys;
    }
  }
?>
