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
  }
?>

