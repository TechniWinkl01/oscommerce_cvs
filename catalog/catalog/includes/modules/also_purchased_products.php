<?
  if ($HTTP_GET_VARS['products_id']) {
    $orders_query = tep_db_query("select distinct p.products_id, p.products_name from orders_products op1, orders_products op2, orders o, products p where op1.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and op1.orders_id = op2.orders_id and op2.products_id <> '" . $HTTP_GET_VARS['products_id'] . "' and op2.products_id = p.products_id and op2.orders_id = o.orders_id order by p.products_name");
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
<!-- also_purchased_products //-->
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>">&nbsp;<b><? echo TEXT_ALSO_PURCHASED_PRODUCTS; ?></b>&nbsp;</font></td>
              </tr>
              <tr>
                <td><? echo tep_black_line(); ?></td>
              </tr>
<?
    // randomly select products from products ordered
    $rows_to_display = array();

    srand((double)microtime()*1000000); // seed the random number generator
    while (sizeof($rows_to_display) < MAX_DISPLAY_ALSO_PURCHASED && sizeof($rows_to_display) < $num_products_ordered) {
      $random_row = @rand(0, ($num_products_ordered - 1));
      if (!tep_in_array($random_row, $rows_to_display)) $rows_to_display[sizeof($rows_to_display)] = $random_row;
    }
    sort($rows_to_display);
    for ($i = 0; $i < sizeof($rows_to_display); $i++) {
      tep_db_data_seek($orders_query, $rows_to_display[$i]);
      $orders_values = tep_db_fetch_array($orders_query);
      if ((($i + 1) / 2) == floor(($i + 1) / 2)) {
        echo '              <tr bgcolor="' . TABLE_ROW_BACKGROUND_COLOR . '">' . "\n";
      } else {
        echo '              <tr bgcolor="' . TABLE_ALT_BACKGROUND_COLOR . '">' . "\n";
      }
      echo '                <td ><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders_values['products_id'], 'NONSSL') . '">' . $orders_values['products_name'] . '</a>&nbsp;</font></td>' . "\n";
      echo '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<!-- also_purchased_products_eof //-->
<?
    }
  }
?>