<?php
/*
  $Id: table.php,v 1.13 2001/11/25 22:57:18 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  $table_shipping_options = array('weight' => MODULE_SHIPPING_TABLE_TEXT_WEIGHT, 
                                  'price' => MODULE_SHIPPING_TABLE_TEXT_AMOUNT
                                 );

  function tep_get_shipping_modes($value) {
    global $table_shipping_options;

    return $table_shipping_options[$value];
  }

  function tep_set_shipping_modes($key, $value = '') {
    global $table_shipping_options;

    return tep_mod_select_option($table_shipping_options, $key, $value);
  }

  class table {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function table() {
      $this->code = 'table';
      $this->title = MODULE_SHIPPING_TABLE_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_TABLE_TEXT_DESCRIPTION;
      $this->icon = '';
      $this->enabled = MODULE_SHIPPING_TABLE_STATUS;
    }

// class methods
    function selection() {
      $selection_string = '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . '&nbsp; ' . MODULE_SHIPPING_TABLE_TEXT_TITLE . '&nbsp;</td>' . "\n" .
                          '    <td align="right" class="main">&nbsp;' . tep_draw_checkbox_field('shipping_quote_table', '1', true) . '&nbsp;</td>' . "\n" .
                          '  </tr>' . "\n" .
                          '</table>' . "\n";

      return $selection_string;
    }

    function quote() {
      global $cart, $shipping_quoted, $shipping_table_cost, $shipping_table_method;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_table'] == '1') ) {
        $shipping_quoted = 'table';

        if (MODULE_SHIPPING_TABLE_MODE == 'price') {
          $order_total = $cart->show_total();
        } else {
          $order_total = $cart->show_weight();
        }
        $table_cost = MODULE_SHIPPING_TABLE_COST;
        $high = split('[-,]' , $table_cost);
        $n = 1;
        $y = 2;
        for ($i=0; $i<count($high); $i++) {
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
      global $shipping_count, $shipping_cheapest, $shipping_cheapest_cost, $shipping_table_cost;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_table'] == '1') ) {
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
      global $HTTP_GET_VARS, $currencies, $shipping_cheapest, $shipping_table_method, $shipping_table_cost, $shipping_selected;

// set a global for the radio field (auto select cheapest shipping method)
      if (!$HTTP_GET_VARS['shipping_selected']) $shipping_selected = $shipping_cheapest;

      if ( ($GLOBALS['shipping_quote_all'] == '1') || ($GLOBALS['shipping_quote_table'] == '1') ) {
        $display_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n" .
                          '  <tr>' . "\n" .
                          '    <td class="main">&nbsp;' . (($this->icon) ? tep_image($this->icon, $this->title) : '') . '&nbsp;' . MODULE_SHIPPING_TABLE_TEXT_TITLE . ' <small><i>(' . $shipping_table_method . ')</i></small>&nbsp;</td>' . "\n" .
                          '    <td align="right" class="main">&nbsp;' . $currencies->format($shipping_table_cost);
        if (tep_count_shipping_modules() > 1) {
          $display_string .= '&nbsp;&nbsp;' . tep_draw_radio_field('shipping_selected', 'table') .
                                              tep_draw_hidden_field('shipping_table_cost', $shipping_table_cost) .
                                              tep_draw_hidden_field('shipping_table_method', $shipping_table_method) . '&nbsp;</td>' . "\n";
        } else {
          $display_string .= '&nbsp;&nbsp;' . tep_draw_hidden_field('shipping_selected', 'table') .
                                              tep_draw_hidden_field('shipping_table_cost', $shipping_table_cost) .
                                              tep_draw_hidden_field('shipping_table_method', $shipping_table_method) . '&nbsp;</td>' . "\n";
        }
        $display_string .= '  </tr>' . "\n" .
                           '</table>' . "\n";
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
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Table Method', 'MODULE_SHIPPING_TABLE_MODE', 'weight', 'Is the shipping table based on total Weight or Total amount of order.', '6', '0', 'tep_get_shipping_modes', 'tep_set_shipping_modes(', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TABLE_STATUS'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TABLE_COST'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TABLE_HANDLING'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_TABLE_MODE'");
    }

    function keys() {
      $keys = array('MODULE_SHIPPING_TABLE_STATUS', 'MODULE_SHIPPING_TABLE_COST', 'MODULE_SHIPPING_TABLE_HANDLING', 'MODULE_SHIPPING_TABLE_MODE');
      return $keys;
    }
  }
?>
