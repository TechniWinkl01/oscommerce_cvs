<?php
/*
  $Id: item.php,v 1.32 2002/05/30 15:38:29 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class item {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function item() {
      $this->code = 'item';
      $this->title = MODULE_SHIPPING_ITEM_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_ITEM_TEXT_DESCRIPTION;
      $this->icon = '';
      $this->enabled = MODULE_SHIPPING_ITEM_STATUS;
    }

// class methods
    function selection() {
      $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . MODULE_SHIPPING_ITEM_TEXT_TITLE . '</td>' . "\n" .
                          '    <td align="right" class="main">' . tep_draw_checkbox_field('shipping_quote_item', '1', true) . '</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }

    function quote() {
      global $shipping_quoted, $shipping_item_cost, $shipping_item_method, $total_count;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_item'] == '1') ) {
        $shipping_quoted = 'item';
        $shipping_item_cost = SHIPPING_HANDLING + (MODULE_SHIPPING_ITEM_COST * $total_count);
        $shipping_item_method = MODULE_SHIPPING_ITEM_TEXT_WAY;
      }
    }

    function cheapest() {
      global $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_item_cost;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_item'] == '1') ) {
        if ($shipping_count == 0) {
          $shipping_cheapest = 'item';
          $shipping_cheapest_cost = $shipping_item_cost;
        } else {
          if ($shipping_item_cost < $shipping_cheapest_cost) {
            $shipping_cheapest = 'item';
            $shipping_cheapest_cost = $shipping_item_cost;
          }
        }
        $shipping_count++;
      }
    }

    function display() {
      global $HTTP_GET_VARS, $currencies, $shipping_cheapest, $shipping_item_method, $shipping_item_cost, $shipping_selected;

// set a global for the radio field (auto select cheapest shipping method)
      if (!$shipping_selected) $shipping_selected = $shipping_cheapest;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_item'] == '1') ) {
        $display_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . ' ' . MODULE_SHIPPING_ITEM_TEXT_TITLE . ' <small><i>(' . $shipping_item_method . ')</i></small></td>' . "\n" .
                          '    <td align="right" class="main">' . $currencies->format($shipping_item_cost);
        if (tep_count_shipping_modules() > 1) {
          $display_string .= tep_draw_radio_field('shipping_selected', 'item') .
                             tep_draw_hidden_field('shipping_item_cost', $shipping_item_cost) .
                             tep_draw_hidden_field('shipping_item_method', $shipping_item_method) . '</td>' . "\n";
        } else {
          $display_string .= tep_draw_hidden_field('shipping_selected', 'item') .
                             tep_draw_hidden_field('shipping_item_cost', $shipping_item_cost) .
                             tep_draw_hidden_field('shipping_item_method', $shipping_item_method) . '</td>' . "\n";
        }
        $display_string .= '  </tr>' . "\n" .
                           '</table>' . "\n";
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method, $shipping_selected;

      if ($shipping_selected == 'item') {
        $shipping_cost = $HTTP_POST_VARS['shipping_item_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_item_method'];
      }
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ITEM_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }
      return $this->check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable Item Shipping', 'MODULE_SHIPPING_ITEM_STATUS', '1', 'Do you want to offer per item rate shipping?', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Per Item shipping cost', 'MODULE_SHIPPING_ITEM_COST', '2.50', 'How much will be charged for each item ordered?', '6', '8', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ITEM_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ITEM_COST'");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_ITEM_STATUS', 'MODULE_SHIPPING_ITEM_COST');

      return $keys;
    }
  }
?>
