<? include('includes/application_top.php'); ?>
<? $include_file = DIR_LANGUAGES . $language . '/' . FILENAME_ORDERS; include(DIR_INCLUDES . 'include_once.php'); ?>
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
            <td align="right" nowrap>&nbsp;<?=tep_image(DIR_IMAGES . 'pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, '0', '');?>&nbsp;</td>
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
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td valign="top" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_CUSTOMERS_INFO;?>&nbsp;</b></font></td>
            <td valign="top" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_DELIVERY_INFO;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_CUSTOMER;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['customers_name'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_STREET_ADDRESS;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['customers_street_address'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_SUBURB;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['customers_suburb'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_CITY;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['customers_city'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_POST_CODE;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['customers_postcode'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_STATE;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['customers_state'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_COUNTRY;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['customers_country'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_TELEPHONE;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['customers_telephone'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_EMAIL_ADDRESS;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<a href="mailto:<?=$orders_values['customers_email_address'];?>"><u><?=$orders_values['customers_email_address'];?></u></a>&nbsp;</font></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_DELIVERY_TO;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['delivery_name'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_STREET_ADDRESS;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['delivery_street_address'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_SUBURB;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['delivery_suburb'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_CITY;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['delivery_city'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_POST_CODE;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['delivery_postcode'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_STATE;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['delivery_state'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_COUNTRY;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['delivery_country'];?>&nbsp;</font></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td nowrap colspan="2"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PAYMENT_INFORMATION;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td nowrap colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td colspan="2"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_PAYMENT_METHOD;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<? if ($orders_values['payment_method'] == 'cc') { echo TEXT_CREDIT_CARD; } elseif ($orders_values['payment_method']== 'cod') { echo TEXT_CASH_ON_DELIVERY; } else { echo TEXT_UNKNOWN; } ?>&nbsp;</font></td>
              </tr>
<?
    if ($orders_values['payment_method'] == 'cc') {
?>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_CREDIT_CARD_TYPE;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['cc_type'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_CREDIT_CARD_OWNER;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['cc_owner'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_CREDIT_CARD_NUMBER;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['cc_number'];?>&nbsp;</font></td>
              </tr>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_CREDIT_CARD_EXPIRES;?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['cc_expires'];?>&nbsp;</font></td>
              </tr>
<?
    }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="2"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_QUANTITY;?>&nbsp;</b></font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PRODUCTS;?>&nbsp;</b></font></td>
                <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_TOTAL;?>&nbsp;</b></font></td>
              </tr>
              <tr>
                <td colspan="3"><?=tep_black_line();?></td>
              </tr>
<?
    $total = 0;
    $grandtotal = 0;
    $info = tep_db_query("select date_purchased, orders_status, orders_date_finished, products_tax, shipping_cost, shipping_method from orders where orders_id = '" . $HTTP_GET_VARS['orders_id'] . "'");
    $info_values = tep_db_fetch_array($info);
    $date_purchased = date('l, jS F, Y', mktime(0,0,0,substr($info_values['date_purchased'], 4, 2),substr($info_values['date_purchased'], -2),substr($info_values['date_purchased'], 0, 4)));
    $products = tep_db_query("select products_name, products_price, products_quantity from orders_products where orders_id = '" . $HTTP_GET_VARS['orders_id'] . "'");
    while ($products_values = tep_db_fetch_array($products)) {
      $total = $products_values['products_quantity'] * $products_values['products_price'];
      $subtotal = $subtotal + $total;
?>
              <tr>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$products_values['products_quantity'];?>&nbsp;</font></td>
                <td nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$products_values['products_name'];?>&nbsp;</font></td>
                <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;$<?=$total; ?>&nbsp;</font></td>
              </tr>
<?
    }
    $shipping = $info_values['shipping_cost'];
    $shipping_method = $info_values['shipping_method'];
    $taxed = ($subtotal * ($info_values['products_tax']/100));
    $grandtotal = number_format(($subtotal + $taxed + $shipping), 2);
    $taxed = number_format($taxed, 2);
?>
              <tr>
                <td colspan="3"><?=tep_black_line();?></td>
              </tr>
              <tr>
                <td align="right" colspan="3"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=ENTRY_SUB_TOTAL;?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;$<?=$subtotal;?>&nbsp;</font></td>
                  </tr>
                  <tr>
                    <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=sprintf(ENTRY_TAX, $info_values['products_tax'] . '%');?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;$<?=$taxed;?>&nbsp;</font></td>
                  </tr>
<?
  if (!SHIPPING_FREE || $shipping != 0) {
?>
                  <tr>
                    <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;<?=$shipping_method . " " . ENTRY_SHIPPING;?>&nbsp;</font></td>
                    <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;$<?=number_format($shipping, 2);?>&nbsp;</font></td>
                  </tr>
<?
  }
?>
                  <tr>
                    <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_TOTAL;?>&nbsp;</b></font></td>
                    <td align="right" nowrap><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;$<?=$grandtotal;?>&nbsp;</b></font></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td nowrap colspan="2"><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_STATUS;?>&nbsp;</b></font></td>
           </tr>
          <tr>
            <td colspan="2"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap colspan="2"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_DATE_PURCHASED;?></b> <?=$date_purchased;?>&nbsp;</font></td>
          </tr>
          <tr><form name="status" <?='action="' . tep_href_link(FILENAME_ORDERS, '', 'NONSSL') . '"';?> method="get"><input type="hidden" name="action" value="update_order"><input type="hidden" name="orders_id" value="<?=$HTTP_GET_VARS['orders_id'];?>">
            <td nowrap colspan="2"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_STATUS;?></b> <select name="status"><option value="Processing"<? if ($info_values['orders_status'] == 'Processing') { echo ' SELECTED'; } ?>>Processing</option><option value="Delivered"<? if ($info_values['orders_status'] == 'Delivered') { echo ' SELECTED'; } ?>>Delivered</option><option value="Pending"<? if ($info_values['orders_status'] == 'Pending') { echo ' SELECTED'; } ?>>Pending</option></select>&nbsp;<?=tep_image_submit(DIR_IMAGES . 'button_update.gif', '50', '14', '0', IMAGE_UPDATE);?>&nbsp;</font></td>
          </tr></form>
<?
    if (@$info_values['orders_date_finished'] != '0') {
      $date_updated = substr($info_values['orders_date_finished'], 6, 2) . '/' . substr($info_values['orders_date_finished'], 4, 2) . '/' . substr($info_values['orders_date_finished'], 0, 4) . '&nbsp;' . substr($info_values['orders_date_finished'], -6, 2) . ':' . substr($info_values['orders_date_finished'], -4, 2) . ':' . substr($info_values['orders_date_finished'], -2);
?>
          <tr>
            <td nowrap colspan="2"><br><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>"><b>&nbsp;<?=ENTRY_DATE_LAST_UPDATED;?></b> <?=$date_updated;?>&nbsp;</font></td>
          </tr>
<?
    }
?>
          <tr>
             <td colspan="2"><br><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td align="right" colspan="2"><br><?='<a href="' . tep_href_link(FILENAME_ORDERS, '', 'NONSSL') . '">';?><?=tep_image(DIR_IMAGES . 'button_back.gif', '58', '24', '0', IMAGE_BACK);?></a>&nbsp;&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2"><font face="<?=TEXT_FONT_FACE;?>" size="<?=TEXT_FONT_SIZE;?>" color="<?=TEXT_FONT_COLOR;?>">&nbsp;</font></td>
          </tr>
        </table></td>
      </tr>
<?
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_CUSTOMERS;?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_ORDER_TOTAL;?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_PAYMENT_METHOD;?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_DATE_PURCHASED;?>&nbsp;</b></font></td>
            <td align="right" nowrap><font face="<?=TABLE_HEADING_FONT_FACE;?>" size="<?=TABLE_HEADING_FONT_SIZE;?>" color="<?=TABLE_HEADING_FONT_COLOR;?>"><b>&nbsp;<?=TABLE_HEADING_STATUS;?>&nbsp;</b></font></td>
          </tr>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
<?
    $orders = tep_db_query("select orders_id, customers_name, payment_method, date_purchased, products_tax, shipping_cost, orders_status from orders order by orders_id DESC");
    $rows = 0;
    while ($orders_values = tep_db_fetch_array($orders)) {
      $rows++;
      $total = 0;
      $orders_products = tep_db_query("select products_price, products_quantity from orders_products where orders_id = '" . $orders_values['orders_id'] . "'");
      while ($orders_products_values = tep_db_fetch_array($orders_products)) {
        $total = $total + ($orders_products_values['products_price'] * $orders_products_values['products_quantity']);
      }
      $total = $total + ($total*$orders_values['products_tax']/100) + $orders_values['shipping_cost'];
      if (floor($rows/2) == ($rows/2)) {
        echo '          <tr bgcolor="#ffffff">' . "\n";
      } else {
        echo '          <tr bgcolor="#f4f7fd">' . "\n";
      }
      if ($orders_values['payment_method'] == 'cc') {
        $payment = TEXT_CREDIT_CARD;
      } elseif ($orders_values['payment_method'] == 'cod') {
        $payment = TEXT_CASH_ON_DELIVERY;
      } else {
        $payment = TEXT_UNKNOWN;
      }
      $date_formatted = substr($orders_values['date_purchased'], 6, 2) . '/' . substr($orders_values['date_purchased'], 4, 2) . '/' . substr($orders_values['date_purchased'], 0, 4);
?>
            <td nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?='<a href="' . tep_href_link(FILENAME_ORDERS, 'orders_id=' . $orders_values['orders_id'], 'NONSSL') . '">';?><?=$orders_values['customers_name'];?></a>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;$<?=number_format($total, 2);?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$payment;?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$date_formatted;?>&nbsp;</font></td>
            <td align="right" nowrap><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=$orders_values['orders_status'];?>&nbsp;</font></td>
          </tr>
<?
    }
?>
          <tr>
            <td colspan="5"><?=tep_black_line();?></td>
          </tr>
          <tr>
            <td align="right" colspan="5"><font face="<?=SMALL_TEXT_FONT_FACE;?>" size="<?=SMALL_TEXT_FONT_SIZE;?>" color="<?=SMALL_TEXT_FONT_COLOR;?>">&nbsp;<?=TEXT_ORDER_INFORMATION;?>&nbsp;</font></td>
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
