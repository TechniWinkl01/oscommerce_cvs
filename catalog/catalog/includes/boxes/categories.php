<!-- categories //-->
          <tr>
            <td bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;<?=BOX_HEADING_CATEGORIES;?>&nbsp;</font></td>
          </tr>
<?
  if (($HTTP_GET_VARS['category_id']) && (!$HTTP_GET_VARS['index_id'])) {
    echo '          <tr>' . "\n";
    $categories = tep_db_query("select category_index.category_index_id, category_index.category_index_name from category_index, category_index_to_top where category_index_to_top.category_top_id = '" . $HTTP_GET_VARS['category_id'] . "' and category_index_to_top.category_index_id = category_index.category_index_id");
    echo '            <td bgcolor="' . BOX_CONTENT_BACKGROUND_COLOR . '" nowrap><font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">';
    while ($categories_values = tep_db_fetch_array($categories)) {
      echo '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'category_id=' . $HTTP_GET_VARS['category_id'] . '&index_id=' . $categories_values['category_index_id'], 'NONSSL') . '">' . $categories_values['category_index_name'] . '</a><br>';
    }
    echo '</font></td>' . "\n";
    echo '          </tr>' . "\n";
  } elseif (($HTTP_GET_VARS['category_id']) && ($HTTP_GET_VARS['index_id'])) {
    echo '          <tr>' . "\n";
    $listby_query = tep_db_query("select sql_select from category_index where category_index_id = '" . $HTTP_GET_VARS['index_id'] . "'");
    $listby_values = tep_db_fetch_array($listby_query);
    $listby = $listby_values['sql_select'];
    $subcategories = tep_db_query("select " . $listby . "." . $listby . "_id as id, " . $listby . "." . $listby . "_name as name from " . $listby . ", " . $listby . "_to_category where " . $listby . "_to_category.category_top_id = '" . $HTTP_GET_VARS['category_id'] . "' and " . $listby . "_to_category." . $listby . "_id = " . $listby . "." . $listby . "_id order by " . $listby . "." . $listby . "_name");
    echo '            <td bgcolor="' . BOX_CONTENT_BACKGROUND_COLOR . '" nowrap><font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">';
    while ($subcategories_values = tep_db_fetch_array($subcategories)) {
      $number_of_products = tep_db_query("select count(*) as total from products_to_" . $listby . " where " . $listby . "_id = '" . $subcategories_values['id'] . "'");
      $number_of_products_values = tep_db_fetch_array($number_of_products);
      echo '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_LIST, 'category_id=' . $HTTP_GET_VARS['category_id'] . '&index_id=' . $HTTP_GET_VARS['index_id'] . '&subcategory_id=' . $subcategories_values['id'], 'NONSSL') . '">' . $subcategories_values['name'] . '</a>&nbsp;(' . $number_of_products_values['total'] . ')&nbsp;<br>';
    }
    echo '</font></td>' . "\n";
    echo '          </tr>' . "\n";
  } else {
    echo '          <tr>' . "\n";
    $top_categories = tep_db_query("select category_top_id, category_top_name from category_top order by sort_order");
    echo '            <td bgcolor="' . BOX_CONTENT_BACKGROUND_COLOR . '" nowrap><font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">';
    while ($top_categories_values = tep_db_fetch_array($top_categories)) {
      $total_products = tep_db_query("select count(*) as total from products, products_to_subcategories, subcategories_to_category where products.products_id = products_to_subcategories.products_id and products_to_subcategories.subcategories_id = subcategories_to_category.subcategories_id and subcategories_to_category.category_top_id = '" . $top_categories_values['category_top_id'] . "'");
      $total_products_values = tep_db_fetch_array($total_products);
      echo '<b><a href="' . tep_href_link(FILENAME_DEFAULT, 'category_id=' . $top_categories_values['category_top_id'], 'NONSSL') . '">' . $top_categories_values['category_top_name'] . '</a>: (' . $total_products_values['total'] . ')</b><br>';
      $index_to_top = tep_db_query("select category_index_to_top.category_top_id, category_index_to_top.category_index_id, category_index.category_index_name from category_index, category_index_to_top where category_index_to_top.category_top_id = '" . $top_categories_values['category_top_id'] . "' and category_index_to_top.category_index_id = category_index.category_index_id order by category_index_to_top.sort_order");
      if (!tep_db_num_rows($index_to_top)) {
        $subcategories_to_top = tep_db_query("select subcategories.subcategories_id, subcategories.subcategories_name from subcategories, subcategories_to_category where subcategories_to_category.category_top_id = '" . $top_categories_values['category_top_id'] . "' and subcategories_to_category.subcategories_id = subcategories.subcategories_id");
        while ($subcategories_to_top_values = tep_db_fetch_array($subcategories_to_top)) {
          echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_LIST, 'category_id=' . $top_categories_values['category_top_id'] . '&subcategory_id=' . $subcategories_to_top_values['subcategories_id'], 'NONSSL') . '">' . $subcategories_to_top_values['subcategories_name'] . '</a><br>';
        }
      } else {
        while ($index_to_top_values = tep_db_fetch_array($index_to_top)) {
          echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'category_id=' . $index_to_top_values['category_top_id'] . '&index_id=' . $index_to_top_values['category_index_id'], 'NONSSL') . '">' . $index_to_top_values['category_index_name'] . '</a><br>';
        }
      }
    }
    echo '</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
<!-- categories_eof //-->
