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
    if ($np_category_id == '0') {
    $new = tep_db_query("select products.products_id, products.products_name, products.products_image, manufacturers.manufacturers_name, manufacturers.manufacturers_location from products, products_to_manufacturers, manufacturers where products.products_status='1' and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id order by products.products_date_added DESC, products.products_id DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
    } else {
    $new = tep_db_query("select products.products_id, products.products_name, products.products_image, manufacturers.manufacturers_name, manufacturers.manufacturers_location from products, products_to_manufacturers, manufacturers, products_to_subcategories, subcategories_to_category where products.products_status='1' and products.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id and products.products_id = products_to_subcategories.products_id and products_to_subcategories.subcategories_id = subcategories_to_category.subcategories_id and subcategories_to_category.category_top_id = '" . $np_category_id . "' order by products.products_date_added DESC, products.products_id DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
    }
    $row = 0;
    while ($new_values = tep_db_fetch_array($new)) {
      $row++;
      $products_name = tep_products_name($new_values['manufacturers_location'], $new_values['manufacturers_name'], $new_values['products_name']);
      echo '                <td align="center"><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_values['products_id'], 'NONSSL') . '">' . tep_image($new_values['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, '0', $products_name) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_values['products_id'], 'NONSSL') . '">' . $products_name . '</a></font></td>' . "\n";
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
