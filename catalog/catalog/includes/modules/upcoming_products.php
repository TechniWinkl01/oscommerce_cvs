<?php
/*
  $Id: upcoming_products.php,v 1.20 2001/09/22 20:26:05 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  $expected_query = tep_db_query("select p.products_id, pd.products_name, products_date_available as date_expected from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT . " limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);
  if (tep_db_num_rows($expected_query) > 0) {
?>
<!-- upcoming_products //-->
          <tr>
            <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="tableHeading">&nbsp;<?php echo TABLE_HEADING_UPCOMING_PRODUCTS; ?>&nbsp;</td>
                <td align="right" class="tableHeading">&nbsp;<?php echo TABLE_HEADING_DATE_EXPECTED; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr>
<?php
    $row = 0;
    while ($expected = tep_db_fetch_array($expected_query)) {
      $row++;
      if (($row / 2) == floor($row / 2)) {
        echo '              <tr class="upcomingProducts-even">' . "\n";
      } else {
        echo '              <tr class="upcomingProducts-odd">' . "\n";
      }
      echo '                <td class="smallText">&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected['products_id'], 'NONSSL') . '">' . $expected['products_name'] . '</a>&nbsp;</td>' . "\n";
      echo '                <td align="right" class="smallText">&nbsp;' . tep_date_short($expected['date_expected']) . '&nbsp;</td>' . "\n";
      echo '              </tr>' . "\n";
    }
?>
              <tr>
                <td colspan="2"><?php echo tep_black_line(); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
<!-- upcoming_products_eof //-->
