<!-- categories //-->
          <tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<?=BOX_HEADING_CATEGORIES;?>&nbsp;</font></td>
          </tr>
          <tr>
<?
  if (($HTTP_GET_VARS['cPath']) && (ereg('_', $HTTP_GET_VARS['cPath']))) {
// check to see if there are deeper categories within the current category
    $category_links = array_reverse($cPath_array);
    for($i=0;$i<sizeof($category_links);$i++) {
      $categories = tep_db_query("select categories_id, categories_name, parent_id from categories where parent_id = '" . $category_links[$i] . "' order by sort_order");
      if (tep_db_num_rows($categories) < 1) {
        // do nothing, go through the loop
      } else {
        break; // we've found the deepest category the customer is in
      }
    }
  } else {
    $categories = tep_db_query("select categories_id, categories_name, parent_id from categories where parent_id = '" . $current_category_id . "' order by sort_order");
  }
  echo '            <td bgcolor="' . BOX_CONTENT_BACKGROUND_COLOR . '" nowrap><font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">';
  while ($categories_values = tep_db_fetch_array($categories)) {
    if (@$HTTP_GET_VARS['cPath']) {
      $total_products = tep_db_query("select count(*) as total from products p, products_to_categories p2c, categories c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = c.categories_id and c.categories_id = '" . $categories_values['categories_id'] . "'");
    } else {
      $total_products = tep_db_query("select count(*) as total from products p, products_to_categories p2c, categories c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = c.categories_id and c.parent_id = '" . $categories_values['categories_id'] . "'");
    }
    $total_products_values = tep_db_fetch_array($total_products);
    $cPath_new = tep_get_path($categories_values['categories_id']);
    echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . $categories_values['categories_name'] . '</a> (' . $total_products_values['total'] . ')<br>';
  }
  echo '</font></td>' . "\n";
?>
          </tr>
<!-- categories_eof //-->
