<?php /* $Id: upcoming_products.php,v 1.11 2001/04/13 19:46:11 hpdl Exp $ */ ?>
<!-- upcoming_products //-->
<?
    $expected = tep_db_query("select products_id, products_name, UNIX_TIMESTAMP(products_date_available) date_expected from products where products_date_available != '' order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT . " limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);
    if (tep_db_num_rows($expected) > 0) {
?>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?><b>&nbsp;<? echo TABLE_HEADING_UPCOMING_PRODUCTS; ?>&nbsp;</b></font></td>
                <td align="right" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?><b>&nbsp;<? echo TABLE_HEADING_DATE_EXPECTED; ?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
<?
    $row = 0;
    while ($expected_values = tep_db_fetch_array($expected)) {
      $row++;
      if (($row / 2) == floor($row / 2)) {
        echo '              <tr bgcolor="' . TABLE_ROW_BACKGROUND_COLOR . '">' . "\n";
      } else {
        echo '              <tr bgcolor="' . TABLE_ALT_BACKGROUND_COLOR . '">' . "\n";
      }
      echo '                <td>' . FONT_STYLE_SMALL_TEXT . '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected_values['products_id'], 'NONSSL') . '">' . $expected_values['products_name'] . '</a>&nbsp;</font></td>' . "\n";
      echo '                <td align="right">' . FONT_STYLE_SMALL_TEXT . '&nbsp;' . strftime(DATE_FORMAT_SHORT, $expected_values['date_expected']) . '&nbsp;</font></td>' . "\n";
      echo '              </tr>' . "\n";
    }
?>
              <tr>
                <td colspan="2"><? echo tep_black_line(); ?></td>
              </tr>
            </table></td>
          </tr>
<?
    }
?>
<!-- upcoming_products_eof //-->
