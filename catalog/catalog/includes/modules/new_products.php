<?php
/*
  $Id: new_products.php,v 1.23 2001/12/19 21:30:55 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- new_products //-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));
  new contentBoxHeading($info_box_contents);

  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    $new_products_query = tep_db_query("select p.products_id, pd.products_name, p.products_image, IF(s.status, s.specials_new_products_price,p.products_price) as products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by p.products_date_added DESC, pd.products_name limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    $new_products_query = tep_db_query("select distinct p.products_id, pd.products_name, p.products_image, IF(s.status, s.specials_new_products_price,p.products_price) as products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_id = p2c.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p2c.categories_id = c.categories_id and c.parent_id = '" . $new_products_category_id . "' and p.products_status = '1' order by p.products_date_added DESC, pd.products_name limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }

  $info_box_contents = array();
  $row = 0;
  while ($new_products = tep_db_fetch_array($new_products_query)) {
    $new_products_array[] = array('id' => $new_products['products_id'],
                                  'name' => $new_products['products_name'],
                                  'image' => $new_products['products_image'],
                                  'price' => $new_products['products_price']);
  }

  for ($i=0; $i<sizeof($new_products_array); $i++) {
    $info_box_contents[] = array(array('align' => 'center', 'params' => 'class="main"', 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $new_products_array[$i]['image'], $new_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_array[$i]['id'], 'NONSSL') . '">' . $new_products_array[$i]['name'] . '</a><br>' . $currencies->format($new_products_array[$i]['price'])),
                                 array('align' => 'center', 'params' => 'class="main"', 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_array[$i+1]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $new_products_array[$i+1]['image'], $new_products_array[$i+1]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_array[$i+1]['id'], 'NONSSL') . '">' . $new_products_array[$i+1]['name'] . '</a><br>' . $currencies->format($new_products_array[$i+1]['price'])),
                                 array('align' => 'center', 'params' => 'class="main"', 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_array[$i+2]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $new_products_array[$i+2]['image'], $new_products_array[$i+2]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_array[$i+2]['id'], 'NONSSL') . '">' . $new_products_array[$i+2]['name'] . '</a><br>' . $currencies->format($new_products_array[$i+2]['price'])));
    $i = $i+2;
  }

  new contentBox($info_box_contents);
?>
<!-- new_products_eof //-->
