<?
  class item {
    var $code, $title, $description, $enabled;

// class constructor
    function item() {
      $this->code = 'item';
      $this->title = MODULE_SHIPPING_ITEM_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_ITEM_TEXT_DESCRIPTION;
      $this->enabled = MODULE_SHIPPING_ITEM_STATUS;
    }

// class methods
    function select() {
      $select_string = '<TR><TD class="main">&nbsp;' . MODULE_SHIPPING_ITEM_TEXT_TITLE . '</td>' .
                       '<td>&nbsp;</td>' .
                       '<td align="right">&nbsp;<input type="checkbox" name="shipping_quote_item" value="1" CHECKED></td></tr>' . "\n";

      return $select_string;
    }

    function quote() {
      global $shipping_quote_item, $shipping_quote_all, $shipping_quoted, $shipping_item_cost, $shipping_item_method, $total_count;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_item) ) {
        $shipping_quoted = 'item';
        $shipping_item_cost = SHIPPING_HANDLING + (MODULE_SHIPPING_ITEM_COST * $total_count);
        $shipping_item_method = MODULE_SHIPPING_ITEM_TEXT_WAY;
      }
    }

    function cheapest() {
      global $shipping_count, $shipping_quote_item, $shipping_quote_all, $shipping_cheapest, $shipping_cheapest_cost, $shipping_item_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_item) ) {
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
      global $shipping_quote_item, $shipping_quote_all, $shipping_cheapest, $shipping_item_method, $shipping_item_cost;

      if ( ($shipping_quote_all == '1') || ($shipping_quote_item) ) {
        $display_string = '<tr>' . "\n" .
                          '  <td class="main">&nbsp;' . MODULE_SHIPPING_ITEM_TEXT_TITLE . '</td>' . "\n" .
                          '  <td class="main">' . $shipping_item_method . '</td>' . "\n" .
                          '  <td align="right" class="main">' . tep_currency_format($shipping_item_cost) . '</td>' . "\n" .
                          '  <td align="right">&nbsp;<input type="radio" name="shipping_selected" value="item"';
        if ($shipping_cheapest == 'item') $display_string .= ' CHECKED';
        $display_string .= '>&nbsp;</td>' . "\n" .
                           '</tr>' . "\n" .
                           '<input type="hidden" name="shipping_item_cost" value="' . $shipping_item_cost . '">' .
                           '<input type="hidden" name="shipping_item_method" value="' . $shipping_item_method . '">' . "\n";
      }

      return $display_string;
    }

    function confirm() {
      global $HTTP_POST_VARS, $shipping_cost, $shipping_method;

      if ($HTTP_POST_VARS['shipping_selected'] == 'item') {
        $shipping_cost = $HTTP_POST_VARS['shipping_item_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_item_method'];
      }
    }

    function check() {
      $check = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_ITEM_STATUS'");
      $check = tep_db_num_rows($check);

      return $check;
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
