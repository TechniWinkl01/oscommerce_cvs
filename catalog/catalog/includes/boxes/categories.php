<!-- categories //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_CATEGORIES
                              );
  new infoBoxHeading($info_box_contents);

  $categories_string = '';
  if (($HTTP_GET_VARS['cPath']) && (ereg('_', $HTTP_GET_VARS['cPath']))) {
// check to see if there are deeper categories within the current category
    $category_links = tep_array_reverse($cPath_array);
    for($i=0;$i<sizeof($category_links);$i++) {
      $categories = tep_db_query("select categories_id, categories_name, parent_id from categories where parent_id = '" . $category_links[$i] . "' order by sort_order, categories_name");
      if (tep_db_num_rows($categories) < 1) {
        // do nothing, go through the loop
      } else {
        break; // we've found the deepest category the customer is in
      }
    }
  } else {
    $categories = tep_db_query("select categories_id, categories_name, parent_id from categories where parent_id = '" . $current_category_id . "' order by sort_order, categories_name");
  }

  while ($categories_values = tep_db_fetch_array($categories)) {
    $count_str = '';
    if (SHOW_COUNTS) {
      if (USE_RECURSIVE_COUNT) {  
        $total_count = tep_count_products_in_category($categories_values['categories_id']);
      } else {
        if (@$HTTP_GET_VARS['cPath']) {
          $total_products = tep_db_query("select count(*) as total from products p, products_to_categories p2c, categories c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = c.categories_id and c.categories_id = '" . $categories_values['categories_id'] . "'");
        } else {
          $total_products = tep_db_query("select count(*) as total from products p, products_to_categories p2c, categories c where p.products_id = p2c.products_id and p.products_status = 1 and p2c.categories_id = c.categories_id and c.parent_id = '" . $categories_values['categories_id'] . "'");
        }
        $total_products_values = tep_db_fetch_array($total_products);
        $total_count = $total_products_values['total'];
      }
      if ($total_count > 0) $count_str = ' (' . $total_count . ')';
    }

    $cPath_new = tep_get_path($categories_values['categories_id']);
    $categories_string .= '<a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . $categories_values['categories_name'] . '</a>' . $count_str . '<br>';
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $categories_string
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- categories_eof //-->
