<?php
/*
  $Id: products_new.php,v 1.6 2001/12/05 22:41:46 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (sizeof($products_new_array) == '0') {
?>
  <tr>
    <td class="main"><?php echo TEXT_NO_NEW_PRODUCTS; ?></td>
  </tr>
<?php
  } else {
    for($i=0; $i<sizeof($products_new_array); $i++) {
      if ($products_new_array[$i]['specials_price']) {
        $products_price = '<s>' .  $currencies->format($products_new_array[$i]['price']) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->format($products_new_array[$i]['specials_price']) . '</span>';
      } else {
        $products_price = $currencies->format($products_new_array[$i]['price']);
      }
?>
  <tr>
    <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $products_new_array[$i]['image'], $products_new_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
    <td valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new_array[$i]['id'], 'NONSSL') . '"><b><u>' . $products_new_array[$i]['name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . $products_new_array[$i]['date_added'] . '<br>' . TEXT_MANUFACTURER . ' ' . $products_new_array[$i]['manufacturer'] . '<br><br>' . TEXT_PRICE . ' ' . $products_price; ?></td>
    <td align="right" valign="middle" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_NEW, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>'; ?></td>
  </tr>
<?php
      if (($i+1) != sizeof($products_new_array)) {
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
