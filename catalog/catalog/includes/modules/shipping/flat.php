<?php
/*
  $Id: flat.php,v 1.31 2002/01/15 20:27:22 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class flat {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function flat() {
      $this->code = 'flat';
      $this->title = MODULE_SHIPPING_FLAT_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FLAT_TEXT_DESCRIPTION;
      $this->icon = '';
      $this->enabled = MODULE_SHIPPING_FLAT_STATUS;
    }

// class methods
    function selection() {
      $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . MODULE_SHIPPING_FLAT_TEXT_TITLE . '</td>' . "\n" .
                          '    <td align="right" class="main">' . tep_draw_checkbox_field('shipping_quote_flat', '1', true) . '</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }

    function quote() {
      global $shipping_quoted, $shipping_flat_cost, $shipping_flat_method;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_flat'] == '1') ) {
        $shipping_quoted = 'flat';
        $shipping_flat_cost = SHIPPING_HANDLING + MODULE_SHIPPING_FLAT_COST;
        $shipping_flat_method = MODULE_SHIPPING_FLAT_TEXT_WAY;
      }
    }

    function cheapest() {
      global $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_flat_cost;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_flat'] == '1') ) {
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
      global $HTTP_GET_VARS, $currencies, $shipping_cheapest, $shipping_flat_method, $shipping_flat_cost, $shipping_selected;

// set a global for the radio field (auto select cheapest shipping method)
      if (!$HTTP_GET_VARS['shipping_selected']) $shipping_selected = $shipping_cheapest;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_flat'] == '1') ) {
        $display_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . MODULE_SHIPPING_FLAT_TEXT_TITLE . ' <small><i>(' . $shipping_flat_method . ')</i></small></td>' . "\n" .
                          '    <td align="right" class="main">' . $currencies->format($shipping_flat_cost);
        if (tep_count_shipping_modules() > 1) {
          $display_string .= tep_draw_radio_field('shipping_selected', 'flat') .
                             tep_draw_hidden_field('shipping_flat_cost', $shipping_flat_cost) .
                             tep_draw_hidden_field('shipping_flat_method', $shipping_flat_method) . '</td>' . "\n";
        } else {
          $display_string .= tep_draw_hidden_field('shipping_selected', 'flat') .
                             tep_draw_hidden_field('shipping_flat_cost', $shipping_flat_cost) .
                             tep_draw_hidden_field('shipping_flat_method', $shipping_flat_method) . '</td>' . "\n";
        }
        $display_string .= '  </tr>' . "\n" .
                           '</table>' . "\n";
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
