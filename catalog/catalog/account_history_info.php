<?php
/*
  $Id: account_history_info.php,v 1.73 2002/03/31 20:10:42 clescuyer Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (tep_session_is_registered('customer_id')) {
    $customer_number = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". $HTTP_GET_VARS['order_id'] . "'");
    $customer_number_values = tep_db_fetch_array($customer_number);
    if ($customer_number_values['customers_id'] != $customer_id) {
      tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
    }
  } else {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);

  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_1 . '</a> &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_2 . '</a> &raquo; <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $HTTP_GET_VARS['order_id'], 'SSL') . '" class="headerNavigation">' . NAVBAR_TITLE_3 . '</a>';

// load payment modules as objects
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;
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

<?php
  $order_currency_query = tep_db_query("select currency, currency_value from " . TABLE_ORDERS . " where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $order_currency = tep_db_fetch_array($order_currency_query);

  $orders_products_query = tep_db_query("select products_id, products_name, products_price, final_price, products_tax, products_quantity, orders_products_id 
                                         from " . TABLE_ORDERS_PRODUCTS . " 
                                         where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $total_cost = 0;
  $total_tax = 0;
  $i = 0;
  while ($orders_products = tep_db_fetch_array($orders_products_query)) {
    $products[$i]['name'] = $orders_products['products_name'];
    $products[$i]['quantity'] = $orders_products['products_quantity'];
    $products[$i]['tax'] = $orders_products['products_tax'];
    $products[$i]['price'] = $orders_products['products_price'];
    $final_price = $orders_products['final_price'];
    $attributes_query = tep_db_query("select products_options, products_options_values, price_prefix, options_values_price 
                        from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " 
                        where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'
                         and orders_products_id = '" . $orders_products['orders_products_id'] . "'");
// Build data structure for order_details module
    if (@tep_db_num_rows($attributes_query)) {
      $j = 0;
      while ($attributes = tep_db_fetch_array($attributes_query)) {
        $products[$i][$j]['products_options_name'] = $attributes['products_options'];
        $products[$i][$j]['products_options_values_name'] = $attributes['products_options_values'];
        $products[$i][$j]['price_prefix'] = $attributes['price_prefix'];
        $products[$i][$j]['options_values_price'] = $attributes['options_values_price'];
        $products[$i]['attributes'][$j] = 1;
        $j++;
      }
    }
    $i++;

    $cost = ($orders_products['products_quantity'] * $final_price);
    if (DISPLAY_PRICE_WITH_TAX == true) {
      $total_tax += (($orders_products['products_quantity'] * $final_price) * (($orders_products['products_tax']/100)+1) - ($orders_products['products_quantity'] * $final_price));
    } else {
      $total_tax += ($cost * $orders_products['products_tax']/100);
    }
    $total_cost += $cost;
  }
  require(DIR_WS_MODULES. 'order_details.php');

?>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td colspan="4" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
              <tr>
<?php
    if (DISPLAY_PRICE_WITH_TAX == true) {
?>
                <td align="right" class="main"><?php echo TABLE_SUBHEADING_SUBTOTAL; ?></td>
                <td align="right" class="main"><?php echo $currencies->format($total_cost + $total_tax, true, $order_currency['currency'], $order_currency['currency_value']); ?></td>
<?php
    } else {
?>
                <td align="right" class="main"><?php echo TABLE_SUBHEADING_SUBTOTAL; ?></td>
                <td align="right" class="main"><?php echo $currencies->format($total_cost, true, $order_currency['currency'], $order_currency['currency_value']); ?></td>
<?php
    }
?>
              </tr>
<?php
  $order_query = tep_db_query("select delivery_name as name, delivery_street_address as street_address, delivery_suburb as suburb, delivery_city as city, delivery_postcode as postcode, delivery_state as state, delivery_country as country, delivery_address_format_id as format_id, payment_method, shipping_cost, shipping_method, comments from " . TABLE_ORDERS . " where orders_id = '" . $HTTP_GET_VARS['order_id'] . "'");
  $order = tep_db_fetch_array($order_query);

  if ($total_tax > 0) {
?>
              <tr>
                <td align="right" class="main"><?php echo TABLE_SUBHEADING_TAX; ?></td>
                <td align="right" class="main"><?php echo $currencies->format($total_tax, true, $order_currency['currency'], $order_currency['currency_value']); ?></td>
              </tr>
<?php
  }

  $shipping = $order['shipping_cost'];
  if ( (MODULE_SHIPPING_INSTALLED) || ($shipping > 0) ) {
?>
              <tr>
                <td align="right" class="main"><?php echo $order['shipping_method'] . " " . TABLE_SUBHEADING_SHIPPING; ?></td>
                <td align="right" class="main"><?php echo $currencies->format($shipping, true, $order_currency['currency'], $order_currency['currency_value']); ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td align="right" class="main"><b><?php echo TABLE_SUBHEADING_TOTAL; ?></td>
                <td align="right" class="main"><?php echo $currencies->format($total_cost + $total_tax + $shipping, true, $order_currency['currency'], $order_currency['currency_value']); ?></b></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading"><?php echo TABLE_HEADING_DELIVERY_ADDRESS; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
     $boln = '<tr>' . "\n" . '            <td class="main">';
     $eoln = "</td>\n              </tr>\n";
     echo tep_address_format($order['format_id'], $order, true, $boln, $eoln);
?>
        </table></td>
      </tr>
<?php
   if (MODULE_PAYMENT_INSTALLED) {
?>
      <tr>
        <td class="main"><br><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="tableHeading"><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
<?php
// load the show_info function from the payment modules
    echo $payment_modules->show_info();
?>
        </table></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($order['comments']) {
?>
          <tr>
            <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo nl2br($order['comments']); ?></td>
          </tr>
<?php
  }
?>
        </table></td>
      </tr>
<?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_BOXES . 'downloads.php'); ?>
      <tr>
        <td align="right" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
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