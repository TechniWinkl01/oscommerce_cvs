<?php
/*
  $Id: account_history.php,v 1.47 2002/03/07 19:58:10 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY);

  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_1 . '</a> &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_2 . '</a>';

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
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
            <td align="center" class="tableHeading"><?php echo TABLE_HEADING_ORDER_NUMBER; ?></td>
            <td class="tableHeading"><?php echo TABLE_HEADING_ORDER_DATE; ?></td>
            <td align="right" class="tableHeading"><?php echo TABLE_HEADING_ORDER_COST; ?></td>
            <td align="right" class="tableHeading"><?php echo TABLE_HEADING_ORDER_STATUS; ?></td>
          </tr>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
  $history_query_raw = "select orders_id, date_purchased, shipping_cost, orders_status, currency, currency_value from " . TABLE_ORDERS . " where customers_id = '" . $customer_id . "' order by orders_id DESC";
  $history_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $history_query_raw, $history_numrows);
  $history_query = tep_db_query($history_query_raw);
  if (@!tep_db_num_rows($history_query)) {
?>
          <tr class="accountHistory-odd">
            <td colspan="4" class="smallText"><?php echo TEXT_NO_PURCHASES; ?></td>
          </tr>
<?php
  } else {
    $row = 0;
    while ($history = tep_db_fetch_array($history_query)) {
      $row++;
      $total_cost = 0;
      $history_total_query = tep_db_query("select final_price, products_tax, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $history['orders_id'] . "'");
      while ($history_total = tep_db_fetch_array($history_total_query)) {
        $cost = ($history_total['final_price'] * $history_total['products_quantity']);
        if (TAX_INCLUDE) {
          $total_cost += $cost;
        } else {
          $total_cost += $cost + ($cost * ($history_total['products_tax']/100));
        }
      }
      $total_cost += $history['shipping_cost'];

      if (($row / 2) == floor($row / 2)) {
        echo '          <tr class="accountHistory-even">' . "\n";
      } else {
        echo '          <tr class="accountHistory-odd">' . "\n";
      }
      echo '            <td align="center" class="smallText">' . $history['orders_id'] . '</td>' . "\n";
      echo '            <td class="smallText"><a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, tep_get_all_get_params(array('order_id')) . 'order_id=' . $history['orders_id'], 'SSL') . '">' . tep_date_long($history['date_purchased']) . '</a></td>' . "\n";
      echo '            <td align="right" class="smallText">' . $currencies->format($total_cost, true, $history['currency'], $history['currency_value']) . '</td>' . "\n";
      echo '            <td align="right" class="smallText">' . tep_get_orders_status_name($history['orders_status'], $languages_id) . '</td>' . "\n";
      echo '          </tr>' . "\n";
    }
  }
?>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td valign="top" class="smallText"><?php echo $history_split->display_count($history_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?><br><?php echo TEXT_RESULT_PAGE; ?> <?php echo $history_split->display_links($history_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
                <td align="right" class="smallText"><a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a><br><br><?php echo TABLE_TEXT; ?></td>
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
