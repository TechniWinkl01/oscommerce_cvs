<?
  /* $Id: item.php,v 1.17 2001/03/09 03:57:58 tmoulton Exp $ */
  if ($action != 'install' && $action != 'remove' && $action != 'check') { // Only use language for catalog
    $include_file = DIR_WS_LANGUAGES . $language . '/modules/shipping/item.php';include(DIR_WS_INCLUDES . 'include_once.php');
  }

  if ($action == 'select') {
?>
              <tr>
                <td><?php echo FONT_STYLE_MAIN; ?>&nbsp;<? echo SHIPPING_ITEM_NAME; ?></font></td>
                <td>&nbsp;</td>
                <td align="right">&nbsp;<input type="checkbox" name="shipping_quote_item" value="1" CHECKED></td>
             </tr>
<?
  } elseif ($action == 'quote') {
    if ($shipping_quote_item == "1" || $shipping_quote_all == "1") {
      $shipping_quoted = 'item';
      $shipping_item_cost = SHIPPING_HANDLING + (SHIPPING_ITEM_COST * $total_count);
      $shipping_item_method = SHIPPING_ITEM_WAY;
    }
  } elseif ($action == 'cheapest') {
    if ($shipping_quote_item == "1" || $shipping_quote_all == "1") {
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
  } elseif ($action == 'display') {
      if ($shipping_quote_item == "1" || $shipping_quote_all == "1") {
        echo "              <tr>\n";
        echo '                <td>' . FONT_STYLE_MAIN . '&nbsp;' . SHIPPING_ITEM_NAME . "</font></td>\n";
        echo '                <td>' . FONT_STYLE_MAIN . $shipping_item_method . "</font></td>\n";
        echo '                <td align="right">' . FONT_STYLE_MAIN . tep_currency_format($shipping_item_cost) . "</font></td>\n";
        echo '                <td align="right" nowrap>&nbsp;<input type="radio" name="shipping_selected" value="item"';
        if ($shipping_cheapest == 'item') echo ' CHECKED';
        echo ">&nbsp;</td>\n";
        echo "              </tr>\n";
        echo '              <input type="hidden" name="shipping_item_cost" value=' . $shipping_item_cost . ">\n";
        echo '              <input type="hidden" name="shipping_item_method" value="' . $shipping_item_method . "\">\n";
      }
  } elseif ($action == 'confirm') {
      if ($HTTP_POST_VARS['shipping_selected'] == 'item') {
        $shipping_cost = $HTTP_POST_VARS['shipping_item_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_item_method'];
      }
  } elseif ($action == 'check') {
    $check = tep_db_query("select configuration_value from configuration where configuration_key = 'SHIPPING_ITEM_ENABLED'");
    $check = tep_db_num_rows($check) + 1;
  } elseif ($action == 'install') {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Item Shipping', 'SHIPPING_ITEM_ENABLED', '1', 'Do you want to offer per item rate shipping?', '7', '7', now())");
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Per Item shipping cost', 'SHIPPING_ITEM_COST', '2.50', 'How much will be charged for each item ordered?', '7', '8', now())");
  } elseif ($action == 'remove') {
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SHIPPING_ITEM_ENABLED'");
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SHIPPING_ITEM_COST'");
  }
?>
