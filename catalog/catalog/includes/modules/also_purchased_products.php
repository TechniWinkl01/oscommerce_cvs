<?php
/*
  $Id: also_purchased_products.php,v 1.18 2002/03/23 09:26:16 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  if ($HTTP_GET_VARS['products_id']) {
    $orders_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where opa.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . $HTTP_GET_VARS['products_id'] . "' and opb.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and opb.orders_id = o.orders_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
<!-- also_purchased_products //-->
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_ALSO_PURCHASED_PRODUCTS);
      new contentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($orders = tep_db_fetch_array($orders_query)) {
        $info_box_contents[$row][$col] = array('align' => 'center',
                                               'params' => 'class="smallText" width="33%" valign="top"',
                                               'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a>');
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
