<?php
/*
  $Id: best_sellers.php,v 1.13 2001/12/19 01:37:55 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- best_sellers //-->
<?php
  if ($HTTP_GET_VARS['cPath']) {
    $best_sellers_query = tep_db_query("select p.products_id, pd.products_name, sum(op.products_quantity) as ordersum from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_status = '1' and p.products_id = op.products_id and pd.products_id = op.products_id and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = c.categories_id and ((c.categories_id = '" . $current_category_id . "') OR (c.parent_id = '" . $current_category_id . "')) group by p.products_id order by ordersum DESC, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  } else {
    $best_sellers_query = tep_db_query("select p.products_id, pd.products_name, sum(op.products_quantity) as ordersum from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_ORDERS_PRODUCTS . " op where p.products_status = '1' and p.products_id = op.products_id and pd.products_id = op.products_id and pd.language_id = '" . $languages_id . "' group by p.products_id order by ordersum DESC, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS);
  }

  if (tep_db_num_rows($best_sellers_query) > MIN_DISPLAY_BESTSELLERS) {
?>
          <tr>
            <td>
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => BOX_HEADING_BESTSELLERS
                                );
    new infoBoxHeading($info_box_contents, false, false);

    $rows = 0;
    $info_box_contents = array();
    while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
      $rows++;
      $info_box_contents[] = array(
                                   array('align' => 'center',
                                         'params' => 'valign="top" class="infoBox"',
                                         'text'  => tep_row_number_format($rows) . '.'
                                        ),
                                   array('align' => 'left',
                                         'params' => 'valign="top" class="infoBox"',
                                         'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($best_sellers['products_id']) . '&products_id=' . $best_sellers['products_id'], 'NONSSL') . '">' . $best_sellers['products_name'] . '</a>'
                                        )
                                  );
    }

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<?php
  }
?>
<!-- best_sellers_eof //-->

