<?php
/*
  $Id: account_history.php,v 1.52 2002/05/23 01:07:36 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY);

  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_1 . '</a> &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_2 . '</a>';
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
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_history.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading" align="center"><?php echo TABLE_HEADING_ORDER_NUMBER; ?></td>
            <td class="tableHeading"><?php echo TABLE_HEADING_ORDER_DATE; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ORDER_COST; ?></td>
            <td class="tableHeading" align="right"><?php echo TABLE_HEADING_ORDER_STATUS; ?></td>
          </tr>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
  $history_query_raw = "select o.orders_id, o.date_purchased, o.orders_status, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where o.customers_id = '" . $customer_id . "' and ot.class = 'ot_total' order by orders_id DESC";
  $history_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $history_query_raw, $history_numrows);
  $history_query = tep_db_query($history_query_raw);
  if (!tep_db_num_rows($history_query)) {
?>
          <tr class="accountHistory-odd">
            <td colspan="4" class="smallText"><?php echo TEXT_NO_PURCHASES; ?></td>
          </tr>
<?php
  } else {
    $row = 0;
    while ($history = tep_db_fetch_array($history_query)) {
      $row++;

      if (($row / 2) == floor($row / 2)) {
        echo '          <tr class="accountHistory-even">' . "\n";
      } else {
        echo '          <tr class="accountHistory-odd">' . "\n";
      }
      echo '            <td class="smallText" align="center">' . $history['orders_id'] . '</td>' . "\n" .
           '            <td class="smallText"><a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, tep_get_all_get_params(array('order_id')) . 'order_id=' . $history['orders_id'], 'SSL') . '">' . tep_date_long($history['date_purchased']) . '</a></td>' . "\n" .
           '            <td class="smallText" align="right">' . strip_tags($history['order_total']) . '</td>' . "\n" .
           '            <td class="smallText" align="right">' . tep_get_orders_status_name($history['orders_status'], $languages_id) . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
  }
?>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $history_split->display_count($history_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE; ?><?php echo $history_split->display_links($history_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a><br><br>' . TABLE_TEXT; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
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