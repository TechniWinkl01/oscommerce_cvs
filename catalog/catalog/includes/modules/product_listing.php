<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
<?
  // create column list
  $configuration_query = tep_db_query("select c.configuration_key from configuration_group cg, configuration c where cg.configuration_group_title = 'Product Listing' and cg.configuration_group_id = c.configuration_group_id and c.configuration_value != '0' and c.configuration_key not in ('PRODUCT_LIST_FILTER', 'PREV_NEXT_BAR_LOCATION', 'PRODUCT_LIST_USE_ROLLOVER', 'PRODUCT_LIST_BACKGROUND_COLOR', 'PRODUCT_LIST_ALTERNATE_COLOR') order by c.configuration_value");

  $column_list = array();

  while ($configuration = tep_db_fetch_array($configuration_query)) {
    $column_list[] = $configuration['configuration_key'];
  }

  $colspan = sizeof($column_list);

  $listing_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $listing_sql, $listing_numrows);

  if ($listing_numrows > 0 && (PREV_NEXT_BAR_LOCATION == '1' || PREV_NEXT_BAR_LOCATION == '3')) {
?>
  <tr>
    <td colspan="<? echo $colspan; ?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $listing_split->display_count($listing_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?>&nbsp;</font></td>
        <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $listing_split->display_links($listing_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>&nbsp;</font></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="<? echo $colspan; ?>"><? echo tep_black_line(); ?></td>
  </tr>
<?
  }
?>
  <tr>
    <td nowrap>
<?
  $list_box_contents = array();
  $list_box_contents[] = array( );
  $cur_row = sizeof($list_box_contents) - 1;

  for ($col=0; $col<sizeof($column_list); $col++) {
    switch ($column_list[$col]) {
      case 'PRODUCT_LIST_MODEL':
        $lc_text = TABLE_HEADING_MODEL;
        $lc_align = 'left';
        break;
      case 'PRODUCT_LIST_NAME':
        $lc_text = TABLE_HEADING_PRODUCTS;
        $lc_align = 'left';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $lc_text = TABLE_HEADING_MANUFACTURER;
        $lc_align = 'left';
        break;
      case 'PRODUCT_LIST_PRICE':
        $lc_text = TABLE_HEADING_PRICE;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $lc_text = TABLE_HEADING_QUANTITY;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $lc_text = TABLE_HEADING_WEIGHT;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $lc_text = TABLE_HEADING_IMAGE;
        $lc_align = 'center';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = TABLE_HEADING_BUY_NOW;
        $lc_align = 'center';
        break;
    }
    
    if ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW' &&
        $column_list[$col] != 'PRODUCT_LIST_IMAGE')
      $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_text);

    $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                           'text'  => "&nbsp;<B>$lc_text</B>&nbsp;");
  }

  if ($listing_numrows > 0) {
    $number_of_products = '0';
    $listing = tep_db_query($listing_sql);
    while ($listing_values = tep_db_fetch_array($listing)) {
      $number_of_products++;

      $list_box_contents[] = array();
      $cur_row = sizeof($list_box_contents) - 1;
      
      for ($col=0; $col<sizeof($column_list); $col++) {
        $lc_align = '';
        $lc_params = 'nowrap';
        $lc_form = '';

        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_text = '&nbsp;' . $listing_values['products_model'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_NAME':
            if ($HTTP_GET_VARS['manufacturers_id']) {
              $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing_values['products_id'], 'NONSSL') . '">' . $listing_values['products_name'] . '</a>';
            } else {
              $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . ($HTTP_GET_VARS['cPath'] ? $HTTP_GET_VARS['cPath'] : tep_get_product_path($listing_values['products_id']) ) . '&products_id=' . $listing_values['products_id'], 'NONSSL') . '">' . $listing_values['products_name'] . '</a>&nbsp;';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing_values['manufacturers_id'], 'NONSSL') . '">' . $listing_values['manufacturers_name'] . '</a>&nbsp;';
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';
            if ($listing_values['specials_new_products_price']) {
              $lc_text = '&nbsp;<s>' .  tep_currency_format($listing_values['products_price']) . '</s>&nbsp;&nbsp;<font color="' . SPECIALS_PRICE_COLOR . '">' . tep_currency_format($listing_values['specials_new_products_price']) . '&nbsp;';
            } else {
              $lc_text = '&nbsp;' . tep_currency_format($listing_values['products_price']) . '&nbsp;';
            }
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing_values['products_quantity'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing_values['products_weight'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            $lc_text = '&nbsp;' . tep_image($listing_values['products_image'], $listing_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '&nbsp;';
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $lc_align = 'center';
            $lc_form = '<form method="post" action="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=add_update_product', 'NONSSL') . '">';
            $lc_text = '&nbsp;<input type="hidden" name="cart_quantity" value="1"><input type="hidden" name="products_id" value="' . $listing_values['products_id'] . '">' . tep_image_submit(DIR_IMAGES . 'button_buy_now.gif', TEXT_BUY . $listing_values['products_name'] . TEXT_NOW) . '&nbsp;';

            break;
        }

        $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => $lc_params,
                                               'form' => $lc_form,
                                               'text'  => $lc_text);

      }
    }
    new listBox($list_box_contents);

    echo '    </td>' . "\n";
    echo '  </tr>' . "\n";
  } else {
?>
  <tr bgcolor="<? echo TABLE_ALT_BACKGROUND_COLOR; ?>">
    <td colspan="<? echo $colspan; ?>" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo ($HTTP_GET_VARS['manufacturers_id'] ? TEXT_NO_PRODUCTS2 : TEXT_NO_PRODUCTS); ?>&nbsp;</font></td>
  </tr>
<?
  }
?>
  <tr>
    <td colspan="<? echo $colspan; ?>"><? echo tep_black_line(); ?></td>
  </tr>
<?
  if ($listing_numrows > 0 && (PREV_NEXT_BAR_LOCATION == '2' || PREV_NEXT_BAR_LOCATION == '3')) {
?>
  <tr>
    <td colspan="<? echo $colspan; ?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $listing_split->display_count($listing_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?>&nbsp;</font></td>
        <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $listing_split->display_links($listing_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>&nbsp;</font></td>
      </tr>
    </table></td>
  </tr>
<?
  }
?>
</table>
