<?php
/*
  $Id: products_new.php,v 1.9 2002/11/23 02:08:11 thomasamoulton Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  $psize = sizeof($products_new_array);
  if ($psize == '0') {
?>
  <tr>
    <td class="main"><?php echo TEXT_NO_NEW_PRODUCTS; ?></td>
  </tr>
<?php
  } else {
    for($i=0; $i<$psize; $i++) {
      if ($products_new_array[$i]['specials_price']) {
        $products_price = '<s>' .  $currencies->display_price($products_new_array[$i]['price'], tep_get_tax_rate($products_new_array[$i]['tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($products_new_array[$i]['specials_price'], tep_get_tax_rate($products_new_array[$i]['tax_class_id'])) . '</span>';
      } else {
        $products_price = $currencies->display_price($products_new_array[$i]['price'], tep_get_tax_rate($products_new_array[$i]['tax_class_id']));
      }
?>
  <tr>
    <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $products_new_array[$i]['image'], $products_new_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
    <td valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new_array[$i]['id'], 'NONSSL') . '"><b><u>' . $products_new_array[$i]['name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . $products_new_array[$i]['date_added'] . '<br>' . TEXT_MANUFACTURER . ' ' . $products_new_array[$i]['manufacturer'] . '<br><br>' . TEXT_PRICE . ' ' . $products_price; ?></td>
    <td align="right" valign="middle" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>'; ?></td>
  </tr>
<?php
      if (($i+1) != $psize)) {
?>
  <tr>
    <td colspan="3" class="main">&nbsp;</td>
  </tr>
<?php
      }
    }
  }
?>
</table>
