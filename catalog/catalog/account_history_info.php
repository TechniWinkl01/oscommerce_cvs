<?php
/*
  $Id: account_history_info.php,v 1.77 2002/04/08 01:55:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (tep_session_is_registered('customer_id')) {
    $customer_number = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". $HTTP_GET_VARS['order_id'] . "'");
    $customer_number_values = tep_db_fetch_array($customer_number);
    if ($customer_number_values['customers_id'] != $customer_id) {
      tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
    }
  } else {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);

  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_1 . '</a> &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_2 . '</a> &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $HTTP_GET_VARS['order_id'], 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_3 . '</a>';

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order($HTTP_GET_VARS['order_id']);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_history.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_TOTAL; ?></td>
          </tr>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
  for ($i=0; $i<sizeof($order->products); $i++) {
      echo '          <tr>' . "\n" .
           '            <td class="main" valign="top" align="right" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="main" valign="top">' . $order->products[$i]['name'];

      if (sizeof($order->products[$i]['attributes']) > 0) {
        for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '</td>' . "\n" .
           '            <td class="main" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";

      if (DISPLAY_PRICE_WITH_TAX == true) {
        echo '            <td class="main" align="right" valign="top">' . $currencies->format((($order->products[$i]['final_price'] * $order->products[$i]['qty']) * $order->products[$i]['tax']/100) + ($order->products[$i]['final_price'] * $order->products[$i]['qty']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
      } else {
        echo '            <td class="main" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
      }
      echo '          </tr>' . "\n";
  }
?>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td colspan="4" align="right"><table border="0" cellspacing="0" cellpadding="1">
<?php
  for ($i=0; $i<sizeof($order->totals); $i++) {
    echo '              <tr>' . "\n" .
         '                <td class="main" align="right">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '                <td class="main" align="right">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '              </tr>' . "\n";
  }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading"><?php echo TABLE_HEADING_DELIVERY_ADDRESS; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></b></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (tep_not_null($order->info['comments'])) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo nl2br($order->info['comments']); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }

  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_BOXES . 'downloads.php');
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>