<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a>'; ?>
<html>
<head>
<title><?=TITLE;?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_left.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="boxborder">
          <tr>
            <td bgcolor="<?=TOP_BAR_BACKGROUND_COLOR;?>" width="100%" nowrap><font face="<?=TOP_BAR_FONT_FACE;?>" size="<?=TOP_BAR_FONT_SIZE;?>" color="<?=TOP_BAR_FONT_COLOR;?>">&nbsp;<?=TOP_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<?=HEADING_FONT_FACE;?>" size="<?=HEADING_FONT_SIZE;?>" color="<?=HEADING_FONT_COLOR;?>">&nbsp;<?=HEADING_TITLE;?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_history.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td nowrap><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ORDER_NUMBER;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ORDER_DATE;?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ORDER_QUANTITY;?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ORDER_COST;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="4"><?=tep_black_line();?></td>
          </tr>
<?
  $history = tep_db_query("select orders_id, date_purchased, products_tax, shipping_cost from orders where customers_id = '" . $customer_id . "' order by orders_id DESC");
  if (@!tep_db_num_rows($history)) {
?>
          <tr bgcolor="#f4f7fd">
            <td colspan="4" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_NO_PURCHASES;?>&nbsp;</font></td>
          </tr>
<?
  } else {
    $row = 0;
    while ($history_values = tep_db_fetch_array($history)) {
      $total_cost = 0;
      $total_quantity = 0;
      $row++;
      $history_total = tep_db_query("select products_price, products_quantity from orders_products where orders_id = '" . $history_values['orders_id'] . "'");
      while ($history_total_values = tep_db_fetch_array($history_total)) {
        $total_cost = $total_cost + ($history_total_values['products_price'] * $history_total_values['products_quantity']);
        $total_quantity = $total_quantity + $history_total_values['products_quantity'];
      }
      $total_cost = ($total_cost + $history_values['shipping_cost'] + ($total_cost * ($history_values['products_tax']/100)));
      $history_date = date('l, dS F, Y', mktime(0,0,0,substr($history_values['date_purchased'], 4, 2),substr($history_values['date_purchased'], -2),substr($history_values['date_purchased'], 0, 4)));
      if (($row / 2) == floor($row / 2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
      if (strlen($row) == 1) {
        $row = '0' . $row;
      }
      echo '            <td align="center" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $row . '.&nbsp;</font></td>' . "\n";
      echo '            <td nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $history_values['orders_id'], 'NONSSL') . '">' . $history_date . '</a>&nbsp;</font></td>' . "\n";
      echo '            <td align="center" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . $total_quantity . '&nbsp;</font></td>' . "\n";
      echo '            <td align="right" nowrap><font face="' . SMALL_TEXT_FONT_FACE . '" size="' . SMALL_TEXT_FONT_SIZE . '" color="' . SMALL_TEXT_FONT_COLOR . '">&nbsp;' . tep_currency_format($total_cost) . '&nbsp;</font></td>' . "\n";
      echo '          </tr>' . "\n";
    }
  }
?>
          <tr>
            <td colspan="4"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="3" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TABLE_TEXT;?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>"><br>&nbsp;<a href="<?=tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK);?></a>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?=BOX_WIDTH;?>" valign="top"><table border="0" width="<?=BOX_WIDTH;?>" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<? $include_file = DIR_INCLUDES . 'column_right.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
