<?
  if (($HTTP_GET_VARS['category_id']) && ($HTTP_GET_VARS['index_id']) && ($HTTP_GET_VARS['subcategory_id'])) {
    $listby_query = tep_db_query("select sql_select from category_index where category_index_id = '" . $HTTP_GET_VARS['index_id'] . "'");
    $listby_values = tep_db_fetch_array($listby_query);
    $listby = $listby_values['sql_select'];
    tep_db_free_result($listby_query) ;

    $subcategory_name_query = tep_db_query("select " . $listby . "_name as name from " . $listby . " where " . $listby . "_id = '" . $HTTP_GET_VARS['subcategory_id'] . "'");
    $subcategory_name_values = tep_db_fetch_array($subcategory_name_query);
    tep_db_free_result($subcategory_name_query) ;

    $box_header = BOX_HEADING_BESTSELLERS_IN . substr($subcategory_name_values['name'], 0, 15);
    
    $best_sellers_sql = "select products.products_id, products.products_name, sum(orders_products.products_quantity) as ordersum from products, orders_products, products_to_" . $listby . " where products.products_id = orders_products.products_id 
and products.products_id = products_to_" . $listby . ".products_id and products_to_" . $listby . "." . $listby . "_id = '" . $HTTP_GET_VARS['subcategory_id'] . "' group by products.products_id order by ordersum DESC, products.products_name 
limit " . MAX_DISPLAY_BESTSELLERS;
  }
  elseif ($HTTP_GET_VARS['category_id']) {
    $category_name_sql = tep_db_query("select category_top_name from category_top where category_top_id = '" . $HTTP_GET_VARS['category_id'] . "'");
    $category_name_values = tep_db_fetch_array($category_name_sql);
    tep_db_free_result($category_name_sql) ;

    $box_header = BOX_HEADING_BESTSELLERS_IN . $category_name_values['category_top_name'];
    
    $best_sellers_sql = "select products.products_id, products.products_name, sum(orders_products.products_quantity) as ordersum from products, orders_products, products_to_subcategories, subcategories_to_category where products.products_id = orders_products.products_id and products.products_id = products_to_subcategories.products_id and products_to_subcategories.subcategories_id = subcategories_to_category.subcategories_id and subcategories_to_category.category_top_id = '" . $HTTP_GET_VARS['category_id'] . "' group by products.products_id 
order by ordersum DESC, products.products_name limit " . MAX_DISPLAY_BESTSELLERS;
  }
  else {
    $best_sellers_sql = "select products.products_id, products.products_name, sum(orders_products.products_quantity) as ordersum from products, orders_products where products.products_id = orders_products.products_id group by products.products_id order by ordersum DESC, products.products_name limit " . MAX_DISPLAY_BESTSELLERS;
    $box_header = BOX_HEADING_BESTSELLERS ;
  }

  $best_sellers = tep_db_query($best_sellers_sql);
  if (tep_db_num_rows($best_sellers) >= MIN_DISPLAY_BESTSELLERS) {
?>  
<!-- best_sellers //-->
          <tr><td>
            <TABLE border="0" cellPadding="0" cellSpacing="0" width="100%">
            <TBODY>    
          
              <tr>
                <td colspan="2" bgcolor="<?=BOX_HEADING_BACKGROUND_COLOR;?>" class="boxborder" nowrap><font face="<?=BOX_HEADING_FONT_FACE;?>" color="<?=BOX_HEADING_FONT_COLOR;?>" size="<?=BOX_HEADING_FONT_SIZE;?>">&nbsp;&nbsp;<?=$box_header;?>&nbsp;&nbsp;</font></td>
              </tr>
<?
    $class = 'class="blacklinkwithrollover"';	// To use standard attributes, enter ''
    $use_mouseover = 1;			// To use no mouseover effect, enter 0

    $row_num = 0;
    while ($best_sellers_values = tep_db_fetch_array($best_sellers)) {
      $row_num++;

      // assuming the product will have only one manufacturer; if else the
      // name of the first manufacturer retrieved will be displayed
      $product_info_query = tep_db_query("select distinct subcategories_to_category.category_top_id, category_index.category_index_id, products_to_manufacturers.manufacturers_id, manufacturers.manufacturers_location, manufacturers.manufacturers_name  from category_index, category_index_to_top, products_to_subcategories, subcategories_to_category, products_to_manufacturers, manufacturers where category_index.category_index_id = category_index_to_top.category_index_id and products_to_subcategories.subcategories_id = subcategories_to_category.subcategories_id and subcategories_to_category.category_top_id = category_index_to_top.category_top_id and products_to_subcategories.products_id = products_to_manufacturers.products_id and products_to_manufacturers.manufacturers_id = manufacturers.manufacturers_id and category_index.sql_select = 'manufacturers' and products_to_subcategories.products_id = '" . $best_sellers_values['products_id'] . "'");
      $product_info_values = tep_db_fetch_array($product_info_query);

      $products_name = tep_products_name($product_info_values['manufacturers_location'], $product_info_values['manufacturers_name'], $best_sellers_values['products_name']);

      $jump_parameters = 'category_id=' . $product_info_values['category_top_id'] . '&index_id=' . $product_info_values['category_index_id'] . '&subcategory_id=' . $product_info_values['manufacturers_id'] . '&products_id=' . $best_sellers_values['products_id'];
      tep_db_free_result($product_info_query);
  
      if ($use_mouseover) {
        echo '              <tr onclick="window.location.href=\'' . tep_href_link(FILENAME_PRODUCT_INFO, $jump_parameters, 'NONSSL') . '\';" onmouseout="this.style.backgroundColor=\'' . BOX_CONTENT_BACKGROUND_COLOR . '\';" onmouseover="this.style.backgroundColor=\'' . BOX_CONTENT_HIGHLIGHT_COLOR . '\';this.style.cursor=\'hand\';">' . "\n";
      } else {
        echo '              <tr>' . "\n";
      }
  
      echo '                <td width="20%" align="center" valign="top"><font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">' . $row_num . '.</font></td>' . "\n";
      echo '                <td width="80%"><font face="' . BOX_CONTENT_FONT_FACE . '" color="' . BOX_CONTENT_FONT_COLOR . '" size="' . BOX_CONTENT_FONT_SIZE . '">' . '<a ' . $class . 'href="' . tep_href_link(FILENAME_PRODUCT_INFO, $jump_parameters, 'NONSSL') . '">' . $products_name . '</a></font></td>' . "\n";
      echo '              </tr>' . "\n";
    }
    echo "\n";
?>
            </TBODY>    
            </TABLE>
          </tr></td>
<!-- best_sellers_eof //-->
<?
  }
?>

