<!-- upcoming_products //-->
<?
    $expected = tep_db_query("select products_name, date_expected from products_expected order by date_expected DESC limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);
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
        echo '              <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '              <tr bgcolor="#f4f7fd">' . "\n";
      }
      echo '                <td><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $expected_values['products_name'] . '&nbsp;</font></td>' . "\n";
      echo '                <td align="right"><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . strftime(DATE_FORMAT_SHORT, mktime(0,0,0,substr($expected_values['date_expected'], 4, 2), substr($expected_values['date_expected'], 6, 2), substr($expected_values['date_expected'], 0, 4))) . '&nbsp;</font></td>' . "\n";
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
