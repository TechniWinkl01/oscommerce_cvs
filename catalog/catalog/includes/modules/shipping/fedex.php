<?php
/*
  $Id: fedex.php,v 1.33 2002/08/13 16:00:42 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class fedex {
    var $code, $title, $description, $icon, $enabled, $fedex_countries, $fedex_countries_nbr;

// class constructor
    function fedex() {
      $this->code = 'fedex';
      $this->title = MODULE_SHIPPING_FEDEX_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FEDEX_TEXT_DESCRIPTION;
      $this->icon = DIR_WS_ICONS . 'shipping_fedex.gif';
      $this->enabled = MODULE_SHIPPING_FEDEX_STATUS;

// only these three are needed since FedEx only ships to them
// convert TEP country id to ISO 3166 id
      $this->fedex_countries = array(38 => 'CA', 138 => 'MX', 223 => 'US');
      $this->fedex_countries_nbr = array(38, 138, 223);
    }

// class methods
    function selection() {
      $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . MODULE_SHIPPING_FEDEX_TEXT_TITLE . '</td>' . "\n" .
                          '    <td align="right" class="main">' . tep_draw_checkbox_field('shipping_quote_fedex', '1', true) . '</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }

    function quote() {
      global $shipping_quoted, $address_values, $shipping_weight, $shipping_num_boxes, $shipping_fedex_cost, $shipping_fedex_method, $quote;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_fedex'] == '1') ) {
        $shipping_quoted = 'fedex';
// only calculate if FedEx ships there.
        if (in_array($address_values['country_id'], $this->fedex_countries_nbr)) {
          include(DIR_WS_CLASSES . '_fedex.php');
          $rate = new _FedEx(STORE_ORIGIN_ZIP, STORE_ORIGIN_COUNTRY);
          $rate->SetDest($address_values['postcode'], $this->fedex_countries[$address_values['country_id']]);
// fedex doesnt accept weights below one
          $rate->SetWeight($shipping_weight);
          $quote = $rate->GetQuote();
          $shipping_fedex_cost = $shipping_num_boxes * (SHIPPING_HANDLING + $quote['TotalCharges']);
// clean up the service text a little
          $shipping_fedex_method = str_replace(' Package', '', $quote['Service']);
          $shipping_fedex_method = str_replace(' FedEx', '', $shipping_fedex_method);
          $shipping_fedex_method .= ' ' . $shipping_num_boxes . ' x ' . ($shipping_weight < 1 ? 1 : $shipping_weight);
        } else {
          $quote['ErrorNbr'] = 1;
          $quote['Error'] = MODULE_SHIPPING_FEDEX_TEXT_NOTAVAILABLE;
        }
      }
    }

    function cheapest() {
      global $address_values, $quote, $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_fedex_cost;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_fedex'] == '1') ) {
// only calculate if FedEx ships there.
        if ( (in_array($address_values['country_id'], $this->fedex_countries_nbr)) && (!$quote['ErrorNbr']) ) {
          if ($shipping_count == 0) {
            $shipping_cheapest = 'fedex';
            $shipping_cheapest_cost = $shipping_fedex_cost;
          }	else {
            if ($shipping_fedex_cost < $shipping_cheapest_cost) {
              $shipping_cheapest = 'fedex';
              $shipping_cheapest_cost = $shipping_fedex_cost;
            }
          }
        }
        $shipping_count++;
      }
    }

    function display() {
      global $HTTP_GET_VARS, $currencies, $quote, $shipping_fedex_method, $shipping_fedex_cost, $shipping_cheapest, $shipping_selected;

// set a global for the radio field (auto select cheapest shipping method)
      if (!$shipping_selected) $shipping_selected = $shipping_cheapest;

      $display_string = '';
      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_fedex'] == '1') ) {
// check for errors
        if ($quote['ErrorNbr']) {
          $display_string .= '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . MODULE_SHIPPING_FEDEX_TEXT_TITLE . '</td>' . "\n" .
                             '    <td class="main"><font color="#ff0000">Error:</font> ' . $quote['Error'] . '</td>' . "\n" .
                             '  </tr>' . "\n" .
                             '</table>' . "\n";
        } else {
          $display_string .= '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n" .
                             '  <tr>' . "\n" .
                             '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . MODULE_SHIPPING_FEDEX_TEXT_TITLE . ' <small><i>(' . $shipping_fedex_method . ')</i></small></td>' . "\n" .
                             '    <td align="right" class="main">' . $currencies->format($shipping_fedex_cost);
          if (tep_count_shipping_modules() > 1) {
            $display_string .= tep_draw_radio_field('shipping_selected', 'fedex') .
                               tep_draw_hidden_field('shipping_fedex_cost', $shipping_fedex_cost) .
                               tep_draw_hidden_field('shipping_fedex_method', $shipping_fedex_method) . '</td>' . "\n";
          } else {
            $display_string .= tep_draw_hidden_field('shipping_selected', 'fedex') .
                               tep_draw_hidden_field('shipping_fedex_cost', $shipping_fedex_cost) .
                               tep_draw_hidden_field('shipping_fedex_method', $shipping_fedex_method) . '</td>' . "\n";
          }
          $display_string .= '  </tr>' . "\n" .
                             '</table>' . "\n";
        }
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method, $shipping_selected;

      if ($shipping_selected == 'fedex') {
        $shipping_cost = $HTTP_POST_VARS['shipping_fedex_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_fedex_method'];
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable FedEx Shipping', 'MODULE_SHIPPING_FEDEX_STATUS', '1', 'Do you want to offer Federal Express (FedEx) shipping?', '6', '10', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX_STATUS'");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_FEDEX_STATUS');

      return $keys;
    }
  }
?>
