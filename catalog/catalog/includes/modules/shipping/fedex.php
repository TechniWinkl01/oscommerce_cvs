<?php
/*
  $Id: fedex.php,v 1.21 2001/08/23 21:36:39 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class fedex {
    var $code, $title, $description, $enabled, $fedex_countries, $fedex_countries_nbr;

// class constructor
    function fedex() {
      $this->code = 'fedex';
      $this->title = MODULE_SHIPPING_FEDEX_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FEDEX_TEXT_DESCRIPTION;
      $this->enabled = MODULE_SHIPPING_FEDEX_STATUS;

// only these three are needed since FedEx only ships to them
// convert TEP country id to ISO 3166 id
      $this->fedex_countries = array(38 => 'CA', 138 => 'MX', 223 => 'US');
      $this->fedex_countries_nbr = array(38, 138, 223);
    }

// class methods
    function select() {
      $select_string = '<tr>' . "\n" .
                       '  <td class="main">&nbsp;' . MODULE_SHIPPING_FEDEX_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                       '  <td class="main">&nbsp;</td>' . "\n" .
                       '  <td align="right" class="main">&nbsp;' . tep_draw_checkbox_field('shipping_quote_fedex', 'checkbox', '1', true) . '&nbsp;</td>' . "\n" .
                       '</tr>' . "\n";

      return $select_string;
    }

    function quote() {
      global $shipping_quote_fedex, $shipping_quote_all, $shipping_quoted, $address_values, $shipping_weight, $shipping_num_boxes, $shipping_fedex_cost, $shipping_fedex_method, $quote;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_fedex) ) {
        $shipping_quoted = 'fedex';
// only calculate if FedEx ships there.
        if (tep_in_array($address_values['country_id'], $this->fedex_countries_nbr)) {
          include(DIR_WS_CLASSES . '_fedex.php');
          $rate = new _FedEx(STORE_ORIGIN_ZIP, STORE_ORIGIN_COUNTRY);
          $rate->SetDest($address_values['postcode'], $this->fedex_countries[$address_values['country_id']]);
          $rate->SetWeight($shipping_weight);
          $quote = $rate->GetQuote();
          $shipping_fedex_cost = $shipping_num_boxes * (SHIPPING_HANDLING + $quote['TotalCharges']);
// clean up the service text a little
          $shipping_fedex_method = str_replace(' Package', '', $quote['Service']);
          $shipping_fedex_method = str_replace(' FedEx', '', $shipping_fedex_method);
          $shipping_fedex_method .= ' ' . $shipping_num_boxes . ' x ' . $shipping_weight;
        } else {
          $quote['ErrorNbr'] = 1;
          $quote['Error'] = MODULE_SHIPPING_FEDEX_TEXT_NOTAVAILABLE;
        }
      }
    }

    function cheapest() {
      global $shipping_quote_fedex, $shipping_quote_all, $address_values, $quote, $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_fedex_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_fedex) ) {
// only calculate if FedEx ships there.
        if ( (tep_in_array($address_values['country_id'], $this->fedex_countries_nbr)) && (!$quote['ErrorNbr']) ) {
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
      global $HTTP_GET_VARS, $shipping_quote_fedex, $shipping_quote_all, $quote, $shipping_fedex_method, $shipping_fedex_cost, $shipping_cheapest, $shipping_selected;

// set a global for the radio field (auto select cheapest shipping method)
      if (!$HTTP_GET_VARS['shipping_selected']) $shipping_selected = $shipping_cheapest;

      $display_string = '';
      if ( ($shipping_quote_all == '1') || ($shipping_quote_fedex) ) {
// check for errors
        if ($quote['ErrorNbr']) {
          $display_string .= '<tr>' . "\n" .
                             '  <td class="main">&nbsp;' . MODULE_SHIPPING_FEDEX_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                             '  <td class="main">&nbsp;<font color="#ff0000">Error:</font> ' . $quote['Error'] . '&nbsp;</td>' . "\n" .
                             '  <td align="right" class="main">&nbsp;</td>' . "\n" .
                             '  <td align="right" class="main">&nbsp;</td>' . "\n" .
                             '</tr>' . "\n";
        } else {
          $display_string .= '<tr>' . "\n" .
                             '  <td class="main">&nbsp;' . MODULE_SHIPPING_FEDEX_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                             '  <td class="main">&nbsp;' . $shipping_fedex_method . '&nbsp;</td>' . "\n" .
                             '  <td align="right" class="main">&nbsp;' . tep_currency_format($shipping_fedex_cost) . '&nbsp;</td>' . "\n" .
                             '  <td align="right">&nbsp;' . tep_draw_radio_field('shipping_selected', 'fedex') .
                                                            tep_draw_hidden_field('shipping_fedex_cost', $shipping_fedex_cost) . 
                                                            tep_draw_hidden_field('shipping_fedex_method', $shipping_fedex_method) . '&nbsp;</td>' . "\n" .
                             '</tr>' . "\n";
        }
      }

      echo $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method;

      if ($HTTP_POST_VARS['shipping_selected'] == 'fedex') {
        $shipping_cost = $HTTP_POST_VARS['shipping_fedex_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_fedex_method'];
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FEDEX_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
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
