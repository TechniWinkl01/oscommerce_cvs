<?php /* $Id: upcoming_products.php,v 1.9 2001/01/26 22:22:00 dwatkins Exp $ */ ?>
<!-- upcoming_products //-->
<?
    $expected = tep_db_query("select products_name, UNIX_TIMESTAMP(products_expected.date_expected) AS u_date from products_expected order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT . " limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);
    if (tep_db_num_rows($expected) > 0) {
?>
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_UPCOMING_PRODUCTS; ?>&nbsp;</b></font></td>
                <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_DATE_EXPECTED; ?>&nbsp;</b></font></td>
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
      echo '                <td><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $expected_values['products_name'] . '&nbsp;</font></td>' . "\n";
      echo '                <td align="right"><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . strftime(DATE_FORMAT_SHORT, $expected_values['u_date']) . '&nbsp;</font></td>' . "\n";
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
