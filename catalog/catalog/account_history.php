<? include('includes/application_top.php'); ?>
<? $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a>'; ?>
<?
  if (!@tep_session_is_registered('customer_id')) {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ACCOUNT_HISTORY, 'NONSSL'));
    tep_exit();
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_WS_INCLUDES . 'header.php';  include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_left.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="topBarTitle">
          <tr>
            <td width="100%" class="topBarTitle" nowrap>&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" nowrap>&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_history.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td nowrap><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?><b>&nbsp;<? echo TABLE_HEADING_ORDER_NUMBER; ?>&nbsp;</b></font></td>
            <td nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?><b>&nbsp;<? echo TABLE_HEADING_ORDER_DATE; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?><b>&nbsp;<? echo TABLE_HEADING_ORDER_COST; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><?php echo FONT_STYLE_TABLE_HEADING; ?><b>&nbsp;<? echo TABLE_HEADING_ORDER_STATUS; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
<?
  $history_sql = "select orders_id, date_purchased, shipping_cost, orders_status, currency, currency_value from orders where customers_id = '" . $customer_id . "' order by orders_id DESC";
  $history_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $history_sql, $history_numrows);
  $history = tep_db_query($history_sql);
  if (@!tep_db_num_rows($history)) {
?>
          <tr bgcolor="<? echo TABLE_ALT_BACKGROUND_COLOR; ?>">
            <td colspan="4" class="smallText" nowrap>&nbsp;<? echo TEXT_NO_PURCHASES; ?>&nbsp;</td>
          </tr>
<?
  } else {
    $row = 0;
    while ($history_values = tep_db_fetch_array($history)) {
      $row++;
      $total_cost = 0;
      $history_total = tep_db_query("select final_price, products_tax, products_quantity from orders_products where orders_id = '" . $history_values['orders_id'] . "'");
      while ($history_total_values = tep_db_fetch_array($history_total)) {
        $cost = ($history_total_values['final_price'] * $history_total_values['products_quantity']);
        $total_cost += $cost + ($cost * ($history_total_values['products_tax']/100));
      }
      $total_cost += $history_values['shipping_cost'];

      if (($row / 2) == floor($row / 2)) {
        echo '          <tr bgcolor="' . TABLE_ROW_BACKGROUND_COLOR . '">' . "\n";
      } else {
        echo '          <tr bgcolor="' . TABLE_ALT_BACKGROUND_COLOR . '">' . "\n";
      }
      echo '            <td align="center" class="smallText" nowrap>&nbsp;' . $history_values['orders_id'] . '&nbsp;</td>' . "\n";
      echo '            <td class="smallText" nowrap>&nbsp;<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, tep_get_all_get_params(array('order_id')) . 'order_id=' . $history_values['orders_id'], 'NONSSL') . '">' . tep_date_long($history_values['date_purchased']) . '</a>&nbsp;</td>' . "\n";
      echo '            <td align="right" class="smallText" nowrap>&nbsp;' . tep_currency_format($total_cost, true, $history_values['currency'], $history_values['currency_value']) . '&nbsp;</td>' . "\n";
      echo '            <td align="right" class="smallText" nowrap>&nbsp;' . $history_values['orders_status'] . '&nbsp;</td>' . "\n";
      echo '          </tr>' . "\n";
    }
  }
?>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" nowrap>&nbsp;<? echo $history_split->display_count($history_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?>&nbsp;<br>&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $history_split->display_links($history_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>&nbsp;</td>
                <td align="right" class="smallText" nowrap>&nbsp;<a href="<? echo tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL'); ?>"><? echo tep_image(DIR_WS_IMAGES . 'button_back.gif', IMAGE_BACK); ?></a>&nbsp;<br><? echo TABLE_TEXT; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_WS_INCLUDES . 'column_right.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_WS_INCLUDES . 'footer.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_WS_INCLUDES . 'application_bottom.php'; include(DIR_WS_INCLUDES . 'include_once.php'); ?>
