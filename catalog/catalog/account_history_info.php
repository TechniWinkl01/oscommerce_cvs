<? include('includes/application_top.php'); ?>
<?
  if (tep_session_is_registered('customer_id')) {
    $customer_number = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". $HTTP_GET_VARS['order_id'] . "'");
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
<?php
  $include_file = DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO; include(DIR_WS_INCLUDES . 'include_once.php');
  $location = ' : <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_1 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_2 . '</a> : <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $HTTP_GET_VARS['order_id'], 'NONSSL') . '" class="whitelink">' . NAVBAR_TITLE_3 . '</a>';

// load payment modules as objects
  include(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;
?>
<html>
<head>
<title><? echo TITLE; ?></title>
<base href="<? echo (getenv('SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
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
            <td width="100%" class="topBarTitle">&nbsp;<? echo TOP_BAR_TITLE; ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<? echo HEADING_TITLE; ?>&nbsp;</td>
            <td align="right">&nbsp;<? echo tep_image(DIR_WS_IMAGES . 'table_background_history.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><? echo tep_black_line(); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center" class="tableHeading">&nbsp;<? echo TABLE_HEADING_QUANTITY; ?>&nbsp;</td>
            <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
            <td align="center" class="tableHeading">&nbsp;<? echo TABLE_HEADING_TAX; ?>&nbsp;</td>
            <td align="right" class="tableHeading">&nbsp;<? echo TABLE_HEADING_TOTAL; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
<?
  $order_currency_query = tep_db_query("select currency, currency_value from " . TABLE_ORDERS . " where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $order_currency = tep_db_fetch_array($order_currency_query);

  $orders_products = tep_db_query("select products_id, products_name, products_price, final_price, products_tax, products_quantity, orders_products_id from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $total_cost = 0;
  $total_tax = 0;
  while ($orders_products_values = tep_db_fetch_array($orders_products)) {
    $final_price = $orders_products_values['final_price'];

    echo '          <tr>' . "\n";
    echo '            <td align="center" valign="top" class="main">&nbsp;' . $orders_products_values['products_quantity'] . '&nbsp;</td>' . "\n";
    echo '            <td valign="top" class="main"><b>&nbsp;' . $orders_products_values['products_name'] . '&nbsp;</b>' . "\n";
//------display customer choosen option --------
    $attributes_exist = '0';
    $attributes_query = tep_db_query("select products_options, products_options_values from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . $HTTP_GET_VARS['order_id'] . "' and orders_products_id = '" . $orders_products_values['orders_products_id'] . "'");
    if (@tep_db_num_rows($attributes_query)) {
      $attributes_exist = '1';
      while ($attributes = tep_db_fetch_array($attributes_query)) {
		echo '<br><small><i>&nbsp;-&nbsp;' . $attributes['products_options'] . '&nbsp;:&nbsp;' . $attributes['products_options_values'] . '</i></small>';
      }
    }
//------display customer choosen option eof-----
	echo '</td>' . "\n";
    echo '            <td align="center" valign="top" class="main">&nbsp;' . number_format($orders_products_values['products_tax'], TAX_DECIMAL_PLACES) . '%&nbsp;</td>' . "\n";
    echo '            <td align="right" valign="top" class="main">&nbsp;<b>' . tep_currency_format($orders_products_values['products_quantity'] * $orders_products_values['products_price'], true, $order_currency['currency'], $order_currency['currency_value']) . '</b>&nbsp;';
//------display customer choosen option --------
    if ($attributes_exist == '1') {
      $attributes = tep_db_query("select options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . $HTTP_GET_VARS['order_id'] . "' and orders_products_id = '" . $orders_products_values['orders_products_id'] . "'");
      while ($attributes_values = tep_db_fetch_array($attributes)) {
        if ($attributes_values['options_values_price'] != '0') {
          echo '<br><small><i>' . $attributes_values['price_prefix'] . tep_currency_format($orders_products_values['products_quantity'] * $attributes_values['options_values_price'], true, $order_currency['currency'], $order_currency['currency_value']) . '</i></small>&nbsp;';
        } else {
          echo '<br>&nbsp;';
        }
      }
    }
//------display customer choosen option eof-----
	echo '</td>' . "\n";
    echo '          </tr>' . "\n";

    $cost = ($orders_products_values['products_quantity'] * $final_price);
    if (TAX_INCLUDE == true) {
      $total_tax += (($orders_products_values['products_quantity'] * $final_price) - (($orders_products_values['products_quantity'] * $final_price) / (($orders_products_values['products_tax']/100)+1)));
    } else {
      $total_tax += ($cost * $orders_products_values['products_tax']/100);
    }
    $total_cost += $cost;
  }
?>
          <tr>
            <td colspan="4"><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td colspan="4" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td align="right" width="100%" class="main">&nbsp;<? echo TABLE_SUBHEADING_SUBTOTAL; ?>&nbsp;</td>
                <td align="right" width="100%" class="main">&nbsp;<? echo tep_currency_format($total_cost, true, $order_currency['currency'], $order_currency['currency_value']); ?>&nbsp;</td>
              </tr>
<?
  $order = tep_db_query("select delivery_name as name, delivery_street_address as street_address, delivery_suburb as suburb, delivery_city as city, delivery_postcode as postcode, delivery_state as state, delivery_country as country, delivery_address_format_id as format_id, payment_method, shipping_cost, shipping_method, comments from " . TABLE_ORDERS . " where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $order_values = tep_db_fetch_array($order);

  if ($total_tax > 0) {
?>
              <tr>
                <td align="right" width="100%" class="main">&nbsp;<? echo TABLE_SUBHEADING_TAX; ?>:&nbsp;</td>
                <td align="right" width="100%" class="main">&nbsp;<? echo tep_currency_format($total_tax, true, $order_currency['currency'], $order_currency['currency_value']); ?>&nbsp;</td>
              </tr>
<?
  }
  $shipping = $order_values['shipping_cost'];
  if ( (MODULE_SHIPPING_INSTALLED) || ($shipping > 0) ) {
?>
              <tr>
                <td align="right" width="100%" class="main">&nbsp;<? echo $order_values['shipping_method'] . " " . TABLE_SUBHEADING_SHIPPING; ?>&nbsp;</td>
                <td align="right" width="100%" class="main">&nbsp;<? echo tep_currency_format($shipping, true, $order_currency['currency'], $order_currency['currency_value']); ?>&nbsp;</td>
              </tr>
<?
  }
?>
              <tr>
                <td align="right" width="100%" class="main">&nbsp;<b><? echo TABLE_SUBHEADING_TOTAL; ?></b>&nbsp;</td>
<?
  if (TAX_INCLUDE == true) {
?>
              <td align="right" width="100%" class="main">&nbsp;<b><? echo tep_currency_format($total_cost + $shipping, true, $order_currency['currency'], $order_currency['currency_value']); ?></b>&nbsp;</td>
<?
  } else {
?>
              <td align="right" width="100%" class="main">&nbsp;<b><? echo tep_currency_format($total_cost + $total_tax + $shipping, true, $order_currency['currency'], $order_currency['currency_value']); ?></b>&nbsp;</td>
<?
  }
?>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_DELIVERY_ADDRESS; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
     $boln = '<tr>' . "\n" . '            <td class="main">&nbsp;';
     $eoln = "&nbsp;</td>\n              </tr>\n";
     echo tep_address_format($order_values['format_id'], $order_values, true, $boln, $eoln);
?>
        </table></td>
      </tr>
<?
   if (MODULE_PAYMENT_INSTALLED) {
?>
      <tr>
        <td class="main"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading">&nbsp;<? echo TABLE_HEADING_PAYMENT_METHOD; ?>&nbsp;</td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
<?
// load the show_info function from the payment modules
  echo $payment_modules->show_info();
?>
        </table></td>
      </tr>
<?
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?
  if ($order_values['comments']) {
?>
          <tr>
            <td class="main"><br><b>&nbsp;<? echo TABLE_HEADING_COMMENTS; ?>&nbsp;</b></td>
          </tr>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
          <tr>
            <td class="main"><? echo '&nbsp;' . nl2br($order_values['comments']); ?></td>
          </tr>
<?
  }
?>
          <tr>
            <td><? echo tep_black_line(); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>&nbsp;&nbsp;</td>
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
