<!-- new_products //-->
          <tr>
            <td nowrap><br><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>">&nbsp;<b><?=sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B'));?></b>&nbsp;</font></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
    if ($new_products_category_id == '0') {
      $new_products_query = tep_db_query("select p.products_id, p.products_name, p.products_image, m.manufacturers_name, m.manufacturers_location from products p, products_to_manufacturers p2m, manufacturers m where p.products_status = '1' and p.products_id = p2m.products_id and p2m.manufacturers_id = m.manufacturers_id order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
    } else {
      $new_products_query_string = "select p.products_id, p.products_name, p.products_image, m.manufacturers_name, m.manufacturers_location
                from products p
                left join products_to_manufacturers p2m on p.products_id=p2m.products_id
                left join manufacturers m on p2m.manufacturers_id=m.manufacturers_id
                left join products_to_categories p2c on p.products_id=p2c.products_id
                left join categories c on p2c.categories_id=c.categories_id
                where p.products_status='1'
                and c.parent_id = " . $new_products_category_id . "
                order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS;
      $new_products_query = tep_db_query($new_products_query_string);
    }
    $row = 0;
    while ($new_products = tep_db_fetch_array($new_products_query)) {
      $row++;
      $products_name = tep_products_name($new_products['manufacturers_location'], $new_products['manufacturers_name'], $new_products['products_name']);
      echo '                <td align="center"><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id'], 'NONSSL') . '">' . tep_image($new_products['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0', $products_name) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id'], 'NONSSL') . '">' . $products_name . '</a></font></td>' . "\n";
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
            <td><?=tep_black_line();?></td>
          </tr>
<!-- new_products_eof //-->
