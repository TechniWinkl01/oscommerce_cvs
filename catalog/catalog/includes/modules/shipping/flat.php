<?
  // define('SHIPPING_FLAT_COST', '5.00');
  // define('SHIPPING_FLAT_NAME', 'Flat Rate');
  // define('SHIPPING_FLAT_WAY', 'Best Way');

  if ($action == 'select') {
?>
              <tr>
                <td>&nbsp<? echo SHIPPING_FLAT_NAME; ?></td>
                <td>&nbsp</td>
                <td>&nbsp<input type="checkbox" name="shipping_quote_flat" value="1"></td>
             </tr>
<?
  } elseif ($action == 'quote') {
      if ($HTTP_POST_VARS['shipping_quote_flat'] == "1") {
        $shipping_flat_cost = SHIPPING_HANDLING + SHIPPING_FLAT_COST;
        $shipping_flat_method = SHIPPING_FLAT_WAY;
        echo "              <tr>\n";
        echo '                <td>&nbsp' . SHIPPING_FLAT_NAME . "</td>\n";
        echo '                <td>' . $shipping_flat_method . "</td>\n";
        echo '                <td align="right">' . tep_currency_format($shipping_flat_cost) . "</td>\n";
        echo '                <td>&nbsp<input type="radio" name="shipping_selected" value="flat"';
        if ($shipping_quotes == 0) echo ' CHECKED';
        echo "></td>\n";
        echo "              </tr>\n";
        $shipping_quotes = $shipping_quotes+1;
        echo '              <input type="hidden" name="shipping_flat_cost" value=' . $shipping_flat_cost . ">\n";
        echo '              <input type="hidden" name="shipping_flat_method" value=' . $shipping_flat_method . ">\n";
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
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Flate Cost', 'SHIPPING_FLAT_COST', '5.00', 'What is the Shipping cost? The Handling fee will also be added.', '7', '6', now())");
  } elseif ($action == 'remove') {
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SHIPPING_FLAT_ENABLED'");
    tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SHIPPING_FLAT_COST'");
  }
?>
