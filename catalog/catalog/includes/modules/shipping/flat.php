<?php
  class flat {

// class constructor
    function flat() {
    }

// class methods
    function select() {
      $select_string = '<TR><TD>' . FONT_STYLE_MAIN . '&nbsp;' . SHIPPING_FLAT_NAME . '</font></td>' .
                       '<td>&nbsp;</td>' .
                       '<td align="right">&nbsp;<input type="checkbox" name="shipping_quote_flat" value="1" CHECKED></td></tr>' . "\n";

      return $select_string;
    }

    function quote() {
      global $shipping_quote_all, $shipping_quoted, $shipping_flat_cost, $shipping_flat_method;

      if ($shipping_quote_all == '1') {
        $shipping_quoted = 'flat';
        $shipping_flat_cost = SHIPPING_HANDLING + SHIPPING_FLAT_COST;
        $shipping_flat_method = SHIPPING_FLAT_WAY;
      }
    }

    function cheapest() {
      global $shipping_count, $shipping_quote_all, $shipping_cheapest, $shipping_cheapest_cost, $shipping_flat_cost;

      if ($shipping_quote_all == '1') {
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
      global $shipping_quote_all, $shipping_cheapest, $shipping_flat_method, $shipping_flat_cost;

      if ($shipping_quote_all == '1') {
        $display_string = '<tr>' . "\n" .
                          '  <td>' . FONT_STYLE_MAIN . '&nbsp;' . SHIPPING_FLAT_NAME . '</font></td>' . "\n" .
                          '  <td>' . FONT_STYLE_MAIN . $shipping_flat_method . '</font></td>' . "\n" .
                          '  <td align="right">' . FONT_STYLE_MAIN . tep_currency_format($shipping_flat_cost) . '</font></td>' . "\n" .
                          '  <td align="right" nowrap>&nbsp;<input type="radio" name="shipping_selected" value="flat"';
        if ($shipping_cheapest == 'flat') $display_string .= ' CHECKED';
        $display_string .= '>&nbsp;</td>' . "\n" .
                           '</tr>' . "\n" .
                           '<input type="hidden" name="shipping_flat_cost" value="' . $shipping_flat_cost . '">' .
                           '<input type="hidden" name="shipping_flat_method" value="' . $shipping_flat_method . '">' . "\n";
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
      $check = tep_db_query("select configuration_value from configuration where configuration_key = 'SHIPPING_FLAT_ENABLED'");
      $check = tep_db_num_rows($check) + 1;

      return $check;
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enable Flat Shipping', 'SHIPPING_FLAT_ENABLED', '1', 'Do you want to offer flat rate shipping?', '7', '5', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Flat Cost', 'SHIPPING_FLAT_COST', '5.00', 'What is the Shipping cost? The Handling fee will also be added.', '7', '6', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key = 'SHIPPING_FLAT_ENABLED'");
      tep_db_query("delete from configuration where configuration_key = 'SHIPPING_FLAT_COST'");
    }
  }
?>
