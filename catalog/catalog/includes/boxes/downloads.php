<?php
/*
  $Id: downloads.php,v 1.2 2002/02/03 01:05:39 clescuyer Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- downloads //-->
<?php
  if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
// Get last order id for checkout_success
    $orders_query_raw = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE customers_id = '" . $customer_id . "' ORDER BY orders_id DESC LIMIT 1";
    $orders_query = tep_db_query($orders_query_raw);
    $orders_values = tep_db_fetch_array($orders_query);
    $last_order = $orders_values['orders_id'];
  } else {
    $last_order = $HTTP_GET_VARS['order_id'];
  }

// Now get all downloadable products in that order
  $downloads_query_raw = "SELECT date_purchased + INTERVAL opd.download_maxdays DAY as download_expiry, UNIX_TIMESTAMP(date_purchased + INTERVAL opd.download_maxdays DAY) as download_timestamp, op.products_name, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays
                          FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd
                          WHERE customers_id = '" . $customer_id . "' 
                           AND o.orders_id = '" . $last_order . "'
                           AND op.orders_id = '" . $last_order . "'
                           AND opd.orders_products_id=op.orders_products_id
                           AND opd.orders_products_filename<>''";
  $downloads_query = tep_db_query($downloads_query_raw);

// Don't display if there is no downloadable product
  if (tep_db_num_rows($downloads_query) > 0) {
?>
<tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '30'); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="tableHeading"><?php echo HEADING_DOWNLOAD; ?></td>
        <td align="center"class="tableHeading"><?php echo TABLE_HEADING_DOWNLOAD_DATE; ?></td>
        <td align="center"class="tableHeading"><?php echo TABLE_HEADING_DOWNLOAD_COUNT; ?></td>
      </tr>
      <tr>
        <td colspan="4"><?php echo tep_draw_separator(); ?></td>
      </tr>
<?php
    $row = 0;
    while ($downloads_values = tep_db_fetch_array($downloads_query)) {
      if (($row % 2) == 0) {
        echo '          <tr class="accountHistory-even">' . "\n";
      } else {
        echo '          <tr class="accountHistory-odd">' . "\n";
      }
      $row++;
// The link will appear only if:
// - Download remaining count is > 0, AND
// - The file is present in the DOWNLOAD directory, AND EITHER
// - No expiry date is enforced (maxdays == 0), OR
// - The expiry date is not reached
      if (($downloads_values['download_count'] > 0) &&
          (file_exists(DIR_FS_DOWNLOAD . $downloads_values['orders_products_filename'])) &&
          (($downloads_values['download_maxdays'] == 0) ||
           ($downloads_values['download_timestamp'] > time()))) {
        echo '            <td class="smallText"><a href="' . tep_href_link(FILENAME_DOWNLOAD, 'order=' . $last_order . '&id=' . $downloads_values['orders_products_download_id']) . '">' . $downloads_values['products_name'] . '</a></td>' . "\n";
      } else {
        echo '            <td class="smallText">' . $downloads_values['products_name'] . '</td>' . "\n";
      }
      echo '            <td align="center" class="smallText">' . tep_date_long($downloads_values['download_expiry']) . '</td>' . "\n";
      echo '            <td align="center" class="smallText">' . $downloads_values['download_count'] . '</td>' . "\n";
      echo '          </tr>' . "\n";
    }

    if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
?>
      <tr>
        <td colspan="4"><?php echo tep_draw_separator(); ?></td>
      </tr>
      <tr>
        <td class="smalltext" colspan="4"><p><?php printf(FOOTER_DOWNLOAD, '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . HEADER_TITLE_MY_ACCOUNT . '</a>'); ?></p></td>
      </tr>
<?php
    }
?>
     </table>
    </td>
  </tr>
<?php
  }
?>
<!-- downloads_eof //-->
