<?php
/*
  $Id: ups.php,v 1.37 2001/09/12 21:33:16 dwatkins Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class ups {
    var $code, $title, $descrption, $icon, $enabled;

// class constructor
    function ups() {
      $this->code = 'ups';
      $this->title = MODULE_SHIPPING_UPS_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_UPS_TEXT_DESCRIPTION;
      $this->icon = DIR_WS_ICONS . 'shipping_ups.gif';
      $this->enabled = MODULE_SHIPPING_UPS_STATUS;
    }

// class methods
    function selection() {
      $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . '&nbsp; ' . MODULE_SHIPPING_UPS_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                          '    <td align="right" class="main">&nbsp;<select name="shipping_ups_prod">' .
                                                                   '<option value="GND">' . MODULE_SHIPPING_UPS_TEXT_OPT_GND . '</option>' .
                                                                   '<option value="1DM">' . MODULE_SHIPPING_UPS_TEXT_OPT_1DM . '</option>' .
                                                                   '<option value="1DA">' . MODULE_SHIPPING_UPS_TEXT_OPT_1DA . '</option>' .
                                                                   '<option value="1DP">' . MODULE_SHIPPING_UPS_TEXT_OPT_1DP . '</option>' .
                                                                   '<option value="2DM">' . MODULE_SHIPPING_UPS_TEXT_OPT_2DM . '</option>' .
                                                                   '<option value="3DS">' . MODULE_SHIPPING_UPS_TEXT_OPT_3DS . '</option>' .
                                                                   '<option value="STD">' . MODULE_SHIPPING_UPS_TEXT_OPT_STD . '</option>' .
                                                                   '<option value="XPR">' . MODULE_SHIPPING_UPS_TEXT_OPT_XPR . '</option>' .
                                                                   '<option value="XDM">' . MODULE_SHIPPING_UPS_TEXT_OPT_XDM . '</option>' .
                                                                   '<option value="XPD">' . MODULE_SHIPPING_UPS_TEXT_OPT_XPD . '</option>' .
                                                                   '</select>&nbsp;&nbsp;' . tep_draw_checkbox_field('shipping_quote_ups', '1', true) . '&nbsp;</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }

    function quote() {
      global $HTTP_POST_VARS, $address_values, $shipping_weight, $shipping_ups_cost, $shipping_ups_method, $shipping_num_boxes, $shipping_quoted;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_ups'] == '1') ) {
        $prod = ($HTTP_POST_VARS['shipping_ups_prod']) ? $HTTP_POST_VARS['shipping_ups_prod'] : 'GND';
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
      global $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_ups_cost;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_ups'] == '1') ) {
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
      global $HTTP_GET_VARS, $currencies, $shipping_ups_cost, $shipping_ups_method, $shipping_cheapest, $shipping_selected;

// set a global for the radio field (auto select cheapest shipping method)
      if (!$HTTP_GET_VARS['shipping_selected']) $shipping_selected = $shipping_cheapest;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_ups'] == '1') ) {
        $display_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . '&nbsp;' . MODULE_SHIPPING_UPS_TEXT_TITLE . ' <small><i>(' . $shipping_ups_method . ')</i></small>&nbsp;</td>' . "\n" .
                          '    <td align="right" class="main">&nbsp;' . $currencies->format($shipping_ups_cost);
        if (tep_count_shipping_modules() > 1) {
          $display_string .= '&nbsp;&nbsp;' . tep_draw_radio_field('shipping_selected', 'ups') .
                                              tep_draw_hidden_field('shipping_ups_cost', $shipping_ups_cost) .
                                              tep_draw_hidden_field('shipping_ups_method', $shipping_ups_method) . '&nbsp;</td>' . "\n";
        } else {
          $display_string .= '&nbsp;&nbsp;' . tep_draw_hidden_field('shipping_selected', 'ups') .
                                              tep_draw_hidden_field('shipping_ups_cost', $shipping_ups_cost) .
                                              tep_draw_hidden_field('shipping_ups_method', $shipping_ups_method) . '&nbsp;</td>' . "\n";
        }
        $display_string .= '  </tr>' . "\n" .
                           '</table>' . "\n";
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
