<?
  /* $Id: flat.php,v 1.17 2001/03/05 22:26:08 hpdl Exp $ */
  if ($action != 'install' && $action != 'remove' && $action != 'check') { // Only use language for catalog
    $include_file = DIR_WS_LANGUAGES . $language . '/modules/shipping/flat.php';include(DIR_WS_INCLUDES . 'include_once.php');
  }

  if ($action == 'select') {
?>
              <tr>
                <td><?php echo FONT_STYLE_MAIN; ?>&nbsp;<? echo SHIPPING_FLAT_NAME; ?></font></td>
                <td>&nbsp;</td>
                <td align="right">&nbsp;<input type="checkbox" name="shipping_quote_flat" value="1"
<?
  // if ($shipping_count == 0)
  echo ' CHECKED';
  echo "></td>\n";
?>
             </tr>
<?
  } elseif ($action == 'quote') {
    if ($shipping_quote_flat == "1" || $shipping_quote_all == "1") {
      $shipping_quoted = 'flat';
      $shipping_flat_cost = SHIPPING_HANDLING + SHIPPING_FLAT_COST;
      $shipping_flat_method = SHIPPING_FLAT_WAY;
    }
  } elseif ($action == 'cheapest') {
    if ($shipping_quote_flat == "1" || $shipping_quote_all == "1") {
      if ($shipping_count == 0) {
        $shipping_cheapest = 'flat';
        $shipping_cheapest_cost = $shipping_flat_cost;
      } else {
        if ($shipping_flat_cost < $shipping_cheapest_cost) {
          $shipping_cheapest = 'flat';
          $shipping_cheapest_cost = $shipping_flat_cost;
        }
      }
    }
  } elseif ($action == 'display') {
      if ($shipping_quote_flat == "1" || $shipping_quote_all == "1") {
        echo "              <tr>\n";
        echo '                <td>' . FONT_STYLE_MAIN . '&nbsp;' . SHIPPING_FLAT_NAME . "</font></td>\n";
        echo '                <td>' . FONT_STYLE_MAIN . $shipping_flat_method . "</font></td>\n";
        echo '                <td align="right">' . FONT_STYLE_MAIN . tep_currency_format($shipping_flat_cost) . "</font></td>\n";
        echo '                <td align="right" nowrap>&nbsp;<input type="radio" name="shipping_selected" value="flat"';
        if ($shipping_cheapest == 'flat') echo ' CHECKED';
        echo ">&nbsp;</td>\n";
        echo "              </tr>\n";
        echo '              <input type="hidden" name="shipping_flat_cost" value=' . $shipping_flat_cost . ">\n";
        echo '              <input type="hidden" name="shipping_flat_method" value="' . $shipping_flat_method . "\">\n";
      }
  } elseif ($action == 'confirm') {
      if ($HTTP_POST_VARS['shipping_selected'] == 'flat') {
        $shipping_cost = $HTTP_POST_VARS['shipping_flat_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_flat_method'];
      }
  } elseif ($action == 'check') {
    $check = tep_db_query("select configuration_value from configuration where configuration_key = 'SHIPPING_FLAT_ENABLED'");
    $check = tep_db_num_rows($check) + 1;
  } elseif ($action == 'install') {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Flat Shipping', 'SHIPPING_FLAT_ENABLED', '1', 'Do you want to offer flat rate shipping?', '7', '5', now())");
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Flat Cost', 'SHIPPING_FLAT_COST', '5.00', 'What is the Shipping cost? The Handling fee will also be added.', '7', '6', now())");
  } elseif ($action == 'remove') {
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SHIPPING_FLAT_ENABLED'");
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SHIPPING_FLAT_COST'");
  }
  $shipping_count = $shipping_count+1;
?>
