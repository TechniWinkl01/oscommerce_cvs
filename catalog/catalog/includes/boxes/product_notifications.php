<?php
/*
  $Id: product_notifications.php,v 1.2 2002/04/05 10:34:15 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- notifications //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_NOTIFICATIONS);
  new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'NONSSL'));

  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $HTTP_GET_VARS['products_id'] . "' and customers_id = '" . $customer_id . "'");
  $check = tep_db_fetch_array($check_query);

  $info_box_contents = array();
  if ($check['count'] > 0) {
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBox"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'box_products_notifications_remove.gif', IMAGE_BUTTON_REMOVE_NOTIFICATIONS) . '</a></td><td class="infoBox"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', 'NONSSL') . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY_REMOVE, $product_info_values['products_name']) .'</a></td></tr></table>');
  } else {
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<table border="0" cellspacing="0" cellpadding="2"><tr><td class="infoBox"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'box_products_notifications.gif', IMAGE_BUTTON_NOTIFICATIONS) . '</a></td><td class="infoBox"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', 'NONSSL') . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY, $product_info_values['products_name']) .'</a></td></tr></table>');
  }
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- notifications_eof //-->