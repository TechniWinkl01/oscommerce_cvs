<?
  // define('SHIPPING_ITEM_NAME', 'Per Item');
  // define('SHIPPING_ITEM_WAY', 'Best Way');
  // define('SHIPPING_ITEM_COST', '2.50');

  if ($action == 'select') {
?>
              <tr>
                <td>&nbsp<? echo SHIPPING_ITEM_NAME; ?></td>
                <td>&nbsp</td>
                <td>&nbsp<input type="checkbox" name="shipping_quote_item" value="1"></td>
             </tr>
<?
  } elseif ($action == 'quote') {
      if ($HTTP_POST_VARS['shipping_quote_item'] == "1") {
        $shipping_item_cost = SHIPPING_HANDLING + (SHIPPING_ITEM_COST * $total_count);
        $shipping_item_method = SHIPPING_ITEM_WAY;
        echo "              <tr>\n";
        echo '                <td>&nbsp' . SHIPPING_ITEM_NAME . "</td>\n";
        echo '                <td>' . $shipping_item_method . "</td>\n";
        echo '                <td align="right">' . tep_currency_format($shipping_item_cost) . "</td>\n";
        echo '                <td>&nbsp<input type="radio" name="shipping_selected" value="item"';
        if ($shipping_quotes == 0) echo ' CHECKED';
        echo "></td>\n";
        echo "              </tr>\n";
        $shipping_quotes = $shipping_quotes+1;
        echo '              <input type="hidden" name="shipping_item_cost" value=' . $shipping_item_cost . ">\n";
        echo '              <input type="hidden" name="shipping_item_method" value=' . $shipping_item_method . ">\n";
      }
  } elseif ($action == 'confirm') {
      if ($HTTP_POST_VARS['shipping_selected'] == 'item') {
        $shipping_cost = $HTTP_POST_VARS['shipping_item_cost'];
        $shipping_method = $HTTP_POST_VARS['shipping_item_method'];
      }
  }
?>

