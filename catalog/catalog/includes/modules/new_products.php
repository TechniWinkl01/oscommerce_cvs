<!-- new_products //-->
          <tr>
            <td class="tableHeading"><br>&nbsp;<? echo sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')); ?>&nbsp;</td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?
    if ($new_products_category_id == '0') {
      $new_products_query = tep_db_query("select p.products_id, pd.products_name, p.products_image, IFNULL(s.specials_new_products_price,p.products_price) as products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
    } else {
      $new_products_query = tep_db_query("select distinct p.products_id, pd.products_name, p.products_image, IFNULL(s.specials_new_products_price,p.products_price) as products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = c.categories_id and c.parent_id = '" . $new_products_category_id . "' and p.products_status = '1' order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
    }
    $row = 0;
    while ($new_products = tep_db_fetch_array($new_products_query)) {
      $row++;
      echo '                <td align="center" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id'], 'NONSSL') . '">' . tep_image($new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id'], 'NONSSL') . '">' . $new_products['products_name'] . '</a><br>' . tep_currency_format($new_products['products_price']) . '</td>' . "\n";
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