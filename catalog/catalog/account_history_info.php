<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO; include(DIR_INCLUDES . 'include_once.php'); ?>
<? $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $HTTP_GET_VARS['order_id'], 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_3 . '</a>'; ?>
<?
  if (tep_session_is_registered('customer_id')) {
    $customer_number = tep_db_query("select customers_id from orders where orders_id = '". $HTTP_GET_VARS['order_id'] . "'");
    $customer_number_values = tep_db_fetch_array($customer_number);
    if ($customer_number_values['customers_id'] != $customer_id) {
      header('Location: ' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'NONSSL'));
      tep_exit();
    }
  } else {
    header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=account_history', 'NONSSL'));
    tep_exit();
  }
?>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_QUANTITY;?>&nbsp;</b></font></td>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCT;?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_TOTAL;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
<?
  $orders_products = tep_db_query("select orders_products_id, products_name, products_price, final_price, products_quantity from orders_products where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $total_cost = 0;
  while ($orders_products_values = tep_db_fetch_array($orders_products)) {
    echo '          <tr>' . "\n";
    echo '            <td align="center" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $orders_products_values['products_quantity'] . '&nbsp;</font></td>' . "\n";
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>&nbsp;' . $orders_products_values['products_name'] . '&nbsp;</b>' . "\n";
//------insert customer choosen option --------
		$attributes_exist = '';
		$attributes = tep_db_query("select products_options, products_options_values from orders_products_attributes where orders_products_id = '" . $orders_products_values['orders_products_id'] . "'");
        if (@tep_db_num_rows($attributes)) {
		$attributes_exist = '1';
		while ($attributes_values = tep_db_fetch_array($attributes)) {
		echo "\n" . '<br>&nbsp;-&nbsp;' . $attributes_values['products_options'] . '&nbsp:&nbsp' . $attributes_values['products_options_values'];
		}
		}
//------insert customer choosen option eof-----		
	echo '</font></td>' . "\n";
    echo '            <td align="right" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . tep_currency_format($orders_products_values['products_quantity'] * $orders_products_values['products_price']) . '&nbsp;';
//------insert customer choosen option --------
		if ($attributes_exist == '1') {
        $attributes = tep_db_query("select options_values_price, price_prefix from orders_products_attributes where orders_products_id = '" . $orders_products_values['orders_products_id'] . "'");
		$final_price=$orders_products_values['final_price'];
		while ($attributes_values = tep_db_fetch_array($attributes)) {
			  if ($attributes_values['options_values_price'] != '0') {
			  echo "\n" . '<br>' . $attributes_values['price_prefix'] . tep_currency_format($orders_products_values['products_quantity'] * $attributes_values['options_values_price']) . '&nbsp;';
			  } else {
			  echo "\n" . '<br>&nbsp;';
			  }
		}
		}		
//------insert customer choosen option eof-----	
	echo '</font></td>' . "\n";
    echo '          </tr>' . "\n";
//------insert customer choosen option --------
		if ($attributes_exist == '1') {
		echo '<tr><td colspan="2" align="right"><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>' . SUB_TITLE_FINAL . '</b></font></td>';
		echo '<td align="right"><font face="' , TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>' . tep_currency_format($final_price) . '&nbsp;</b></font></td>';
		} else {
		$final_price = $orders_products_values['products_price'];
		}
//------insert customer choosen option eof-----
    $total_cost = $total_cost + ($orders_products_values['products_quantity'] * $final_price);
  }
?>
          <tr>
            <td colspan="3"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="3" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TABLE_SUBHEADING_SUBTOTAL;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=tep_currency_format($total_cost);?>&nbsp;</font></td>
              </tr>
<?
  $order = tep_db_query("select delivery_name, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, products_tax, payment_method, shipping_cost, shipping_method from orders where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $order_values = tep_db_fetch_array($order);
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=TABLE_SUBHEADING_TAX;?> (<? echo $order_values['products_tax']; ?>%):&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=tep_currency_format($total_cost * $order_values['products_tax']/100);?>&nbsp;</font></td>
              </tr>
<?
  $shipping = $order_values['shipping_cost'];
  if(!SHIPPING_FREE || $shipping > 0) {
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$order_values['shipping_method'] . " " . TABLE_SUBHEADING_SHIPPING;?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=tep_currency_format($shipping);?>&nbsp;</font></td>
              </tr>
<?
  }
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=TABLE_SUBHEADING_TOTAL;?></b>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<b><?=tep_currency_format(($total_cost * $order_values['products_tax']/100) + $total_cost + $shipping);?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_DELIVERY_ADDRESS;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$order_values['delivery_name'];?>&nbsp;</font></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$order_values['delivery_street_address'];?>&nbsp;</font></td>
          </tr>
<?
  if ($order_values['delivery_suburb'] != '') {
    echo '          <tr>' . "\n";
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $order_values['delivery_suburb'] . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
          <tr>
            <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$order_values['delivery_city'] . ', ' . $order_values['delivery_postcode'];?>&nbsp;</font></td>
          </tr>
          <tr>
<?
  if ($order_values['delivery_state'] != '') {
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $order_values['delivery_state'] . ', ' . $order_values['delivery_country'] . '&nbsp;</font></td>' . "\n";
  } else {
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $order_values['delivery_country'] . '&nbsp;</font></td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PAYMENT_METHOD;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td><?=tep_black_line();?></td>
          </tr>
<?
  if ($order_values['payment_method'] == 'cod') {
    echo '          <tr>' . "\n";
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_COD . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  } elseif ($order_values['payment_method'] == 'cc') {
    echo '          <tr>' . "\n";
    echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . TEXT_CC . '&nbsp;</font></td>' . "\n";
    echo '          </tr>' . "\n";
  }
?>
        </table></font></td>
      </tr>
      <tr>
        <td><?=tep_black_line();?></td>
      </tr>
      <tr>
        <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><br>&nbsp;<a href="<?=tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'NONSSL');?>"><?=tep_image(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK);?></a>&nbsp;&nbsp;</font></td>
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
