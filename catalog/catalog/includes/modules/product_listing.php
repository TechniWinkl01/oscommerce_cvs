<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
<?
  $colspan = 2;
  if (PRODUCT_LIST_MODEL) $colspan++;
  if (PRODUCT_LIST_MANUFACTURER) $colspan++;
  if (PRODUCT_LIST_BUY_NOW) $colspan++;

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
  
  if (PRODUCT_LIST_MODEL) echo '<td nowrap><font face="' . TABLE_HEADING_FONT_FACE . '" size="' . TABLE_HEADING_FONT_SIZE .'" color="' . TABLE_HEADING_FONT_COLOR . '"><b>&nbsp;' . tep_create_sort_heading($HTTP_GET_VARS['sort'], 1, TABLE_HEADING_MODEL) . '&nbsp;</b></font></td>';
?>
    <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo tep_create_sort_heading($HTTP_GET_VARS['sort'], 2, TABLE_HEADING_PRODUCTS); ?>&nbsp;</b></font></td>
<?
  if (PRODUCT_LIST_MANUFACTURER) echo '<td nowrap><font face="' . TABLE_HEADING_FONT_FACE . '" size="' . TABLE_HEADING_FONT_SIZE .'" color="' . TABLE_HEADING_FONT_COLOR . '"><b>&nbsp;' . tep_create_sort_heading($HTTP_GET_VARS['sort'], 3, TABLE_HEADING_MANUFACTURER) . '&nbsp;</b></font></td>';
?>
    <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo tep_create_sort_heading($HTTP_GET_VARS['sort'], 4, TABLE_HEADING_PRICE); ?>&nbsp;</b></font></td>
<? 
  if (PRODUCT_LIST_BUY_NOW) echo '<td align="center" nowrap><font face="' . TABLE_HEADING_FONT_FACE . '" size="' . TABLE_HEADING_FONT_SIZE .'" color="' . TABLE_HEADING_FONT_COLOR . '"><b>&nbsp;' . TABLE_HEADING_BUY_NOW . '&nbsp;</b></font></td>';
?>
  </tr>
  <tr>
<?
  echo '    <td colspan="' . $colspan . '">' . tep_black_line() . '</td>';
  echo '  </tr>';
  if ($listing_numrows > 0) {
    $number_of_products = '0';
    $listing = tep_db_query($listing_sql);
    while ($listing_values = tep_db_fetch_array($listing)) {
      $number_of_products++;
      if (($number_of_products / 2) == floor($number_of_products / 2)) {
        echo '  <tr bgcolor="' . TABLE_ROW_BACKGROUND_COLOR . '">' . "\n";
      } else {
        echo '  <tr bgcolor="' . TABLE_ALT_BACKGROUND_COLOR . '">' . "\n";
      }
      if (PRODUCT_LIST_MODEL) echo '    <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $listing_values['products_model'] . '&nbsp;</font></td>';
      if ($HTTP_GET_VARS['manufacturers_id']) {
        echo '    <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing_values['products_id'], 'NONSSL') . '">';
      } else {
        echo '    <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . ($HTTP_GET_VARS['cPath'] ? $HTTP_GET_VARS['cPath'] : tep_get_product_path($listing_values['products_id']) ) . '&products_id=' . $listing_values['products_id'], 'NONSSL') . '">';
      }
      echo $listing_values['products_name'] . '</a>&nbsp;</font></td>' . "\n";
      if (PRODUCT_LIST_MANUFACTURER) echo '    <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing_values['manufacturers_id'], 'NONSSL') . '">' . $listing_values['manufacturers_name'] . '</a>&nbsp;</font></td>';
      echo '    <td align="right" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;';
      if ($listing_values['specials_new_products_price']) {
        echo '<s>' .  tep_currency_format($listing_values['products_price']) . '</s>&nbsp;&nbsp;<font color="' . SPECIALS_PRICE_COLOR . '">' . tep_currency_format($listing_values['specials_new_products_price']) . '</font>';
      } else {
        echo tep_currency_format($listing_values['products_price']);
      }
      echo '&nbsp;</font></td>' . "\n";
      if (PRODUCT_LIST_BUY_NOW) {
        echo '    <form method="post" action="' . tep_href_link(FILENAME_SHOPPING_CART, 'action=add_update_product', 'NONSSL') . '"><td align="center" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<input type="hidden" name="cart_quantity" value="1"><input type="hidden" name="products_id" value="' . $listing_values['products_id'] . '">' . tep_image_submit(DIR_IMAGES . 'button_buy_now.gif', '50', '14', '0', TEXT_BUY . $listing_values['products_name'] . TEXT_NOW) . '&nbsp;</font></td></form>';
      }
      echo '  </tr>' . "\n";
    }
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
