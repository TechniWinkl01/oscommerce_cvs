<!-- best_sellers //-->
<?
  $class = 'class="blacklinkwithrollover"'; // To use standard attributes, enter ''
  $use_mouseover = 1; // To use no mouseover effect, enter 0

  if ($HTTP_GET_VARS['cPath']) {
    $best_sellers_query = tep_db_query("select p.products_id, p.products_name, sum(op.products_quantity) as ordersum from products p, orders_products op, products_to_categories p2c, categories c where p.products_id = op.products_id and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and ((c.categories_id = '" . $current_category_id . "') OR (c.parent_id = '" . $current_category_id . "')) group by p.products_id order by ordersum DESC, p.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  } else {
    $best_sellers_query = tep_db_query("select p.products_id, p.products_name, sum(op.products_quantity) as ordersum from products p, orders_products op where p.products_id = op.products_id group by p.products_id order by ordersum DESC, p.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  }
  if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
?>  
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2" bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<?=BOX_HEADING_BESTSELLERS;?>&nbsp;</font></td>
              </tr>
<?
    $rows = 0;
    while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
      $rows++;
      $product_info_query = tep_db_query("select m.manufacturers_location, m.manufacturers_name from products_to_manufacturers p2m, manufacturers m where p2m.manufacturers_id = m.manufacturers_id and p2m.products_id = '" . $best_sellers['products_id'] . "'");
      $product_info = tep_db_fetch_array($product_info_query);
      $products_name = tep_products_name($product_info['manufacturers_location'], $product_info['manufacturers_name'], $best_sellers['products_name']);
      tep_db_free_result($product_info_query);

      if ($use_mouseover) {
        echo '              <tr onclick="window.location.href=\'' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id'], 'NONSSL') . '\';" onmouseout="this.style.backgroundColor=\'' . BOX_CONTENT_BACKGROUND_COLOR . '\';" onmouseover="this.style.backgroundColor=\'' . BOX_CONTENT_HIGHLIGHT_COLOR . '\';this.style.cursor=\'hand\';">' . "\n";
      } else {
        echo '              <tr>' . "\n";
      }

      echo '                <td valign="top"><font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">' . $rows . '.</font></td>' . "\n";
      echo '                <td><font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">' . '<a ' . $class . 'href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id'], 'NONSSL') . '">' . $products_name . '</a></font></td>' . "\n";
      echo '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
<?
  }
?>
<!-- best_sellers_eof //-->