<!-- best_sellers //-->
<?
  if ($HTTP_GET_VARS['cPath']) {
    $best_sellers_query = tep_db_query("select p.products_id, pd.products_name, sum(op.products_quantity) as ordersum from products p, products_description pd, orders_products op, products_to_categories p2c, categories c where p.products_status = '1' and p.products_id = op.products_id and pd.products_id = op.products_id and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = c.categories_id and ((c.categories_id = '" . $current_category_id . "') OR (c.parent_id = '" . $current_category_id . "')) group by p.products_id order by ordersum DESC, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  } else {
    $best_sellers_query = tep_db_query("select p.products_id, pd.products_name, sum(op.products_quantity) as ordersum from products p, products_description pd, orders_products op where p.products_status = '1' and p.products_id = op.products_id and pd.products_id = op.products_id and pd.language_id = '" . $languages_id . "' group by p.products_id order by ordersum DESC, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  }

  if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
?>
          <tr>
            <td>
<?
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => BOX_HEADING_BESTSELLERS
                                );
    new infoBoxHeading($info_box_contents);

    $rows = 0;
    $info_box_contents = array();
    while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
      $rows++;
      $info_box_contents[] = array(
                                   array('align' => 'center',
                                         'params' => 'valign="top" class="infoBox"',
                                         'text'  => tep_row_number_format($rows)
                                        ),
                                   array('align' => 'left',
                                         'params' => 'valign="top" class="infoBox"',
                                         'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id'], 'NONSSL') . '">' . $best_sellers['products_name'] . '</a>'
                                        )
                                  );
    }

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<?
  }
?>
<!-- best_sellers_eof //-->

