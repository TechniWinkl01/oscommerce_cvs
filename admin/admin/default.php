<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_DEFAULT; include(DIR_INCLUDES . 'include_once.php'); ?>
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
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'table_background_button.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE);?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
          <tr bgcolor="<?=SUB_BAR_BACKGROUND_COLOR;?>">
            <td nowrap><font face="<?=SUB_BAR_FONT_FACE;?>" size="<?=SUB_BAR_FONT_SIZE;?>" color="<?=SUB_BAR_FONT_COLOR;?>">&nbsp;<?=SUB_BAR_TITLE;?>&nbsp;</font></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><?=TEXT_MAIN;?></font></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2"><?=tep_black_line();?></td>
              </tr>
              <tr bgcolor="<?=SUB_BAR_BACKGROUND_COLOR;?>">
                <td colspan="2" nowrap><font face="<?=SUB_BAR_FONT_FACE;?>" size="<?=SUB_BAR_FONT_SIZE;?>" color="<?=SUB_BAR_FONT_COLOR;?>">&nbsp;<?=TABLE_HEADING_NEW_CUSTOMERS;?>&nbsp;</font></td>
              </tr>
              <tr>
                <td colspan="2"><?=tep_black_line();?></td>
              </tr>
<?
  $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, i.customers_info_date_account_created from customers c, customers_info i where c.customers_id = i.customers_info_id order by i.customers_info_date_account_created DESC limit 5");
  while ($customers = tep_db_fetch_array($customers_query)) {
    echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'"">' . "\n";
    echo '                <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '&nbsp;</font></td>' . "\n";
    echo '                <td align="right" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_date_short($customers['customers_info_date_account_created']) . '&nbsp;</font></td>' . "\n";
    echo '              </tr>' . "\n";
  }
?>
              <tr>
                <td colspan="2"><?=tep_black_line();?></td>
              </tr>
            </table></td>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="3"><?=tep_black_line();?></td>
              </tr>
              <tr bgcolor="<?=SUB_BAR_BACKGROUND_COLOR;?>">
                <td colspan="3" nowrap><font face="<?=SUB_BAR_FONT_FACE;?>" size="<?=SUB_BAR_FONT_SIZE;?>" color="<?=SUB_BAR_FONT_COLOR;?>">&nbsp;<?=TABLE_HEADING_LAST_ORDERS;?>&nbsp;</font></td>
              </tr>
              <tr>
                <td colspan="3"><?=tep_black_line();?></td>
              </tr>
<?
  $orders_query = tep_db_query("select orders_id, customers_name, date_purchased, orders_status from orders order by date_purchased DESC limit 5");
  while ($orders = tep_db_fetch_array($orders_query)) {
    $total_cost = 0;
    $orders_products_query = tep_db_query("select products_price, products_quantity from orders_products where orders_id = '" . $orders['orders_id'] . "'");
    while ($orders_products = tep_db_fetch_array($orders_products_query)) {
      $total_cost = $total_cost + ($orders_products['products_price'] * $orders_products['products_quantity']);
    }

    echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, 'orders_id=' . $orders['orders_id'], 'NONSSL') . '\'">' . "\n";
    echo '                <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS, 'orders_id=' . $orders['orders_id'], 'NONSSL') . '" class="blacklink">' . $orders['customers_name'] . '</a>&nbsp;</font></td>' . "\n";
    echo '                <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_currency_format($total_cost) . '&nbsp;</font></td>' . "\n";
    echo '                <td align="right" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_date_short($orders['date_purchased']) . '&nbsp;</font></td>' . "\n";
    echo '              </tr>' . "\n";
  }
?>
              <tr>
                <td colspan="3"><?=tep_black_line();?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2"><?=tep_black_line();?></td>
              </tr>
              <tr bgcolor="<?=SUB_BAR_BACKGROUND_COLOR;?>">
                <td colspan="2" nowrap><font face="<?=SUB_BAR_FONT_FACE;?>" size="<?=SUB_BAR_FONT_SIZE;?>" color="<?=SUB_BAR_FONT_COLOR;?>">&nbsp;<?=TABLE_HEADING_NEW_PRODUCTS;?>&nbsp;</font></td>
              </tr>
              <tr>
                <td colspan="2"><?=tep_black_line();?></td>
              </tr>
<?
  $products_query = tep_db_query("select products_id, products_date_added from products order by products_date_added DESC limit 5");
  while ($products = tep_db_fetch_array($products_query)) {
    echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $products['products_id'], 'NONSSL') . '\'">' . "\n";
    echo '                <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $products['products_id'], 'NONSSL') . '" class="blacklink">' . tep_products_name($products['products_id']) . '</a>&nbsp;</font></td>' . "\n";
    echo '                <td align="right" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_date_short($products['products_date_added']) . '&nbsp;</font></td>' . "\n";
    echo '              </tr>' . "\n";
  }
?>
              <tr>
                <td colspan="2"><?=tep_black_line();?></td>
              </tr>
            </table></td>
            <td width="50%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td colspan="2"><?=tep_black_line();?></td>
              </tr>
              <tr bgcolor="<?=SUB_BAR_BACKGROUND_COLOR;?>">
                <td colspan="2" nowrap><font face="<?=SUB_BAR_FONT_FACE;?>" size="<?=SUB_BAR_FONT_SIZE;?>" color="<?=SUB_BAR_FONT_COLOR;?>">&nbsp;<?=TABLE_HEADING_NEW_REVIEWS;?>&nbsp;</font></td>
              </tr>
              <tr>
                <td colspan="2"><?=tep_black_line();?></td>
              </tr>
<?
  $reviews_query = tep_db_query("select reviews_id, products_id, date_added from reviews_extra order by date_added DESC limit 5");
  while ($reviews = tep_db_fetch_array($reviews_query)) {
    echo '              <tr bgcolor="#d8e1eb" onmouseover="this.style.background=\'#cc9999\';this.style.cursor=\'hand\'" onmouseout="this.style.background=\'#d8e1eb\'">' . "\n";
    echo '                <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_products_name($reviews['products_id']) . '&nbsp;</font></td>' . "\n";
    echo '                <td align="right" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_date_short($reviews['date_added']) . '&nbsp;</font></td>' . "\n";
    echo '              </tr>' . "\n";
  }
?>
              <tr>
                <td colspan="2"><?=tep_black_line();?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? include('includes/application_bottom.php'); ?>
