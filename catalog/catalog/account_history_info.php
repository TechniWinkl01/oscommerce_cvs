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
    if (@$HTTP_GET_VARS['order_id']) {
      header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ACCOUNT_HISTORY_INFO . '&order_id=' . $HTTP_GET_VARS['order_id'], 'NONSSL'));
      tep_exit();
    } else {
      header('Location: ' . tep_href_link(FILENAME_LOGIN, 'origin=' . FILENAME_ACCOUNT_HISTORY, 'NONSSL'));
      tep_exit();
    }
  }
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<? $include_file = DIR_INCLUDES . 'header.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="5" cellpadding="5">
  <tr>
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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
            <td bgcolor="<? echo TOP_BAR_BACKGROUND_COLOR; ?>" width="100%" nowrap><font face="<? echo TOP_BAR_FONT_FACE; ?>" size="<? echo TOP_BAR_FONT_SIZE; ?>" color="<? echo TOP_BAR_FONT_COLOR; ?>">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap><font face="<? echo HEADING_FONT_FACE; ?>" size="<? echo HEADING_FONT_SIZE; ?>" color="<? echo HEADING_FONT_COLOR; ?>">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</font></td>
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'table_background_history.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', HEADING_TITLE); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_QUANTITY; ?>&nbsp;</b></font></td>
            <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_PRODUCT; ?>&nbsp;</b></font></td>
            <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_TAX; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_TOTAL; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
<?
  $order_currency_query = tep_db_query("select currency, currency_value from orders where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $order_currency = tep_db_fetch_array($order_currency_query);

  $orders_products = tep_db_query("select products_id, products_name, products_price, final_price, products_tax, products_quantity from orders_products where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $total_cost = 0;
  $total_tax = 0;
  while ($orders_products_values = tep_db_fetch_array($orders_products)) {
    $final_price = $orders_products_values['final_price'];

    echo '          <tr>' . "\n";
    echo '            <td align="center" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $orders_products_values['products_quantity'] . '&nbsp;</font></td>' . "\n";
    echo '            <td valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>&nbsp;' . $orders_products_values['products_name'] . '&nbsp;</b>' . "\n";
//------display customer choosen option --------
    $attributes_exist = '0';
    $attributes_query = tep_db_query("select products_options, products_options_values from orders_products_attributes where orders_id = '" . $HTTP_GET_VARS['order_id'] . "' and orders_products_id = '" . $orders_products_values['products_id'] . "'");
    if (@tep_db_num_rows($attributes_query)) {
      $attributes_exist = '1';
      while ($attributes = tep_db_fetch_array($attributes_query)) {
		echo '<br><small><i>&nbsp;-&nbsp;' . $attributes['products_options'] . '&nbsp;:&nbsp;' . $attributes['products_options_values'] . '</i></small>';
      }
    }
//------display customer choosen option eof-----
	echo '</font></td>' . "\n";
    echo '            <td align="center" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . number_format($orders_products_values['products_tax'], TAX_DECIMAL_PLACES) . '%&nbsp;</font></td>' . "\n";
    echo '            <td align="right" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<b>' . tep_currency_format($orders_products_values['products_quantity'] * $orders_products_values['products_price'], true, $order_currency['currency'], $order_currency['currency_value']) . '</b>&nbsp;';
//------display customer choosen option --------
    if ($attributes_exist == '1') {
      $attributes = tep_db_query("select options_values_price, price_prefix from orders_products_attributes where orders_id = '" . $HTTP_GET_VARS['order_id'] . "' and orders_products_id = '" . $orders_products_values['products_id'] . "'");
      while ($attributes_values = tep_db_fetch_array($attributes)) {
        if ($attributes_values['options_values_price'] != '0') {
          echo '<br><small><i>' . $attributes_values['price_prefix'] . tep_currency_format($orders_products_values['products_quantity'] * $attributes_values['options_values_price'], true, $order_currency['currency'], $order_currency['currency_value']) . '</i></small>&nbsp;';
        } else {
          echo '<br>&nbsp;';
        }
      }
    }
//------display customer choosen option eof-----
	echo '</font></td>' . "\n";
    echo '          </tr>' . "\n";

    $cost = ($orders_products_values['products_quantity'] * $final_price);
    $total_tax += ($cost * $orders_products_values['products_tax']/100);
    $total_cost += $cost;
  }
?>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="4" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TABLE_SUBHEADING_SUBTOTAL; ?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_currency_format($total_cost, true, $order_currency['currency'], $order_currency['currency_value']); ?>&nbsp;</font></td>
              </tr>
<?
  $order = tep_db_query("select delivery_name as name, delivery_street_address as street_address, delivery_suburb as suburb, delivery_city as city, delivery_postcode as postcode, delivery_state as state, delivery_country as country, delivery_address_format_id as format_id, payment_method, shipping_cost, shipping_method, comments from orders where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $order_values = tep_db_fetch_array($order);

  if ($total_tax > 0) {
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo TABLE_SUBHEADING_TAX; ?>:&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_currency_format($total_tax, true, $order_currency['currency'], $order_currency['currency_value']); ?>&nbsp;</font></td>
              </tr>
<?
  }
  $shipping = $order_values['shipping_cost'];
  if(SHIPPING_MODULES != '' || $shipping > 0) {
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $order_values['shipping_method'] . " " . TABLE_SUBHEADING_SHIPPING; ?>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_currency_format($shipping, true, $order_currency['currency'], $order_currency['currency_value']); ?>&nbsp;</font></td>
              </tr>
<?
  }
?>
              <tr>
                <td align="right" width="100%" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo TABLE_SUBHEADING_TOTAL; ?></b>&nbsp;</font></td>
                <td align="right" width="100%" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<b><? echo tep_currency_format($total_cost + $total_tax + $shipping, true, $order_currency['currency'], $order_currency['currency_value']); ?></b>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_DELIVERY_ADDRESS; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
     $boln = "<tr>\n            <td nowrap><font face=\"" . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;';
     $eoln = "&nbsp;</font></td>\n              </tr>\n";
     echo tep_address_format($order_values['format_id'], $order_values, 1, $boln, $eoln);
?>
        </table></td>
      </tr>
      <tr>
        <td><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_PAYMENT_METHOD; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
  $payment_action = 'PM_SHOW_INFO';
  include(DIR_MODULES . 'payment.php');
?>
        </table></font></td>
      </tr>
        <tr>
          <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><br><b>&nbsp;<? echo TABLE_HEADING_COMMENTS; ?>&nbsp;</b></font></td>
        </tr>
        <tr>
          <td><? echo tep_black_line(); ?></td>
        </tr>
        <tr>
          <td><? echo '&nbsp;' . nl2br(stripslashes($order_values['comments'])); ?></td>
        </tr>
        <tr>
          <td><? echo tep_black_line(); ?></td>
        </tr>
      <tr>
        <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><br>&nbsp;<a href="<? echo tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'NONSSL'); ?>"><? echo tep_image(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK); ?></a>&nbsp;&nbsp;</font></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<? echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<? echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0">
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
