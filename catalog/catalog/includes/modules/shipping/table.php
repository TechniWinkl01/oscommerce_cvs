<?php
/*
  $Id: table.php,v 1.7 2001/08/25 10:28:03 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  class table {
    var $code, $title, $description, $enabled;

// class constructor
    function table() {
      $this->code = 'table';
      $this->title = MODULE_SHIPPING_TABLE_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_TABLE_TEXT_DESCRIPTION;
      $this->enabled = MODULE_SHIPPING_TABLE_STATUS;
    }

// class methods
    function select() {
      $select_string = '<tr>' . "\n" .
                       '  <td class="main">&nbsp;' . MODULE_SHIPPING_TABLE_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                       '  <td class="main">&nbsp;</td>' . "\n" .
                       '  <td align="right" class="main">&nbsp;' . tep_draw_checkbox_field('shipping_quote_table', 'checkbox', '1', true) . '&nbsp;</td>' . "\n" .
                       '</tr>' . "\n";

      return $select_string;
    }

    function quote() {
      global $cart, $shipping_quote_table, $shipping_quote_all, $shipping_quoted, $shipping_table_cost, $shipping_table_method;

      if ( ($shipping_quote_table) || ($shipping_quote_all == "1") ) {
        $shipping_quoted = 'table';

        $order_total = $cart->show_total();
        $table_cost = MODULE_SHIPPING_TABLE_COST;
        $high = split("[-,]" , $table_cost);
        $n=1;
        $y=2;
        for ($i = 0; $i < count($high); $i ++) {
          if ( ($order_total >= $high[$i]) && ($order_total < $high[$n]) ) {
            $shipping = $high[$y];
            $shipping_table_method = MODULE_SHIPPING_TABLE_TEXT_WAY;
            break;
          }
          $i = $i + 2;
          $n = $n + 3;
          $y = $y + 3;
        }
        $shipping_table_cost = ($shipping + MODULE_SHIPPING_TABLE_HANDLING);
      }
    }

    function cheapest() {
      global $shipping_count, $shipping_quote_table, $shipping_quote_all, $shipping_cheapest, $shipping_cheapest_cost, $shipping_table_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_table) ) {
        if ($shipping_count == 0) {
          $shipping_cheapest = 'table';
          $shipping_cheapest_cost = $shipping_table_cost;
        } else {
          if ($shipping_table_cost < $shipping_cheapest_cost) {
            $shipping_cheapest = 'table';
            $shipping_cheapest_cost = $shipping_table_cost;
          }
        }
        $shipping_count++;
      }
    }

    function display() {
      global $HTTP_GET_VARS, $shipping_quote_table, $shipping_quote_all, $shipping_cheapest, $shipping_table_method, $shipping_table_cost, $shipping_selected;

// set a global for the radio field (auto select cheapest shipping method)
      if (!$HTTP_GET_VARS['shipping_selected']) $shipping_selected = $shipping_cheapest;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_table) ) {
        $display_string = '<tr>' . "\n" .
                          '  <td class="main">&nbsp;' . MODULE_SHIPPING_TABLE_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                          '  <td class="main">&nbsp;' . $shipping_table_method . '&nbsp;</td>' . "\n" .
                          '  <td align="right" class="main">&nbsp;' . tep_currency_format($shipping_table_cost);
        if (tep_count_shipping_modules() > 1) {
          $display_string .= '&nbsp;</td>' . "\n" .
                             '  <td align="right" class="main">&nbsp;' . tep_draw_radio_field('shipping_selected', 'table') .
                                                                         tep_draw_hidden_field('shipping_table_cost', $shipping_table_cost) .
                                                                         tep_draw_hidden_field('shipping_table_method', $shipping_table_method) . '&nbsp;</td>' . "\n";
        } else {
          $display_string .= '&nbsp;' . tep_draw_hidden_field('shipping_selected', 'table') .
                                        tep_draw_hidden_field('shipping_table_cost', $shipping_table_cost) .
                                        tep_draw_hidden_field('shipping_table_method', $shipping_table_method) . '&nbsp;</td>' . "\n";
        }
        $display_string .= '</tr>' . "\n";
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method;

      if ($HTTP_POST_VARS['shipping_selected'] == 'table') {
        $shipping_cost = $HTTP_POST_VARS['shipping_table_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_table_method'];
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TABLE_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Table Method', 'MODULE_SHIPPING_TABLE_STATUS', '1', 'Do you want to offer table rate shipping?', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Table', 'MODULE_SHIPPING_TABLE_COST', '1-25,8.50,25-50,5.50,50-10000,0.00', 'Shipping based on the total cost of items. Example: 1-25,8.50,25-50,5.50,etc.. From 1 to 25 charge 8.50, from 25 to 50 charge 5.50, etc', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_TABLE_HANDLING', '5', 'Handling Fee for this shipping method', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TABLE_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TABLE_COST'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TABLE_HANDLING'");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_TABLE_STATUS', 'MODULE_SHIPPING_TABLE_COST', 'MODULE_SHIPPING_TABLE_HANDLING');
      return $keys;
    }
  }
?>
