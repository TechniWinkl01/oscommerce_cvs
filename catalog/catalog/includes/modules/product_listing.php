<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
<?
  if (PRODUCT_LIST_MODEL) echo '<td nowrap><font face="' . TABLE_HEADING_FONT_FACE . '" size="' . TABLE_HEADING_FONT_SIZE .'" color="' . TABLE_HEADING_FONT_COLOR . '"><b>&nbsp;' . TABLE_HEADING_MODEL . '&nbsp;</b></font></td>';
?>
    <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<? echo TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
    <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<? echo TABLE_HEADING_PRICE;?>&nbsp;</b></font></td>
  </tr>
  <tr>
<?
  $colspan = 2;
  if (PRODUCT_LIST_MODEL) $colspan++;
  echo '    <td colspan="' . $colspan . '">' . tep_black_line() . '</td>';
  echo '  </tr>';
  $listing_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $listing_sql, $listing_numrows);
  $listing = tep_db_query($listing_sql);
  $number_of_products = '0';
  if (tep_db_num_rows($listing)) {
    while ($listing_values = tep_db_fetch_array($listing)) {
      $number_of_products++;
      if (($number_of_products / 2) == floor($number_of_products / 2)) {
        echo '  <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '  <tr bgcolor="#f4f7fd">' . "\n";
      }
      if (PRODUCT_LIST_MODEL) echo '    <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $listing_values['products_model'] . '&nbsp;</font></td>';
      if ($HTTP_GET_VARS['manufacturers_id']) {
        echo '    <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing_values['products_id'], 'NONSSL') . '">';
      } else {
        echo '    <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&products_id=' . $listing_values['products_id'], 'NONSSL') . '">';
      }
      echo $listing_values['products_name'] . '</a>&nbsp;</font></td>' . "\n";
      $check_special = tep_db_query("select specials.specials_new_products_price from specials where products_id = '" . $listing_values['products_id'] . "'");
      if (tep_db_num_rows($check_special)) {
        $check_special_values = tep_db_fetch_array($check_special);
        $new_price = $check_special_values['specials_new_products_price'];
      }
      echo '    <td align="right" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;';
      if ($new_price) {
        echo '<s>' .  tep_currency_format($listing_values['products_price']) . '</s>&nbsp;&nbsp;<font color="' . SPECIALS_PRICE_COLOR . '">' . tep_currency_format($new_price) . '</font>';
        unset($new_price);
      } else {
        echo tep_currency_format($listing_values['products_price']);
      }
      echo '&nbsp;</font></td>' . "\n";
      echo '  </tr>' . "\n";
    }
  } else {
?>
  <tr bgcolor="#f4f7fd">
    <td colspan="<? echo $colspan;?>" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE;?>" size="<? echo SMALL_TEXT_FONT_SIZE;?>" color="<? echo SMALL_TEXT_FONT_COLOR;?>">&nbsp;<? echo ($HTTP_GET_VARS['manufacturers_id'] ? TEXT_NO_PRODUCTS2 : TEXT_NO_PRODUCTS);?>&nbsp;</font></td>
  </tr>
<?
  }
?>
  <tr>
    <td colspan="<? echo $colspan;?>"><? echo tep_black_line();?></td>
  </tr>
<?
  if ($listing_numrows > 0) {
?>
  <tr>
    <td colspan="<? echo $colspan;?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
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
