<!-- new_products //-->
          <tr>
            <td nowrap><br><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>">&nbsp;<b><? echo sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')); ?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
    if ($new_products_category_id == '0') {
      $new_products_query = tep_db_query("select products_id, products_name, products_image from products where products_status = '1' order by products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
    } else {
      $new_products_query = tep_db_query("select p.products_id, p.products_name, p.products_image from products p, products_to_categories p2c, categories c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . $new_products_category_id . "' and p.products_status = '1' order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
    }
    $row = 0;
    while ($new_products = tep_db_fetch_array($new_products_query)) {
      $row++;
      echo '                <td align="center"><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id'], 'NONSSL') . '">' . tep_image($new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id'], 'NONSSL') . '">' . $new_products['products_name'] . '</a></font></td>' . "\n";
      if ((($row / 3) == floor($row / 3)) && ($row != MAX_DISPLAY_NEW_PRODUCTS)) {
        echo '              </tr>' . "\n";
        echo '              <tr>' . "\n";
        echo '                <td>&nbsp;</td>' . "\n";
        echo '              </tr>' . "\n";
        echo '              <tr>' . "\n";
      }
    }
?>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<!-- new_products_eof //-->
