<?php
/*
  $Id: flat.php,v 1.28 2001/08/25 20:04:41 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class flat {
    var $code, $title, $description, $enabled;

// class constructor
    function flat() {
      $this->code = 'flat';
      $this->title = MODULE_SHIPPING_FLAT_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FLAT_TEXT_DESCRIPTION;
      $this->enabled = MODULE_SHIPPING_FLAT_STATUS;
    }

// class methods
    function select() {
      $select_string = '<tr>' . "\n" .
                       '  <td class="main">&nbsp;' . MODULE_SHIPPING_FLAT_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                       '  <td class="main">&nbsp;</td>' . "\n" .
                       '  <td align="right" class="main">&nbsp;' . tep_draw_checkbox_field('shipping_quote_flat', '1', true) . '&nbsp;</td>' . "\n" .
                       '</tr>' . "\n";

      return $select_string;
    }

    function quote() {
      global $shipping_quote_flat, $shipping_quote_all, $shipping_quoted, $shipping_flat_cost, $shipping_flat_method;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_flat == '1') ) {
        $shipping_quoted = 'flat';
        $shipping_flat_cost = SHIPPING_HANDLING + MODULE_SHIPPING_FLAT_COST;
        $shipping_flat_method = MODULE_SHIPPING_FLAT_TEXT_WAY;
      }
    }

    function cheapest() {
      global $shipping_count, $shipping_quote_flat, $shipping_quote_all, $shipping_cheapest, $shipping_cheapest_cost, $shipping_flat_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_flat == '1') ) {
        if ($shipping_count == 0) {
          $shipping_cheapest = 'flat';
          $shipping_cheapest_cost = $shipping_flat_cost;
        } else {
          if ($shipping_flat_cost < $shipping_cheapest_cost) {
            $shipping_cheapest = 'flat';
            $shipping_cheapest_cost = $shipping_flat_cost;
          }
        }
        $shipping_count++;
      }
    }

    function display() {
      global $HTTP_GET_VARS, $shipping_quote_flat, $shipping_quote_all, $shipping_cheapest, $shipping_flat_method, $shipping_flat_cost, $shipping_selected;

// set a global for the radio field (auto select cheapest shipping method)
      if (!$HTTP_GET_VARS['shipping_selected']) $shipping_selected = $shipping_cheapest;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_flat == '1') ) {
        $display_string = '<tr>' . "\n" .
                          '  <td class="main">&nbsp;' . MODULE_SHIPPING_FLAT_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                          '  <td class="main">&nbsp;' . $shipping_flat_method . '&nbsp;</td>' . "\n" .
                          '  <td align="right" class="main">&nbsp;' . tep_currency_format($shipping_flat_cost);
        if (tep_count_shipping_modules() > 1) {
          $display_string .= '&nbsp;</td>' . "\n" .
                             '  <td align="right" class="main">&nbsp;' . tep_draw_radio_field('shipping_selected', 'flat') .
                                                                         tep_draw_hidden_field('shipping_flat_cost', $shipping_flat_cost) .
                                                                         tep_draw_hidden_field('shipping_flat_method', $shipping_flat_method) . '&nbsp;</td>' . "\n";
        } else {
          $display_string .= '&nbsp;' . tep_draw_hidden_field('shipping_selected', 'flat') .
                                        tep_draw_hidden_field('shipping_flat_cost', $shipping_flat_cost) .
                                        tep_draw_hidden_field('shipping_flat_method', $shipping_flat_method) . '&nbsp;</td>' . "\n";
        }
        $display_string .= '</tr>' . "\n";
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method;

      if ($HTTP_POST_VARS['shipping_selected'] == 'flat') {
        $shipping_cost = $HTTP_POST_VARS['shipping_flat_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_flat_method'];
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FLAT_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable Flat Shipping', 'MODULE_SHIPPING_FLAT_STATUS', '1', 'Do you want to offer flat rate shipping?', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Flat Cost', 'MODULE_SHIPPING_FLAT_COST', '5.00', 'What is the Shipping cost? The Handling fee will also be added.', '6', '6', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FLAT_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FLAT_COST'");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_FLAT_STATUS', 'MODULE_SHIPPING_FLAT_COST');

      return $keys;
    }
  }
?>
