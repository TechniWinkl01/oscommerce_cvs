<?php
/*
  $Id: also_purchased_products.php,v 1.14 2002/01/11 19:20:11 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/
?>
<!-- also_purchased_products //-->
<?php
  if ($HTTP_GET_VARS['products_id']) {
    $orders_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name from " . TABLE_ORDERS_PRODUCTS . " op1, " . TABLE_ORDERS_PRODUCTS . " op2, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where op1.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and op1.orders_id = op2.orders_id and op2.products_id <> '" . $HTTP_GET_VARS['products_id'] . "' and op2.products_id = p.products_id and op2.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and op2.orders_id = o.orders_id order by pd.products_name");
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {

      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_ALSO_PURCHASED_PRODUCTS);
      new contentBoxHeading($info_box_contents);

      // randomly select products from products ordered
      $rows_to_display = array();
      srand((double)microtime()*1000000); // seed the random number generator
      while (sizeof($rows_to_display) < MAX_DISPLAY_ALSO_PURCHASED && sizeof($rows_to_display) < $num_products_ordered) {
        $random_row = @rand(0, ($num_products_ordered - 1));
        if (!tep_in_array($random_row, $rows_to_display)) $rows_to_display[sizeof($rows_to_display)] = $random_row;
      }
      sort($rows_to_display);
      $row = 0;
      $col = 0;
      for ($i = 0; $i < sizeof($rows_to_display); $i++) {
        tep_db_data_seek($orders_query, $rows_to_display[$i]);
        $orders_values = tep_db_fetch_array($orders_query);
        $info_box_contents = array();
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
