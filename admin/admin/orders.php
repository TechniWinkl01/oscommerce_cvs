<? include('includes/application_top.php'); ?>
<?
  if ($HTTP_GET_VARS['action']) {
    if ($HTTP_GET_VARS['action'] == 'update_order') {
      $date_now = date('YmdHis');
      tep_db_query("update orders set orders_status = '" . $HTTP_GET_VARS['status'] . "', orders_date_finished = '" . $date_now . "' where orders_id = '" . $HTTP_GET_VARS['orders_id'] . "'");
      header('Location: ' . tep_href_link(FILENAME_ORDERS, 'orders_id=' . $HTTP_GET_VARS['orders_id'])); tep_exit();
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
            <td align="right" nowrap>&nbsp;<? echo tep_image(DIR_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', ''); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
<?
  if (@$HTTP_GET_VARS['orders_id']) {
    $orders = tep_db_query("select customers_name, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, delivery_name, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, payment_method, cc_type, cc_owner, cc_number, cc_expires, date_purchased, orders_status from orders where orders_id = '" . $HTTP_GET_VARS['orders_id'] . "'");
    $orders_values = tep_db_fetch_array($orders);
?>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_CUSTOMERS_INFO; ?>&nbsp;</b></font></td>
            <td valign="top" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_DELIVERY_INFO; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_CUSTOMER; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['customers_name']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_STREET_ADDRESS; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['customers_street_address']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_SUBURB; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['customers_suburb']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_CITY; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['customers_city']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_POST_CODE; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['customers_postcode']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_STATE; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['customers_state']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_COUNTRY; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['customers_country']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_TELEPHONE; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['customers_telephone']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_EMAIL_ADDRESS; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<a href="mailto:<? echo $orders_values['customers_email_address']; ?>"><u><? echo $orders_values['customers_email_address']; ?></u></a>&nbsp;</font></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_DELIVERY_TO; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['delivery_name']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_STREET_ADDRESS; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['delivery_street_address']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_SUBURB; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['delivery_suburb']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_CITY; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['delivery_city']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_POST_CODE; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['delivery_postcode']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_STATE; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['delivery_state']; ?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_COUNTRY; ?>&nbsp;</font></td>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['delivery_country']; ?>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td nowrap colspan="2"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_PAYMENT_INFORMATION; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td nowrap colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="2"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_PAYMENT_METHOD; ?>&nbsp;</font></td>
<?php
	print "<td nowrap><font face=\"" . TEXT_FONT_FACE . "\" size=\"" . TEXT_FONT_SIZE . "\" color=\"" . TEXT_FONT_COLOR . "\">&nbsp;";
	switch($orders_values['payment_method']) {
		case 'cc' : // Credit Card
			print htmlentities(TEXT_CREDIT_CARD);
			break;
		case 'cod' : // Cash On Delivery
			print htmlentities(TEXT_CASH_ON_DELIVERY);
			break;
		case 'paypal' : // PayPal
			print htmlentities(TEXT_PAYPAL);
			break;
		default :
			print htmlentities(TEXT_UNKNOWN);
			break;
	}
	print "&nbsp;</font></td>\n";
?>
              </tr>
<?php
	switch($orders_values['payment_method']) {
		case 'cc' : // Credit Card
			print "<tr>\n";
			print "<td colspan=\"2\">&nbsp;</td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td nowrap><font face=\"" . TEXT_FONT_FACE . "\" size=\"" . TEXT_FONT_SIZE . "\" color=\"" . TEXT_FONT_COLOR . "\">&nbsp;" . htmlentities(ENTRY_CREDIT_CARD_TYPE) . "&nbsp;</font></td>\n";
			print "<td nowrap><font face=\"" . TEXT_FONT_FACE . "\" size=\"" . TEXT_FONT_SIZE . "\" color=\"" . TEXT_FONT_COLOR . "\">&nbsp;" . htmlentities($orders_values['cc_type']) . "&nbsp;</font></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td nowrap><font face=\"" . TEXT_FONT_FACE . "\" size=\"" . TEXT_FONT_SIZE . "\" color=\"" . TEXT_FONT_COLOR . "\">&nbsp;" . htmlentities(ENTRY_CREDIT_CARD_OWNER) . "&nbsp;</font></td>\n";
			print "<td nowrap><font face=\"" . TEXT_FONT_FACE . "\" size=\"" . TEXT_FONT_SIZE . "\" color=\"" . TEXT_FONT_COLOR . "\">&nbsp;" . htmlentities($orders_values['cc_owner']) . "&nbsp;</font></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td nowrap><font face=\"" . TEXT_FONT_FACE . "\" size=\"" . TEXT_FONT_SIZE . "\" color=\"" . TEXT_FONT_COLOR . "\">&nbsp;" . htmlentities(ENTRY_CREDIT_CARD_NUMBER) . "&nbsp;</font></td>\n";
			print "<td nowrap><font face=\"" . TEXT_FONT_FACE . "\" size=\"" . TEXT_FONT_SIZE . "\" color=\"" . TEXT_FONT_COLOR . "\">&nbsp;" . $orders_values['cc_number'] . "&nbsp;</font></td>\n";
			print "</tr>\n";
			print "<tr>\n";
			print "<td nowrap><font face=\"" . TEXT_FONT_FACE . "\" size=\"" . TEXT_FONT_SIZE . "\" color=\"" . TEXT_FONT_COLOR . "\">&nbsp;" . htmlentities(ENTRY_CREDIT_CARD_EXPIRES) . "&nbsp;</font></td>\n";
			print "<td nowrap><font face=\"" . TEXT_FONT_FACE . "\" size=\"" . TEXT_FONT_SIZE . "\" color=\"" . TEXT_FONT_COLOR . "\">&nbsp;" . $orders_values['cc_expires'] . "&nbsp;</font></td>\n";
			print "</tr>\n";
			break;
	}
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<? echo TABLE_HEADING_QUANTITY;?>&nbsp;</b></font></td>
                <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<? echo TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
                <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<? echo TABLE_HEADING_TAX;?>&nbsp;</b></font></td>
                <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE;?>" size="<? echo TABLE_HEADING_FONT_SIZE;?>" color="<? echo TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<? echo TABLE_HEADING_TOTAL;?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="4"><? echo tep_black_line(); ?></td>
              </tr>
<?
    $subtotal = 0;
    $taxed = 0;
    $info = tep_db_query("select date_purchased, orders_status, orders_date_finished, shipping_cost, shipping_method from orders where orders_id = '" . $HTTP_GET_VARS['orders_id'] . "'");
    $info_values = tep_db_fetch_array($info);
    $date_purchased = date('l, jS F, Y', mktime(0,0,0,substr($info_values['date_purchased'], 4, 2),substr($info_values['date_purchased'], -2),substr($info_values['date_purchased'], 0, 4)));
    $products = tep_db_query("select orders_products_id, products_id, products_name, products_price, products_quantity, final_price, products_tax from orders_products where orders_id = '" . $HTTP_GET_VARS['orders_id'] . "'");
    while ($products_values = tep_db_fetch_array($products)) {
      echo '          <tr>' . "\n";
      echo '            <td align="center" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;' . $products_values['products_quantity'] . '&nbsp;</font></td>' . "\n";
      echo '            <td nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '"><b>&nbsp;' . $products_values['products_name'] . '&nbsp;</b>' . "\n";
//------display customer choosen option --------
      $attributes_exist = '0';
      $attributes_query = tep_db_query("select products_options, products_options_values from orders_products_attributes where orders_id = '" . $HTTP_GET_VARS['orders_id'] . "' and orders_products_id = '" . $products_values['products_id'] . "'");
      if (@tep_db_num_rows($attributes_query)) {
        $attributes_exist = '1';
        while ($attributes = tep_db_fetch_array($attributes_query)) {
		  echo "\n" . '<br><small><i>&nbsp;-&nbsp;' . $attributes['products_options'] . '&nbsp;:&nbsp;' . $attributes['products_options_values'] . '</i></small>';
        }
      }
//------display customer choosen option eof-----
      echo '</font></td>' . "\n";
      echo '            <td valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;</font></td>' . "\n";
      echo '            <td align="right" valign="top" nowrap><font face="' . TEXT_FONT_FACE . '" size="' . TEXT_FONT_SIZE . '" color="' . TEXT_FONT_COLOR . '">&nbsp;<b>' . tep_currency_format($products_values['products_quantity'] * $products_values['products_price']) . '</b>&nbsp;';
//------display customer choosen option --------
      if ($attributes_exist == '1') {
        $attributes = tep_db_query("select options_values_price, price_prefix from orders_products_attributes where orders_id = '" . $HTTP_GET_VARS['orders_id'] . "' and orders_products_id = '" . $products_values['products_id'] . "'");
        $final_price = $products_values['final_price'];
        while ($attributes_values = tep_db_fetch_array($attributes)) {
          if ($attributes_values['options_values_price'] != '0') {
            echo "\n" . '<br><small><i>' . $attributes_values['price_prefix'] . tep_currency_format($products_values['products_quantity'] * $attributes_values['options_values_price']) . '</i></small>&nbsp;';
          } else {
            echo "\n" . '<br>&nbsp;';
          }
        }
      }
//------display customer choosen option eof-----
      echo '</font></td>' . "\n";
      echo '          </tr>' . "\n";
      if (!$attributes_exist == '1') $final_price = $products_values['products_price'];
      $cost = ($products_values['products_quantity'] * $final_price);
      $total_tax += ($cost * ($products_values['products_tax']/100));
      $total_cost += $cost;
    }
?>
              <tr>
                <td colspan="4"><? echo tep_black_line(); ?></td>
              </tr>
              <tr>
                <td align="right" colspan="4"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_SUB_TOTAL; ?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;$<? echo $total_cost; ?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo ENTRY_TAX; ?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_currency_format($total_tax); ?>&nbsp;</font></td>
                  </tr>
<?
  if (!SHIPPING_FREE || $shipping != 0) {
?>
                  <tr>
                    <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo $shipping_method . " " . ENTRY_SHIPPING; ?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;<? echo tep_currency_format($shipping); ?>&nbsp;</font></td>
                  </tr>
<?
  }
?>
                  <tr>
                    <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo ENTRY_TOTAL; ?>&nbsp;</b></font></td>
                    <td align="right" nowrap><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo tep_currency_format($total_cost + $total_tax + $shipping); ?>&nbsp;</b></font></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td nowrap colspan="2"><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_STATUS; ?>&nbsp;</b></font></td>
           </tr>
          <tr>
            <td colspan="2"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td nowrap colspan="2"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo ENTRY_DATE_PURCHASED; ?></b> <? echo $date_purchased; ?>&nbsp;</font></td>
          </tr>
          <tr><form name="status" <? echo 'action="' . tep_href_link(FILENAME_ORDERS, '', 'NONSSL') . '"'; ?> method="get"><input type="hidden" name="action" value="update_order"><input type="hidden" name="orders_id" value="<? echo $HTTP_GET_VARS['orders_id']; ?>">
            <td nowrap colspan="2"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo ENTRY_STATUS; ?></b> <select name="status"><option value="Processing"<? if ($info_values['orders_status'] == 'Processing') { echo ' SELECTED'; } ?>>Processing</option><option value="Delivered"<? if ($info_values['orders_status'] == 'Delivered') { echo ' SELECTED'; } ?>>Delivered</option><option value="Pending"<? if ($info_values['orders_status'] == 'Pending') { echo ' SELECTED'; } ?>>Pending</option></select>&nbsp;<? echo tep_image_submit(DIR_IMAGES . 'button_update.gif', '50', '14', '0', IMAGE_UPDATE); ?>&nbsp;</font></td>
          </tr></form>
<?
    if (@$info_values['orders_date_finished'] != '0') {
      $date_updated = substr($info_values['orders_date_finished'], 6, 2) . '/' . substr($info_values['orders_date_finished'], 4, 2) . '/' . substr($info_values['orders_date_finished'], 0, 4) . '&nbsp;' . substr($info_values['orders_date_finished'], -6, 2) . ':' . substr($info_values['orders_date_finished'], -4, 2) . ':' . substr($info_values['orders_date_finished'], -2);
?>
          <tr>
            <td nowrap colspan="2"><br><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>"><b>&nbsp;<? echo ENTRY_DATE_LAST_UPDATED; ?></b> <? echo $date_updated; ?>&nbsp;</font></td>
          </tr>
<?
    }
?>
          <tr>
             <td colspan="2"><br><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td align="right" colspan="2"><br><? echo '<a href="' . tep_href_link(FILENAME_ORDERS, '', 'NONSSL') . '">'; ?><? echo tep_image(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK); ?></a>&nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2"><font face="<? echo TEXT_FONT_FACE; ?>" size="<? echo TEXT_FONT_SIZE; ?>" color="<? echo TEXT_FONT_COLOR; ?>">&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="5"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_CUSTOMERS; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_ORDER_TOTAL; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_PAYMENT_METHOD; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_DATE_PURCHASED; ?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<? echo TABLE_HEADING_FONT_FACE; ?>" size="<? echo TABLE_HEADING_FONT_SIZE; ?>" color="<? echo TABLE_HEADING_FONT_COLOR; ?>"><b>&nbsp;<? echo TABLE_HEADING_STATUS; ?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="5"><? echo tep_black_line(); ?></td>
          </tr>
<?
    $orders_query_raw = "select orders_id, customers_name, payment_method, date_purchased, shipping_cost, orders_status from orders order by orders_id DESC";
    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders = tep_db_query($orders_query_raw);
    $rows = 0;
    while ($orders_values = tep_db_fetch_array($orders)) {
      $rows++;
      $total = 0;
      $orders_products = tep_db_query("select products_price, final_price, products_quantity, products_tax from orders_products where orders_id = '" . $orders_values['orders_id'] . "'");
      while ($orders_products_values = tep_db_fetch_array($orders_products)) {
        $subtotal = ($orders_products_values['final_price'] * $orders_products_values['products_quantity']);
        $tax = $subtotal * ($orders_products_values['products_tax']/100);
        $total = $total + $subtotal + $tax;
      }
      $total = $total + $orders_values['shipping_cost'];
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
		switch($orders_values['payment_method']) {
			case 'cc' : // Credit Card
				$payment = htmlentities(TEXT_CREDIT_CARD);
				break;
			case 'cod' : // Cash On Delivery
				$payment = htmlentities(TEXT_CASH_ON_DELIVERY);
				break;
			case 'paypal' : // PayPal
				$payment = htmlentities(TEXT_PAYPAL);
				break;
			default :
				$payment = htmlentities(TEXT_UNKNOWN);
				break;
		}
      $date_formatted = substr($orders_values['date_purchased'], 6, 2) . '/' . substr($orders_values['date_purchased'], 4, 2) . '/' . substr($orders_values['date_purchased'], 0, 4);
?>
            <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo '<a href="' . tep_href_link(FILENAME_ORDERS, 'orders_id=' . $orders_values['orders_id'], 'NONSSL') . '">'; ?><? echo $orders_values['customers_name']; ?></a>&nbsp;</font></td>
            <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;$<? echo number_format($total, 2); ?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $payment; ?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $date_formatted; ?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_values['orders_status']; ?>&nbsp;</font></td>
          </tr>
<?
    }
?>
          <tr>
            <td colspan="5"><? echo tep_black_line(); ?></td>
          </tr>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<? echo SMALL_TEXT_FONT_FACE; ?>" size="<? echo SMALL_TEXT_FONT_SIZE; ?>" color="<? echo SMALL_TEXT_FONT_COLOR; ?>">&nbsp;<? echo TEXT_RESULT_PAGE; ?> <? echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</font></td>
                  </tr>
                </table></td>
              </tr>
        </table></td>
      </tr>
<?
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<? $include_file = DIR_INCLUDES . 'footer.php';  include(DIR_INCLUDES . 'include_once.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<? $include_file = DIR_INCLUDES . 'application_bottom.php'; include(DIR_INCLUDES . 'include_once.php'); ?>
