<?php
/*
  $Id: whats_new.php,v 1.34 2004/02/16 07:26:55 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  $Qwhatsnew = $osC_Database->query('select products_id, products_image, products_tax_class_id, products_price from :table_products where products_status = 1 order by products_date_added desc limit :max_random_select_new');
  $Qwhatsnew->bindRaw(':table_products', TABLE_PRODUCTS);
  $Qwhatsnew->bindInt(':max_random_select_new', MAX_RANDOM_SELECT_NEW);

  if ($Qwhatsnew->executeRandomMulti()) {
?>
<!-- whats_new //-->
          <tr>
            <td>
<?php
    $products_name = tep_get_products_name($Qwhatsnew->valueInt('products_id'));
    $specials_price = tep_get_products_special_price($Qwhatsnew->valueInt('products_id'));

    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_WHATS_NEW);

    new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_PRODUCTS_NEW));

    $products_price = $osC_Currencies->displayPrice($Qwhatsnew->valueDecimal('products_price'), $Qwhatsnew->valueInt('products_tax_class_id'));

    if (tep_not_null($specials_price)) {
      $products_price = '<s>' . $products_price . '</s>&nbsp;<span class="productSpecialPrice">' . $osC_Currencies->displayPrice($specials_price, $Qwhatsnew->valueInt('products_tax_class_id')) . '</span>';
    }

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $Qwhatsnew->valueInt('products_id')) . '">' . tep_image(DIR_WS_IMAGES . $Qwhatsnew->value('products_image'), $products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $Qwhatsnew->valueInt('products_id')) . '">' . $products_name . '</a><br>' . $products_price);

    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- whats_new_eof //-->
<?php
    $Qwhatsnew->freeResult();
  }
?>
