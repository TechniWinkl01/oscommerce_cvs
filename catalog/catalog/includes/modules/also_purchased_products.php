<?php
/*
  $Id: also_purchased_products.php,v 1.16 2002/01/11 22:17:57 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/
?>
<!-- also_purchased_products //-->
<?php
  if ($HTTP_GET_VARS['products_id']) {
    $orders_query = tep_db_query("select p.products_id, p.products_image, pd.products_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where o.orders_id = op.orders_id and op.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id <> '" . $HTTP_GET_VARS['products_id'] . "' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {

      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_ALSO_PURCHASED_PRODUCTS);
      new contentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($orders_values = tep_db_fetch_array($orders_query)) {
        $info_box_contents[$row][$col] = array('align' => 'center',
                                               'params' => 'class="smallText" width="33%" valign="top"',
                                               'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders_values['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders_values['products_image'], $orders_values['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders_values['products_id']) . '">' . $orders_values['products_name'] . '</a>');
        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }
      new contentBox($info_box_contents);
?>
<!-- also_purchased_products_eof //-->
<?php
    }
  }
?>
