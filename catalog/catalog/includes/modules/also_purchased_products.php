<?
  if ($HTTP_GET_VARS['products_id']) {
    $orders_query = tep_db_query("select distinct products.products_id, products.products_name, manufacturers.manufacturers_id, manufacturers.manufacturers_name, manufacturers.manufacturers_location 
from orders_products a, orders_products b, orders, products, products_to_manufacturers, manufacturers where a.products_id = $HTTP_GET_VARS[products_id] and a.orders_id = b.orders_id and b.products_id <> $HTTP_GET_VARS[products_id] and b.products_id = products.products_id and b.orders_id = orders.orders_id and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by products.products_name");

    $num_products_ordered = tep_db_num_rows($orders_query);

    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
<!-- also_purchased_products //-->
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><? echo tep_black_line();?></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><? echo TEXT_ALSO_PURCHASED_PRODUCTS;?></b>&nbsp;</font></td>
              </tr>
              <tr>
                <td><? echo tep_black_line();?></td>
              </tr>
<?
    // randomly select products from products ordered
    $rows_to_display = array();

    srand((double)microtime()*1000000); // seed the random number generator
    while (sizeof($rows_to_display) < MAX_DISPLAY_ALSO_PURCHASED && sizeof($rows_to_display) < $num_products_ordered) {
      $random_row = @rand(0, ($num_products_ordered - 1));
      
      if (!tep_in_array($random_row, $rows_to_display))
        $rows_to_display[sizeof($rows_to_display)] = $random_row;
    }
    
    sort($rows_to_display);
    
    for ($i = 0; $i < sizeof($rows_to_display); $i++) {
      tep_db_data_seek($orders_query, $rows_to_display[$i]);
      $orders_values = tep_db_fetch_array($orders_query);

      $products_name = tep_products_name($orders_values['manufacturers_location'], $orders_values['manufacturers_name'], $orders_values['products_name']);

      if ((($i + 1) / 2) == floor(($i + 1) / 2)) {
        echo '              <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '              <tr bgcolor="#f4f7fd">' . "\n";
      }
      echo '                <td ><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders_values['products_id'], 'NONSSL') . '">' . $products_name . '</a>&nbsp;</font></td>' . "\n";
      echo '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line();?></td>
          </tr>
<!-- also_purchased_products_eof //-->
<?
    }
  }
?>